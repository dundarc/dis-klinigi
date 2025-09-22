<?php

namespace App\Providers;

// Gerekli tüm sınıfların import edildiğinden emin olalım
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Prescription;
use App\Models\File;
use App\Policies\AppointmentPolicy;
use App\Policies\PatientPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PrescriptionPolicy;
use App\Policies\FilePolicy;
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
        Encounter::class => EncounterPolicy::class, // Bu satırı ekleyin
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate::before kuralının boot() metodu içinde olduğundan emin olun
         Gate::before(function (User $user, string $ability) {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }
    });

    // Admin özelliklerine erişim için bir Gate tanımlayalım
    Gate::define('accessAdminFeatures', function (User $user) {
        return $user->role === UserRole::ADMIN;
    });




    Gate::define('accessAccountingFeatures', function (User $user) {
        return in_array($user->role, [UserRole::ADMIN, UserRole::ACCOUNTANT]);
    });



    //YENİEKLENDİ
    Gate::before(function ($user, $ability) {
        if ($user->role === 'admin') {
            return true;
        }
    });


     // YENİ GATE
    Gate::define('accessReceptionistFeatures', function (User $user) {
        return in_array($user->role, [UserRole::ADMIN, UserRole::RECEPTIONIST]);
    });



    }
}