<?php
namespace App\Filament\Widgets;
use App\Models\Registration; use Filament\Tables\Columns\TextColumn; use Filament\Tables\Table; use Filament\Widgets\TableWidget; use Illuminate\Database\Eloquent\Builder;
class RecentRegistrations extends TableWidget { protected static bool $isLazy = false; protected static ?string $heading = 'Recent registrations'; public function table(Table $table): Table { return $table->query(fn(): Builder => Registration::query()->with(['user','package','event'])->latest())->columns([TextColumn::make('user.name'), TextColumn::make('event.name'), TextColumn::make('package.name'), TextColumn::make('status')->badge(), TextColumn::make('payment_status')->badge(), TextColumn::make('created_at')->dateTime()])->defaultPaginationPageOption(5); } }
