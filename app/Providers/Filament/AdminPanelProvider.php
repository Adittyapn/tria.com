<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use App\Filament\Auth\Registration;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('dashboard')
            ->login()
            ->registration(Registration::class) 
            ->colors([
                'primary' => Color::Blue, 
            ])
            // Make the dashboard content wider on desktop so widgets/cards don't get truncated.
            // You can switch to 'full' if you prefer edge-to-edge content.
            ->maxContentWidth('full')
            ->resources([
                config('filament-logger.activity_resource'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
                ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
                ->discoverPages(in: app_path('Filament/PublicPages'), for: 'App\\Filament\\PublicPages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\NewOrdersAlertWidget::class,
                \App\Filament\Widgets\LatestOrdersWidget::class,
                \App\Filament\Widgets\AdminStatsOverview::class,
                \App\Filament\Widgets\CustomerOrderStats::class,
                \App\Filament\Widgets\CustomerRecentOrders::class,
                \App\Filament\Widgets\CustomerActivityWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Home')
                ->url(url('/')) 
                ->icon('heroicon-o-home')
            ])
            ->navigationGroups([
                NavigationGroup::make('Catalog'),
                NavigationGroup::make('Produk & Layanan'),
                NavigationGroup::make('Transactions'),
                NavigationGroup::make('Administration'),
                NavigationGroup::make('Pelindung'),
                NavigationGroup::make('Settings'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,

                // SetTheme::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                BreezyCore::make()
                    ->myProfile(shouldRegisterUserMenu: true),
                // FilamentBackgroundsPlugin::make(),
                // ThemesPlugin::make(),
            ])
            ->darkMode(false)
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandLogo(asset('images/logo-name.png'))
            ->brandLogoHeight('40px');

    }
}
