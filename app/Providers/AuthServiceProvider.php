<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\File;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Policies\EncounterPolicy;
use App\Policies\FilePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PatientPolicy;
use App\Policies\PrescriptionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Patient::class => PatientPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Prescription::class => PrescriptionPolicy::class,
        File::class => FilePolicy::class,
        Encounter::class => EncounterPolicy::class,
        Notification::class => NotificationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Admin override - admins can do everything
        Gate::before(function (User $user, string $ability) {
            return $user->isAdmin() ? true : null;
        });

        // Define gates for permissions that need special logic
        // Most permissions are now handled through UserRole enum
        Gate::define('accessAdminFeatures', fn (User $user) => $user->isAdmin());
        Gate::define('accessAccountingFeatures', fn (User $user) => $user->isAdmin() || $user->isAccountant());
        Gate::define('accessStockManagement', fn (User $user) => $user->isAdmin() || $user->isAccountant());
        Gate::define('accessReceptionistFeatures', fn (User $user) => $user->role?->can('accessReceptionistFeatures') ?? false);
        Gate::define('accessKvkkFeatures', fn (User $user) => $user->isAdmin() || $user->isReceptionist() || $user->isDentist());
    }
}
