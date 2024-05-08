<?php

namespace App\Models\Membership;

use App\Models\Membership;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Granter
{
    public function __construct(private Room $room)
    {
    }

    public function grantTo(Collection|User $users)
    {
        if ($users instanceof User) {
            $users = Collection::wrap([$users->id]);
        } else if ($users->first() instanceof User) {
            $users = $users->pluck('id');
        }

        Membership::upsert(
            $users->map(fn ($userId) => ['user_id' => $userId, 'room_id' => $this->room->id])->all(),
            uniqueBy: ['user_id', 'room_id'],
            update: [],
        );
    }

    public function revokeFrom(Collection|User $users)
    {
        if ($users instanceof User) {
            $users = Collection::wrap([$users->id]);
        } else if ($users->first() instanceof User) {
            $users = $users->pluck('id');
        }

        $this->room->memberships()->whereIn('user_id', $users->all())->delete();
    }

    public function revise(Collection|User $granted, Collection|User $revoked)
    {
        DB::transaction(function () use ($granted, $revoked) {
            $this->grantTo($granted);
            $this->revokeFrom($revoked);
        });
    }
}
