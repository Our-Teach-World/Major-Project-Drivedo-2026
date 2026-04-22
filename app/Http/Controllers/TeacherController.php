<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use App\Models\Upload;
use App\Models\Teacher;
use App\Services\UploadService;
use App\Notifications\SystemAlert;
use Illuminate\Support\Facades\Notification;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // Load teacher profile from DB (null if not yet created)
        $teacherProfile = Teacher::where('user_id', $user->id)->first();
        return view('teacher.dashboard', ['user' => $user, 'teacherProfile' => $teacherProfile]);
    }

    /**
     * Update display name — saves to teachers table (creates row if first time)
     */
    public function updateName(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $user = Auth::user();

        Teacher::updateOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $request->name]
        );

        return back()->with('success', 'Name updated successfully.');
    }

    /**
     * Update profile image — saves file to disk AND path to teachers table
     */
    public function updateImage(Request $request)
    {
        $request->validate(['profileImage' => 'required|image|max:5000']);

        $user = Auth::user();

        if ($request->file('profileImage')) {
            // Save the actual file to disk (same as before)
            $savedPath = UploadService::saveProfileImage($request->file('profileImage'), $user->username);

            // Build a web-accessible relative path
            $sanitized = preg_replace('/[^a-zA-Z0-9_]/', '', $user->username);
            $relativePath = 'uploads/' . $sanitized . '/profile.jpg';

            // Also store the path in the teachers table
            Teacher::updateOrCreate(
                ['user_id' => $user->id],
                ['profile_image' => $relativePath]
            );
        }

        return back()->with('success', 'Profile image updated successfully.');
    }

    /**
     * Update teacher's branch and semester (saved in teachers table)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'semesters'    => 'required|array|min:1', // Ab ye ek array hona chahiye
            'semesters.*'  => 'integer|min:1|max:6',  // Array ki har value integer ho
        ]);

        $user = Auth::user();

        Teacher::updateOrCreate(
            ['user_id' => $user->id],
            [
                'semester' => json_encode($request->semesters),
            ]
        );

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload one or more files (files[] array from multi-select input)
     */
    public function upload(Request $request)
    {
        $request->validate([
            'semester' => 'required|integer|min:1|max:6',
            'files'    => 'required|array|min:1',
            'files.*'  => 'required|file|max:10240',
        ]);

        $semester = (int) $request->semester;

        $user = Auth::user();
        $allowedExtensions = ['pdf', 'xlsx', 'docx', 'pptx', 'txt', 'png', 'jpg', 'mp3', 'mp4', 'zip', 'jpeg', 'mkv', 'rar', 'svg'];

        $userName  = preg_replace('/[^a-zA-Z0-9_]/', '', $user->username);
        $uploadDir = public_path('uploads/' . $userName . '/');
        @mkdir($uploadDir, 0755, true);

        $uploadedCount = 0;
        $errors        = [];

        foreach ($request->file('files') as $file) {
            $fileExt = strtolower($file->getClientOriginalExtension());

            if (!in_array($fileExt, $allowedExtensions)) {
                $errors[] = "'{$file->getClientOriginalName()}' has an unsupported file type.";
                continue;
            }

            // Categorize and save
            $fileType = $this->categorizeFile($fileExt);
            $typePath = $uploadDir . $fileType . '/';
            @mkdir($typePath, 0755, true);

            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($typePath, $fileName);

            $extractedText = '';

            if ($fileExt === 'pdf') {
                try {
                    $parser = new Parser();
                    $pdf    = $parser->parseFile($typePath . $fileName);
                    foreach ($pdf->getPages() as $page) {
                        $extractedText .= $page->getText() . "\n";
                    }
                } catch (\Exception $e) {
                    $extractedText = 'PDF extraction failed: ' . $e->getMessage();
                }
            } elseif (in_array($fileExt, ['txt', 'docx'])) {
                $extractedText = file_get_contents($typePath . $fileName);
            }

            Upload::create([
                'user_id'        => $user->id,
                'semester'       => $semester,
                'filename'       => $fileName,
                'filepath'       => $fileType . '/' . $fileName,
                'extracted_text' => mb_substr($extractedText, 0, 65535),
            ]);

            $uploadedCount++;
        }

        // Send Notifications to Students
        if ($uploadedCount > 0) {
            try {
                $teacherProfile = Teacher::where('user_id', $user->id)->first();
                $branch = $teacherProfile->branch;
                
                $notificationTitle = "New Study Material";
                $notificationMsg = "{$user->username} has uploaded new content for Semester {$semester}.";
                $actionUrl = url('/student/dashboard?section=study&teacher=' . $user->username);

                $students = \App\Models\User::where('role', 'student')
                    ->whereHas('studentProfile', function($query) use ($branch, $semester) {
                        $query->where('branch', $branch)->where('semester', $semester);
                    })->get();

                Notification::send($students, new SystemAlert($notificationTitle, $notificationMsg, '📚', $actionUrl));
            } catch (\Exception $e) {
                \Log::error('Upload Notification Error: ' . $e->getMessage());
            }
        }

        if (!empty($errors)) {
            return back()
                ->withErrors($errors)
                ->with('success', $uploadedCount > 0 ? "{$uploadedCount} file(s) uploaded successfully." : null);
        }

        return back()->with('success', "{$uploadedCount} file(s) uploaded successfully.");
    }

    public function getFiles()
    {
        $user  = Auth::user();
        $files = Upload::where('user_id', $user->id)
                        ->orderBy('uploaded_at', 'desc')
                        ->get(['id', 'filename', 'filepath', 'semester', 'uploaded_at']);
        return response()->json($files);
    }

    /**
     * Delete a file — removes DB record AND physical file from disk.
     * Only allows deleting files that belong to the authenticated teacher.
     */
    public function deleteFile($id)
    {
        $user   = Auth::user();
        $upload = Upload::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Delete physical file from disk
        $userName  = preg_replace('/[^a-zA-Z0-9_]/', '', $user->username);
        $fullPath  = public_path('uploads/' . $userName . '/' . $upload->filepath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Delete DB record
        $upload->delete();

        return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
    }

    /**
     * Preview a file in the browser (inline for images/PDFs, download for others).
     * Only allows previewing files that belong to the authenticated teacher.
     */
    public function previewFile($id)
    {
        $user   = Auth::user();
        $upload = Upload::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $userName = preg_replace('/[^a-zA-Z0-9_]/', '', $user->username);
        $fullPath = public_path('uploads/' . $userName . '/' . $upload->filepath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found.');
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        // MIME map for inline preview
        $inlineMimes = [
            'pdf'  => 'application/pdf',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'svg'  => 'image/svg+xml',
            'txt'  => 'text/plain',
            'mp4'  => 'video/mp4',
            'mp3'  => 'audio/mpeg',
        ];

        if (isset($inlineMimes[$ext])) {
            // Serve inline so browser can display it
            return response()->file($fullPath, [
                'Content-Type'        => $inlineMimes[$ext],
                'Content-Disposition' => 'inline; filename="' . $upload->filename . '"',
            ]);
        }

        // For unsupported preview types (docx, xlsx, pptx, zip…) → force download
        return response()->download($fullPath, $upload->filename);
    }

    private function categorizeFile($ext)
    {
        $documentExt = ['pdf', 'docx', 'pptx', 'xlsx', 'txt'];
        $imageExt    = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'bmp', 'svg'];
        $audioExt    = ['m4a', 'mp3', 'wav', 'flac', 'aac'];
        $videoExt    = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv'];

        if (in_array($ext, $documentExt)) return 'documents';
        if (in_array($ext, $imageExt))    return 'images';
        if (in_array($ext, $audioExt))    return 'audio';
        if (in_array($ext, $videoExt))    return 'video';

        return 'others';
    }
}
