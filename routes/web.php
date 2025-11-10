<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;

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

// Route สำหรับ Live Search (ที่ JavaScript เรียก)
Route::get('/live-search', [SearchController::class, 'liveSearch'])->name('search.live');

// Route สำหรับ Search ปกติ (กด Enter)
Route::get('/search', [SearchController::class, 'fullSearch'])->name('search.full');

// Route สำหรับ AI Search (กด Atom)
Route::get('/ai-search', [SearchController::class, 'aiSearch'])->name('search.ai');

require __DIR__.'/auth.php';
