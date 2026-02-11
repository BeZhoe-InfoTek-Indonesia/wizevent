@extends('layouts.app')

@section('title', 'My Activity')

@section('content')
<div class="user-activity">
    <!-- Header -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900">My Activity</h3>
        <p class="text-sm text-gray-600">View your recent account activity and login history</p>
    </div>

    <!-- Activity Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Logins</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Successful Logins</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Failed Logins</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Login History -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-3 bg-gray-50 border-b">
            <h4 class="text-md font-medium text-gray-900">Recent Login History</h4>
            <p class="text-sm text-gray-600 mt-1">Your recent login attempts and their status</p>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($recentLogins as $login)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Status Icon -->
                        <div class="flex-shrink-0">
                            @if($login['successful'])
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @else
                            <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Login Details -->
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $login['successful'] ? 'Successful Login' : 'Failed Login Attempt' }}
                                </span>
                                @if($login['current_session'])
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    Current Session
                                </span>
                                @endif
                            </div>
                            <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                <span>{{ $login['ip_address'] }}</span>
                                <span>{{ $login['location'] ?? 'Unknown Location' }}</span>
                                <span>{{ $login['user_agent'] ?? 'Unknown Browser' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="text-right">
                        <div class="text-sm text-gray-900">{{ $login['created_at'] }}</div>
                        <div class="text-xs text-gray-500">{{ $login['time_ago'] }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No login history</h3>
                <p class="mt-1 text-sm text-gray-500">Activity logging will be available in a future update.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Profile Changes -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-3 bg-gray-50 border-b">
            <h4 class="text-md font-medium text-gray-900">Recent Profile Changes</h4>
            <p class="text-sm text-gray-600 mt-1">Changes you've made to your profile information</p>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($profileChanges as $change)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Change Icon -->
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Change Details -->
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $change['action'] }}</div>
                            <div class="mt-1 text-xs text-gray-500">
                                Changed from "{{ $change['old_value'] }}" to "{{ $change['new_value'] }}"
                            </div>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="text-right">
                        <div class="text-sm text-gray-900">{{ $change['created_at'] }}</div>
                        <div class="text-xs text-gray-500">{{ $change['time_ago'] }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No profile changes</h3>
                <p class="mt-1 text-sm text-gray-500">Profile change tracking will be available in a future update.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Security Information -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Activity Logging</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Detailed activity logging and security monitoring will be implemented in a future task. This will include:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Complete login history with IP geolocation</li>
                        <li>Profile change tracking and notifications</li>
                        <li>Suspicious activity alerts</li>
                        <li>Session management and logout from all devices</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection