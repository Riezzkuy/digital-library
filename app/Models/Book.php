<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function copies()
    {
        return $this->hasMany(Copy::class, 'book_id', 'id');
    }

    public function getCoverUrl()
    {
        $isUrl = str_contains($this->cover, 'http');

        return ($isUrl) ? $this->cover : Storage::disk('public')->url($this->cover);
    }

    public function scopeSearch($query, $search = '')
    {
        $query->where('title', 'like', "%{$search}%");
    }
}
