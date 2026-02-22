<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Activitylog\Models\Activity;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Internal Users', User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Event Manager', 'Finance Admin', 'Check-in Staff', 'super_admin']);
            })->count())
                ->description('Staff and administrators')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Total Visitors', User::role('Visitor')->count())
                ->description('Registered event attendees')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make('Net Revenue', 'IDR ' . number_format(Order::completed()->sum('total_amount'), 0, ',', '.'))
                ->description('Total revenue from completed orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart(Order::completed()->latest('completed_at')->take(7)->pluck('total_amount')->reverse()->toArray())
                ->color('success'),
            Stat::make('Total Events', Event::count())
                ->description('All events created')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Stat::make('Total Activities', Activity::count())
                ->description('System events logged')
                ->color('warning'),
        ];
    }
}

