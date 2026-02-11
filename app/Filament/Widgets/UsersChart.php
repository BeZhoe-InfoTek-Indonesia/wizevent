<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Spatie\Permission\Models\Role;

class UsersChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return 'Users by Role';
    }

    protected function getData(): array
    {
        $data = Role::withCount('users')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data->pluck('users_count')->toArray(),
                    'backgroundColor' => [
                        '#36A2EB',
                        '#FF6384',
                        '#4BC0C0',
                        '#FF9F40',
                        '#9966FF',
                        '#FFCD56',
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
