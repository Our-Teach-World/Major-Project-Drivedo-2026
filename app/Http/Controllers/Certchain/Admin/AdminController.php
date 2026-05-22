<?php

namespace App\Http\Controllers\Certchain\Admin;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\User;
use App\Models\BlockchainBlock;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct(protected BlockchainService $blockchain) {}

    public function dashboard()
    {
        $stats = [
            'total_users'        => User::count(),
            'total_events'       => Event::count(),
            'total_certificates' => Certificate::count(),
            'total_blocks'       => BlockchainBlock::count(),
            'emails_sent'        => Certificate::where('email_sent', true)->count(),
            'revoked'            => Certificate::where('status', 'revoked')->count(),
        ];

        $recentCertificates = Certificate::with(['event', 'issuer'])
            ->latest()->limit(8)->get();

        $chainStatus = $this->blockchain->validateChain();

       $monthlyStats = Certificate::selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
    ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
    ->groupBy('month')
    ->pluck('count', 'month')
    ->toArray();
        return view('admin.dashboard', compact('stats', 'recentCertificates', 'chainStatus', 'monthlyStats'));
    }

    // ── Users ──────────────────────────────────────────────
    public function users()
    {
        $users = User::with('roles')->latest()->paginate(15);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|confirmed',
            'employee_id' => 'nullable|unique:users',
            'department'  => 'nullable|string',
            'designation' => 'nullable|string',
            'role'        => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => bcrypt($data['password']),
            'employee_id' => $data['employee_id'] ?? null,
            'department'  => $data['department'] ?? null,
            'designation' => $data['designation'] ?? null,
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'employee_id' => 'nullable|unique:users,employee_id,' . $user->id,
            'department'  => 'nullable|string',
            'designation' => 'nullable|string',
            'role'        => 'required|exists:roles,name',
            'is_active'   => 'boolean',
        ]);

        $user->update([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'employee_id' => $data['employee_id'] ?? null,
            'department'  => $data['department'] ?? null,
            'designation' => $data['designation'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }

    // ── Blockchain Ledger ─────────────────────────────────
    public function blockchain()
    {
        $blocks     = BlockchainBlock::with('certificate.event')->orderBy('block_index', 'desc')->paginate(20);
        $chainValid = $this->blockchain->validateChain();
        return view('admin.blockchain', compact('blocks', 'chainValid'));
    }
}
