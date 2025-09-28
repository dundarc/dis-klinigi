<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Appointment;
use App\Models\TreatmentPlanItem;
use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockUsage;
use App\Observers\AppointmentObserver;
use App\Observers\TreatmentPlanItemObserver;
use App\Observers\StockPurchaseInvoiceObserver;
use App\Observers\StockUsageObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register observers
        Appointment::observe(AppointmentObserver::class);
        TreatmentPlanItem::observe(TreatmentPlanItemObserver::class);
        StockPurchaseInvoice::observe(StockPurchaseInvoiceObserver::class);
        StockUsage::observe(StockUsageObserver::class);
    }
}