<?php

namespace App\Filament\Resources\ProgrammeItems\Pages;

use App\Filament\Resources\ProgrammeItems\ProgrammeItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProgrammeItem extends CreateRecord
{
    protected static string $resource = ProgrammeItemResource::class;
}
