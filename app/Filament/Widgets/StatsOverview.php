<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Roles', Role::count())
                ->description('Active roles')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info'),
            Stat::make('Total Activities', Activity::count())
                ->description('System events logged')
                ->color('warning'),
        ];
    }
}
