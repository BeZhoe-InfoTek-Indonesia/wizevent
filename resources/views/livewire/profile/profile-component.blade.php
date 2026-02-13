<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="lg:w-64 flex-shrink-0">
                <x-profile.user-info-card :user="$user" />

                <!-- Navigation Menu -->
                <nav class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <x-profile.nav-item tab="account">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </x-slot>
                        Account
                    </x-profile.nav-item>
                    <x-profile.nav-item tab="reviews" class="border-t border-gray-100 dark:border-gray-700">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </x-slot>
                        Your Reviews
                    </x-profile.nav-item>
                    <x-profile.nav-item tab="wishlist" class="border-t border-gray-100 dark:border-gray-700">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </x-slot>
                        Wishlist
                    </x-profile.nav-item>
                    <x-profile.nav-item tab="orders" class="border-t border-gray-100 dark:border-gray-700">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </x-slot>
                        Your Orders
                    </x-profile.nav-item>
                    <x-profile.nav-item tab="help" class="border-t border-gray-100 dark:border-gray-700">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </x-slot>
                        Help Center <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">New</span>
                    </x-profile.nav-item>
                    <x-profile.nav-item tab="settings" class="border-t border-gray-100 dark:border-gray-700">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </x-slot>
                        Settings
                    </x-profile.nav-item>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Account Tab Content -->
                <div x-show="activeTab === 'account'" x-transition>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Account</h2>
                        <x-profile.section-card title="Account">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 mb-6">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Account Center</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    To access your profile details and these categories below, go to the 
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">Blibli Tiket Account Center</span>.
                                </p>

                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4">
                                    <div class="flex items-center gap-4">
                                        @if($user->avatar)
                                            <img class="h-16 w-16 rounded-full object-cover" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                                <span class="text-xl text-white font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->phone ?? '+62813284351' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold text-sm">
                                    To Account Center →
                                </a>
                            </div>

                            <x-profile.profile-form />
                        </x-profile.section-card>

                        <x-profile.section-card>
                            <x-profile.avatar-section :user="$user" />
                        </x-profile.section-card>

                        <x-profile.section-card>
                            <x-profile.password-section />
                        </x-profile.section-card>

                        <x-profile.section-card>
                            <x-profile.account-info :user="$user" />
                        </x-profile.section-card>
                    </div>

                    <!-- Placeholder content for other tabs -->
                    <div x-show="activeTab !== 'account'" x-transition>
                            <x-profile.placeholder />
                    </div>
            </main>
        </div>
    </div>
</div>

<!-- Alpine.js initialization for tab switching -->
<div x-data="{ activeTab: 'account' }" style="display: none;"></div>

<!-- Event Listeners for Success Messages -->
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('profile-updated', (event) => {
        // Show success message
        alert(event.message);
    });

    Livewire.on('password-updated', (event) => {
        alert(event.message);
    });

    Livewire.on('avatar-updated', (event) => {
        alert(event.message);
        // Refresh the page to show the new avatar
        window.location.reload();
    });

    Livewire.on('avatar-deleted', (event) => {
        alert(event.message);
        // Refresh the page to remove the avatar
        window.location.reload();
    });
});
</script>