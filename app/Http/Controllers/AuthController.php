<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('blog.index');
        }
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email address daalna zaroori hai.',
            'email.email' => 'Sahi email address likhein.',
            'password.required' => 'Password daalna zaroori hai.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('blog.index'))
                             ->with('success', 'Aapka swagat hai! Login safal raha.');
        }

        return back()->withErrors([
            'email' => 'Galat email ya password diya gaya hai.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('blog.index');
        }
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Apna naam likhein.',
            'email.required' => 'Email address zaroori hai.',
            'email.unique' => 'Ye email pehle se registered hai.',
            'password.required' => 'Password banana zaroori hai.',
            'password.min' => 'Password kam se kam 6 akshar ka hona chahiye.',
            'password.confirmed' => 'Password confirm match nahi ho raha.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('blog.index')
                         ->with('success', 'Khata safaltapoorvak ban gaya! Aap login hain.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('blog.index')
                         ->with('info', 'Aap logout ho chuke hain.');
    }
}
