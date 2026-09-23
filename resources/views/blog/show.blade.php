@extends('layouts.app')

@section('title', $post->title)

@section('content')
<article class="single-post">
    <div class="post-meta-top">
        <span class="badge">{{ $post->category->name ?? 'General' }}</span>
        <time datetime="{{ $post->created_at }}">{{ $post->created_at->format('M d, Y') }}</time>
    </div>
    
    <h1 class="post-title">{{ $post->title }}</h1>
    <p class="post-author">By {{ $post->user->name ?? 'Admin' }}</p>

    <div class="post-body">
        {!! nl2br(e($post->content)) !!}
    </div>

    <div class="post-actions">
        <a href="{{ route('blog.index') }}" class="btn-secondary">&larr; Back to Feed</a>
    </div>
</article>
@endsection
