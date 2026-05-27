<?php

namespace App\Filament\Resources\ProgrammeItems\Pages;

use App\Filament\Resources\ProgrammeItems\ProgrammeItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProgrammeItem extends EditRecord
{
    protected static string $resource = ProgrammeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
