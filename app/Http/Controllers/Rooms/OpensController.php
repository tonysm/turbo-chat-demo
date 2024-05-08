<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Room\Open;
use App\Models\User;
use Illuminate\Http\Request;

class OpensController extends Controller
{
    public function create()
    {
        return view('rooms.opens.create', [
            'room' => Open::make([
                'name' => Open::DEFAULT_NAME,
            ]),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $room = Open::createFor(
            attributes: $request->validate([
                'name' => ['required'],
            ]),
            users: $request->user(),
        );

        return to_route('rooms.show', $room);
    }

    public function edit($room)
    {
        $room = Room::findOrFail($room)->become(Open::class);

        return view('rooms.opens.edit', [
            'room' => $room,
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $room)
    {
        $room = Room::findOrFail($room)->become(Open::class);

        $room->update($request->validate([
            'name' => ['required'],
        ]));

        return to_route('rooms.show', $room);
    }
}
