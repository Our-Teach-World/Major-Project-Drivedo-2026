<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Upload;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }

    /**
     * Returns approved teachers.
     * Optional ?semester=N filter — only returns teachers whose uploaded files include that semester.
     */
    public function getTeachers(Request $request)
    {
        $semester = $request->get('semester'); // optional filter

        $query = Student::where('role', 'teacher')
                         ->where('status', 'approved')
                         ->with('teacherProfile');

        if ($semester) {
            // Only return teachers who have at least one file for this semester
            $query->whereHas('uploads', function ($q) use ($semester) {
                $q->where('semester', (int) $semester);
            });
        }

        $teachers = $query->get(['id', 'username'])
                          ->map(function ($teacher) {
                              return [
                                  'id'            => $teacher->id,
                                  'username'      => $teacher->username,
                                  'display_name'  => optional($teacher->teacherProfile)->display_name ?? $teacher->username,
                                  'profile_image' => optional($teacher->teacherProfile)->profile_image,
                                  'bio'           => optional($teacher->teacherProfile)->bio,
                                  'branch'        => optional($teacher->teacherProfile)->branch,
                              ];
                          });

        return response()->json($teachers);
    }

    public function getFiles(Request $request)
    {
        $validated = $request->validate([
            'action'   => 'required|in:teachers,folders,files',
            'teacher'  => 'nullable|string|max:255',
            'folder'   => 'nullable|string|max:255',
            'semester' => 'nullable|integer|min:1|max:6',
        ]);

        $action      = $validated['action'];
        $teacherName = $validated['teacher'] ?? null;
        $folderName  = $validated['folder'] ?? null;
        $semester    = isset($validated['semester']) ? (int) $validated['semester'] : null;

        if ($action === 'teachers') {
            $query = Student::where('role', 'teacher')
                             ->where('status', 'approved')
                             ->with('teacherProfile');

            if ($semester) {
                $query->whereHas('uploads', function ($q) use ($semester) {
                    $q->where('semester', $semester);
                });
            }

            $teachers = $query->get(['id', 'username'])
                              ->map(function ($t) {
                                  return [
                                      'username'      => $t->username,
                                      'display_name'  => optional($t->teacherProfile)->display_name ?? $t->username,
                                      'profile_image' => optional($t->teacherProfile)->profile_image,
                                  ];
                              });
            return response()->json($teachers);
        }

        if ($action === 'folders' && $teacherName) {
            $teacher = Student::where('username', $teacherName)->where('role', 'teacher')->first();

            if ($teacher) {
                $baseQuery = fn($type) => Upload::where('user_id', $teacher->id)
                    ->where('filepath', 'like', "%{$type}%")
                    ->when($semester, fn($q) => $q->where('semester', $semester));

                $folders = [
                    ['name' => 'documents', 'icon' => '📄', 'count' => $baseQuery('documents')->count()],
                    ['name' => 'images',    'icon' => '🖼️', 'count' => $baseQuery('images')->count()],
                    ['name' => 'audio',     'icon' => '🎵', 'count' => $baseQuery('audio')->count()],
                    ['name' => 'video',     'icon' => '🎬', 'count' => $baseQuery('video')->count()],
                ];
                return response()->json($folders);
            }
        }

        if ($action === 'files' && $teacherName && $folderName) {
            $teacher = Student::where('username', $teacherName)->where('role', 'teacher')->first();

            if ($teacher) {
                $files = Upload::where('user_id', $teacher->id)
                               ->where('filepath', 'like', "%{$folderName}%")
                               ->when($semester, fn($q) => $q->where('semester', $semester))
                               ->get(['filename', 'filepath', 'semester']);
                return response()->json($files);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}
