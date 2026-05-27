<?php

namespace App\Filament\Resources\TireRequests\Pages;

use App\Filament\Resources\TireRequests\TireRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTireRequest extends EditRecord
{
    protected static string $resource = TireRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
