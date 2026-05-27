<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\FontProviders\LocalFontProvider;
use Filament\Widgets\AccountWidget;
use App\Filament\Widgets\RecentRegistrations;
use App\Filament\Widgets\RegistrationsByPackage;
use App\Filament\Widgets\SpeedweekStatsOverview;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Speedweek')
            ->brandLogo(asset('images/logo_25_meetthespeed_edited_edited.avif'))
            ->brandLogoHeight('2.5rem')
            ->font('Play', asset('fonts/play.css'), LocalFontProvider::class)
            ->colors([
                'primary' => [50 => '#fdf3f2', 100 => '#fbe3e1', 200 => '#f6cbc7', 300 => '#efa8a1', 400 => '#e57970', 500 => '#d0362e', 600 => '#bd3029', 700 => '#9d2823', 800 => '#81231f', 900 => '#6c211e', 950 => '#3b0e0c'],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                SpeedweekStatsOverview::class,
                RegistrationsByPackage::class,
                RecentRegistrations::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
