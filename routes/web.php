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
})->name('home');

Route::controller(PageController::class)->group(function(){
    Route::get("/about", "show_aboutpage")->name("site.about");
    Route::get("/terms", "show_termspage")->name("site.terms");
    Route::get("/privacy", "show_privacypage")->name("site.privacy");
});

// Anonymous Room Routes
Route::get('/room', \App\Livewire\Room\RoomFeed::class)->name('room.feed');
Route::get('/room/terms', function() {
    return view('site.room.terms');
})->name('room.terms');

// public profile route
Route::get('/user/{username}', [PublicProfileController::class, 'fetch_profile'])->middleware('ip.blocked')->name('site.public_profile');  
Route::get('/poll/{slug}', \App\Livewire\Public\PollVote::class)->name('poll.view');


Route::get('/account-inactive', function () {
    return view('account_inactive');
})->name('account.inactive');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $currentIp = request()->ip();
    
    // Get unique profile visits excluding user's own IP
    $uniqueVisits = $user->profileVisits()
        ->where('visitor_ip', '!=', $currentIp)
        ->distinct('visitor_ip')
        ->count('visitor_ip');
    
    return view('dashboard', compact('uniqueVisits'));
})->middleware(['auth', 'verified', 'status.check'])->name('dashboard');

Route::middleware(['auth', 'status.check'])->group(function () {
    Route::get('/dashboard/messages', \App\Livewire\Message::class)->name('dashboard.messages');
    Route::get('/dashboard/polls', \App\Livewire\Dashboard\Polls::class)->name('dashboard.polls');
    Route::get('/admin/polls', \App\Livewire\Admin\ManagePolls::class)->name('admin.polls');
    
    // User Profile Routes (replaces old controller routes)
    Route::get('/profile', \App\Livewire\UserProfile::class)->name('profile.edit');


    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/messages', [MessageController::class, 'showMessageList'])->name('site.messages');

});

//message route
Route::controller(MessageController::class)->group(function(){
    Route::post('/send-message/{username}', 'sendMessage')->middleware(['guest', 'ip.blocked'])->name('site.send_message');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', \App\Livewire\Admin\UserList::class)->name('admin.users');
    Route::get('/reports', \App\Livewire\Admin\ReportList::class)->name('admin.reports');
});

require __DIR__.'/auth.php';
