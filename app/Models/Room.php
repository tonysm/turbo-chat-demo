<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Parental\HasChildren;

class Room extends Model
{
    const DEFAULT_NAME = "New Room";

    use HasFactory;
    use HasChildren;

    protected $guarded = [];

    protected $childTypes = [
        'closed' => Room\Closed::class,
        'open' => Room\Open::class,
    ];

    public static function booted(): void
    {
        static::creating(function (Room $room) {
            if (auth()->check()) {
                $room->creator()->associate(auth()->user());
            }
        });
    }

    public static function createFor(array $attributes, $users)
    {
        return DB::transaction(function () use ($attributes, $users) {
            $room = static::create($attributes);
            $room->membership()->grantTo($users);
            return $room;
        });
    }

    public function scopeOpens(Builder $query): void
    {
        $query->where('type', 'open');
    }

    public function scopeCloseds(Builder $query): void
    {
        $query->where('type', 'closed');
    }

    public function become($model)
    {
        return tap(new $model($attributes = $this->getAttributes()), function ($instance) use ($model, $attributes) {
            $instance->setRawAttributes(array_merge($attributes, [
                'type' => array_search($model, $this->childTypes),
            ]));

            $instance->exists = true;

            $instance->setRelations($this->relations);
        });
    }

    public function membership()
    {
        return new Membership\Granter($this);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
