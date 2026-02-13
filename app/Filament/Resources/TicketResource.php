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
                ViewAction::make(),
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
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['Super Admin', 'Event Manager', 'Check-in Staff', 'IT Supervisor']) 
            || auth()->user()->can('tickets.view');
    }
}
