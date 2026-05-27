<?php

namespace App\Filament\Resources\ProgrammeItems\Schemas;

use App\Support\SpeedweekLabels;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProgrammeItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('event_id')->label('Event')->relationship('event', 'name')->required(),
            TextInput::make('title')->label('Titel')->required(),
            Textarea::make('description')->label('Omschrijving'),
            DateTimePicker::make('starts_at')->label('Start')->required(),
            DateTimePicker::make('ends_at')->label('Einde'),
            TextInput::make('location')->label('Locatie'),
            Select::make('type')->label('Type')->options(SpeedweekLabels::programmeTypeOptions())->required(),
        ]);
    }
}
