<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Support\PaymentDisplay;
use App\Support\SpeedweekLabels;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('registration_id')->label('Registratie')->relationship('registration', 'id')->required(),
            TextInput::make('invoice_number')->label('Factuurnummer')->required(),
            Select::make('type')->label('Type')->options(SpeedweekLabels::invoiceTypeOptions())->required(),
            Select::make('status')->label('Status')->options(SpeedweekLabels::invoiceStatusOptions())->required(),
            TextInput::make('amount_eur')
                ->label('Bedrag (€)')
                ->required()
                ->numeric()
                ->step(0.01)
                ->dehydrated(false)
                ->formatStateUsing(fn ($state, $record) => $record ? number_format(((int) $record->amount_cents) / 100, 2, '.', '') : null)
                ->afterStateHydrated(function (TextInput $component, $state, $record): void {
                    if ($record) {
                        $component->state(number_format(((int) $record->amount_cents) / 100, 2, '.', ''));
                    }
                })
                ->afterStateUpdated(function ($state, callable $set): void {
                    $cents = (int) round(((float) $state) * 100);
                    $set('amount_cents', $cents);
                }),
            TextInput::make('amount_cents')->hidden()->required(),
            DatePicker::make('due_at')->label('Vervaldatum'),
            DateTimePicker::make('sent_at')->label('Verstuurd op'),
            DateTimePicker::make('paid_at')->label('Betaald op'),
            Textarea::make('notes')->label('Notities'),
        ]);
    }
}
