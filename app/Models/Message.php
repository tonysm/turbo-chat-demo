<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    use Message\Pagination;

    protected $guarded = [];

    public static function booted(): void
    {
        static::creating(function (Message $message) {
            if (auth()->check() && ! $message->creator_id) {
                $message->creator()->associate(auth()->user());
            }
        });
    }

    public function scopeOrdered(Builder $query, string $direction = 'ASC'): void
    {
        $query->orderBy('created_at', $direction);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
