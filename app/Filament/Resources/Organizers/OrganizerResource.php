<?php

namespace App\Filament\Resources\Organizers;

use App\Filament\Resources\Organizers\Pages\ListOrganizers;
use App\Filament\Resources\Organizers\Schemas\OrganizerForm;
use App\Filament\Resources\Organizers\Tables\OrganizersTable;
use App\Models\Organizer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class OrganizerResource extends Resource
{
    protected static ?string $model = Organizer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function getNavigationGroup(): string
    {
        return __('admin.groups.master_data');
    }

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OrganizerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizers::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
