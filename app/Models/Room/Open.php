<?php

namespace App\Models\Room;

use App\Models\Room;
use App\Models\User;
use Parental\HasParent;

class Open extends Room
{
    use HasParent;

    public static function booted(): void
    {
        static::saved(function (Open $room) {
            if ($room->wasChanged('type') || $room->wasRecentlyCreated) {
                $room->membership()->grantTo(User::all());
            }
        });
    }
}
