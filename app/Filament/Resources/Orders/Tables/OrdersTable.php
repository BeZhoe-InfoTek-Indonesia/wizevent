<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('order.order_number'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label(__('order.user'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event.title')
                    ->label(__('order.event'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),
                TextColumn::make('total_tickets')
                    ->label(__('order.total_tickets'))
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label(__('order.status_label'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_payment' => 'warning',
                        'pending_verification' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'expired' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __("order.status.{$state}")),
                TextColumn::make('total_amount')
                    ->label(__('order.total_amount'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->prefix('IDR ')
                    ->badge()
                    ->color('success'),
                TextColumn::make('expires_at')
                    ->label(__('order.expires_at'))
                    ->dateTime()
                    ->sortable()
                    ->since(),
                TextColumn::make('created_at')
                    ->label(__('order.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('order.status_label'))
                    ->options([
                        'pending_payment' => __('order.status.pending_payment'),
                        'pending_verification' => __('order.status.pending_verification'),
                        'completed' => __('order.status.completed'),
                        'cancelled' => __('order.status.cancelled'),
                        'expired' => __('order.status.expired'),
                    ])
                    ->default('pending_verification'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label(__('order.filter.created_from')),
                        DatePicker::make('created_until')
                            ->label(__('order.filter.created_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make(),
                    Action::make('review_payment')
                        ->label(__('payment_proof.review'))
                        ->visible(fn ($record): bool => $record->status === 'pending_verification')
                        ->icon('heroicon-o-shield-check')
                        ->color('warning')
                        ->url(fn ($record) => \App\Filament\Resources\Orders\OrderResource::getUrl('review', ['record' => $record])),
                    Action::make('view_payment_proof')
                        ->label(__('payment_proof.view'))
                        ->visible(fn ($record): bool => $record->paymentProof && $record->paymentProof->fileBucket && $record->status !== 'pending_verification')
                        ->icon('heroicon-o-document')
                        ->modalContent(fn ($record) => new \Illuminate\Support\HtmlString(
                            '<img src="' . $record->paymentProof->fileBucket->url . '" class="w-full rounded-lg shadow-md" alt="Payment Proof">'
                        ))
                        ->modalHeading(__('payment_proof.view'))
                        ->modalWidth('lg')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
