<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LaravelBlog')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container nav-container">
            <a href="{{ route('blog.index') }}" class="logo">Laravel<span>Blog</span></a>
            <nav class="main-nav">
                <a href="{{ route('blog.index') }}">Home</a>
                @auth
                    <a href="{{ route('blog.create') }}">New Post</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="btn-link">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary-sm">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="site-main container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} LaravelBlog. Built with simplicity for mobile & desktop.</p>
        </div>
    </footer>
</body>
</html>
