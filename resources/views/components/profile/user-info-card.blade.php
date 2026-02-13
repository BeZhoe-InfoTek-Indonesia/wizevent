@props(['user'])
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6 text-center">
    <div class="relative inline-block mb-3">
        @if($user->avatar)
            <img class="h-20 w-20 rounded-full object-cover mx-auto border-4 border-blue-100 dark:border-blue-900" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
        @else
            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mx-auto border-4 border-blue-100 dark:border-blue-900">
                <span class="text-2xl text-white font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
            </div>
        @endif
        <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-1">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
    <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
</div>
