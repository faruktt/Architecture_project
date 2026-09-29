<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'author';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'company',
        'title',
        'country',
        'website',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor for avatar to return public URL
     * while database stores only the file name.
     */
    public function getAvatarAttribute($value): string
    {
        if (!$value) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Architect') . '&background=0f172a&color=fff';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset('uploads/' . basename($value));
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function approvedProjects(): HasMany
    {
        return $this->hasMany(Project::class)->where('status', 'approved');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function approvedProducts(): HasMany
    {
        return $this->hasMany(Product::class)->where('status', 'approved');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_follows', 'author_id', 'follower_id')->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_follows', 'follower_id', 'author_id')->withTimestamps();
    }

    public function isFollowedBy(?Author $author): bool
    {
        if (!$author) {
            return false;
        }
        return $this->followers()->where('follower_id', $author->id)->exists();
    }
}
