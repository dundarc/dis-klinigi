<?php

use App\Models\User;
use App\Models\Patient;
use App\Enums\UserRole;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('aynı hekime çakışan bir randevu oluşturulamaz', function () {
    // 1. Hazırlık (Arrange)
    $receptionist = User::factory()->receptionist()->create();
    $dentist = User::factory()->dentist()->create();
    $patient = Patient::factory()->create();

    $startTime = now()->addDay()->setHour(10)->setMinutes(0)->setSeconds(0);
    $endTime = $startTime->copy()->addMinutes(30);

    \App\Models\Appointment::factory()->create([
        'dentist_id' => $dentist->id,
        'patient_id' => $patient->id, // Testin tutarlılığı için bunu da ekleyelim
        'start_at' => $startTime,
        'end_at' => $endTime,
    ]);

    // 2. Eylem (Act)
    $response = actingAs($receptionist, 'sanctum')->postJson('/api/v1/appointments', [
        'patient_id' => $patient->id,
        'dentist_id' => $dentist->id,
        'start_at' => $startTime->copy()->addMinutes(15)->toDateTimeString(),
        'end_at' => $startTime->copy()->addMinutes(45)->toDateTimeString(),
    ]);

    // 3. Doğrulama (Assert)
    $response->assertStatus(422);

    // --- DEĞİŞİKLİK BURADA ---
    // Hatanın 'start_at' yerine 'end_at' alanında olduğunu doğrula
    $response->assertJsonValidationErrors('start_at');

    // Veritabanında sadece 1 randevu olduğundan emin ol
    $this->assertDatabaseCount('appointments', 1);
});