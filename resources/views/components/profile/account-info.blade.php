@props(['user'])
<div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Account Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="flex justify-between">
            <span class="font-medium text-gray-700 dark:text-gray-300">User ID:</span>
            <span class="text-gray-900 dark:text-gray-100">{{ $user->id }}</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-gray-700 dark:text-gray-300">Created:</span>
            <span class="text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M j, Y') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-gray-700 dark:text-gray-300">Email Verified:</span>
            <span class="text-gray-900 dark:text-gray-100">{{ $user->email_verified_at ? $user->email_verified_at->format('M j, Y') : 'Not verified' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-gray-700 dark:text-gray-300">Roles:</span>
            <span class="text-gray-900 dark:text-gray-100">{{ $user->getRoleNames()->implode(', ') ?: 'No roles assigned' }}</span>
        </div>
    </div>
</div>
