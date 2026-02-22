<?php

namespace App\Filament\Resources\Performers;

use App\Filament\Resources\Performers\Pages\ListPerformers;
use App\Filament\Resources\Performers\Schemas\PerformerForm;
use App\Filament\Resources\Performers\Tables\PerformersTable;
use App\Models\Performer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PerformerResource extends Resource
{
    protected static ?string $model = Performer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function getNavigationGroup(): string
    {
        return __('admin.groups.master_data');
    }

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PerformerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerformersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPerformers::route('/'),
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
