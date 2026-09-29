<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'content',
        'status',
        'order',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
