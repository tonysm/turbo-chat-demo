<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
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
}
