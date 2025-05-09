<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class authController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
           session()->regenerate();

            return redirect()->intended('/')->with('success', 'Login successful');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users,login',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create the user
        $user = \App\Models\User::create([
            'name' => $request->name,
            'login' => $request->login,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'isAdmin' => false,
        ]);
        
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');

    }
    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}
