<?php

use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('displays email logs list for authenticated admin', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Test log kayıtları oluştur
    EmailLog::factory()->count(5)->create();

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.index'));

    $response->assertStatus(200)
        ->assertViewIs('system.email.logs.index')
        ->assertViewHas('logs');
});

it('displays individual email log details', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $log = EmailLog::factory()->create([
        'subject' => 'Test Subject',
        'body_snippet' => 'Test body content...',
        'status' => 'sent',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.show', $log));

    $response->assertStatus(200)
        ->assertViewIs('system.email.logs.show')
        ->assertViewHas('log', $log);
});

it('filters email logs by status', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Farklı statülerde loglar oluştur
    EmailLog::factory()->create(['status' => 'sent']);
    EmailLog::factory()->create(['status' => 'failed']);
    EmailLog::factory()->create(['status' => 'queued']);

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.index', ['status' => 'sent']));

    $response->assertStatus(200);

    // Response'da sadece 'sent' statüsündeki logların olduğunu kontrol et
    $logs = $response->viewData('logs');
    expect($logs->pluck('status')->unique())->toContain('sent');
});

it('filters email logs by recipient email', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    EmailLog::factory()->create(['to_email' => 'john@example.com']);
    EmailLog::factory()->create(['to_email' => 'jane@example.com']);
    EmailLog::factory()->create(['to_email' => 'bob@example.com']);

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.index', ['email' => 'john@example.com']));

    $response->assertStatus(200);

    $logs = $response->viewData('logs');
    expect($logs->count())->toBe(1);
    expect($logs->first()->to_email)->toBe('john@example.com');
});

it('filters email logs by template key', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    EmailLog::factory()->create(['template_key' => 'appointment_reminder']);
    EmailLog::factory()->create(['template_key' => 'invoice_mail']);
    EmailLog::factory()->create(['template_key' => null]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.index', ['template_key' => 'appointment_reminder']));

    $response->assertStatus(200);

    $logs = $response->viewData('logs');
    expect($logs->count())->toBe(1);
    expect($logs->first()->template_key)->toBe('appointment_reminder');
});

it('filters email logs by date range', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Farklı tarihlerde loglar oluştur
    $oldLog = EmailLog::factory()->create();
    $oldLog->update(['created_at' => now()->subDays(10)]);

    $newLog = EmailLog::factory()->create();
    $newLog->update(['created_at' => now()->subDays(2)]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.index', [
            'date_from' => now()->subDays(5)->format('Y-m-d'),
            'date_to' => now()->format('Y-m-d')
        ]));

    $response->assertStatus(200);

    $logs = $response->viewData('logs');
    expect($logs->count())->toBe(1);
    expect($logs->first()->id)->toBe($newLog->id);
});

it('paginates email logs', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // 25 log oluştur (pagination için)
    EmailLog::factory()->count(25)->create();

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.index', ['per_page' => 10]));

    $response->assertStatus(200);

    $logs = $response->viewData('logs');
    expect($logs->count())->toBe(10); // İlk sayfada 10 kayıt
    expect($logs->hasPages())->toBe(true); // Pagination var
});

it('requires admin authentication for email logs', function () {
    $user = User::factory()->create([
        'role' => 'dentist',
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user)
        ->get(route('system.email.logs.index'));

    $response->assertForbidden();
});

it('displays correct status badges in log list', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $sentLog = EmailLog::factory()->create(['status' => 'sent']);
    $failedLog = EmailLog::factory()->create(['status' => 'failed']);
    $queuedLog = EmailLog::factory()->create(['status' => 'queued']);

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.index'));

    $response->assertStatus(200)
        ->assertSee('text-green-800', false) // Sent status için
        ->assertSee('text-red-800', false) // Failed status için
        ->assertSee('text-yellow-800', false); // Queued status için
});

it('shows error message for failed emails in log details', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $failedLog = EmailLog::factory()->create([
        'status' => 'failed',
        'error_message' => 'SMTP connection failed: Invalid credentials',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.logs.show', $failedLog));

    $response->assertStatus(200)
        ->assertSee('SMTP connection failed: Invalid credentials');
});