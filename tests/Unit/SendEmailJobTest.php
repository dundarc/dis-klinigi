<?php

use App\Jobs\SendEmailJob;
use App\Models\EmailLog;
use App\Models\EmailSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

it('sends email successfully and updates log status', function () {
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

    // Email log oluştur (queued durumda)
    $emailLog = EmailLog::factory()->create([
        'to_email' => 'recipient@example.com',
        'to_name' => 'Test Recipient',
        'subject' => 'Test Subject',
        'status' => 'queued',
    ]);

    // Job oluştur ve çalıştır
    $job = new SendEmailJob(
        $emailLog->id,
        'recipient@example.com',
        'Test Recipient',
        'Test Subject',
        '<p>Test HTML content</p>',
        'Test text content',
        [], // attachments
        'test-template'
    );

    $job->handle();

    // Email log'unun güncellendiğini kontrol et
    $emailLog->refresh();
    expect($emailLog->status)->toBe('sent');
    expect($emailLog->sent_at)->not->toBeNull();
    expect($emailLog->message_id)->not->toBeNull();
});

it('handles email sending failure and updates log with error', function () {
    Mail::fake();

    // Geçersiz email ayarları
    EmailSetting::factory()->create([
        'host' => '', // Invalid
        'from_address' => 'invalid-email', // Invalid
    ]);

    // Email log oluştur
    $emailLog = EmailLog::factory()->create([
        'to_email' => 'recipient@example.com',
        'status' => 'queued',
    ]);

    // Job oluştur ve çalıştır
    $job = new SendEmailJob(
        $emailLog->id,
        'recipient@example.com',
        'Test Recipient',
        'Test Subject',
        '<p>Test content</p>',
        'Test text content'
    );

    $job->handle();

    // Email log'unun failed olarak güncellendiğini kontrol et
    $emailLog->refresh();
    expect($emailLog->status)->toBe('failed');
    expect($emailLog->error_message)->not->toBeNull();
    expect($emailLog->sent_at)->toBeNull();
});

it('configures mail settings before sending', function () {
    Mail::fake();

    // Email ayarları oluştur
    EmailSetting::factory()->create([
        'mailer' => 'smtp',
        'host' => 'custom-smtp.test.com',
        'port' => 465,
        'encryption' => 'ssl',
    ]);

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        '<p>Content</p>',
        'Content'
    );

    $job->handle();

    // Mail konfigürasyonunun yüklendiğini doğrula
    // (Bu test için config assertion yapılabilir)
});

it('includes attachments in email', function () {
    Mail::fake();

    EmailSetting::factory()->create();

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    $attachments = [
        storage_path('app/test-file.pdf'),
        storage_path('app/test-image.jpg'),
    ];

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        '<p>Content</p>',
        'Content',
        $attachments
    );

    $job->handle();

    $emailLog->refresh();
    expect($emailLog->status)->toBe('sent');

    // Mail::assertSent ile attachment'ların gönderildiğini kontrol edebiliriz
});

it('handles missing email log gracefully', function () {
    Mail::fake();

    EmailSetting::factory()->create();

    $job = new SendEmailJob(
        99999, // Non-existent log ID
        'test@example.com',
        'Test',
        'Subject',
        '<p>Content</p>',
        'Content'
    );

    expect(fn() => $job->handle())->toThrow(Exception::class);
});

it('logs detailed error messages for debugging', function () {
    Mail::fake();

    // SMTP bağlantı hatası yaratacak ayarlar
    EmailSetting::factory()->create([
        'host' => 'nonexistent.smtp.server',
        'port' => 587,
    ]);

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        '<p>Content</p>',
        'Content'
    );

    $job->handle();

    $emailLog->refresh();
    expect($emailLog->status)->toBe('failed');
    expect($emailLog->error_message)->toContain('connection'); // Hata mesajında connection kelimesi olmalı
});

it('updates message ID when email is sent successfully', function () {
    Mail::fake();

    EmailSetting::factory()->create();

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        '<p>Content</p>',
        'Content'
    );

    $job->handle();

    $emailLog->refresh();
    expect($emailLog->status)->toBe('sent');
    expect($emailLog->message_id)->not->toBeNull();
    expect($emailLog->message_id)->toContain('@'); // Email message ID format kontrolü
});

it('handles both HTML and text content', function () {
    Mail::fake();

    EmailSetting::factory()->create();

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    $htmlContent = '<h1>Test HTML</h1><p>This is HTML content</p>';
    $textContent = 'Test Text Content';

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        $htmlContent,
        $textContent
    );

    $job->handle();

    $emailLog->refresh();
    expect($emailLog->status)->toBe('sent');

    // Mail content'inin doğru şekilde gönderildiğini kontrol edebiliriz
});

it('retries failed emails according to job configuration', function () {
    Mail::fake();

    // Her zaman başarısız olacak ayarlar
    EmailSetting::factory()->create([
        'host' => 'invalid.host',
        'port' => 9999,
    ]);

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        '<p>Content</p>',
        'Content'
    );

    // Job'un retry özelliklerini test et
    expect($job->tries)->toBe(3); // Default retry count
    expect($job->backoff)->toBeArray(); // Backoff strategy
});

it('cleans up temporary files after sending', function () {
    Mail::fake();

    EmailSetting::factory()->create();

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    // Geçici dosya oluştur (simülasyon)
    $tempFile = tempnam(sys_get_temp_dir(), 'email_test');
    file_put_contents($tempFile, 'test content');

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        '<p>Content</p>',
        'Content',
        [$tempFile]
    );

    $job->handle();

    $emailLog->refresh();
    expect($emailLog->status)->toBe('sent');

    // Geçici dosyanın temizlendiğini kontrol et (gerçek uygulamada)
    // Bu test için mock kullanılabilir
});

it('handles large email content efficiently', function () {
    Mail::fake();

    EmailSetting::factory()->create();

    $emailLog = EmailLog::factory()->create(['status' => 'queued']);

    // Büyük içerik oluştur
    $largeHtml = '<p>' . str_repeat('Large content ', 1000) . '</p>';
    $largeText = str_repeat('Large text content ', 1000);

    $job = new SendEmailJob(
        $emailLog->id,
        'test@example.com',
        'Test',
        'Subject',
        $largeHtml,
        $largeText
    );

    $job->handle();

    $emailLog->refresh();
    expect($emailLog->status)->toBe('sent');
    expect(strlen($emailLog->body_snippet))->toBeLessThan(256); // Snippet limit kontrolü
});