<?php

namespace App\Filament\Resources\TravelInfos;

use App\Filament\Resources\TravelInfos\Pages\CreateTravelInfo;
use App\Filament\Resources\TravelInfos\Pages\EditTravelInfo;
use App\Filament\Resources\TravelInfos\Pages\ListTravelInfos;
use App\Filament\Resources\TravelInfos\Schemas\TravelInfoForm;
use App\Filament\Resources\TravelInfos\Tables\TravelInfosTable;
use App\Models\TravelInfo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TravelInfoResource extends Resource
{
    protected static ?string $model = TravelInfo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TravelInfoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TravelInfosTable::configure($table);
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
            'index' => ListTravelInfos::route('/'),
            'create' => CreateTravelInfo::route('/create'),
            'edit' => EditTravelInfo::route('/{record}/edit'),
        ];
    }
}
