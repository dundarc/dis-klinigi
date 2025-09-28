<?php

use App\Models\Patient;
use App\Models\Encounter;
use App\Enums\TriageLevel;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('bekleme odası acil hastaları triage seviyesine göre doğru sıralar', function () {
    // 1. Hazırlık (Arrange)
    $receptionist = User::factory()->receptionist()->create();
    $patient1 = Patient::factory()->create();
    $patient2 = Patient::factory()->create();
    $patient3 = Patient::factory()->create();

    // Farklı triage seviyelerinde ve karışık sırada 3 acil vaka oluştur
    Encounter::factory()->emergency()->create(['patient_id' => $patient2->id, 'triage_level' => TriageLevel::YELLOW, 'arrived_at' => now()->subMinutes(10)]);
    Encounter::factory()->emergency()->create(['patient_id' => $patient3->id, 'triage_level' => TriageLevel::RED, 'arrived_at' => now()->subMinutes(5)]); // En son gelen ama en acil
    Encounter::factory()->emergency()->create(['patient_id' => $patient1->id, 'triage_level' => TriageLevel::GREEN, 'arrived_at' => now()->subMinutes(15)]);

    // 2. Eylem (Act)
    // Bekleme odası sayfasını (veya API endpoint'ini) çağır
    // Şimdilik WaitingRoomController'daki mantığı doğrudan test edelim
    $controller = app()->make(\App\Http\Controllers\WaitingRoomController::class);
    $view = $controller->emergency();
    $waitingEncounters = $view->getData()['emergencyEncounters'];
    
    // 3. Doğrulama (Assert)
    // Listenin doğru sıralandığından emin ol: RED, YELLOW, GREEN
    expect($waitingEncounters->count())->toBe(3);
    expect($waitingEncounters->get(0)->patient_id)->toBe($patient3->id); // Red
    expect($waitingEncounters->get(1)->patient_id)->toBe($patient2->id); // Yellow
    expect($waitingEncounters->get(2)->patient_id)->toBe($patient1->id); // Green
});