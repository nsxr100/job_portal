<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==========================================
    // REGISTRATION
    // ==========================================

    // 1. Show the Registration Form
    public function showRegister()
    {
        return view('auth.register');
    }

    // 2. Process the Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:applicant,employer' // Ensure they pick a valid role
        ]);

        // Create the user and hash the password for security
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Log the user in immediately after registering
        Auth::login($user);

        return redirect('/')->with('success', 'Account created successfully! You are now logged in.');
    }


    // 3. Show the Login Form
    public function showLogin()
    {
        return view('auth.login');
    }

    // 4. Process the Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Protect against session fixation attacks
            $request->session()->regenerate();

            // Optional: Redirect employers to their dashboard, applicants to home
            if (Auth::user()->role === 'employer') {
                return redirect()->intended('/employer/dashboard')->with('success', 'Welcome back!');
            }

            return redirect()->intended('/')->with('success', 'Logged in successfully!');
        }

        // If authentication fails, send them back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    // 5. Process Logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}