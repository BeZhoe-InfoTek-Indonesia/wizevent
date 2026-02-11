<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\Pages\ViewOrder;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction as TableViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
            ])
            ->recordActions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make(),
                    Action::make('view_payment_proof')
                        ->label(__('payment_proof.view'))
                        ->visible(fn ($record): bool => $record->paymentProof && $record->paymentProof->fileBucket)
                        ->icon('heroicon-o-document')
                        ->modalContent(fn ($record) => view('filament.orders.payment-proof-view', [
                            'paymentProof' => $record->paymentProof,
                            'fileBucket' => $record->paymentProof->fileBucket,
                        ]))
                        ->modalHeading(__('payment_proof.view'))
                        ->modalWidth('lg'),
                    Action::make('approve_payment')
                        ->label(__('payment_proof.approve'))
                        ->visible(fn ($record): bool => $record->paymentProof && $record->paymentProof->status === 'pending' && $record->status === 'pending_verification')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->form([
                            \Filament\Forms\Components\Textarea::make('notes')
                                ->label(__('payment_proof.approval_notes'))
                                ->placeholder(__('payment_proof.approval_notes_placeholder'))
                                ->rows(3),
                        ])
                        ->action(function ($record, array $data) {
                            app(\App\Services\OrderService::class)->approvePayment($record->paymentProof, auth()->user());

                            \Filament\Notifications\Notification::make()
                                ->title(__('payment_proof.approved_successfully'))
                                ->success()
                                ->send();
                        }),
                    Action::make('reject_payment')
                        ->label(__('payment_proof.reject'))
                        ->visible(fn ($record): bool => $record->paymentProof && $record->paymentProof->status === 'pending' && $record->status === 'pending_verification')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->form([
                            \Filament\Forms\Components\Textarea::make('rejection_reason')
                                ->label(__('payment_proof.rejection_reason'))
                                ->placeholder(__('payment_proof.rejection_reason_placeholder'))
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function ($record, array $data) {
                            app(\App\Services\OrderService::class)->rejectPayment(
                                $record->paymentProof,
                                auth()->user(),
                                $data['rejection_reason']
                            );

                            \Filament\Notifications\Notification::make()
                                ->title(__('payment_proof.rejected_successfully'))
                                ->warning()
                                ->send();
                        }),
                    Action::make('approve_manual')
                        ->label(__('payment_proof.approve_manual'))
                        ->visible(fn ($record): bool => !$record->paymentProof && $record->status === 'pending_verification')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            app(\App\Services\OrderService::class)->approveManualOrder($record);

                            \Filament\Notifications\Notification::make()
                                ->title(__('payment_proof.approved_successfully'))
                                ->success()
                                ->send();
                        }),
                    Action::make('reject_manual')
                        ->label(__('payment_proof.reject_manual'))
                        ->visible(fn ($record): bool => !$record->paymentProof && $record->status === 'pending_verification')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->form([
                            \Filament\Forms\Components\Textarea::make('rejection_reason')
                                ->label(__('payment_proof.rejection_reason'))
                                ->placeholder(__('payment_proof.rejection_reason_placeholder'))
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function ($record, array $data) {
                            app(\App\Services\OrderService::class)->cancelOrder($record, $data['rejection_reason']);

                            \Filament\Notifications\Notification::make()
                                ->title(__('payment_proof.rejected_successfully'))
                                ->warning()
                                ->send();
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
