<?php

namespace App\Filament\Resources\TravelInfos\Pages;

use App\Filament\Resources\TravelInfos\TravelInfoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTravelInfo extends EditRecord
{
    protected static string $resource = TravelInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
