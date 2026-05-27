<?php

namespace App\Filament\Resources\ParticipantProfiles;

use App\Filament\Resources\ParticipantProfiles\Pages\CreateParticipantProfile;
use App\Filament\Resources\ParticipantProfiles\Pages\EditParticipantProfile;
use App\Filament\Resources\ParticipantProfiles\Pages\ListParticipantProfiles;
use App\Filament\Resources\ParticipantProfiles\Schemas\ParticipantProfileForm;
use App\Filament\Resources\ParticipantProfiles\Tables\ParticipantProfilesTable;
use App\Models\ParticipantProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ParticipantProfileResource extends Resource
{
    protected static ?string $model = ParticipantProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ParticipantProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParticipantProfilesTable::configure($table);
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
            'index' => ListParticipantProfiles::route('/'),
            'create' => CreateParticipantProfile::route('/create'),
            'edit' => EditParticipantProfile::route('/{record}/edit'),
        ];
    }
}
