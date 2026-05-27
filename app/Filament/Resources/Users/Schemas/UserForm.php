<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Naam')->required(),
            TextInput::make('email')->label('E-mail')->email()->required(),
            TextInput::make('phone')->label('Telefoon'),
            TextInput::make('password')->label('Wachtwoord')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation): bool => $operation === 'create'),
            Toggle::make('is_admin')->label('Admin'),
        ]);
    }
}
