<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    // ─────────────── Helpers ────────────────────────────────────────────────

    private function allProjects(): array
    {
        return require app_path('Data/marketplace_projects.php');
    }

    private function allInternships(): array
    {
        return require app_path('Data/marketplace_internships.php');
    }

    private function getProfile(): array
    {
        return session('marketplace_profile', []);
    }

    private function getMyProjects(): array
    {
        return session('marketplace_projects', []);
    }

    private function hasCompletedOnboarding(): bool
    {
        $profile = $this->getProfile();
        return !empty($profile['skills']) && !empty($profile['interests']);
    }

    /**
     * Auto-derive college year from the student's current semester (from DB).
     * Sem 1-2 → Year 1 | Sem 3-4 → Year 2 | Sem 5+ → Year 3
     */
    private function autoDetectYear(): int
    {
        $semester = optional(Auth::user()->studentProfile)->semester ?? 2;
        if ($semester <= 2) return 1;
        if ($semester <= 4) return 2;
        return 3;
    }

    /**
     * Get rich student info from the database for display.
     */
    private function getStudentInfo(): array
    {
        $user    = Auth::user();
        $profile = optional($user->studentProfile);
        $name    = trim($user->name ?? '');

        return [
            'display_name'  => $name ?: ucfirst($user->username),
            'username'      => $user->username,
            'branch'        => $profile->branch ?? 'CS',
            'semester'      => $profile->semester ?? 0,
            'semester_label'=> $this->semesterLabel($profile->semester ?? 0),
            'enrollment_no' => $profile->enrollment_no ?? '—',
            'year'          => $this->autoDetectYear(),
        ];
    }

    private function semesterLabel(int $sem): string
    {
        $map = [
            1 => '1st Sem', 2 => '2nd Sem', 3 => '3rd Sem',
            4 => '4th Sem', 5 => '5th Sem', 6 => '6th Sem',
            7 => '7th Sem', 8 => '8th Sem',
        ];
        return $map[$sem] ?? "Sem $sem";
    }

    // ─────────────── Routes ──────────────────────────────────────────────────

    /**
     * Root: redirect to onboarding or feed.
     */
    public function index()
    {
        if ($this->hasCompletedOnboarding()) {
            return redirect()->route('marketplace.feed');
        }
        return redirect()->route('marketplace.onboarding');
    }

    /**
     * Show the 2-step onboarding wizard (Year is now auto-detected from DB).
     */
    public function onboarding()
    {
        if ($this->hasCompletedOnboarding()) {
            return redirect()->route('marketplace.feed');
        }
        $studentInfo = $this->getStudentInfo();
        return view('student.marketplace.onboarding', compact('studentInfo'));
    }

    /**
     * Save onboarding profile to session.
     * Year is auto-detected from DB — not submitted by the form.
     */
    public function saveProfile(Request $request)
    {
        $validated = $request->validate([
            'skills'      => 'required|array|min:1',
            'skills.*'    => 'string|max:100',
            'interests'   => 'required|array|min:1',
            'interests.*' => 'string|max:100',
        ]);

        session([
            'marketplace_profile' => [
                'year'      => $this->autoDetectYear(),   // from DB
                'skills'    => $validated['skills'],
                'interests' => $validated['interests'],
            ],
        ]);

        return redirect()->route('marketplace.feed');
    }

    /**
     * Project feed with filtering and sorting.
     */
    public function feed(Request $request)
    {
        if (!$this->hasCompletedOnboarding()) {
            return redirect()->route('marketplace.onboarding');
        }

        $profile    = $this->getProfile();
        $myProjects = $this->getMyProjects();
        $allProjects = $this->allProjects();
        $studentInfo = $this->getStudentInfo();

        // Always keep year in sync with DB semester (sem may have changed)
        $year      = $studentInfo['year'];
        $skills    = $profile['skills'];
        $interests = $profile['interests'];

        // ── Filters from query string ──────────────────────────────────────
        $filterDifficulty = $request->get('difficulty', 'All');
        $filterTag        = $request->get('tag', 'All');
        $sortBy           = $request->get('sort', 'recommended');

        // ── Base filter: match year ────────────────────────────────────────
        $projects = array_filter($allProjects, fn($p) => in_array($year, $p['year']));

        // ── Difficulty filter ──────────────────────────────────────────────
        if ($filterDifficulty !== 'All') {
            $projects = array_filter($projects, fn($p) => $p['difficulty'] === $filterDifficulty);
        }

        // ── Tag filter ─────────────────────────────────────────────────────
        if ($filterTag !== 'All') {
            $projects = array_filter($projects, fn($p) => in_array($filterTag, $p['tags']));
        }

        // ── Score each project for relevance ───────────────────────────────
        $projects = array_map(function ($p) use ($skills, $interests) {
            $skillMatch    = count(array_intersect($p['skillsRequired'], $skills));
            $interestMatch = count(array_intersect($p['interests'], $interests));
            $p['_score']   = ($skillMatch * 2) + ($interestMatch * 3);
            return $p;
        }, $projects);

        // ── Sort ───────────────────────────────────────────────────────────
        usort($projects, function ($a, $b) use ($sortBy) {
            if ($sortBy === 'recommended') {
                return $b['_score'] <=> $a['_score'];
            }
            if ($sortBy === 'difficulty_asc') {
                $order = ['Normal' => 1, 'Good' => 2, 'Stretch' => 3];
                return ($order[$a['difficulty']] ?? 2) <=> ($order[$b['difficulty']] ?? 2);
            }
            if ($sortBy === 'difficulty_desc') {
                $order = ['Normal' => 1, 'Good' => 2, 'Stretch' => 3];
                return ($order[$b['difficulty']] ?? 2) <=> ($order[$a['difficulty']] ?? 2);
            }
            if ($sortBy === 'placement') {
                return (int)$b['placementRelevant'] <=> (int)$a['placementRelevant'];
            }
            return 0;
        });

        $projects = array_values($projects);

        return view('student.marketplace.feed', compact(
            'projects', 'profile', 'myProjects',
            'filterDifficulty', 'filterTag', 'sortBy', 'studentInfo'
        ));
    }

    /**
     * Internship listings.
     */
    public function internships()
    {
        if (!$this->hasCompletedOnboarding()) {
            return redirect()->route('marketplace.onboarding');
        }

        $internships = $this->allInternships();
        $studentInfo = $this->getStudentInfo();
        return view('student.marketplace.internships', compact('internships', 'studentInfo'));
    }

    /**
     * My Projects — started / completed tracking.
     */
    public function myProjects()
    {
        if (!$this->hasCompletedOnboarding()) {
            return redirect()->route('marketplace.onboarding');
        }

        $myProjects  = $this->getMyProjects();
        $allProjects = $this->allProjects();
        $profile     = $this->getProfile();
        $studentInfo = $this->getStudentInfo();

        // Build indexed lookup
        $indexed = [];
        foreach ($allProjects as $p) {
            $indexed[$p['id']] = $p;
        }

        $started   = [];
        $completed = [];
        foreach ($myProjects as $id => $status) {
            if (!isset($indexed[$id])) continue;
            if ($status === 'started')   $started[]   = $indexed[$id];
            if ($status === 'completed') $completed[] = $indexed[$id];
        }

        // Stats
        $stats = [
            'Normal'  => count(array_filter($completed, fn($p) => $p['difficulty'] === 'Normal')),
            'Good'    => count(array_filter($completed, fn($p) => $p['difficulty'] === 'Good')),
            'Stretch' => count(array_filter($completed, fn($p) => $p['difficulty'] === 'Stretch')),
        ];

        return view('student.marketplace.my-projects', compact(
            'started', 'completed', 'stats', 'profile', 'myProjects', 'studentInfo'
        ));
    }

    /**
     * Show profile edit page — pre-fills DB data.
     */
    public function profile()
    {
        if (!$this->hasCompletedOnboarding()) {
            return redirect()->route('marketplace.onboarding');
        }
        $profile     = $this->getProfile();
        $studentInfo = $this->getStudentInfo();
        return view('student.marketplace.profile', compact('profile', 'studentInfo'));
    }

    /**
     * Update profile in session.
     * Year stays auto-detected from DB.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'skills'      => 'required|array|min:1',
            'skills.*'    => 'string|max:100',
            'interests'   => 'required|array|min:1',
            'interests.*' => 'string|max:100',
        ]);

        session([
            'marketplace_profile' => [
                'year'      => $this->autoDetectYear(),   // always from DB
                'skills'    => $validated['skills'],
                'interests' => $validated['interests'],
            ],
        ]);

        return redirect()->route('marketplace.feed')->with('success', 'Profile updated! Showing fresh recommendations.');
    }

    /**
     * Mark / unmark a project as started or completed.
     */
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:started,completed,remove',
        ]);

        $myProjects = $this->getMyProjects();

        if ($validated['status'] === 'remove') {
            unset($myProjects[$id]);
        } else {
            $myProjects[$id] = $validated['status'];
        }

        session(['marketplace_projects' => $myProjects]);

        return redirect()->back()->with('success', 'Project status updated!');
    }

    /**
     * Reset all marketplace session data.
     */
    public function reset()
    {
        session()->forget(['marketplace_profile', 'marketplace_projects']);
        return redirect()->route('marketplace.onboarding');
    }
}
