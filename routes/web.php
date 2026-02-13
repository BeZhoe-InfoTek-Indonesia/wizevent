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

use App\Livewire\Event\EventDiscovery;

use App\Livewire\User\LovedEventsList;
use App\Livewire\User\NotificationCenter;

Route::get('/', EventDiscovery::class)->name('welcome');

Route::get('dashboard', \App\Livewire\Order\OrderList::class)
    ->middleware(['auth', 'role.redirect'])
    ->name('dashboard');

Route::get('dashboard/loved-events', LovedEventsList::class)
    ->middleware(['auth', 'role.redirect'])
    ->name('dashboard.loved-events');

Route::get('/notifications', NotificationCenter::class)
    ->middleware(['auth', 'role.redirect'])
    ->name('notifications.center');

Route::prefix('profile')
    ->middleware(['auth'])
    ->name('profile.notifications')
    ->group(function () {
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
            ->name('profile.notifications.index');

        Route::post('/notifications', [\App\Http\Controllers\NotificationController::class, 'update'])
            ->name('profile.notifications.update');
    });

Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

// Profile routes
Route::get('profile', \App\Livewire\Profile\ProfileComponent::class)
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
