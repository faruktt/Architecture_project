<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'type',
        'image',
        'gallery',
        'has_audio',
        'has_video',
        'badge_text',
        'summary',
        'content',
        'author_name',
        'status',
        'published_at',
    ];

    protected $casts = [
        'gallery' => 'array',
        'has_audio' => 'boolean',
        'has_video' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Scope for published articles/news
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for articles only
     */
    public function scopeArticles($query)
    {
        return $query->where('type', 'article');
    }

    /**
     * Scope for news only
     */
    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    /**
     * Accessor for image to return public URL
     * while database stores only the file name.
     */
    public function getImageAttribute($value): string
    {
        if (!$value) {
            return 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset('uploads/' . basename($value));
    }

    /**
     * Accessor for gallery images array.
     */
    public function getGalleryAttribute($value): array
    {
        $gallery = is_string($value) ? json_decode($value, true) : $value;
        if (!is_array($gallery)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($item) {
            if (!$item) return null;
            if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                return $item;
            }
            return asset('uploads/' . basename($item));
        }, $gallery)));
    }
}
