<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use App\Models\Upload;
use App\Services\UploadService;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return view('teacher.dashboard', ['user' => $user]);
    }

    public function updateName(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $user = Auth::user();
        $uploadDir = UploadService::ensureUploadDirectory($user->username);

        file_put_contents($uploadDir . 'name.txt', $request->name);

        return back()->with('success', 'Name updated successfully.');
    }

    public function updateImage(Request $request)
    {
        $request->validate(['profileImage' => 'required|image|max:5000']);

        $user = Auth::user();

        if ($request->file('profileImage')) {
            UploadService::saveProfileImage($request->file('profileImage'), $user->username);
        }

        return back()->with('success', 'Profile image updated successfully.');
    }

    public function upload(Request $request)
    {
        $request->validate(['file' => 'required|file|max:10240']); // 10MB

        $user = Auth::user();
        $allowedExtensions = ['pdf', 'xlsx', 'docx', 'pptx', 'txt', 'png', 'jpg', 'mp3', 'mp4', 'zip', 'jpeg', 'mkv', 'rar', 'svg'];

        $file = $request->file('file');
        $fileExt = strtolower($file->getClientOriginalExtension());

        if (!in_array($fileExt, $allowedExtensions)) {
            return back()->withErrors('Invalid file type.');
        }

        $userName = preg_replace('/[^a-zA-Z0-9_]/', '', $user->username);
        $uploadDir = public_path('uploads/' . $userName . '/');
        @mkdir($uploadDir, 0755, true);

        // Categorize file
        $fileType = $this->categorizeFile($fileExt);
        $typePath = $uploadDir . $fileType . '/';
        @mkdir($typePath, 0755, true);

        // Store file
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->move($typePath, $fileName);

        $extractedText = '';

        // Extract PDF text
        if ($fileExt === 'pdf') {
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($typePath . $fileName);
                $pages = $pdf->getPages();

                foreach ($pages as $page) {
                    $extractedText .= $page->getText() . "\n";
                }
            } catch (\Exception $e) {
                $extractedText = 'PDF extraction failed: ' . $e->getMessage();
            }
        } elseif (in_array($fileExt, ['txt', 'docx'])) {
            $extractedText = file_get_contents($typePath . $fileName);
        }

        // Save to database
        Upload::create([
            'user_id' => $user->id,
            'filename' => $fileName,
            'filepath' => $fileType . '/' . $fileName,
            'extracted_text' => mb_substr($extractedText, 0, 65535), // LONGTEXT limit
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function getFiles()
    {
        $user = Auth::user();
        $files = Upload::where('user_id', $user->id)->get();
        return response()->json($files);
    }

    private function categorizeFile($ext)
    {
        $documentExt = ['pdf', 'docx', 'pptx', 'xlsx', 'txt'];
        $imageExt = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'bmp', 'svg'];
        $audioExt = ['m4a', 'mp3', 'wav', 'flac', 'aac'];
        $videoExt = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv'];

        if (in_array($ext, $documentExt)) return 'documents';
        if (in_array($ext, $imageExt)) return 'images';
        if (in_array($ext, $audioExt)) return 'audio';
        if (in_array($ext, $videoExt)) return 'video';

        return 'others';
    }
}
