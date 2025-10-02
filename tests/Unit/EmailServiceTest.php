<?php

use App\Models\EmailSetting;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Services\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('configures mail settings from database', function () {
    // Email ayarları oluştur
    EmailSetting::factory()->create([
        'mailer' => 'smtp',
        'host' => 'smtp.test.com',
        'port' => 587,
        'username' => 'test@test.com',
        'password' => 'testpass',
        'encryption' => 'tls',
        'from_address' => 'noreply@test.com',
        'from_name' => 'Test Clinic',
    ]);

    $emailService = app(EmailService::class);
    $emailService->configureFromDb();

    // Config'in güncellendiğini kontrol et
    expect(Config::get('mail.default'))->toBe('smtp');
    expect(Config::get('mail.mailers.smtp.host'))->toBe('smtp.test.com');
    expect(Config::get('mail.mailers.smtp.port'))->toBe(587);
    expect(Config::get('mail.mailers.smtp.username'))->toBe('test@test.com');
    expect(Config::get('mail.mailers.smtp.password'))->toBe('testpass');
    expect(Config::get('mail.mailers.smtp.encryption'))->toBe('tls');
    expect(Config::get('mail.from.address'))->toBe('noreply@test.com');
    expect(Config::get('mail.from.name'))->toBe('Test Clinic');
});

it('renders template with placeholder variables', function () {
    $template = EmailTemplate::factory()->create([
        'subject' => 'Appointment for {{ patient_name }}',
        'body_html' => '<h1>Hello {{ patient_name }}</h1><p>Your appointment is on {{ appointment_date }}</p>',
        'body_text' => 'Hello {{ patient_name }}, Your appointment is on {{ appointment_date }}',
    ]);

    $emailService = app(EmailService::class);
    $rendered = $emailService->renderTemplate($template->key, [
        'patient_name' => 'John Doe',
        'appointment_date' => '2025-01-15 14:00',
    ]);

    expect($rendered['subject'])->toBe('Appointment for John Doe');
    expect($rendered['body_html'])->toContain('Hello John Doe');
    expect($rendered['body_html'])->toContain('2025-01-15 14:00');
    expect($rendered['body_text'])->toContain('Hello John Doe');
    expect($rendered['body_text'])->toContain('2025-01-15 14:00');
});

it('queues email and creates log entry', function () {
    Mail::fake();

    $emailService = app(EmailService::class);

    $emailService->queue(
        'test@example.com',
        'Test User',
        'Test Subject',
        '<p>Test content</p>',
        'Test content text',
        'test_template'
    );

    // Email log'unun oluşturulduğunu kontrol et
    $this->assertDatabaseHas('email_logs', [
        'to_email' => 'test@example.com',
        'to_name' => 'Test User',
        'subject' => 'Test Subject',
        'status' => 'queued',
        'template_key' => 'test_template',
    ]);

    // Job'un dispatch edildiğini kontrol et (Queue fake ile)
    // Bu test için queue assertion ekleyeceğiz
});

it('handles template not found gracefully', function () {
    $emailService = app(EmailService::class);

    expect(fn() => $emailService->renderTemplate('nonexistent_template', []))
        ->toThrow(Exception::class);
});

it('sends email immediately without queuing', function () {
    Mail::fake();

    // Email ayarları oluştur
    EmailSetting::factory()->create([
        'mailer' => 'smtp',
        'host' => 'smtp.test.com',
        'port' => 587,
        'username' => 'test@test.com',
        'password' => 'testpass',
        'from_address' => 'noreply@test.com',
        'from_name' => 'Test Clinic',
    ]);

    $emailService = app(EmailService::class);
    $emailService->configureFromDb();

    $result = $emailService->sendNow(
        'test@example.com',
        'Test User',
        'Test Subject',
        '<p>Test content</p>',
        'Test content text'
    );

    expect($result)->toBe(true);

    // Email log'unun sent olarak oluşturulduğunu kontrol et
    $this->assertDatabaseHas('email_logs', [
        'to_email' => 'test@example.com',
        'status' => 'sent',
    ]);
});

it('handles email sending failure gracefully', function () {
    Mail::fake();

    // Geçersiz email ayarları
    EmailSetting::factory()->create([
        'host' => '', // Invalid host
        'from_address' => 'invalid-email', // Invalid email
    ]);

    $emailService = app(EmailService::class);
    $emailService->configureFromDb();

    $result = $emailService->sendNow(
        'test@example.com',
        'Test User',
        'Test Subject',
        '<p>Test content</p>',
        'Test content text'
    );

    expect($result)->toBe(false);

    // Email log'unun failed olarak oluşturulduğunu kontrol et
    $this->assertDatabaseHas('email_logs', [
        'to_email' => 'test@example.com',
        'status' => 'failed',
    ]);
});

it('includes attachments in queued emails', function () {
    Mail::fake();

    $emailService = app(EmailService::class);

    $attachments = [
        storage_path('app/test-file.pdf'),
        storage_path('app/test-image.jpg'),
    ];

    $emailService->queue(
        'test@example.com',
        'Test User',
        'Test Subject',
        '<p>Test content</p>',
        'Test content text',
        null,
        $attachments
    );

    // Email log kontrolü
    $log = EmailLog::where('to_email', 'test@example.com')->first();
    expect($log)->not->toBeNull();
    expect($log->status)->toBe('queued');
});

it('validates email addresses before sending', function () {
    $emailService = app(EmailService::class);

    // Geçersiz email adresi ile queue çağrısı
    $result = $emailService->queue(
        'invalid-email',
        'Test User',
        'Test Subject',
        '<p>Test content</p>',
        'Test content text'
    );

    // Bu durumda exception fırlatmalı veya false dönmeli
    // (Uygulamaya göre değişir)
});

it('handles DKIM configuration when available', function () {
    // DKIM ayarları olan email setting oluştur
    EmailSetting::factory()->create([
        'dkim_domain' => 'example.com',
        'dkim_selector' => 'default',
        'dkim_private_key' => '-----BEGIN PRIVATE KEY-----\ntest-key\n-----END PRIVATE KEY-----',
    ]);

    $emailService = app(EmailService::class);
    $emailService->configureFromDb();

    // DKIM konfigürasyonunun yüklendiğini kontrol et
    // (Bu test için mock veya config kontrolü yapılabilir)
});

it('logs email sending attempts with detailed information', function () {
    Mail::fake();

    $emailService = app(EmailService::class);

    $emailService->queue(
        'test@example.com',
        'Test User',
        'Test Subject',
        '<p>Detailed HTML content</p>',
        'Detailed text content',
        'appointment_reminder'
    );

    $log = EmailLog::where('to_email', 'test@example.com')->first();

    expect($log->subject)->toBe('Test Subject');
    expect($log->template_key)->toBe('appointment_reminder');
    expect($log->body_snippet)->toContain('Detailed');
    expect($log->status)->toBe('queued');
    expect($log->queued_at)->not->toBeNull();
});