<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $currentRoute = Route::currentRouteName();
        $userType = 'pelamar'; // default
        if ($currentRoute === 'login.hrd') {
            $userType = 'hrd';
        }

        return view('auth.login', ['userType' => $userType]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'hrd') {
                return redirect()->intended(route('dashboard.hrd'))->with('success', 'Selamat datang, HRD ' . $user->name . '!');
            } elseif ($user->role === 'pelamar') {
                return redirect()->intended(route('dashboard.pelamar'))->with('success', 'Selamat datang, ' . $user->name . '!');
            }
        }

        return back()->withInput($request->only('email'))
                     ->with('error', 'Email atau password salah. Silakan coba lagi.');
    }

    public function logout(Request $request)
    {
        $role = Auth::user()->role; // Get role before logout

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($role === 'hrd') {
            return redirect()->route('login.hrd')->with('success', 'Anda telah berhasil logout.');
        }

        return redirect()->route('login.pelamar')->with('success', 'Anda telah berhasil logout.');
    }
}