<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function show(Request $request)
    {
        return view('sidebar.show', [
            'memberships' => $request->user()
                ->memberships()
                ->with(['room'])
                ->get()
                ->sortBy('room.name')
                ->values(),
        ]);
    }
}
