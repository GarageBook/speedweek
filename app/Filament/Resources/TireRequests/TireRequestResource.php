<?php

namespace App\Filament\Resources\TireRequests;

use App\Filament\Resources\TireRequests\Pages\CreateTireRequest;
use App\Filament\Resources\TireRequests\Pages\EditTireRequest;
use App\Filament\Resources\TireRequests\Pages\ListTireRequests;
use App\Filament\Resources\TireRequests\Schemas\TireRequestForm;
use App\Filament\Resources\TireRequests\Tables\TireRequestsTable;
use App\Models\TireRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TireRequestResource extends Resource
{
    protected static ?string $model = TireRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TireRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TireRequestsTable::configure($table);
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
            'index' => ListTireRequests::route('/'),
            'create' => CreateTireRequest::route('/create'),
            'edit' => EditTireRequest::route('/{record}/edit'),
        ];
    }
}
