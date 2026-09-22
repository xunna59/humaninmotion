<?php

namespace App\Providers;

use App\Services\Cart\CartService;
use App\Services\Catalogue\ProductQuery;
use App\Services\NavigationService;
use App\Services\Pricing\PricingService;
use App\Services\Promotions\PromotionService;
use App\Services\SettingsService;
use App\Services\Shipping\ShippingService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsService::class);
        $this->app->singleton(NavigationService::class);
        $this->app->singleton(CartService::class);
        $this->app->singleton(PricingService::class);
        $this->app->singleton(ShippingService::class);
        $this->app->singleton(PromotionService::class);
        $this->app->bind(ProductQuery::class);
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination.custom');
        Paginator::defaultSimpleView('pagination.custom');
    }
}