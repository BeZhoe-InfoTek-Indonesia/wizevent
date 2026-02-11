<?php

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Permission Name'),

                TextColumn::make('guard_name')
                    ->searchable()
                    ->sortable()
                    ->label('Guard'),

                TextColumn::make('roles_count')
                    ->counts('roles')
                    ->label('Roles')
                    ->sortable(),

                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Direct Users')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created At'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Updated At'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modal(),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->modal(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('attach_role')
                        ->label('Assign to Role')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Select::make('role_id')
                                ->label('Role')
                                ->options(Role::query()->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $role = Role::findById($data['role_id']);
                            foreach ($records as $record) {
                                $role->givePermissionTo($record);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('detach_role')
                        ->label('Remove from Role')
                        ->icon('heroicon-o-user-minus')
                        ->form([
                            Select::make('role_id')
                                ->label('Role')
                                ->options(Role::query()->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $role = Role::findById($data['role_id']);
                            foreach ($records as $record) {
                                $role->revokePermissionTo($record);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
