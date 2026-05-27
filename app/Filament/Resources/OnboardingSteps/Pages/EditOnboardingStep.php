<?php

namespace App\Filament\Resources\OnboardingSteps\Pages;

use App\Filament\Resources\OnboardingSteps\OnboardingStepResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOnboardingStep extends EditRecord
{
    protected static string $resource = OnboardingStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
