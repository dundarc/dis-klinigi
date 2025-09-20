<?php

use App\Models\User;
use App\Models\Appointment;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('hekim kendisine ait olmayan bir randevuyu görüntüleyemez', function () {
    // 1. Hazırlık (Arrange)
    $dentist1 = User::factory()->dentist()->create();
    $dentist2 = User::factory()->dentist()->create();

    // 2. hekime ait bir randevu oluştur
    $appointment = Appointment::factory()->create(['dentist_id' => $dentist2->id]);
    
    // 2. Eylem (Act) & Doğrulama (Assert)
    // 1. hekim olarak giriş yap ve 2. hekimin randevusunu görmeye çalış
    actingAs($dentist1, 'sanctum')
        ->getJson("/api/v1/appointments/{$appointment->id}")
        ->assertForbidden(); // 403 Forbidden hatası bekliyoruz
});

test('admin kendisine ait olmayan bir randevuyu görüntüleyebilir', function () {
    // 1. Hazırlık (Arrange)
    $admin = User::factory()->admin()->create();
    $dentist = User::factory()->dentist()->create();
    $appointment = Appointment::factory()->create(['dentist_id' => $dentist->id]);

    // 2. Eylem (Act) & Doğrulama (Assert)
    // Admin olarak giriş yap ve hekimin randevusunu görmeye çalış
    actingAs($admin, 'sanctum')
        ->getJson("/api/v1/appointments/{$appointment->id}")
        ->assertOk(); // 200 OK yanıtı bekliyoruz
});