<?php

namespace App\Filament\Resources\Works;

use App\Filament\Resources\Works\Pages\CreateWork;
use App\Filament\Resources\Works\Pages\EditWork;
use App\Filament\Resources\Works\Pages\ListWorks;
use App\Filament\Resources\Works\Schemas\WorkForm;
use App\Filament\Resources\Works\Tables\WorksTable;
use App\Models\Work;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WorkResource extends Resource
{
    protected static ?string $model = Work::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static UnitEnum|string|null $navigationGroup = 'İçerik';

    protected static ?string $navigationLabel = 'İşler';

    protected static ?string $modelLabel = 'iş';

    protected static ?string $pluralModelLabel = 'işler';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return WorkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorks::route('/'),
            'create' => CreateWork::route('/create'),
            'edit' => EditWork::route('/{record}/edit'),
        ];
    }
}
