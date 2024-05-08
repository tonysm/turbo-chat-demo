<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
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
}
