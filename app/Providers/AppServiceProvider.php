<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Consent;
use App\Models\Encounter;
use App\Models\InvoiceItem;
use App\Models\TreatmentPlanItem;
use App\Models\Stock\StockPurchaseInvoice;
use App\Models\Stock\StockUsage;
use App\Observers\AppointmentObserver;
use App\Observers\ConsentObserver;
use App\Observers\EncounterObserver;
use App\Observers\TreatmentPlanItemObserver;
use App\Observers\StockPurchaseInvoiceObserver;
use App\Observers\StockUsageObserver;
use App\Modules\Accounting\Observers\InvoiceItemObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Appointment::observe(AppointmentObserver::class);
        TreatmentPlanItem::observe(TreatmentPlanItemObserver::class);
        StockPurchaseInvoice::observe(StockPurchaseInvoiceObserver::class);
        StockUsage::observe(StockUsageObserver::class);
        InvoiceItem::observe(InvoiceItemObserver::class);
        Encounter::observe(EncounterObserver::class);
        Consent::observe(ConsentObserver::class);
    }
}
