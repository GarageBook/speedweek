<?php

namespace App\Filament\Resources\ParticipantProfiles\Pages;

use App\Filament\Resources\ParticipantProfiles\ParticipantProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditParticipantProfile extends EditRecord
{
    protected static string $resource = ParticipantProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
