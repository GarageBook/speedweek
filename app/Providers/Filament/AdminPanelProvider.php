<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
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
                'primary' => [50 => '#00d1c1', 100 => '#00d1c1', 200 => '#00d1c1', 300 => '#00d1c1', 400 => '#00d1c1', 500 => '#00d1c1', 600 => '#00d1c1', 700 => '#00d1c1', 800 => '#00d1c1', 900 => '#00d1c1', 950 => '#00d1c1'],
            ])
            ->navigationItems([
                NavigationItem::make('Dashboard')
                    ->url(url('/dashboard'))
                    ->icon('heroicon-o-home')
                    ->sort(-1000),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
