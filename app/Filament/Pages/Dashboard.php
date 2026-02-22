<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

use Filament\Actions\Action;
use App\Models\User;
use App\Models\Event;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportReport')
                ->label('Export Dashboard Report')
                ->icon('heroicon-m-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $stats = [
                        'total_users' => User::count(),
                        'total_events' => Event::count(),
                        'total_roles' => Role::count(),
                        'total_activities' => Activity::count(),
                    ];

                    $events_by_status = Event::select('status', DB::raw('count(*) as total'))
                        ->groupBy('status')
                        ->get();

                    $users_by_role = Role::withCount('users')->get();

                    $latest_activities = Activity::with('causer')->latest()->limit(10)->get();

                    $financials = [
                        'total_budget_target' => \App\Models\EventPlan::sum('budget_target'),
                        'total_revenue_target' => \App\Models\EventPlan::sum('revenue_target'),
                    ];
                    $financials['projected_profit'] = $financials['total_revenue_target'] - $financials['total_budget_target'];

                    $pdf = Pdf::loadView('reports.dashboard-pdf', [
                        'stats' => $stats,
                        'financials' => $financials,
                        'events_by_status' => $events_by_status,
                        'users_by_role' => $users_by_role,
                        'latest_activities' => $latest_activities,
                    ]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'Dashboard-Report-' . now()->format('Y-m-d') . '.pdf'
                    );
                }),
        ];
    }

    public function getExtraBodyAttributes(): array
    {
        return [
            'class' => 'dashboard-executive-theme',
        ];
    }
}


