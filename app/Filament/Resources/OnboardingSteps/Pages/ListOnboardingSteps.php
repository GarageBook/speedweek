<?php

namespace App\Filament\Resources\OnboardingSteps\Pages;

use App\Filament\Resources\OnboardingSteps\OnboardingStepResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOnboardingSteps extends ListRecords
{
    protected static string $resource = OnboardingStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
