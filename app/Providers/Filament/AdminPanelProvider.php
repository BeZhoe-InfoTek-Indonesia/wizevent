<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(false)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->sidebarCollapsibleOnDesktop()
            ->brandName('Event Management')
            ->favicon(asset('favicon.ico'))
            ->navigationGroups([
                __('admin.groups.event_management'),
                __('admin.groups.operations'),
                __('admin.groups.visitors'),
                __('admin.groups.master_data'),
                __('admin.groups.system'),
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup(__('admin.groups.master_data')),
                FilamentExceptionsPlugin::make(),
            ])
            ->renderHook(
                PanelsRenderHook::SCRIPTS_BEFORE,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <script>
                        // Fallback: ensure the Alpine sidebar store is always initialized,
                        // even when Livewire/Alpine fires `alpine:init` before Filament's
                        // own dist/index.js has had a chance to register its listener.
                        document.addEventListener('alpine:init', function () {
                            if (typeof window.Alpine === 'undefined') return;
                            if (typeof window.Alpine.store('sidebar') !== 'undefined') return;

                            window.Alpine.store('sidebar', {
                                isOpen: localStorage.getItem('_x_isOpen') !== 'false',
                                isOpenDesktop: localStorage.getItem('_x_isOpenDesktop') !== 'false',
                                collapsedGroups: JSON.parse(localStorage.getItem('_x_collapsedGroups') || 'null'),

                                init: function () {},

                                groupIsCollapsed: function (group) {
                                    return Array.isArray(this.collapsedGroups) && this.collapsedGroups.includes(group);
                                },

                                toggleCollapsedGroup: function (group) {
                                    if (!Array.isArray(this.collapsedGroups)) {
                                        this.collapsedGroups = [];
                                    }
                                    this.collapsedGroups = this.collapsedGroups.includes(group)
                                        ? this.collapsedGroups.filter(function (g) { return g !== group; })
                                        : this.collapsedGroups.concat([group]);
                                    localStorage.setItem('_x_collapsedGroups', JSON.stringify(this.collapsedGroups));
                                },

                                open: function () {
                                    this.isOpen = true;
                                    localStorage.setItem('_x_isOpen', 'true');
                                    if (window.innerWidth >= 1024) {
                                        this.isOpenDesktop = true;
                                        localStorage.setItem('_x_isOpenDesktop', 'true');
                                    }
                                },

                                close: function () {
                                    this.isOpen = false;
                                    localStorage.setItem('_x_isOpen', 'false');
                                    if (window.innerWidth >= 1024) {
                                        this.isOpenDesktop = false;
                                        localStorage.setItem('_x_isOpenDesktop', 'false');
                                    }
                                },
                            });
                        });
                    </script>
                HTML)
            )
            ->userMenuItems([
                MenuItem::make()
                    ->label(__('admin.nav.english'))
                    ->url(fn (): string => route('lang.switch', 'en'))
                    ->icon('heroicon-o-language'),
                MenuItem::make()
                    ->label(__('admin.nav.indonesian'))
                    ->url(fn (): string => route('lang.switch', 'id'))
                    ->icon('heroicon-o-language'),
                MenuItem::make()
                    ->label(__('admin.nav.roles'))
                    ->url(fn (): string => route('filament.admin.resources.roles.index'))
                    ->icon('heroicon-o-shield-check')
                    ->visible(fn (): bool => auth()->user()?->can('view_any', \Spatie\Permission\Models\Role::class)),
                MenuItem::make()
                    ->label(__('admin.nav.permissions'))
                    ->url(fn (): string => route('filament.admin.resources.permissions.index'))
                    ->icon('heroicon-o-key')
                    ->visible(fn (): bool => auth()->user()?->can('view_any', \Spatie\Permission\Models\Permission::class)),
            ]);
    }
}
