<?php

namespace Tests\Feature;

use App\Enums\ConsentStatus;
use App\Models\Consent;
use App\Models\Patient;
use App\Services\Kvkk\ConsentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KvkkConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_without_consent_hasKvkkConsent_returns_false()
    {
        $patient = Patient::factory()->create();

        $this->assertFalse($patient->hasKvkkConsent());
    }

    public function test_patient_with_active_consent_hasKvkkConsent_returns_true()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $this->assertTrue($patient->hasKvkkConsent());
    }

    public function test_patient_with_only_canceled_consents_hasKvkkConsent_returns_false()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::CANCELED,
        ]);

        $this->assertFalse($patient->hasKvkkConsent());
    }

    public function test_patient_with_multiple_consents_latest_active_hasKvkkConsent_returns_true()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::CANCELED,
            'accepted_at' => now()->subDays(1),
        ]);
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
            'accepted_at' => now(),
        ]);

        $this->assertTrue($patient->hasKvkkConsent());
    }

    public function test_patient_with_multiple_consents_latest_canceled_hasKvkkConsent_returns_false()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
            'accepted_at' => now()->subDays(1),
        ]);
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::CANCELED,
            'accepted_at' => now(),
        ]);

        $this->assertFalse($patient->hasKvkkConsent());
    }
}