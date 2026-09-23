@extends('layouts.app')

@section('title', 'Register - LaravelBlog')

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
        max-width: 460px;
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
                <h1>Naya Khata Banayein</h1>
                <p>LaravelBlog community se judiye aur apne lekh publish kijiye.</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Poora Naam</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        class="form-control" 
                        placeholder="Aapka Naam" 
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="form-control" 
                        placeholder="apna.email@domain.com" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password (kam se kam 6 akshar)</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="••••••••" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control" 
                        placeholder="••••••••" 
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    <span>Khata Banayein (Register)</span>
                </button>
            </form>

            <div class="auth-footer">
                Pehle se account hai? <a href="{{ route('login') }}">Yahan Login karein</a>
            </div>
        </div>
    </div>
</div>
@endsection
