<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\SettingToko;
use Illuminate\Support\Facades\Cache;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->topNavigation()
            ->spa()
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->favicon($this->getFaviconUrl())
            ->brandName($this->getBrandName())
            ->brandLogo($this->getBrandLogo())
            ->brandLogoHeight('2.5rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /**
     * Get cached setting toko
     */
    private function getSettingToko()
    {
        return Cache::remember('setting_toko', 300, function () { // Cache 5 menit
            try {
                return SettingToko::first();
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    /**
     * Get favicon URL based on setting toko
     */
    private function getFaviconUrl(): string
    {
        $setting = $this->getSettingToko();

        if ($setting && $setting->logo && file_exists(storage_path('app/public/' . $setting->logo))) {
            return asset('storage/' . $setting->logo);
        }

        // Favicon default dengan tema POS
        return "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='grad' x1='0%' y1='0%' x2='100%' y2='100%'><stop offset='0%' style='stop-color:%2306b6d4;stop-opacity:1' /><stop offset='100%' style='stop-color:%230891b2;stop-opacity:1' /></linearGradient></defs><rect width='100' height='100' rx='20' fill='url(%23grad)'/><text x='50' y='70' font-size='35' text-anchor='middle' fill='white'>💰</text></svg>";
    }

    /**
     * Get brand name from setting toko
     */
    private function getBrandName(): string
    {
        $setting = $this->getSettingToko();

        if ($setting && $setting->nama_toko) {
            return $setting->nama_toko . ' - Admin';
        }

        return config('app.name', 'POS') . ' - Admin Panel';
    }

    /**
     * Get brand logo from setting toko
     */
    private function getBrandLogo(): ?string
    {
        $setting = $this->getSettingToko();

        if ($setting && $setting->logo && file_exists(storage_path('app/public/' . $setting->logo))) {
            return asset('storage/' . $setting->logo);
        }

        return null;
    }
}
