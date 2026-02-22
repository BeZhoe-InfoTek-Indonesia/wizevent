<?php

use App\Livewire\Event\Checkout;
use App\Livewire\Event\EventDetail;
use App\Livewire\Event\EventDiscovery;
use App\Livewire\Event\EventList;
use App\Livewire\Event\TestimonialSubmission;
use App\Livewire\Order\BookingConfirmation;
use App\Livewire\Order\OrderStatus;
use App\Livewire\User\LovedEventsList;
use App\Livewire\User\NotificationCenter;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/events', EventList::class)->name('events.index');
Route::get('/events/{slug}', EventDetail::class)->name('events.show');
Route::get('/events/{slug}/checkout', Checkout::class)->name('events.checkout');
Route::get('/events/{slug}/review', TestimonialSubmission::class)->name('events.review');
Route::get('/events/{slug}/reviews', \App\Livewire\Event\EventReviews::class)->name('events.reviews');
Route::get('/events/{slug}/calendar', [\App\Http\Controllers\EventController::class, 'downloadCalendar'])->name('events.calendar');

// Order routes
Route::prefix('orders')->name('orders.')->middleware(['auth'])->group(function () {
    // Invoice and explicit order show should be defined before the generic
    // `{orderNumber}` status route to avoid route collisions where the
    // status Livewire component would capture single-segment requests.
    Route::get('/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('invoice');
    Route::get('/{order}', \App\Livewire\Order\Show::class)->name('show');
    Route::get('/{order}/ticket', [\App\Http\Controllers\OrderController::class, 'downloadTickets'])->name('ticket');
    Route::get('/{order}/calendar', [\App\Http\Controllers\OrderController::class, 'downloadCalendar'])->name('calendar');

    Route::get('/{orderNumber}/confirmation', BookingConfirmation::class)->name('confirmation');
    Route::get('/{orderNumber}', OrderStatus::class)->name('status');
});

Route::get('/tickets/{ticket}', [\App\Http\Controllers\TicketController::class, 'show'])
    ->name('tickets.show')
    ->middleware(['auth']);

Route::get('/tickets/{ticket}/pdf', [\App\Http\Controllers\OrderController::class, 'downloadTicket'])
    ->name('tickets.download')
    ->middleware(['auth']);

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

Route::get('api/event-categories', [\App\Http\Controllers\CategoryLookupController::class, 'eventCategories'])
    ->middleware(['auth'])
    ->name('api.event-categories');

Route::get('profile/download-data', [\App\Http\Controllers\ProfileController::class, 'downloadData'])
    ->middleware(['auth'])
    ->name('profile.download-data');

// Show individual order details for authenticated user (Livewire)
Route::get('profile/orders/{order}', \App\Livewire\Profile\ShowOrder::class)
    ->middleware(['auth'])
    ->name('profile.orders.show');
// Static pages
Route::get('terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('refund', function () {
    return view('pages.refund');
})->name('refund');

// Admin routes are now handled by Filament at /admin
// See: app/Providers/Filament/AdminPanelProvider.php
// Filament automatically registers routes for:
// - /admin (dashboard)
// - /admin/login (if not authenticated)
// - /admin/shield/roles (role management via Shield)
// - Any custom Filament resources you create

// Download all payment proof files for an order as a ZIP
Route::get('/admin/orders/{order}/download-files', [\App\Http\Controllers\OrderFileDownloadController::class, 'downloadPaymentProofs'])
    ->name('admin.orders.download_files')
    ->middleware(['auth']);
