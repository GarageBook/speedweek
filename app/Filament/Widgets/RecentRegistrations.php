<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Support\PaymentDisplay;
use App\Support\SpeedweekLabels;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentRegistrations extends TableWidget
{
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Recente registraties';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Registration::query()->with(['user', 'package', 'event', 'invoices'])->latest())
            ->columns([
                TextColumn::make('user.name')->label('Gebruiker'),
                TextColumn::make('event.name')->label('Event'),
                TextColumn::make('package.name')->label('Pakket'),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (?string $state): string => SpeedweekLabels::registrationStatus($state)),
                TextColumn::make('payment_summary')
                    ->label('Betaalstatus')
                    ->html()
                    ->wrap()
                    ->state(fn ($record): string => PaymentDisplay::registrationPaymentSummaryHtml($record)),
                TextColumn::make('total_amount_cents')
                    ->label('Bedrag (€)')
                    ->state(fn ($record): string => PaymentDisplay::euroFromCents((int) $record->total_amount_cents)),
                TextColumn::make('created_at')->label('Aangemaakt')->dateTime(),
            ])
            ->defaultPaginationPageOption(5);
    }
}