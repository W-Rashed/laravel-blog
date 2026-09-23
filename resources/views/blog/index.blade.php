@extends('layouts.app')

@section('title', 'LaravelBlog - Modern Tech, Code & Design Insights')

@section('styles')
<style>
    /* Hero Section */
    .hero-section {
        padding: clamp(24px, 5vw, 48px) 0;
        text-align: center;
    }
    .hero-tag {
        display: inline-block;
        background: var(--accent-light);
        color: var(--accent);
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 16px;
    }
    .hero-title {
        font-size: clamp(28px, 6vw, 48px);
        font-weight: 800;
        line-height: 1.2;
        color: #ffffff;
        margin-bottom: 14px;
        letter-spacing: -0.02em;
    }
    .hero-subtitle {
        font-size: clamp(16px, 2.5vw, 19px);
        color: var(--text-muted);
        max-width: 620px;
        margin: 0 auto;
    }

    /* Controls Bar: Search & Filter */
    .controls-panel {
        background: var(--surface-color);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius);
        padding: 16px;
        margin: 32px 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .search-form {
        display: flex;
        gap: 10px;
        width: 100%;
    }
    .search-input {
        flex: 1;
        min-height: 44px;
        background: #0d1326;
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-sm);
        padding: 8px 14px;
        color: var(--text-main);
        font-size: 15px;
        font-family: inherit;
        outline: none;
    }
    .search-input:focus {
        border-color: var(--accent);
    }

    .categories-bar {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        -webkit-overflow-scrolling: touch;
    }
    .category-chip {
        padding: 8px 16px;
        background: #0d1326;
        border: 1px solid var(--surface-border);
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-muted);
        white-space: nowrap;
        transition: all 0.2s ease;
        min-height: 40px;
        display: inline-flex;
        align-items: center;
    }
    .category-chip:hover, .category-chip.active {
        background: var(--accent);
        color: #ffffff;
        border-color: var(--accent);
    }

    /* Blog Grid */
    .blog-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }
    @media (min-width: 720px) {
        .blog-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .controls-panel {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        .search-form {
            max-width: 320px;
        }
    }
    @media (min-width: 1024px) {
        .blog-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Blog Card */
    .blog-card {
        background: var(--surface-color);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .blog-card:hover {
        transform: translateY(-4px);
        border-color: rgba(99, 102, 241, 0.4);
    }
    .card-banner {
        height: 140px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 16px;
    }
    .card-banner-icon {
        width: 48px;
        height: 48px;
        color: rgba(255, 255, 255, 0.7);
    }
    .card-category-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(11, 16, 32, 0.85);
        color: #818cf8;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .card-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    .card-title a {
        transition: color 0.2s ease;
    }
    .card-title a:hover {
        color: var(--accent);
    }
    .card-excerpt {
        color: var(--text-muted);
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1;
    }
    .card-footer {
        border-top: 1px solid var(--surface-border);
        padding-top: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .read-more {
        color: var(--accent);
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .read-more:hover {
        text-decoration: underline;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--surface-color);
        border: 1px dashed var(--surface-border);
        border-radius: var(--radius);
        grid-column: 1 / -1;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="hero-section">
        <span class="hero-tag">✨ Gyan aur Code ki Duniya</span>
        <h1 class="hero-title">Behtar Developer aur Thinker Banein</h1>
        <p class="hero-subtitle">Laravel, web development, UI design aur modern tech trends par taaza lekh aur guides.</p>
    </div>

    <!-- Filter & Search Controls -->
    <div class="controls-panel">
        <div class="categories-bar">
            @foreach($categories as $key => $name)
                <a href="{{ route('blog.index', ['category' => $key, 'q' => $searchTerm]) }}" 
                   class="category-chip {{ $activeCategory === $key ? 'active' : '' }}">
                    {{ $name }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('blog.index') }}" method="GET" class="search-form">
            @if($activeCategory !== 'all')
                <input type="hidden" name="category" value="{{ $activeCategory }}">
            @endif
            <input 
                type="text" 
                name="q" 
                value="{{ $searchTerm }}" 
                placeholder="Lekh khojein..." 
                class="search-input"
            >
            <button type="submit" class="btn btn-secondary btn-sm">Khojein</button>
        </form>
    </div>

    <!-- Blog Posts Feed -->
    <div class="blog-grid">
        @forelse($posts as $post)
            <article class="blog-card">
                <div class="card-banner">
                    <svg class="card-banner-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="card-category-badge">{{ ucfirst($post->category) }}</span>
                </div>
                <div class="card-body">
                    <div class="card-meta">
                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : date('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ $post->read_time ?? '3 min read' }}</span>
                    </div>
                    <h2 class="card-title">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>
                    <p class="card-excerpt">
                        {{ Str::limit($post->excerpt, 120) }}
                    </p>
                    <div class="card-footer">
                        <span style="font-size: 13px; color: var(--text-muted);">
                            By {{ $post->author->name ?? 'Admin' }}
                        </span>
                        <a href="{{ route('blog.show', $post->slug) }}" class="read-more">
                            <span>Padhein</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <svg width="48" height="48" style="color: var(--text-muted); margin-bottom: 12px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 style="font-size: 18px; margin-bottom: 6px;">Koi lekh nahi mila</h3>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">Aapke filter ya search query se milta julata koi blog post abhi uplabdh nahi hai.</p>
                <a href="{{ route('blog.index') }}" class="btn btn-secondary btn-sm">Sabhi Lekh Dekhein</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
