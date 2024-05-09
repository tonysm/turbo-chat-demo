<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Room\Closed;
use App\Models\User;
use Illuminate\Http\Request;

class ClosedsController extends Controller
{
    public function create()
    {
        return view('rooms.closeds.create', [
            'room' => Closed::make([
                'name' => Closed::DEFAULT_NAME,
            ]),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $room = Closed::createFor(
            attributes: $request->validate([
                'name' => ['required'],
            ]),
            users: $request->collect('users'),
        );

        return to_route('rooms.show', $room);
    }

    public function edit($room)
    {
        $room = Room::findOrFail($room)->become(Closed::class);

        $selectedUsersIds = $room->memberships()->pluck('user_id')->all();
        [$selectedUsers, $unselectedUsers] = User::query()->orderBy('name')->get()->partition(fn ($user) => in_array($user->id, $selectedUsersIds));

        return view('rooms.closeds.edit', [
            'room' => $room,
            'selectedUsers' => $selectedUsers,
            'unselectedUsers' => $unselectedUsers,
        ]);
    }

    public function update(Request $request, $room)
    {
        $room = Room::findOrFail($room)->become(Closed::class);

        $room->update($request->validate([
            'name' => ['required'],
        ]));

        $room->membership()->revise($grantees = $this->grantees($request->collect('users')), $this->revokees($room, $grantees));

        return to_route('rooms.show', $room);
    }

    private function grantees($ids)
    {
        return User::query()->findMany($ids);
    }

    private function revokees($room, $grantees)
    {
        return $room->memberships()->whereNotIn('user_id', $grantees->pluck('id')->all())->with('user')->get()->map->user;
    }
}
