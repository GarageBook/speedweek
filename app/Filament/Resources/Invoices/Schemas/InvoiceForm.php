<?php

namespace App\Filament\Resources\Invoices\Schemas;

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
            TextInput::make('amount_cents')->label('Bedrag in centen')->numeric()->required(),
            DatePicker::make('due_at')->label('Vervaldatum'),
            DateTimePicker::make('sent_at')->label('Verstuurd op'),
            DateTimePicker::make('paid_at')->label('Betaald op'),
            Textarea::make('notes')->label('Notities'),
        ]);
    }
}
