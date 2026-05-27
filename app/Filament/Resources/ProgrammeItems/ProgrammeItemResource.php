<?php

namespace App\Filament\Resources\ProgrammeItems;

use App\Filament\Resources\ProgrammeItems\Pages\CreateProgrammeItem;
use App\Filament\Resources\ProgrammeItems\Pages\EditProgrammeItem;
use App\Filament\Resources\ProgrammeItems\Pages\ListProgrammeItems;
use App\Filament\Resources\ProgrammeItems\Schemas\ProgrammeItemForm;
use App\Filament\Resources\ProgrammeItems\Tables\ProgrammeItemsTable;
use App\Models\ProgrammeItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgrammeItemResource extends Resource
{
    protected static ?string $model = ProgrammeItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProgrammeItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgrammeItemsTable::configure($table);
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
            'index' => ListProgrammeItems::route('/'),
            'create' => CreateProgrammeItem::route('/create'),
            'edit' => EditProgrammeItem::route('/{record}/edit'),
        ];
    }
}
