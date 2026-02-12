<?php

namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Permissions\Tables\PermissionsTable;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function getNavigationGroup(): string
    {
        return __('admin.groups.master_data');
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
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
            'index' => ListPermissions::route('/'),
        ];
    }

    public static function getLabel(): string
    {
        return 'Permission';
    }

    public static function getPluralLabel(): string
    {
        return 'Permissions';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('permissions.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('permissions.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('permissions.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('permissions.delete') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('permissions.delete') ?? false;
    }
}
