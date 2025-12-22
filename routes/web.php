<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\site\PublicProfileController;



// pages route

Route::get('/', function () {
    return view('site.index');
});

Route::controller(PageController::class)->group(function(){
    Route::get("/about", "show_aboutpage")->name("site.about");
    Route::get("/terms", "show_termspage")->name("site.terms");
    Route::get("/privacy", "show_privacypage")->name("site.privacy");
});

// public profile route
Route::get('/user/{username}', [PublicProfileController::class, 'fetch_profile'])->name('site.public_profile');  



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
