<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.visitor-auth')] class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'Visitor'; // Default role

    public $googleUser = null;

    public function mount(): void
    {
        // Pre-fill form data from Google OAuth session
        if (session('google_user')) {
            $this->googleUser = session('google_user');
            $this->name = $this->googleUser['name'] ?? '';
            $this->email = $this->googleUser['email'] ?? '';
            session()->forget('google_user');
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:Visitor,Event Manager,Finance Admin,Check-in Staff'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Add Google user data if available
        if ($this->googleUser) {
            $validated['google_id'] = $this->googleUser['google_id'];
            $validated['avatar'] = $this->googleUser['avatar'];
        }

        event(new Registered($user = User::create($validated)));

        // Assign role using spatie/laravel-permission if role exists
        try {
            $user->assignRole($validated['role']);
        } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
            // Fallback to Visitor role if specified role doesn't exist
            if (class_exists('\Spatie\Permission\Models\Role')) {
                $visitorRole = \Spatie\Permission\Models\Role::where('name', 'Visitor')->first();
                if ($visitorRole) {
                    $user->assignRole('Visitor');
                }
            }
        }

        Auth::login($user);

        // Redirect based on role
        $redirectRoute = $this->getRedirectRoute($validated['role']);
        $this->redirect(route($redirectRoute, absolute: false), navigate: true);
    }

    /**
     * Get redirect route based on user role
     */
    private function getRedirectRoute(string $role): string
    {
        return match ($role) {
            'Super Admin', 'Event Manager', 'Finance Admin', 'Check-in Staff' => 'filament.admin.pages.dashboard',
            default => 'events.index',
        };
    }
}; ?>

<div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-2xl transition-colors duration-300">
    <!-- Header Section -->
    <div class="bg-indigo-600 dark:bg-slate-800 pt-10 pb-20 px-8 text-center transition-colors duration-300 relative">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm mb-4 shadow-inner">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-2 dark:text-emerald-400 transition-colors duration-300">{{ config('app.name', 'Event Hub') }}</h1>
        <p class="text-indigo-200 dark:text-slate-400 text-sm font-medium tracking-wide">{{ __('Join us and start exploring') }}</p>
    </div>

    <!-- Form Section -->
    <div class="relative bg-white dark:bg-slate-800 px-8 pb-10 pt-8 -mt-12 rounded-t-3xl shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] dark:shadow-none transition-colors duration-300">
        
        <form wire:submit="register" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 pl-1">{{ __('Name') }}</label>
                <div class="relative">
                    <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-indigo-500 dark:focus:border-emerald-500 focus:ring-indigo-500 dark:focus:ring-emerald-500 transition-colors duration-200"
                           placeholder="John Doe">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm px-1" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 pl-1">{{ __('Email Address') }}</label>
                <div class="relative">
                    <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-indigo-500 dark:focus:border-emerald-500 focus:ring-indigo-500 dark:focus:ring-emerald-500 transition-colors duration-200"
                           placeholder="name@example.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm px-1" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 pl-1">{{ __('Password') }}</label>
                <div class="relative">
                    <input wire:model="password" id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-indigo-500 dark:focus:border-emerald-500 focus:ring-indigo-500 dark:focus:ring-emerald-500 transition-colors duration-200"
                           placeholder="••••••••">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm px-1" />
            </div>

            <!-- Confirm Password -->
            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 pl-1">{{ __('Confirm Password') }}</label>
                <div class="relative">
                    <input wire:model="password_confirmation" id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:border-indigo-500 dark:focus:border-emerald-500 focus:ring-indigo-500 dark:focus:ring-emerald-500 transition-colors duration-200"
                           placeholder="••••••••">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm px-1" />
            </div>

            <!-- Register Button -->
            <button type="submit"
                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-slate-900 font-bold rounded-xl shadow-lg shadow-indigo-200 dark:shadow-emerald-900/20 transform transition-all duration-200 hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-emerald-500">
                {{ __('REGISTER') }}
            </button>
        </form>

        <!-- Divider -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200 dark:border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white dark:bg-slate-800 text-gray-500 dark:text-slate-400">{{ __('Or register with') }}</span>
            </div>
        </div>

        <!-- Google Register -->
        <a href="{{ route('auth.google') }}" class="flex items-center justify-center w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
            <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            {{ __('Register with Google') }}
        </a>

        <!-- Sign In Link -->
        <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
            {{ __("Already have an account?") }}
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 dark:text-emerald-400 hover:text-indigo-500 dark:hover:text-emerald-300" wire:navigate>
                {{ __('Log In') }}
            </a>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="/" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors" wire:navigate>
                 <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                 </svg>
                {{ __('Back to Home') }}
            </a>
        </div>
    </div>
</div>
