<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'manufacturer',
        'manufacturer_logo',
        'category',
        'country_region',
        'has_bim',
        'is_property_sell',
        'price',
        'website_url',
        'phone',
        'email',
        'featured_image',
        'gallery',
        'short_description',
        'use_description',
        'applications',
        'characteristics',
        'specifications',
        'status',
        'views_count',
        'admin_notes',
    ];

    protected $casts = [
        'gallery' => 'array',
        'specifications' => 'array',
        'has_bim' => 'boolean',
        'is_property_sell' => 'boolean',
    ];

    /**
     * Accessor for featured_image to return public URL
     * while database stores only the file name.
     */
    public function getFeaturedImageAttribute($value): string
    {
        if (!$value) {
            return 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80';
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

    public function getGalleryImagesAttribute(): array
    {
        return $this->gallery;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(ProductInquiry::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
