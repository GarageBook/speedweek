<?php

namespace App\Filament\Resources\ProgrammeItems\Pages;

use App\Filament\Resources\ProgrammeItems\ProgrammeItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgrammeItems extends ListRecords
{
    protected static string $resource = ProgrammeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
