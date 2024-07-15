<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getCoverUrl()
    {
       $isUrl = str_contains($this->cover, 'http');

       return ($isUrl) ? $this->cover : Storage::disk('public')->url($this->cover);
    }
}
