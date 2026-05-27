<?php

namespace App\Filament\Resources\TravelInfos\Pages;

use App\Filament\Resources\TravelInfos\TravelInfoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTravelInfos extends ListRecords
{
    protected static string $resource = TravelInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
