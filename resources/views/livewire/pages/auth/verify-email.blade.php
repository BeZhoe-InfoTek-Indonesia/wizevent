<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest-verify')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        \App\Jobs\SendEmailVerification::dispatch(Auth::id());

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="grid gap-6 lg:grid-cols-[1.05fr_1.2fr]">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-red-600 via-red-700 to-red-800 p-8 text-white shadow-2xl shadow-red-200/70">
        <div class="space-y-4">
            <p class="text-3xl font-semibold leading-tight">{{ __('Securing your journey.') }}</p>
            <p class="text-sm text-red-100/90">
                {{ __('We sent a verification link to your inbox. One click unlocks full access to your event ticketing platform.') }}
            </p>
        </div>

        <div class="mt-10 flex items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white/90 backdrop-blur">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-10a1 1 0 011 1v4a1 1 0 11-2 0V9a1 1 0 011-1zm0-4a1.25 1.25 0 100 2.5A1.25 1.25 0 0010 4z" clip-rule="evenodd" />
                </svg>
            </span>
            <span>{{ __('Can\'t find it? Check your spam folder.') }}</span>
        </div>

        <div class="pointer-events-none absolute -bottom-16 -right-12 h-40 w-40 rotate-12 rounded-3xl bg-white/15"></div>
        <div class="pointer-events-none absolute -bottom-24 right-10 h-36 w-36 rotate-12 rounded-[2.5rem] border border-white/20"></div>
    </div>

    <div class="relative rounded-3xl bg-white p-8 shadow-2xl shadow-slate-200/70 ring-1 ring-slate-100">
        <div class="flex items-center gap-4">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M3.75 6.75A2.25 2.25 0 016 4.5h12A2.25 2.25 0 0120.25 6.75v10.5A2.25 2.25 0 0118 19.5H6a2.25 2.25 0 01-2.25-2.25V6.75z" />
                    <path d="M4.5 7.5l7.2 4.8a.75.75 0 00.8 0l7.2-4.8" />
                    <path d="M9.75 12.75l2.5 2.5 4.5-4.5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <div>
                <p class="text-2xl font-semibold text-slate-900">{{ __('Verify your email') }}</p>
                <p class="text-sm text-slate-500">{{ __('Check your inbox to continue.') }}</p>
            </div>
        </div>

        <p class="mt-5 text-sm leading-relaxed text-slate-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking the link we just emailed to you?') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <button wire:click="sendVerification" type="button"
            class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-red-200/80 transition hover:-translate-y-0.5 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            {{ __('Resend Verification Email') }}
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        <div class="mt-6 flex items-center justify-between text-sm text-slate-500">
            <span class="inline-flex items-center gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 3.75A8.25 8.25 0 103 12a8.25 8.25 0 009-8.25zM12 6.75a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V7.5A.75.75 0 0112 6.75zm0 9a.75.75 0 110 1.5.75.75 0 010-1.5z" />
                </svg>
                {{ __('Wrong email?') }}
            </span>
            <button wire:click="logout" type="button" class="inline-flex items-center gap-2 font-semibold text-slate-800 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                {{ __('Log Out') }}
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3 4.75A1.75 1.75 0 014.75 3h5.5a1 1 0 110 2h-5.5a.25.25 0 00-.25.25v9.5a.25.25 0 00.25.25h5.5a1 1 0 110 2h-5.5A1.75 1.75 0 013 15.25v-10.5zM11.47 6.47a1 1 0 011.41 0l2.83 2.83a1 1 0 010 1.41l-2.83 2.83a1 1 0 01-1.41-1.41L12.09 11H7.5a1 1 0 110-2h4.59l-1.62-1.62a1 1 0 010-1.41z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="pointer-events-none absolute -right-8 top-16 h-20 w-20 rounded-full bg-slate-100"></div>
    </div>
</div>
