<?php
namespace App\Filament\Widgets;
use App\Models\Package; use Filament\Widgets\StatsOverviewWidget; use Filament\Widgets\StatsOverviewWidget\Stat;
class RegistrationsByPackage extends StatsOverviewWidget { protected function getStats(): array { return Package::withCount('registrations')->orderBy('sort_order')->get()->map(fn($p)=>Stat::make($p->name, $p->registrations_count))->all(); } }
