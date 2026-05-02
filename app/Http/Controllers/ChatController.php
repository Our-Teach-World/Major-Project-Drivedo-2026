<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Upload;
use Smalot\PdfParser\Parser;

class ChatController extends Controller
{
    /**
     * AI Chat API for study materials (RAG).
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'contextFiles' => 'nullable|array',
            'history' => 'nullable|array'
        ]);

        $message = $request->input('message');
        $contextFiles = $request->input('contextFiles', []);
        $history = $request->input('history', []);

        // 1. Retrieval (RAG)
        $context = "";
        $foundFiles = [];

        $query = Upload::query();
        if (!empty($contextFiles)) {
            $query->whereIn('filename', $contextFiles);
        }

        // Basic keyword search for context
        $relevantUploads = $query->whereFullText('extracted_text', $message)
            ->orWhere('extracted_text', 'like', '%' . $message . '%')
            ->limit(5)
            ->get();

        foreach ($relevantUploads as $upload) {
            $context .= "--- Source: " . $upload->filename . " ---\n";
            $context .= $upload->extracted_text . "\n\n";
            $foundFiles[] = $upload->filename;
        }

        // 2. Construct Prompt
        $systemPrompt = "You are a helpful academic assistant for students. " .
            "Use the provided context from study materials to answer the user's question. " .
            "If the answer is not in the context, say you don't know based on the documents, but try to help generally. " .
            "Context:\n\n" . $context;

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Add history (limit to last 5 for tokens)
        foreach (array_slice($history, -5) as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        // 3. Call AI API (NVIDIA/Kimi)
        try {
            $apiKey = config('services.nvidia.key') ?? env('NVIDIA_API_KEY');
            $apiUrl = env('NVIDIA_API_URL', 'https://integrate.api.nvidia.com/v1/chat/completions');

            $response = Http::timeout(120)->connectTimeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($apiUrl, [
                'model' => 'moonshotai/kimi-k2.6',
                'messages' => $messages,
                'temperature' => 1.0,
                'top_p' => 1.0,
                'max_tokens' => 4096,
                'chat_template_kwargs' => ['thinking' => false],
            ]);

            if ($response->successful()) {
                $aiData = $response->json();
                $aiResponse = $aiData['choices'][0]['message']['content'] ?? 'No response from AI.';
                
                return response()->json([
                    'response' => $aiResponse,
                    'foundFiles' => array_unique($foundFiles)
                ]);
            } else {
                Log::error('AI API Error: ' . $response->body());
                return response()->json([
                    'error' => 'Failed to connect to AI service.',
                    'details' => $response->json()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    /**
     * Resume Advisor Chat.
     */
    public function resumeChat(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,txt,docx|max:5120',
            'message' => 'nullable|string'
        ]);

        $file = $request->file('resume');
        $userMessage = $request->input('message', 'Please review my resume and suggest improvements.');
        $extractedText = "";

        // 1. Extract Text
        try {
            $ext = $file->getClientOriginalExtension();
            if ($ext === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($file->getRealPath());
                $extractedText = $pdf->getText();
            } else {
                $extractedText = file_get_contents($file->getRealPath());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to extract text from resume: ' . $e->getMessage()], 422);
        }

        // 2. Call AI API
        try {
            $apiKey = config('services.nvidia.key') ?? env('NVIDIA_API_KEY');
            $apiUrl = env('NVIDIA_API_URL', 'https://integrate.api.nvidia.com/v1/chat/completions');

            $systemPrompt = "You are an expert Career Advisor. Analyze the following resume text and provide constructive feedback on: " .
                "1. Impact & Metrics, 2. Keywords for ATS, 3. Formatting & Clarity, 4. Overall Impression. " .
                "User Request: " . $userMessage . "\n\n" .
                "Resume Text:\n" . $extractedText;

            $response = Http::timeout(120)->connectTimeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($apiUrl, [
                'model' => 'moonshotai/kimi-k2.6',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => 'Analyze this resume.']
                ],
                'temperature' => 0.7,
                'max_tokens' => 4096,
                'chat_template_kwargs' => ['thinking' => false],
            ]);

            if ($response->successful()) {
                $aiData = $response->json();
                return response()->json([
                    'response' => $aiData['choices'][0]['message']['content'] ?? 'No response from AI.'
                ]);
            } else {
                return response()->json(['error' => 'AI analysis failed: ' . $response->status()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resume analysis error: ' . $e->getMessage()], 500);
        }
    }
}
