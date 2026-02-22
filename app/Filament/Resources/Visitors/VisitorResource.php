<?php

namespace App\Filament\Resources\Visitors;

use App\Filament\Resources\Visitors\Pages\ListVisitors;
use App\Filament\Resources\Visitors\Schemas\VisitorForm;
use App\Filament\Resources\Visitors\Tables\VisitorsTable;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VisitorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Visitors';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Visitor';

    protected static ?string $pluralLabel = 'Visitors';

    protected static bool $canCreate = false;

    /**
     * Query to filter only users with "Visitor" role.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('Visitor');
    }

    public static function form(Schema $schema): Schema
    {
        return VisitorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisitorsTable::configure($table);
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
            'index' => ListVisitors::route('/'),
        ];
    }
}
