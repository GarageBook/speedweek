<?php
namespace App\Filament\Resources\Users\Schemas;
use Filament\Forms\Components\DatePicker; use Filament\Forms\Components\DateTimePicker; use Filament\Forms\Components\Select; use Filament\Forms\Components\TextInput; use Filament\Forms\Components\Textarea; use Filament\Forms\Components\Toggle; use Filament\Schemas\Schema;
class UserForm { public static function configure(Schema $schema): Schema { return $schema->components([TextInput::make('name')->required(), TextInput::make('email')->email()->required(), TextInput::make('phone'), TextInput::make('password')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation): bool => $operation === 'create'), Toggle::make('is_admin')]); } }
