<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MarketplaceBranchAccess
{
    /**
     * Allowed branches for the CS/IT Project Marketplace.
     * Only CS and Electronics branch students can access this feature.
     */
    protected array $allowedBranches = ['CS', 'Electronics', 'cs', 'electronics', 'Computer Science', 'computer science'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $studentBranch = optional($user->studentProfile)->branch;

        // Normalize and check branch
        $isAllowed = collect($this->allowedBranches)->contains(function ($branch) use ($studentBranch) {
            return stripos($studentBranch ?? '', $branch) !== false || stripos($branch, $studentBranch ?? '') !== false;
        });

        if (!$isAllowed) {
            return response()->view('student.marketplace.branch-restricted', [
                'branch' => $studentBranch ?? 'Unknown',
            ], 403);
        }

        return $next($request);
    }
}
