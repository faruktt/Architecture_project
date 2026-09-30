<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'subtitle',
        'excerpt',
        'content',
        'country',
        'city',
        'category',
        'lead_architects',
        'associate',
        'year',
        'build_year',
        'area',
        'photographer',
        'illustrations',
        'phone_number',
        'web_address',
        'collaboration',
        'featured_image',
        'gallery',
        'status',
        'admin_notes',
        'is_featured',
        'is_spotlight',
        'is_hero_story',
        'is_property_sell',
        'price',
        'views_count',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_spotlight' => 'boolean',
        'is_hero_story' => 'boolean',
        'is_property_sell' => 'boolean',
    ];

    /**
     * Accessor for featured_image to return full public URL
     * while database stores only the file name.
     */
    public function getFeaturedImageAttribute($value): string
    {
        if (!$value) {
            return 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset('uploads/' . basename($value));
    }

    /**
     * Accessor for gallery images array formatted as public URLs.
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

    public function getGalleryImagesAttribute(): array
    {
        return $this->gallery;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSpotlight(Builder $query): Builder
    {
        return $query->where('is_spotlight', true);
    }

    public function scopePropertySell(Builder $query): Builder
    {
        return $query->where('is_property_sell', true);
    }
}
