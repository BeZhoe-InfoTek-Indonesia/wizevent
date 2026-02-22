<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\Action as TableAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('ticket.plural_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.operations');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')
                    ->label(__('ticket.ticket_number'))
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),
                
                TextColumn::make('holder_name')
                    ->label(__('ticket.holder_name'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('ticketType.event.title')
                    ->label(__('ticket.event'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ticketType.name')
                    ->label(__('ticket.type'))
                    ->badge(),

                BadgeColumn::make('status')
                    ->label(__('ticket.status'))
                    ->colors([
                        'success' => 'active',
                        'warning' => 'used',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => __("ticket.status_{$state}")),

                TextColumn::make('checked_in_at')
                    ->label(__('ticket.checked_in_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('event.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('ticket.status'))
                    ->options([
                        'active' => __('ticket.status_active'),
                        'used' => __('ticket.status_used'),
                        'cancelled' => __('ticket.status_cancelled'),
                    ]),
                SelectFilter::make('event')
                    ->label(__('ticket.event'))
                    ->relationship('ticketType.event', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make()
                    ->modal()
                    ->modalHeading(fn (Ticket $record): string => __('ticket.view_details', ['number' => $record->ticket_number]))
                    ->modalWidth('2xl')
                    ->modalContent(function (Ticket $record) {
                        $statusConfig = match($record->status) {
                            'active' => [
                                'bg' => 'bg-green-50',
                                'text' => 'text-green-700',
                                'border' => 'border-green-200',
                                'icon' => '✓',
                            ],
                            'used' => [
                                'bg' => 'bg-yellow-50',
                                'text' => 'text-yellow-700',
                                'border' => 'border-yellow-200',
                                'icon' => '✓',
                            ],
                            'cancelled' => [
                                'bg' => 'bg-red-50',
                                'text' => 'text-red-700',
                                'border' => 'border-red-200',
                                'icon' => '✕',
                            ],
                            default => [
                                'bg' => 'bg-gray-50',
                                'text' => 'text-gray-700',
                                'border' => 'border-gray-200',
                                'icon' => '?',
                            ],
                        };

                        $eventDate = $record->ticketType->event->event_date ? $record->ticketType->event->event_date->format('d M Y, H:i') : 'N/A';

                        return new HtmlString("
                            <div class='space-y-4'>
                                <!-- Header Section -->
                                <div class='bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100'>
                                    <div class='flex items-center justify-between'>
                                        <div>
                                            <p class='text-sm text-gray-600 mb-1'>".__('ticket.ticket_number')."</p>
                                            <p class='text-xl font-mono font-bold text-gray-800'>{$record->ticket_number}</p>
                                        </div>
                                        <div class='flex items-center gap-2 px-4 py-2 rounded-full {$statusConfig['bg']} {$statusConfig['text']} border {$statusConfig['border']}'>
                                            <span class='text-lg font-bold'>{$statusConfig['icon']}</span>
                                            <span class='font-semibold'>".__("ticket.status_{$record->status}")."</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Information Card -->
                                <div class='bg-white rounded-lg border border-gray-200 p-4 shadow-sm'>
                                    <div class='flex items-center gap-2 mb-4'>
                                        <svg class='w-5 h-5 text-blue-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'></path>
                                        </svg>
                                        <h3 class='text-lg font-bold text-gray-800'>".__('ticket.event_info')."</h3>
                                    </div>
                                    <div class='grid grid-cols-1 md:grid-cols-2 gap-4'>
                                        <div class='bg-gray-50 rounded-lg p-3'>
                                            <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>".__('ticket.event')."</p>
                                            <p class='text-base font-semibold text-gray-800'>{$record->ticketType->event->title}</p>
                                        </div>
                                        <div class='bg-gray-50 rounded-lg p-3'>
                                            <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>Event Date</p>
                                            <p class='text-base font-semibold text-gray-800'>{$eventDate}</p>
                                        </div>
                                        <div class='bg-gray-50 rounded-lg p-3'>
                                            <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>".__('ticket.type')."</p>
                                            <p class='text-base font-semibold text-gray-800'>{$record->ticketType->name}</p>
                                        </div>
                                        <div class='bg-gray-50 rounded-lg p-3'>
                                            <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>".__('ticket.holder_name')."</p>
                                            <p class='text-base font-semibold text-gray-800'>".($record->holder_name ?? 'N/A')."</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Information Card -->
                                <div class='bg-white rounded-lg border border-gray-200 p-4 shadow-sm'>
                                    <div class='flex items-center gap-2 mb-4'>
                                        <svg class='w-5 h-5 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'></path>
                                        </svg>
                                        <h3 class='text-lg font-bold text-gray-800'>".__('ticket.order_info')."</h3>
                                    </div>
                                    <div class='grid grid-cols-1 md:grid-cols-2 gap-4'>
                                        <div class='bg-gray-50 rounded-lg p-3'>
                                            <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>".__('ticket.order_number')."</p>
                                            <p class='text-base font-mono font-semibold text-gray-800'>{$record->order->order_number}</p>
                                        </div>
                                        <div class='bg-gray-50 rounded-lg p-3'>
                                            <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>".__('ticket.quantity')."</p>
                                            <p class='text-base font-semibold text-gray-800'>".($record->orderItem->quantity ?? 1)."</p>
                                        </div>
                                        <div class='bg-gray-50 rounded-lg p-3'>
                                            <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>".__('ticket.unit_price')."</p>
                                            <p class='text-base font-semibold text-gray-800'>Rp " . number_format($record->orderItem->unit_price ?? 0, 0, ',', '.') . "</p>
                                        </div>
                                        <div class='bg-green-50 rounded-lg p-3 border border-green-200'>
                                            <p class='text-xs font-semibold text-green-600 uppercase tracking-wide mb-1'>".__('ticket.total_price')."</p>
                                            <p class='text-lg font-bold text-green-700'>Rp " . number_format($record->orderItem->total_price ?? 0, 0, ',', '.') . "</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Check-in Information -->
                                <div class='bg-white rounded-lg border border-gray-200 p-4 shadow-sm'>
                                    <div class='flex items-center gap-2 mb-4'>
                                        <svg class='w-5 h-5 text-purple-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'></path>
                                        </svg>
                                        <h3 class='text-lg font-bold text-gray-800'>".__('ticket.status')."</h3>
                                    </div>
                                    <div class='bg-gray-50 rounded-lg p-3'>
                                        <p class='text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1'>".__('ticket.checked_in_at')."</p>
                                        <p class='text-base font-semibold text-gray-800'>".($record->checked_in_at ? $record->checked_in_at->format('d M Y, H:i:s') : 'Not checked in yet')."</p>
                                    </div>
                                </div>

                                <!-- Timestamps -->
                                <div class='bg-gray-50 rounded-lg p-4 border border-gray-200'>
                                    <p class='text-xs text-gray-500 text-center'>
                                        Created: {$record->created_at->format('d M Y, H:i:s')} | Updated: {$record->updated_at->format('d M Y, H:i:s')}
                                    </p>
                                </div>
                            </div>
                        ");
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
        ];
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['Super Admin', 'Event Manager', 'Check-in Staff', 'IT Supervisor']) 
            || auth()->user()->can('tickets.view');
    }
}
