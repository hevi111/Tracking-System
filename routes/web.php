<?php

use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
    'reset' => false,
    'logout' => false,
]);

Route::get('/logout', function() {

    auth()->logout();

    return redirect()->route('home');
})->name('logout');
    

Route::get('/', App\Livewire\Home::class)->name('home');
    
Route::middleware(['auth'])->group(function () {
    Route::get('/{type}',  App\Livewire\CategorySelector::class)->name('type');
    Route::get('/folder/{group}',  App\Livewire\GroupSelector::class)->name('folder.index');
});