@extends('layouts.app')

@section('title', 'Login - LaravelBlog')

@section('styles')
<style>
    .auth-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(20px, 6vw, 50px) 0;
    }
    .auth-card {
        background: var(--surface-color);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-lg);
        padding: clamp(24px, 5vw, 40px);
        width: 100%;
        max-width: 440px;
        box-shadow: var(--shadow);
    }
    .auth-header {
        text-align: center;
        margin-bottom: 28px;
    }
    .auth-header h1 {
        font-size: clamp(24px, 5vw, 30px);
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
    }
    .auth-header p {
        color: var(--text-muted);
        font-size: 15px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--text-main);
    }
    .form-control {
        width: 100%;
        min-height: 46px;
        padding: 10px 14px;
        background: #0d1326;
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-sm);
        color: var(--text-main);
        font-size: 16px;
        font-family: inherit;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        font-size: 14px;
        color: var(--text-muted);
        cursor: pointer;
    }
    .form-check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent);
        cursor: pointer;
    }
    .auth-footer {
        margin-top: 24px;
        text-align: center;
        font-size: 14px;
        color: var(--text-muted);
    }
    .auth-footer a {
        color: var(--accent);
        font-weight: 600;
    }
    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Swagat Hai</h1>
                <p>Apne account me login karein aur padhna ya likhna shuru karein.</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="apna.email@domain.com" 
                        required 
                        autocomplete="email"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="••••••••" 
                        required 
                        autocomplete="current-password"
                    >
                </div>

                <label class="form-check">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Mujhe yaad rakhein (Remember me)</span>
                </label>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <span>Login Karein</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="auth-footer">
                Khata nahi hai? <a href="{{ route('register') }}">Naya account banayein</a>
            </div>
        </div>
    </div>
</div>
@endsection
