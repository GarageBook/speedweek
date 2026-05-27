<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Models\MailTemplate;
use App\Support\SpeedweekLabels;
use App\Support\TemplateMailer;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Gebruiker')->searchable(),
                TextColumn::make('event.name')->label('Event'),
                TextColumn::make('package.name')->label('Pakket'),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (?string $state): string => SpeedweekLabels::registrationStatus($state)),
                TextColumn::make('payment_status')->label('Betaalstatus')->badge()->formatStateUsing(fn (?string $state): string => SpeedweekLabels::paymentStatus($state)),
                TextColumn::make('total_amount_cents')->label('Totaal')->money('EUR', divideBy: 100),
            ])
            ->filters([])
            ->recordActions([
                Action::make('sendOnboarding')->label('Stuur onboarding mail')->action(fn ($record) => TemplateMailer::sendByType($record, 'onboarding'))->successNotificationTitle('Onboarding mail verstuurd'),
                Action::make('sendPaymentReminder')->label('Stuur betaalherinnering')->action(fn ($record) => TemplateMailer::sendByType($record, 'payment'))->successNotificationTitle('Betaalherinnering verstuurd'),
                Action::make('sendPracticalInfo')->label('Stuur praktische info')->action(fn ($record) => TemplateMailer::sendByType($record, 'general'))->successNotificationTitle('Praktische info verstuurd'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('sendTemplate')
                        ->label('Stuur geselecteerde template')
                        ->schema([
                            Select::make('mail_template_id')->label('Mailtemplate')->options(MailTemplate::where('is_active', true)->pluck('name', 'id'))->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $template = MailTemplate::findOrFail($data['mail_template_id']);
                            $records->each(fn ($record) => TemplateMailer::send($record, $template));
                            Notification::make()->title('Template verstuurd')->success()->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
