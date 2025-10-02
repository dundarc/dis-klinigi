<?php

namespace Tests\Feature;

use App\Enums\ConsentStatus;
use App\Models\Consent;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KvkkConsentCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancel_consent_without_pdf_download_returns_422()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $consent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $this->actingAs($user)
            ->post(route('kvkk.process-cancel-consent', $patient))
            ->assertStatus(422);
    }

    public function test_cancel_consent_after_pdf_download_returns_200_and_sets_status_to_canceled()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $consent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
            'cancellation_pdf_downloaded_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('kvkk.process-cancel-consent', $patient))
            ->assertRedirect(route('kvkk.show', $patient))
            ->assertSessionHas('success');

        $consent->refresh();
        $this->assertEquals(ConsentStatus::CANCELED, $consent->status);
        $this->assertNotNull($consent->canceled_at);
    }

    public function test_pdf_download_marks_pdf_as_downloaded()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $consent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $this->actingAs($user)
            ->get(route('kvkk.cancel-consent.pdf', $patient))
            ->assertOk();

        $consent->refresh();
        $this->assertNotNull($consent->cancellation_pdf_downloaded_at);
        $this->assertNotNull($consent->cancellation_pdf_generated_at);
    }

    public function test_cancel_consent_creates_audit_log()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $consent = Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
            'cancellation_pdf_downloaded_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('kvkk.process-cancel-consent', $patient));

        $this->assertDatabaseHas('kvkk_cancellation_logs', [
            'consent_id' => $consent->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_create_consent_form_shows_for_patient_without_consent()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $this->actingAs($user)
            ->get(route('kvkk.create-consent', $patient))
            ->assertOk()
            ->assertSee('KVKK Onamı Oluştur');
    }

    public function test_create_consent_redirects_if_patient_already_has_consent()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $this->actingAs($user)
            ->get(route('kvkk.create-consent', $patient))
            ->assertRedirect(route('kvkk.show', $patient));
    }

    public function test_store_consent_creates_new_consent()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $data = [
            'version' => '1.0',
            'snapshot' => [
                'personal_data' => 'Test data',
                'purpose' => 'Test purpose',
            ],
            'signature_path' => '/test/signature.pdf',
        ];

        $this->actingAs($user)
            ->post(route('kvkk.store-consent', $patient), $data)
            ->assertRedirect(route('kvkk.show', $patient))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('consents', [
            'patient_id' => $patient->id,
            'version' => '1.0',
            'status' => ConsentStatus::ACTIVE->value,
        ]);
    }

    public function test_store_consent_fails_if_patient_already_has_active_consent()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        Consent::factory()->create([
            'patient_id' => $patient->id,
            'status' => ConsentStatus::ACTIVE,
        ]);

        $data = [
            'version' => '1.0',
            'snapshot' => ['test' => 'data'],
        ];

        $this->actingAs($user)
            ->post(route('kvkk.store-consent', $patient), $data)
            ->assertRedirect(route('kvkk.show', $patient))
            ->assertSessionHas('error');
    }
}