<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of system users.
     */
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role');

        $query = User::query();

        if ($roleFilter && in_array($roleFilter, ['admin', 'spv', 'user'])) {
            $query->where('role', $roleFilter);
        }

        $users = $query->orderBy('id', 'desc')->get();

        // Calculate Overview Statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalSpvs = User::where('role', 'spv')->count();
        $totalOperators = User::where('role', 'user')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmins',
            'totalSpvs',
            'totalOperators',
            'roleFilter'
        ));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:admin,spv,user',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Pilihan role tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        // Generate clean username from name
        $baseUsername = Str::slug($validated['name'], '_');
        if (empty($baseUsername)) {
            $baseUsername = explode('@', $validated['email'])[0];
        }
        $username = $baseUsername;
        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $count;
            $count++;
        }

        // Color coding for avatar based on role
        $bgColor = match ($validated['role']) {
            'admin' => '0284c7',
            'spv' => 'ec4899',
            default => '10b981',
        };

        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=' . $bgColor . '&color=fff';

        User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'avatar' => $avatarUrl,
        ]);

        $roleLabel = match ($validated['role']) {
            'admin' => 'Administrator',
            'spv' => 'Supervisor (SPV)',
            default => 'Operator Gudang',
        };

        return redirect()->route('admin.users.index')
            ->with('success', "User baru \"{$validated['name']}\" ({$roleLabel}) berhasil ditambahkan!");
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,spv,user',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh user lain.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Pilihan role tidak valid.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Update avatar with new name and role color
        $bgColor = match ($validated['role']) {
            'admin' => '0284c7',
            'spv' => 'ec4899',
            default => '10b981',
        };
        $user->avatar = 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=' . $bgColor . '&color=fff';

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Data user \"{$user->name}\" berhasil diperbarui!");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$userName}\" berhasil dihapus dari sistem.");
    }
}
