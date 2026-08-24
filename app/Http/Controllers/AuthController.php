<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Generate dynamic Security CAPTCHA challenge.
     */
    public function generateCaptcha(): JsonResponse
    {
        $operators = ['+', '-'];
        $op = $operators[array_rand($operators)];

        if ($op === '+') {
            $num1 = rand(3, 15);
            $num2 = rand(2, 12);
            $ans = $num1 + $num2;
        } else {
            $num1 = rand(10, 25);
            $num2 = rand(1, $num1 - 1);
            $ans = $num1 - $num2;
        }

        session(['captcha_answer' => (string) $ans]);

        return response()->json([
            'success' => true,
            'question' => "Berapa hasil {$num1} {$op} {$num2} ?",
            'num1' => $num1,
            'operator' => $op,
            'num2' => $num2,
        ]);
    }

    /**
     * Verify Captcha token or session math answer.
     */
    protected function verifyCaptcha(Request $request): bool
    {
        // 1. If Google reCAPTCHA secret key is configured in .env or config/services.php
        $recaptchaSecret = config('services.recaptcha.secret_key') ?: env('RECAPTCHA_SECRET_KEY');
        $gRecaptchaResponse = $request->input('g-recaptcha-response');

        if (! empty($recaptchaSecret) && ! empty($gRecaptchaResponse)) {
            try {
                $response = Http::timeout(3)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecret,
                    'response' => $gRecaptchaResponse,
                    'remoteip' => $request->ip(),
                ]);

                if ((bool) $response->json('success')) {
                    return true;
                }
            } catch (\Exception $e) {
                // fallback to session captcha if network request fails
            }
        }

        // 2. Built-in Security CAPTCHA verification
        $userAnswer = trim((string) $request->input('captcha_answer'));
        $sessionAnswer = (string) session('captcha_answer');

        if (empty($sessionAnswer) || empty($userAnswer)) {
            return false;
        }

        // Clear captcha answer after single check
        session()->forget('captcha_answer');

        return $userAnswer === $sessionAnswer;
    }

    /**
     * Classic Login (Username/Email & Password).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! $this->verifyCaptcha($request)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi Captcha Keamanan Gagal. Jawab Captcha dengan benar untuk membuktikan Anda bukan bot.',
                ], 422);
            }

            return back()->withInput()->withErrors([
                'captcha_answer' => 'Verifikasi Captcha Gagal! Silakan hitung & isi jawaban Captcha dengan benar.',
            ]);
        }

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
     * Direct Operator Authentication under SPV for Stock Retrieval.
     */
    public function quickUserAuth(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        if (! $this->verifyCaptcha($request)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi Captcha Keamanan Gagal. Jawab Captcha dengan benar untuk membuktikan Anda bukan bot.',
                ], 422);
            }

            return back()->withInput()->withErrors([
                'captcha_answer' => 'Verifikasi Captcha Gagal! Silakan hitung & isi jawaban Captcha dengan benar.',
            ]);
        }

        $supervisor = User::findOrFail($request->supervisor_id);

        // Get or create dedicated operator account for retrieval under this SPV
        $operator = User::firstOrCreate(
            ['username' => 'op_spv_' . $supervisor->id],
            [
                'name' => 'Operator ' . $supervisor->name,
                'email' => 'operator_spv_' . $supervisor->id . '@inventory.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'supervisor_id' => $supervisor->id,
                'avatar' => 'https://ui-avatars.com/api/?name=Operator+' . urlencode($supervisor->name) . '&background=0284c7&color=fff',
            ]
        );

        $operator->supervisor_id = $supervisor->id;
        $operator->save();

        Auth::login($operator);
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Autentikasi Operator (SPV Penanggung Jawab) berhasil.',
                'user' => $operator,
                'redirect' => '/stock/retrieval',
            ]);
        }

        return redirect('/stock/retrieval');
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
     * Get list of available Supervisors for quick selection.
     */
    public function getSupervisors(): JsonResponse
    {
        $supervisors = User::where('role', 'spv')
            ->select('id', 'name', 'email', 'avatar', 'username')
            ->orderBy('name', 'asc')
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
