<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomMessagesController extends Controller
{
    public function index(Request $request, Room $room)
    {
        return view('messages.index', [
            'messages' => $room->messages()
                ->with(['creator'])
                ->when($request->input('before'), fn ($query, $before) => (
                    $query->pageBefore($room->messages()->find($before))
                ))
                ->when($request->input('after'), fn ($query, $after) => (
                    $query->pageAfter($room->messages()->find($after))
                ))
                ->when(! $request->hasAny(['before', 'after']), fn ($query) => $query->lastPage())
                ->get(),
        ]);
    }

    public function store(Request $request, Room $room)
    {
        $message = $room->messages()->create($request->validate([
            'content' => ['required'],
        ]));

        return turbo_stream()->prepend($message)->target(dom_id($room, 'messages'));
    }
}
