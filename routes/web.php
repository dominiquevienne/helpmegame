<?php

use App\Http\Controllers\BggProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/profile/update', [BggProfileController::class, 'store']);
Route::get('/collection/update', [ProfileController::class, 'updateCollection']);
Route::get('/game/update', [GameController::class, 'store']);

//Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);


require __DIR__.'/auth.php';
