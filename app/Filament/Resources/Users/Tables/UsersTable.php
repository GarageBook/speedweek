<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Registration;
use App\Support\PaymentDisplay;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Naam')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                IconColumn::make('is_admin')->label('Admin')->boolean(),
                TextColumn::make('payment_summary')
                    ->label('Betaalstatus')
                    ->html()
                    ->state(function ($record): string {
                        $registration = Registration::with('invoices')
                            ->where('user_id', $record->id)
                            ->latest()
                            ->first();

                        return PaymentDisplay::registrationPaymentSummaryHtml($registration);
                    }),
                TextColumn::make('amount_eur')
                    ->label('Bedrag (€)')
                    ->state(function ($record): string {
                        $registration = Registration::where('user_id', $record->id)->latest()->first();

                        return $registration
                            ? PaymentDisplay::euroFromCents((int) $registration->total_amount_cents)
                            : '€ 0,00';
                    }),
                TextColumn::make('created_at')->label('Aangemaakt')->dateTime()->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
