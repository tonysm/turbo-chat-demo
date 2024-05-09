<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function index(Request $request)
    {
        return to_route('rooms.show', $request->user()->memberships()->latest('updated_at')->first()->room);
    }

    public function show(Request $request, Room $room)
    {
        return view('rooms.show', [
            'room' => $room,
            'messages' => $this->findMessages($request, $room),
        ]);
    }

    private function findMessages(Request $request, Room $room)
    {
        if ($request->has('message_id') && ($message = $room->messages()->with(['creator'])->find($request->input('message_id')))) {
            return $room->messagesAround($message);
        }

        return $room->messages()->with(['creator'])->lastPage()->get();
    }
}
