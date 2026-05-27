<?php

namespace App\Filament\Resources\ParticipantProfiles\Pages;

use App\Filament\Resources\ParticipantProfiles\ParticipantProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParticipantProfiles extends ListRecords
{
    protected static string $resource = ParticipantProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
