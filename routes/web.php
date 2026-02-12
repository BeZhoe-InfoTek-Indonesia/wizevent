<?php

use App\Livewire\Event\Checkout;
use App\Livewire\Event\EventDetail;
use App\Livewire\Event\EventList;
use App\Livewire\Order\BookingConfirmation;
use App\Livewire\Order\OrderStatus;

Route::get('/events', EventList::class)->name('events.index');
Route::get('/events/{slug}', EventDetail::class)->name('events.show');
Route::get('/events/{slug}/checkout', Checkout::class)->name('events.checkout');
Route::get('/events/{slug}/calendar', [\App\Http\Controllers\EventController::class, 'downloadCalendar'])->name('events.calendar');

// Order routes
Route::prefix('orders')->name('orders.')->middleware(['auth'])->group(function () {
    Route::get('/{orderNumber}/confirmation', BookingConfirmation::class)->name('confirmation');
    Route::get('/{orderNumber}', OrderStatus::class)->name('status');
    Route::get('/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('invoice');
});

Route::get('/tickets/{ticket}', [\App\Http\Controllers\TicketController::class, 'show'])
    ->name('tickets.show')
    ->middleware(['auth']);

Route::get('/tickets/{ticket}/pdf', [\App\Http\Controllers\OrderController::class, 'downloadTicket'])
    ->name('tickets.download')
    ->middleware(['auth']);

Route::view('/', 'welcome')->name('welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'role.redirect'])
    ->name('dashboard');

Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

// Profile routes
Route::prefix('profile')->name('profile.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
    Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::delete('/avatar', [App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    Route::get('/activity', [App\Http\Controllers\ProfileController::class, 'activity'])->name('activity');
    Route::get('/delete', [App\Http\Controllers\ProfileController::class, 'deleteAccount'])->name('delete');
    Route::delete('/', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('destroy');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin routes are now handled by Filament at /admin
// See: app/Providers/Filament/AdminPanelProvider.php
// Filament automatically registers routes for:
// - /admin (dashboard)
// - /admin/login (if not authenticated)
// - /admin/shield/roles (role management via Shield)
// - Any custom Filament resources you create

require __DIR__.'/auth.php';
