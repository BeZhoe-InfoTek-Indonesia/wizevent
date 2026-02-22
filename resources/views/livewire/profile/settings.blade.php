<div class="space-y-6 pb-20">

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">{{ __('profile.settings') }}</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('profile.manage_profile_preferences') }}</p>
    </div>

    <form wire:submit="save" class="space-y-6">

        {{-- ── 1 · Notification Preferences ───────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

            {{-- Card header --}}
            <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('profile.notification_preferences') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.manage_profile_preferences') }}</p>
                </div>
            </div>

            {{-- Notification toggle rows --}}
            <div class="divide-y divide-gray-50 dark:divide-gray-700/60">

                {{-- Email Notifications --}}
                <div class="flex items-center justify-between px-6 py-4 gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('profile.email_notifications') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.email_notifications_desc') }}</p>
                        </div>
                    </div>
                    <div class="shrink-0 settings-toggle-only">
                        <input
                            type="checkbox"
                            wire:model="notificationsData.email_notifications"
                            id="email_notifications"
                            class="settings-toggle"
                            role="switch"
                        >
                    </div>
                </div>

                {{-- SMS Notifications --}}
                <div class="flex items-center justify-between px-6 py-4 gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-green-50 dark:bg-green-900/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('profile.sms_notifications') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.sms_notifications_desc') }}</p>
                        </div>
                    </div>
                    <div class="shrink-0 settings-toggle-only">
                        <input
                            type="checkbox"
                            wire:model="notificationsData.sms_notifications"
                            id="sms_notifications"
                            class="settings-toggle"
                            role="switch"
                        >
                    </div>
                </div>

                {{-- Order Updates --}}
                <div class="flex items-center justify-between px-6 py-4 gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('profile.order_updates') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.order_updates_desc') }}</p>
                        </div>
                    </div>
                    <div class="shrink-0 settings-toggle-only">
                        <input
                            type="checkbox"
                            wire:model="notificationsData.order_updates"
                            id="order_updates"
                            class="settings-toggle"
                            role="switch"
                        >
                    </div>
                </div>

                {{-- Marketing & Newsletter --}}
                <div class="flex items-center justify-between px-6 py-4 gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('profile.marketing_newsletter') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.marketing_newsletter_desc') }}</p>
                        </div>
                    </div>
                    <div class="shrink-0 settings-toggle-only">
                        <input
                            type="checkbox"
                            wire:model="notificationsData.marketing_newsletter"
                            id="marketing_newsletter"
                            class="settings-toggle"
                            role="switch"
                        >
                    </div>
                </div>

            </div>
        </div>

        {{-- ── 2 · Privacy Settings ─────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

            <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('profile.privacy_settings') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.manage_privacy_desc') }}</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-5">

                {{-- Profile Visibility --}}
                <div>
                    <label for="profile_visibility" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('profile.profile_visibility') }}</label>
                    <select
                        id="profile_visibility"
                        wire:model="privacyData.profile_visibility"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                        <option value="public">{{ __('profile.public_visible') }}</option>
                        <option value="private">{{ __('profile.private_hidden') }}</option>
                    </select>
                </div>

                <div class="border-t border-gray-50 dark:border-gray-700/60"></div>

                {{-- Show Email --}}
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('profile.show_email') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.show_email_desc') }}</p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <input type="checkbox" wire:model="privacyData.show_email" id="show_email" class="settings-toggle" role="switch">
                    </div>
                </div>

                {{-- Show Phone --}}
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('profile.show_phone') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.show_phone_desc') }}</p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <input type="checkbox" wire:model="privacyData.show_phone" id="show_phone" class="settings-toggle" role="switch">
                    </div>
                </div>

            </div>
        </div>

        {{-- ── 3 · Localization ─────────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

            <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('profile.language_timezone') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('profile.localization_desc') }}</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Language --}}
                    <div>
                        <label for="language" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('profile.language') }}</label>
                        <select
                            id="language"
                            wire:model="localizationData.language"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                        >
                            <option value="en">English</option>
                            <option value="id">Indonesian</option>
                        </select>
                    </div>

                    {{-- Timezone --}}
                    <div>
                        <label for="timezone" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('profile.timezone') }}</label>
                        <select
                            id="timezone"
                            wire:model="localizationData.timezone"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                        >
                            <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                            <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                            <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                            <option value="Asia/Singapore">Asia/Singapore</option>
                            <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur</option>
                            <option value="Asia/Bangkok">Asia/Bangkok</option>
                            <option value="Asia/Hong_Kong">Asia/Hong Kong</option>
                            <option value="Asia/Tokyo">Asia/Tokyo</option>
                            <option value="Australia/Sydney">Australia/Sydney</option>
                            <option value="Europe/London">Europe/London</option>
                            <option value="Europe/Paris">Europe/Paris</option>
                            <option value="America/New_York">America/New York</option>
                            <option value="America/Los_Angeles">America/Los Angeles</option>
                        </select>
                    </div>

                </div>

                <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('profile.changes_apply_after_reload') }}
                </p>
            </div>
        </div>

        {{-- ── Sticky Action Bar ────────────────────────────────────── --}}
        <div class="sticky bottom-6 z-10">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-2xl border border-gray-100 dark:border-gray-700 px-5 py-3.5 shadow-xl flex items-center gap-4">
                <p class="text-xs text-gray-400 dark:text-gray-500 hidden sm:block flex-1">
                    {{ __('profile.manage_profile_preferences') }}
                </p>
                <div class="flex items-center gap-3 ml-auto">
                    <button
                        type="button"
                        wire:click="discardChanges"
                        wire:loading.attr="disabled"
                        class="px-5 py-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all"
                    >
                        {{ __('profile.discard_changes') }}
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-6 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 active:scale-95 rounded-xl shadow-md shadow-red-500/20 transition-all disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        {{ __('profile.save_preferences') }}
                    </button>
                </div>
            </div>
        </div>

    </form>
    {{-- Filament action modals --}}
    <x-filament-actions::modals />

</div>

@push('styles')
<style>
/* ── Custom Toggle Switch ─────────────────────────────────── */
.settings-toggle {
    appearance: none;
    -webkit-appearance: none;
    position: relative;
    width: 44px;
    height: 24px;
    border-radius: 9999px;
    background-color: #d1d5db;
    cursor: pointer;
    transition: background-color 0.2s ease;
    outline: none;
    flex-shrink: 0;
}
.settings-toggle::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    border-radius: 9999px;
    background-color: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: transform 0.2s ease;
}
.settings-toggle:checked {
    background-color: #dc2626;
}
.settings-toggle:checked::after {
    transform: translateX(20px);
}
.settings-toggle:focus-visible {
    box-shadow: 0 0 0 3px rgba(220,38,38,0.3);
}
.dark .settings-toggle {
    background-color: #4b5563;
}
.dark .settings-toggle:checked {
    background-color: #dc2626;
}
</style>
@endpush
