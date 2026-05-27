<?php

namespace App\Filament\Resources\TireRequests\Pages;

use App\Filament\Resources\TireRequests\TireRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTireRequests extends ListRecords
{
    protected static string $resource = TireRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
