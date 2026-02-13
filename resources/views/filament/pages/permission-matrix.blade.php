<x-filament-panels::page>
    <div class="fi-permission-matrix-page space-y-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('permission.matrix.title') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('permission.matrix.description') }}
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
                    {{ __('permission.matrix.audit_logs') }}
                </x-filament::button>
            </div>
        </div>

        {{-- Table Section --}}
        <x-filament::section class="border-2 border-primary-100 dark:border-white/5">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead class="sticky top-0 z-10 bg-white dark:bg-gray-900 shadow-sm">
                        <tr>
                            <th class="p-6 border-b border-gray-100 dark:border-white/5 bg-white dark:bg-gray-900">
                                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 font-medium text-sm">
                                    <x-filament::icon icon="heroicon-o-shield-check" class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" />
                                    {{ __('permission.matrix.permission_name') }}
                                </div>
                            </th>
                            @foreach($roles as $role)
                                <th class="p-6 border-b border-gray-100 dark:border-white/5 bg-white dark:bg-gray-900 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white tracking-tight">
                                            {{ $role->name }}
                                        </span>
                                        <button 
                                            wire:click="toggleRole({{ $role->id }})"
                                            wire:loading.attr="disabled"
                                            class="text-[9px] font-black text-primary-600 dark:text-primary-400 uppercase tracking-widest hover:text-primary-500 transition-colors"
                                        >
                                            {{ __('permission.matrix.select_all') }}
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                        @foreach($permissions as $group => $groupPermissions)
                            <tr class="bg-gray-50/10 dark:bg-white/1">
                                <td class="p-5 border-y border-gray-100 dark:border-white/5">
                                    <div class="flex items-center gap-2">
                                        <x-filament::icon 
                                            :icon="$this->getCategoryIcon($group)" 
                                            class="w-4 h-4 text-gray-900 dark:text-white"
                                        />
                                        <span class="text-xs font-black uppercase tracking-[0.1em] text-gray-900 dark:text-white">
                                            {{ __('permission.categories.' . strtolower($group)) ?? ucfirst($group) }}
                                        </span>
                                    </div>
                                </td>
                                <td colspan="{{ count($roles) }}" class="p-5 border-y border-gray-100 dark:border-white/5 text-right">
                                    <button 
                                        wire:click="toggleGroup('{{ $group }}')"
                                        wire:loading.attr="disabled"
                                        class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-primary-600 transition-colors"
                                    >
                                        {{ __('permission.matrix.toggle_row_all') }}
                                    </button>
                                </td>
                            </tr>
                            @foreach($groupPermissions as $permission)
                                <tr class="group hover:bg-gray-50/30 dark:hover:bg-white/5 transition-colors">
                                    <td class="p-4 pl-12 border-b border-gray-50 dark:border-white/5">
                                        <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                            {{ $permission->name }}
                                        </span>
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="p-4 border-b border-gray-50 dark:border-white/5 text-center">
                                            <div class="flex justify-center items-center">
                                                <x-filament::input.checkbox
                                                    wire:click="togglePermission({{ $role->id }}, '{{ $permission->name }}')"
                                                    :checked="$role->permissions->contains('name', $permission->name)"
                                                    wire:loading.attr="disabled"
                                                    class="cursor-pointer transition-all duration-200 transform scale-125 checked:bg-primary-600 checked:border-transparent rounded-full"
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
            border-radius: 0.75rem;
        }
        .fi-permission-matrix-page table tr:last-child td {
            border-bottom: none;
        }
        /* Make checkboxes perfectly circular with blue checkmarks */
        .fi-permission-matrix-page input[type="checkbox"] {
            border-radius: 9999px !important;
            width: 1.25rem;
            height: 1.25rem;
            border-color: #E5E7EB;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .fi-permission-matrix-page input[type="checkbox"]:checked {
            background-color: #2563EB !important;
            border-color: transparent !important;
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }
        .fi-permission-matrix-page input[type="checkbox"]:hover {
            border-color: #2563EB;
        }
        .dark .fi-permission-matrix-page input[type="checkbox"] {
            border-color: #374151;
            background-color: transparent;
        }
    </style>
    @endpush

    <x-filament-actions::modals />
</x-filament-panels::page>
