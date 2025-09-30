<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminAuthController extends Controller
{
    /**
     * Show the superadmin login form.
     */
    public function showLogin()
    {
        // If already logged in as superadmin, redirect to dashboard
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        return view('superadmin.auth.login');
    }

    /**
     * Handle superadmin login attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Check if user exists and is superadmin
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !$user->isSuperAdmin()) {
            return back()->withErrors([
                'email' => 'Access denied. Superadmin privileges required.',
            ])->withInput($request->except('password'));
        }

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('superadmin.dashboard'));
        }

        return back()->withErrors([
            'password' => 'The provided credentials are incorrect.',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle superadmin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('superadmin.auth.login');
    }
}
