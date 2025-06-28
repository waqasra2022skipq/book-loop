<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\CreateBook;
use App\Livewire\MyBooks;
use App\Livewire\EditBookInstance;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('books/create', CreateBook::class)->name('books.create');
    Route::get('books/mybooks', MyBooks::class)->name('books.mybooks');
    Route::get('books/edit/{bookid}', EditBookInstance::class)->name('books.editBook');
});

require __DIR__ . '/auth.php';
