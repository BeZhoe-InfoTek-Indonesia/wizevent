<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->middleware('throttle:5,1')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->middleware('throttle:5,1')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->middleware('throttle:3,1')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->middleware('throttle:3,1')
        ->name('password.reset');

    // Google OAuth routes
    Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])
        ->name('auth.google');

    Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])
        ->name('auth.google.callback');

    // Alternative Google callback route (for backward compatibility)
    Route::get('google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])
        ->name('google.callback');
});

Route::middleware('auth')->group(function () {
    // Email verification routes
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->name('verification.verify');

    Route::post('email/verification-notification', function (Request $request) {
        \App\Jobs\SendEmailVerification::dispatch($request->user()->id);
        return back()->with('status', 'verification-link-sent');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
