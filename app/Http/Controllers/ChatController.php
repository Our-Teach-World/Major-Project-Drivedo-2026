<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;

class ChatController extends Controller
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = env('NVIDIA_API_KEY', '');
        $this->apiUrl = env('NVIDIA_API_URL', 'https://integrate.api.nvidia.com/v1/chat/completions');
        $this->model  = env('NVIDIA_MODEL', 'moonshotai/kimi-k2-5');
    }

    public function chat(Request $request)
    {
        // ── 1. Validate input ────────────────────────────────────────────────
        if (empty($this->apiKey)) {
            return response()->json(['error' => 'NVIDIA API key is not configured.'], 500);
        }

        $userQuestion   = trim($request->input('message', $request->query('message', '')));
        $history        = $request->input('history',      $request->query('history',      []));
        $contextFiles   = $request->input('contextFiles', $request->query('contextFiles', []));

        if (is_string($history))      $history      = json_decode($history, true)      ?? [];
        if (is_string($contextFiles)) $contextFiles = json_decode($contextFiles, true) ?? [];

        if (empty($userQuestion)) {
            return response()->json(['error' => 'No message provided.'], 400);
        }

        // ── 2. Build RAG context ─────────────────────────────────────────────
        $context    = '';
        $foundFiles = [];

        // Priority: explicitly selected files
        if (!empty($contextFiles)) {
            foreach ((array) $contextFiles as $filename) {
                $upload = Upload::where('filename', $filename)->first();
                if ($upload && !empty(trim((string) $upload->extracted_text))) {
                    $context .= "Source: {$upload->filename}\nContent: "
                        . mb_substr(trim($upload->extracted_text), 0, 1500)
                        . "\n\n";
                    $foundFiles[] = $upload->filename;
                }
            }
        }

        // Fallback: FULLTEXT search across all uploads
        if (empty($context)) {
            try {
                $uploads = Upload::whereNotNull('extracted_text')
                    ->where('extracted_text', '<>', '')
                    ->whereRaw("MATCH(extracted_text) AGAINST(? IN BOOLEAN MODE)", [$userQuestion])
                    ->limit(3)
                    ->get();

                foreach ($uploads as $upload) {
                    $excerpt = mb_substr(trim((string) $upload->extracted_text), 0, 1000);
                    if ($excerpt === '') continue;
                    $context    .= "Source: {$upload->filename}\nContent: {$excerpt}\n\n";
                    $foundFiles[] = $upload->filename;
                }
            } catch (\Exception $e) {
                // FULLTEXT index may not exist – silently skip
            }
        }

        // ── 3. Build messages array ──────────────────────────────────────────
        $systemPrompt = !empty($context)
            ? "You are a helpful educational assistant. Use the following context from uploaded files to answer questions:\n\n{$context}"
            : "You are a helpful educational assistant. Answer questions based on your knowledge.";

        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ((array) $history as $msg) {
            $role    = in_array($msg['role'] ?? '', ['user', 'assistant']) ? $msg['role'] : 'user';
            $content = $msg['content'] ?? $msg['message'] ?? '';
            if (!empty(trim((string) $content))) {
                $messages[] = ['role' => $role, 'content' => $content];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $userQuestion];

        // ── 4. Call NVIDIA API via raw cURL (bypasses XAMPP SSL issue) ───────
        $payload = json_encode([
            'model'       => $this->model,
            'messages'    => $messages,
            'temperature' => 0.7,
            'top_p'       => 1.0,
            'max_tokens'  => 2048,
            'stream'      => false,
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            // Skip SSL verification only in local dev (XAMPP has no CA bundle)
            // On a real hosted server this stays true (secure)
            CURLOPT_SSL_VERIFYPEER => app()->environment('production'),
            CURLOPT_SSL_VERIFYHOST => app()->environment('production') ? 2 : 0,
            // ✅ Fix: generous timeout so NVIDIA has time to respond
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_CONNECTTIMEOUT => 15,
        ]);

        // Override PHP max_execution_time so cURL can finish
        set_time_limit(150);

        $body     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        // ── 5. Handle cURL failure ───────────────────────────────────────────
        if ($body === false || $curlErr) {
            return response()->json([
                'error'   => 'Network error connecting to AI service.',
                'details' => $curlErr,
            ], 500);
        }

        // ── 6. Parse response ────────────────────────────────────────────────
        $data = json_decode($body, true);

        if ($httpCode !== 200 || empty($data['choices'][0]['message']['content'])) {
            return response()->json([
                'error'   => 'AI API returned an unexpected response (HTTP ' . $httpCode . ').',
                'details' => $body,
            ], 500);
        }

        $aiResponse = $data['choices'][0]['message']['content'];
        $reasoning  = $data['choices'][0]['message']['reasoning_content'] ?? null;

        $result = [
            'response'   => $aiResponse,
            'foundFiles' => $foundFiles,
        ];

        if ($reasoning) {
            $result['reasoning'] = $reasoning;
        }

        return response()->json($result);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // Resume Advisor
    // ═══════════════════════════════════════════════════════════════════════
    public function resumeChat(Request $request)
    {
        $request->validate([
            'resume'  => 'required|file|mimes:pdf,txt,docx|max:5120',
            'message' => 'nullable|string|max:500',
        ]);

        if (empty($this->apiKey)) {
            return response()->json(['error' => 'NVIDIA API key not configured.'], 500);
        }

        // ── Extract text from resume file ────────────────────────────────────
        $file    = $request->file('resume');
        $ext     = strtolower($file->getClientOriginalExtension());
        $tmpPath = $file->getRealPath();
        $text    = '';

        try {
            if ($ext === 'pdf') {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($tmpPath);
                foreach ($pdf->getPages() as $page) {
                    $text .= $page->getText() . "\n";
                }
            } else {
                // txt and docx (docx = ZIP; raw read grabs most plaintext)
                $text = file_get_contents($tmpPath);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not read the resume file: ' . $e->getMessage()], 422);
        }

        $text = trim($text);
        if (empty($text)) {
            return response()->json(['error' => 'Could not extract any text from the uploaded file. Please try a TXT version.'], 422);
        }

        $userInstruction = trim($request->input('message', '')) ?: 'Please review and improve this resume.';

        // ── Build messages ───────────────────────────────────────────────────
        $messages = [
            [
                'role'    => 'system',
                'content' => "You are an expert career coach and professional resume writer. " .
                             "When given a resume, you:\n" .
                             "1. Identify weaknesses (vague language, missing metrics, formatting issues, etc.)\n" .
                             "2. Provide a rewritten, improved version with clear sections\n" .
                             "3. Explain the key changes you made\n" .
                             "Format your response with clear headings: " .
                             "**Analysis**, **Improved Resume**, **Key Changes Made**.",
            ],
            [
                'role'    => 'user',
                'content' => "{$userInstruction}\n\n---RESUME START---\n{$text}\n---RESUME END---",
            ],
        ];

        // ── Call NVIDIA API via cURL ─────────────────────────────────────────
        $payload = json_encode([
            'model'       => $this->model,
            'messages'    => $messages,
            'temperature' => 0.7,
            'top_p'       => 1.0,
            'max_tokens'  => 4096,
            'stream'      => false,
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => app()->environment('production'),
            CURLOPT_SSL_VERIFYHOST => app()->environment('production') ? 2 : 0,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_CONNECTTIMEOUT => 15,
        ]);

        set_time_limit(150);

        $body     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($body === false || $curlErr) {
            return response()->json(['error' => 'Network error: ' . $curlErr], 500);
        }

        $data = json_decode($body, true);

        if ($httpCode !== 200 || empty($data['choices'][0]['message']['content'])) {
            return response()->json([
                'error'   => 'AI API error (HTTP ' . $httpCode . ')',
                'details' => $body,
            ], 500);
        }

        return response()->json([
            'response' => $data['choices'][0]['message']['content'],
            'filename' => $file->getClientOriginalName(),
        ]);
    }
}
