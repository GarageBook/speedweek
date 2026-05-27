<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Support\SpeedweekLabels;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label('Factuurnummer')->searchable(),
                TextColumn::make('registration.user.name')->label('Gebruiker'),
                TextColumn::make('type')->label('Type')->badge()->formatStateUsing(fn (?string $state): string => SpeedweekLabels::invoiceType($state)),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (?string $state): string => SpeedweekLabels::invoiceStatus($state)),
                TextColumn::make('amount_cents')->label('Bedrag')->money('EUR', divideBy: 100),
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
