<?php

namespace Tests\Unit;

use App\Enums\ConsentStatus;
use App\Models\Consent;
use App\Models\Patient;
use App\Services\Kvkk\ConsentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_hasActive_returns_false_for_patient_without_consents()
    {
        $patient = Patient::factory()->create();

        $this->assertFalse(ConsentService::hasActive($patient));
    }

    public function test_hasActive_returns_true_for_patient_with_active_consent()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $this->assertTrue(ConsentService::hasActive($patient));
    }

    public function test_hasActive_returns_false_for_patient_with_only_canceled_consents()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::CANCELED,
        ]);

        $this->assertFalse(ConsentService::hasActive($patient));
    }

    public function test_latest_returns_null_for_patient_without_consents()
    {
        $patient = Patient::factory()->create();

        $this->assertNull(ConsentService::latest($patient));
    }

    public function test_latest_returns_most_recent_consent()
    {
        $patient = Patient::factory()->create();
        $oldConsent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'accepted_at' => now()->subDays(2),
        ]);
        $newConsent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'accepted_at' => now(),
        ]);

        $latest = ConsentService::latest($patient);

        $this->assertEquals($newConsent->id, $latest->id);
    }

    public function test_activeConsent_returns_active_consent_if_exists()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::CANCELED,
        ]);
        $activeConsent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $result = ConsentService::activeConsent($patient);

        $this->assertEquals($activeConsent->id, $result->id);
    }

    public function test_activeConsent_returns_null_if_no_active_consent()
    {
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::CANCELED,
        ]);

        $this->assertNull(ConsentService::activeConsent($patient));
    }

    public function test_register_creates_new_consent_if_no_active_exists()
    {
        $patient = Patient::factory()->create();
        $data = [
            'version' => '1.0',
            'snapshot' => ['key' => 'value'],
            'meta' => [
                'ip_address' => '127.0.0.1',
                'user_agent' => 'TestAgent',
            ],
            'signature_path' => '/path/to/signature',
        ];

        $consent = ConsentService::register($patient, $data);

        $this->assertEquals($patient->id, $consent->patient_id);
        $this->assertEquals(ConsentStatus::ACTIVE, $consent->status);
        $this->assertEquals('1.0', $consent->version);
        $this->assertEquals(['key' => 'value'], $consent->snapshot);
        $this->assertEquals('127.0.0.1', $consent->ip_address);
        $this->assertEquals('TestAgent', $consent->user_agent);
        $this->assertEquals('/path/to/signature', $consent->signature_path);
    }

    public function test_register_returns_existing_active_consent()
    {
        $patient = Patient::factory()->create();
        $existingConsent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $data = ['version' => '1.0'];
        $result = ConsentService::register($patient, $data);

        $this->assertEquals($existingConsent->id, $result->id);
    }

    public function test_cancel_sets_status_to_canceled_and_sets_canceled_at()
    {
        $consent = Consent::factory()->create(['status' => ConsentStatus::ACTIVE]);

        $result = ConsentService::cancel($consent);

        $this->assertEquals(ConsentStatus::CANCELED, $result->status);
        $this->assertNotNull($result->canceled_at);
    }

    public function test_cancelActive_cancels_active_consent_and_returns_true()
    {
        $patient = Patient::factory()->create();
        $consent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $result = ConsentService::cancelActive($patient);

        $this->assertTrue($result);
        $this->assertEquals(ConsentStatus::CANCELED, $consent->fresh()->status);
    }

    public function test_cancelActive_returns_false_if_no_active_consent()
    {
        $patient = Patient::factory()->create();

        $this->assertFalse(ConsentService::cancelActive($patient));
    }

    public function test_generateSnapshotHash_creates_sha256_hash()
    {
        $snapshot = ['test' => 'data'];
        $hash = ConsentService::generateSnapshotHash($snapshot);

        $this->assertEquals(hash('sha256', json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)), $hash);
    }

    public function test_initiateCancellation_sets_pdf_generated_at()
    {
        $consent = Consent::factory()->create(['status' => ConsentStatus::ACTIVE]);

        $result = ConsentService::initiateCancellation($consent);

        $this->assertNotNull($result->cancellation_pdf_generated_at);
        $this->assertEquals(ConsentStatus::ACTIVE, $result->status);
    }

    public function test_initiateCancellation_throws_exception_for_non_active_consent()
    {
        $consent = Consent::factory()->create(['status' => ConsentStatus::CANCELED]);

        $this->expectException(\InvalidArgumentException::class);
        ConsentService::initiateCancellation($consent);
    }

    public function test_markPdfDownloaded_sets_pdf_downloaded_at()
    {
        $consent = Consent::factory()->create();

        $result = ConsentService::markPdfDownloaded($consent);

        $this->assertNotNull($result->cancellation_pdf_downloaded_at);
    }

    public function test_cancelWithAudit_throws_exception_if_pdf_not_downloaded()
    {
        $user = \App\Models\User::factory()->create();
        $consent = Consent::factory()->create(['status' => ConsentStatus::ACTIVE]);

        $this->expectException(\InvalidArgumentException::class);
        ConsentService::cancelWithAudit($consent, $user);
    }

    public function test_cancelWithAudit_cancels_consent_and_logs()
    {
        $user = \App\Models\User::factory()->create();
        $consent = Consent::factory()->create([
            'status' => ConsentStatus::ACTIVE,
            'cancellation_pdf_downloaded_at' => now(),
        ]);

        $result = ConsentService::cancelWithAudit($consent, $user);

        $this->assertEquals(ConsentStatus::CANCELED, $result->status);
        $this->assertNotNull($result->canceled_at);

        $this->assertDatabaseHas('kvkk_cancellation_logs', [
            'consent_id' => $consent->id,
            'user_id' => $user->id,
        ]);
    }
}