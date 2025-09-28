<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DENTIST = 'dentist';
    case RECEPTIONIST = 'receptionist';
    case ACCOUNTANT = 'accountant';
    case ASSISTANT = 'assistant';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Yönetici',
            self::DENTIST => 'Diş Hekimi',
            self::RECEPTIONIST => 'Resepsiyonist',
            self::ACCOUNTANT => 'Muhasebeci',
            self::ASSISTANT => 'Asistan',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ADMIN => 'Sistem yöneticisi - tüm yetkilere sahip',
            self::DENTIST => 'Diş hekimi - hasta tedavisi ve randevu yönetimi',
            self::RECEPTIONIST => 'Resepsiyonist - randevu ve hasta kaydı',
            self::ACCOUNTANT => 'Muhasebeci - finans ve fatura yönetimi',
            self::ASSISTANT => 'Asistan - destekleyici görevler',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => [
                'accessAdminFeatures',
                'viewAllPatients',
                'manageUsers',
                'viewAllAppointments',
                'manageStock',
                'viewFinancialReports',
                'sendNotifications',
                'accessAccountingFeatures',
                'manageSystemSettings',
            ],
            self::DENTIST => [
                'viewOwnPatients',
                'manageOwnAppointments',
                'performTreatments',
                'viewOwnSchedule',
                'accessWaitingRoom',
                'manageTreatmentPlans',
                'prescribeMedications',
                'uploadPatientFiles',
            ],
            self::RECEPTIONIST => [
                'viewAllPatients',
                'manageAppointments',
                'registerPatients',
                'viewTodaySchedule',
                'accessWaitingRoom',
                'sendNotifications',
                'viewBasicReports',
            ],
            self::ACCOUNTANT => [
                'accessAccountingFeatures',
                'viewAllInvoices',
                'managePayments',
                'viewFinancialReports',
                'manageStockPurchases',
                'viewStockReports',
            ],
            self::ASSISTANT => [
                'viewAssignedPatients',
                'assistTreatments',
                'manageOwnSchedule',
                'uploadPatientFiles',
                'accessWaitingRoom',
                'viewBasicReports',
            ],
        };
    }

    public function can(string $permission): bool
    {
        return in_array($permission, $this->permissions());
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($role) => [
            $role->value => $role->label()
        ])->toArray();
    }
}
