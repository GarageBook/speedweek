<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationsByPackage extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'Registrations by package';

    protected function getStats(): array
    {
        $packages = Package::withCount('registrations')->orderBy('sort_order')->get();

        if ($packages->isEmpty()) {
            return [
                Stat::make('Full Package', 0),
                Stat::make('Independent', 0),
                Stat::make('Spectator', 0),
            ];
        }

        return $packages
            ->map(fn (Package $package) => Stat::make($package->name, $package->registrations_count))
            ->all();
    }
}
