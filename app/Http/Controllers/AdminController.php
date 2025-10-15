<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(){
        return Inertia::render('/Modules/AdminDashBoard/Dashboard');
    }

    // public function signIn(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);
    //     if (Auth::attempt($credentials)) {

    //         $request->session()->regenerate();
    //         $user = Auth::user();
    //         $roles = $user->getRoleNames();

            
    //         if ($roles->intersect(['Super Admin', 'Admin', 'staff'])->isNotEmpty()) {
    //             $token = $user->createToken('api-token')->plainTextToken;
    //             return redirect()->to(route('admin.dashboard.login'));
    //         }
    //     }
    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);
    // }

    public function signIn(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required','email'],
        'password' => ['required'],
    ]);

    // Optional "remember me" checkbox: <input type="checkbox" name="remember">
    $remember = (bool) $request->boolean('remember');

    if (! Auth::attempt($credentials, $remember)) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();

    $user = Auth::user();


    if ($user->roles()->exists()) {
        // If you actually use API, keep token; else remove this line
        // $token = $user->createToken('api-token')->plainTextToken;

        return redirect()->intended(route('admin.dashboard.login'));
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return back()->withErrors([
        'email' => 'Your account has no role assigned. Please contact an administrator.',
    ]);
}
}
