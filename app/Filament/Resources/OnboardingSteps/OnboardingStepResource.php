<?php

namespace App\Filament\Resources\OnboardingSteps;

use App\Filament\Resources\OnboardingSteps\Pages\CreateOnboardingStep;
use App\Filament\Resources\OnboardingSteps\Pages\EditOnboardingStep;
use App\Filament\Resources\OnboardingSteps\Pages\ListOnboardingSteps;
use App\Filament\Resources\OnboardingSteps\Schemas\OnboardingStepForm;
use App\Filament\Resources\OnboardingSteps\Tables\OnboardingStepsTable;
use App\Models\OnboardingStep;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OnboardingStepResource extends Resource
{
    protected static ?string $model = OnboardingStep::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OnboardingStepForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OnboardingStepsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOnboardingSteps::route('/'),
            'create' => CreateOnboardingStep::route('/create'),
            'edit' => EditOnboardingStep::route('/{record}/edit'),
        ];
    }
}
