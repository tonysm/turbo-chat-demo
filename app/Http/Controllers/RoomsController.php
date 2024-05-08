<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomsController extends Controller
{
    public function show(Room $room)
    {
        return view('rooms.show', [
            'room' => $room,
        ]);
    }
}
