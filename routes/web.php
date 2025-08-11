<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\CreateBook;
use App\Livewire\MyBooks;
use App\Livewire\EditBookInstance;
use App\Livewire\Books;
use App\Livewire\BookInstance;
use App\Livewire\BookRequest;
use App\Livewire\WriteBookSummary;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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
    Route::get('books/mybooks/requests', \App\Livewire\MyBookRequests::class)->name('mybooks.requests');
    Route::get('books/loans', \App\Livewire\BookLoans::class)->name('books.loans');
    Route::get('books/mybooks/{bookid}/summary', WriteBookSummary::class)->name('books.summary.write');
    Route::get('books/{book}/write-summary', WriteBookSummary::class)->name('books.summary.write');
    Route::get('books/mybooks/{bookid}/edit', EditBookInstance::class)->name('books.editBook');
    Route::get('notifications', \App\Livewire\NotificationsPage::class)->name('notifications.index');
});

Route::prefix('books')->group(function () {
    Route::get('/', Books::class)->name('books.all');
    Route::get('/{id}', BookInstance::class)->name('books.instance');
    Route::get('/{bookInstance}/request', BookRequest::class)->name('books.instance.request');
    Route::get('/{bookId}/guest-summary', \App\Livewire\GuestWriteSummary::class)->name('books.guest.write.summary');
});

Route::get('contact', \App\Livewire\ContactUs::class)->name('contact');
Route::get('admin/user-queries', \App\Livewire\UserQueriesList::class)->name('admin.user-queries');

require __DIR__ . '/auth.php';
