<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Copy extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_borrowed', false);
    }

    public function scopeQueue($query)
    {
        $query->with('loans')->whereNull('loaned_at')->count();
    }
}
