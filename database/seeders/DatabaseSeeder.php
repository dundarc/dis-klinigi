<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core system data - run in dependency order
        $this->call([
            SettingSeeder::class,        // System settings
            EmailSettingSeeder::class,   // Email settings
            EmailTemplateSeeder::class,  // Email templates
            UserSeeder::class,           // Users (admin, dentists, staff)
            TreatmentSeeder::class,      // Dental treatments catalog
            PatientSeeder::class,        // Patient records
            TreatmentPlanSeeder::class,  // Treatment plans (before appointments)
            AppointmentSeeder::class,    // Appointments
            EncounterSeeder::class,      // Patient encounters/visits
            PatientTreatmentSeeder::class, // Treatments performed
            InvoiceSeeder::class,        // Invoices and payments
            PrescriptionSeeder::class,   // Prescriptions
            FileSeeder::class,           // Patient files/documents

            // Additional seeders for complete system:
            NotificationSeeder::class,    // System notifications
            ConsentSeeder::class,         // Patient consents
            WorkingHourSeeder::class,     // Staff working hours
            UserUnavailabilitySeeder::class, // Staff unavailability
            XRaySeeder::class,           // X-ray records
            PatientXRaySeeder::class,    // Patient X-ray links

            // Stock management:
            StockCategorySeeder::class,  // Stock categories
            StockSupplierSeeder::class,  // Suppliers
            StockItemSeeder::class,      // Stock items
            StockExpenseCategorySeeder::class, // Stock expense categories
            StockPurchaseSeeder::class,  // Purchase invoices
            StockMovementSeeder::class,  // Stock movements
            StockExpenseTableSeeder::class, // Stock expenses
            // StockUsageSeeder::class,     // Stock usage
            // StockExpenseSeeder::class,   // Expenses
        ]);
    }
}