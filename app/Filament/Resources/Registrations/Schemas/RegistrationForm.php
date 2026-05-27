<?php

namespace App\Filament\Resources\Registrations\Schemas;

use App\Support\SpeedweekLabels;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->label('Gebruiker')->relationship('user', 'name')->searchable()->required(),
            Select::make('event_id')->label('Event')->relationship('event', 'name')->required(),
            Select::make('package_id')->label('Pakket')->relationship('package', 'name')->required(),
            Select::make('status')->label('Status')->options(SpeedweekLabels::registrationStatusOptions())->required(),
            Select::make('payment_status')->label('Betaalstatus')->options(SpeedweekLabels::paymentStatusOptions())->required(),
            Toggle::make('single_room_requested')->label('Eenpersoonskamer'),
            TextInput::make('roommate_preference')->label('Kamergenoot voorkeur'),
            Toggle::make('checked_luggage_requested')->label('Ruimbagage'),
            Textarea::make('notes')->label('Notities'),
            DateTimePicker::make('confirmed_at')->label('Bevestigd op'),
        ]);
    }
}
