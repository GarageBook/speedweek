<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Support\SpeedweekLabels;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentRegistrations extends TableWidget
{
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Recente registraties';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Registration::query()->with(['user', 'package', 'event'])->latest())
            ->columns([
                TextColumn::make('user.name')->label('Gebruiker'),
                TextColumn::make('event.name')->label('Event'),
                TextColumn::make('package.name')->label('Pakket'),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (?string $state): string => SpeedweekLabels::registrationStatus($state)),
                TextColumn::make('payment_status')->label('Betaalstatus')->badge()->formatStateUsing(fn (?string $state): string => SpeedweekLabels::paymentStatus($state)),
                TextColumn::make('created_at')->label('Aangemaakt')->dateTime(),
            ])
            ->defaultPaginationPageOption(5);
    }
}
