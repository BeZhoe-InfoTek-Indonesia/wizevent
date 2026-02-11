<div>
    <!-- Profile Information Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h3>
        
        <form wire:submit="updateProfile" class="space-y-4">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" wire:model="name" id="name" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" id="email" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Avatar Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Picture</h3>
        
        <div class="flex items-center space-x-6">
            <!-- Current Avatar -->
            <div class="flex-shrink-0">
                @if($user->avatar)
                <img class="h-20 w-20 rounded-full object-cover" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                @else
                <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-xl text-gray-600">{{ substr($user->name, 0, 2) }}</span>
                </div>
                @endif
            </div>

            <!-- Avatar Upload Form -->
            <div class="flex-1">
                <form wire:submit="updateAvatar" class="space-y-4">
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700">Upload New Picture</label>
                        <input type="file" wire:model="avatar" id="avatar" 
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('avatar') border-red-500 @enderror"
                               accept="image/jpeg,image/jpg,image/png,image/webp">
                        @error('avatar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, or WebP up to 5MB</p>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            Upload Picture
                        </button>
                        
                        @if($user->avatar)
                        <button type="button" wire:click="deleteAvatar" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                            Remove Picture
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
        
        <form wire:submit="updatePassword" class="space-y-4">
            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" wire:model="current_password" id="current_password" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('current_password') border-red-500 @enderror"
                       required>
                @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" wire:model="password" id="password" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-500 @enderror"
                       required>
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" wire:model="password_confirmation" id="password_confirmation" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                       required>
                @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Change Password
                </button>
            </div>
        </form>
    </div>

    <!-- Account Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">User ID:</span>
                <span class="text-gray-900 ml-2">{{ $user->id }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Created:</span>
                <span class="text-gray-900 ml-2">{{ $user->created_at->format('M j, Y g:i A') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Email Verified:</span>
                <span class="text-gray-900 ml-2">
                    {{ $user->email_verified_at ? $user->email_verified_at->format('M j, Y g:i A') : 'Not verified' }}
                </span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Roles:</span>
                <span class="text-gray-900 ml-2">
                    {{ $user->getRoleNames()->implode(', ') ?: 'No roles assigned' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Event Listeners for Success Messages -->
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('profile-updated', (event) => {
        // Show success message (you can implement your preferred notification system)
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