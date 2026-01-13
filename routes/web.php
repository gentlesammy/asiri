<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\PublicProfileController;
use App\Http\Controllers\users\ProfileController as UsersProfileController;
use App\Http\Controllers\MessageController;



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


Route::get('/account-inactive', function () {
    return view('account_inactive');
})->name('account.inactive');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'status.check'])->name('dashboard');

Route::middleware(['auth', 'status.check'])->group(function () {
    Route::get('/profile', [UsersProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/update-avatar', [UsersProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::patch('/profile', [UsersProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UsersProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/messages', [MessageController::class, 'showMessageList'])->name('site.messages');

});

//message route
Route::controller(MessageController::class)->group(function(){
    Route::post('/send-message/{username}', 'sendMessage')->middleware('guest')->name('site.send_message');
});

require __DIR__.'/auth.php';
