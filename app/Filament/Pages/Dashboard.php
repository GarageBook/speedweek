<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentRegistrations;
use App\Filament\Widgets\RegistrationsByPackage;
use App\Filament\Widgets\SpeedweekOperationsWidget;
use App\Filament\Widgets\SpeedweekStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Speedweek Admin';

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            SpeedweekOperationsWidget::class,
            SpeedweekStatsOverview::class,
            RegistrationsByPackage::class,
            RecentRegistrations::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
