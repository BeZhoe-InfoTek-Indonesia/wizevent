<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Navigation\MenuItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;

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
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
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
                __('admin.groups.master_data'),
                __('admin.groups.system'),
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup(__('admin.groups.master_data')),
                FilamentExceptionsPlugin::make()
            ])
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
