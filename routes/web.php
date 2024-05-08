<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePasswordController;
use App\Http\Controllers\Rooms\ClosedsController;
use App\Http\Controllers\Rooms\OpensController;
use App\Http\Controllers\RoomsController;
use App\Models\Room;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'rooms' => Room::query()
                ->where(fn ($query) => (
                    $query->opens()->orWhere->closeds()
                ))
                ->orderBy('name')
                ->get(),
        ]);
    })->name('dashboard');

    Route::prefix('rooms')->as('rooms.')->group(function () {
        Route::resource('opens', OpensController::class)->only(['create', 'store']);
        Route::resource('closeds', ClosedsController::class)->only(['create', 'store']);
    });

    Route::resource('rooms', RoomsController::class)->only(['show']);
});

Route::middleware('auth')->group(function () {
    Route::prefix('profile')->as('profile.')->group(function () {
        Route::singleton('password', ProfilePasswordController::class)->only(['edit', 'update']);
    });

    Route::get('/delete', [ProfileController::class, 'delete'])->name('profile.delete');
    Route::singleton('profile', ProfileController::class)->destroyable();
});

require __DIR__.'/auth.php';
