<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'read_time',
        'is_published',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Relationship with user (Author)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->latest('published_at');
    }

    /**
     * Scope for category filter
     */
    public function scopeFilterByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $term)
    {
        if (!empty($term)) {
            return $query->where(function($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('excerpt', 'like', "%{$term}%")
                  ->orWhere('content', 'like', "%{$term}%");
            });
        }
        return $query;
    }
}
