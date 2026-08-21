<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Classic Login (Username/Email & Password).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $request->login, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil.',
                    'user' => $user,
                    'redirect' => $user->isAdmin() ? '/admin/dashboard' : '/stock/retrieval',
                ]);
            }

            return redirect()->intended($user->isAdmin() ? '/admin/dashboard' : '/stock/retrieval');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial login tidak valid.',
            ], 401);
        }

        return back()->withErrors([
            'login' => 'Username/Email atau password salah.',
        ]);
    }

    /**
     * Direct SPV Authentication for Stock Retrieval (No Operator Account needed).
     */
    public function quickUserAuth(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        $supervisor = User::findOrFail($request->supervisor_id);
        $supervisor->supervisor_id = $supervisor->id;
        $supervisor->save();

        Auth::login($supervisor);
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Autentikasi SPV penanggung jawab berhasil.',
                'user' => $supervisor,
                'redirect' => '/stock/retrieval',
            ]);
        }

        return redirect()->intended('/stock/retrieval');
    }

    /**
     * Dynamic SPV Assignment during active session.
     */
    public function selectSupervisor(Request $request): JsonResponse
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $user->supervisor_id = $request->supervisor_id;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Supervisor penanggung jawab berhasil diperbarui.',
            'supervisor' => User::find($request->supervisor_id),
        ]);
    }

    /**
     * Redirect to Google OAuth.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth Callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'user',
                ]);
            } else {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? $user->avatar,
                ]);
            }

            Auth::login($user);
            request()->session()->regenerate();

            return redirect()->intended($user->isAdmin() ? '/admin/dashboard' : '/stock/retrieval');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login via Google: '.$e->getMessage());
        }
    }

    /**
     * Get list of available Supervisors for quick selection.
     */
    public function getSupervisors(): JsonResponse
    {
        $supervisors = User::where('role', 'admin')
            ->select('id', 'name', 'email', 'avatar', 'username')
            ->get();

        return response()->json([
            'success' => true,
            'supervisors' => $supervisors,
        ]);
    }

    /**
     * Get list of Operators for quick login switch.
     */
    public function getOperators(): JsonResponse
    {
        $operators = User::where('role', 'user')
            ->select('id', 'name', 'email', 'avatar', 'supervisor_id')
            ->with('supervisor:id,name')
            ->get();

        return response()->json([
            'success' => true,
            'operators' => $operators,
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Logout berhasil.']);
        }

        return redirect('/login');
    }
}
