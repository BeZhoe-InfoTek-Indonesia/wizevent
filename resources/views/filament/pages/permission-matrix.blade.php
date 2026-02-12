<x-filament-panels::page>
    <div class="fi-permission-matrix-page space-y-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    Permission Matrix
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage role-based access control and system permissions across your organization.
                </p>
            </div>
            <div>
                <x-filament::button
                    color="gray"
                    icon="heroicon-o-clock"
                    tag="button"
                    outlined
                    size="sm"
                    class="bg-white dark:bg-gray-800"
                >
                    Audit Logs
                </x-filament::button>
            </div>
        </div>

        {{-- Table Section --}}
        <x-filament::section class="border-t-4 border-t-primary-500">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead class="sticky top-0 z-10 bg-white dark:bg-gray-900 shadow-sm">
                        <tr>
                            <th class="p-6 border-b border-gray-100 dark:border-white/5 bg-white dark:bg-gray-900">
                                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 font-medium text-sm">
                                    <x-filament::icon icon="heroicon-o-shield-check" class="w-5 h-5" />
                                    Permission Name
                                </div>
                            </th>
                            @foreach($roles as $role)
                                <th class="p-6 border-b border-gray-100 dark:border-white/5 bg-white dark:bg-gray-900 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            {{ $role->name }}
                                        </span>
                                        <button 
                                            wire:click="toggleRole({{ $role->id }})"
                                            wire:loading.attr="disabled"
                                            class="text-[10px] font-black text-primary-600 dark:text-primary-400 uppercase tracking-widest hover:text-primary-500 transition-colors"
                                        >
                                            SELECT ALL
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                        @foreach($permissions as $group => $groupPermissions)
                            <tr class="bg-gray-50/20 dark:bg-white/1">
                                <td class="p-5 border-y border-gray-100 dark:border-white/5">
                                    <div class="flex items-center gap-2">
                                        <x-filament::icon 
                                            :icon="$this->getCategoryIcon($group)" 
                                            class="w-4 h-4 text-gray-700 dark:text-gray-300"
                                        />
                                        <span class="text-xs font-black uppercase tracking-[0.15em] text-gray-900 dark:text-white">
                                            {{ $group }}
                                        </span>
                                    </div>
                                </td>
                                <td colspan="{{ count($roles) }}" class="p-5 border-y border-gray-100 dark:border-white/5 text-right">
                                    <button 
                                        wire:click="toggleGroup('{{ $group }}')"
                                        wire:loading.attr="disabled"
                                        class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-primary-600 transition-colors"
                                    >
                                        TOGGLE ROW ALL
                                    </button>
                                </td>
                            </tr>
                            @foreach($groupPermissions as $permission)
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="p-5 pl-8 border-b border-gray-50 dark:border-white/5">
                                        <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                            {{ $permission->name }}
                                        </span>
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="p-5 border-b border-gray-50 dark:border-white/5 text-center">
                                            <div class="flex justify-center items-center">
                                                <x-filament::input.checkbox
                                                    wire:click="togglePermission({{ $role->id }}, '{{ $permission->name }}')"
                                                    :checked="$role->permissions->contains('name', $permission->name)"
                                                    wire:loading.attr="disabled"
                                                    class="cursor-pointer transition-all duration-200 transform scale-125 checked:bg-primary-600 checked:border-transparent"
                                                />
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>

    @push('scripts')
    <style>
        .fi-permission-matrix-page .fi-section {
            padding: 0;
            overflow: hidden;
        }
        .fi-permission-matrix-page table tr:last-child td {
            border-bottom: none;
        }
        /* Design specific checkbox styling if possible via CSS */
        .fi-permission-matrix-page input[type="checkbox"] {
            border-radius: 9999px; /* Rounded checkboxes as in design */
        }
    </style>
    @endpush

    <x-filament-actions::modals />
</x-filament-panels::page>
