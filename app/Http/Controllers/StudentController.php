<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth as UserAuth;
use App\Models\Upload;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }

    public function getTeachers()
    {
        $teachers = UserAuth::where('role', 'teacher')
                            ->where('status', 'approved')
                            ->get(['id', 'username']);

        return response()->json($teachers);
    }

    public function getFiles(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:teachers,folders,files',
            'teacher' => 'nullable|string|max:255',
            'folder' => 'nullable|string|max:255',
        ]);

        $action = $validated['action'];
        $teacherName = $validated['teacher'] ?? null;
        $folderName = $validated['folder'] ?? null;

        if ($action === 'teachers') {
            $teachers = UserAuth::where('role', 'teacher')
                                ->where('status', 'approved')
                                ->get(['username']);
            return response()->json($teachers);
        }

        if ($action === 'folders' && $teacherName) {
            $teacher = UserAuth::where('username', $teacherName)
                               ->where('role', 'teacher')
                               ->first();

            if ($teacher) {
                $folders = [
                    ['name' => 'documents', 'icon' => '📄', 'count' => Upload::where('user_id', $teacher->id)->where('filepath', 'like', '%documents%')->count()],
                    ['name' => 'images', 'icon' => '🖼️', 'count' => Upload::where('user_id', $teacher->id)->where('filepath', 'like', '%images%')->count()],
                    ['name' => 'audio', 'icon' => '🎵', 'count' => Upload::where('user_id', $teacher->id)->where('filepath', 'like', '%audio%')->count()],
                    ['name' => 'video', 'icon' => '🎬', 'count' => Upload::where('user_id', $teacher->id)->where('filepath', 'like', '%video%')->count()],
                ];
                return response()->json($folders);
            }
        }

        if ($action === 'files' && $teacherName && $folderName) {
            $teacher = UserAuth::where('username', $teacherName)
                               ->where('role', 'teacher')
                               ->first();

            if ($teacher) {
                $files = Upload::where('user_id', $teacher->id)
                              ->where('filepath', 'like', "%{$folderName}%")
                              ->get(['filename', 'filepath']);
                return response()->json($files);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}
