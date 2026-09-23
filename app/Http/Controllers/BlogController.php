<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display blog feed with search and category filtering
     */
    public function index(Request $request)
    {
        $activeCategory = $request->query('category', 'all');
        $searchTerm = $request->query('q', '');

        $categories = [
            'all' => 'Sabhi Lekh',
            'tech' => 'Technology',
            'design' => 'Web Design',
            'coding' => 'Coding & Tips',
            'lifestyle' => 'Productivity'
        ];

        $posts = Post::published()
            ->filterByCategory($activeCategory)
            ->search($searchTerm)
            ->paginate(6)
            ->withQueryString();

        return view('blog.index', compact('posts', 'categories', 'activeCategory', 'searchTerm'));
    }

    /**
     * Display a single blog post
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->published()
            ->take(2)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Show form to write a new post
     */
    public function create()
    {
        $categories = [
            'tech' => 'Technology',
            'design' => 'Web Design',
            'coding' => 'Coding & Tips',
            'lifestyle' => 'Productivity'
        ];

        return view('blog.create', compact('categories'));
    }

    /**
     * Store new blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|string',
            'excerpt' => 'required|string|max:300',
            'content' => 'required|string',
        ]);

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);
        $wordCount = str_word_count(strip_tags($validated['content']));
        $readMinutes = max(1, (int) ceil($wordCount / 180));

        Post::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'read_time' => $readMinutes . ' min read',
            'is_published' => true,
            'published_at' => now(),
            'user_id' => auth()->id() ?? 1,
        ]);

        return redirect()->route('blog.index')
                         ->with('success', 'Naya blog post safaltapoorvak publish ho gaya!');
    }
}
