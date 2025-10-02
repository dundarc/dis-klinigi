<?php

use App\Models\EmailSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('displays email settings form for authenticated admin user', function () {
    // Admin kullanıcısı oluştur
    $admin = User::factory()->admin()->create([
        'password' => Hash::make('password'),
    ]);

    // EmailSetting kaydı oluştur
    EmailSetting::factory()->create();

    // Giriş yap ve sayfaya git
    $response = $this->actingAs($admin)
        ->get(route('system.email.index'));

    $response->assertStatus(200)
        ->assertViewIs('system.email.configure')
        ->assertViewHas('settings');
});

it('updates email settings with valid data', function () {
    $admin = User::factory()->admin()->create([
        'password' => Hash::make('password'),
    ]);

    $settingsData = [
        'mailer' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'test@gmail.com',
        'password' => 'testpass',
        'encryption' => 'tls',
        'from_address' => 'noreply@example.com',
        'from_name' => 'Test Clinic',
        'dkim_domain' => 'example.com',
        'dkim_selector' => 'default',
        'dkim_private_key' => '-----BEGIN PRIVATE KEY-----\ntest-key\n-----END PRIVATE KEY-----',
        'spf_record' => 'v=spf1 include:_spf.example.com ~all',
    ];

    $response = $this->actingAs($admin)
        ->post(route('system.email.update'), $settingsData);

    $response->assertRedirect()
        ->assertSessionHas('success', 'E-posta ayarları başarıyla güncellendi.');

    // Veritabanında kayıt kontrolü
    $this->assertDatabaseHas('email_settings', [
        'id' => 1,
        'mailer' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'test@gmail.com',
        'from_address' => 'noreply@example.com',
        'from_name' => 'Test Clinic',
    ]);
});

it('validates required fields when updating email settings', function () {
    $admin = User::factory()->admin()->create([
        'password' => Hash::make('password'),
    ]);

    $invalidData = [
        'host' => '', // Required field boş
        'port' => 'not-a-number', // Invalid format
        'from_address' => 'invalid-email', // Invalid email
    ];

    $response = $this->actingAs($admin)
        ->post(route('system.email.update'), $invalidData);

    $response->assertRedirect()
        ->assertSessionHasErrors(['host', 'port', 'from_address']);
});

it('requires admin authentication for email settings', function () {
    // Admin olmayan kullanıcı
    $user = User::factory()->dentist()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user)
        ->get(route('system.email.index'));

    $response->assertForbidden();
});

it('encrypts DKIM private key when saving', function () {
    $admin = User::factory()->admin()->create([
        'password' => Hash::make('password'),
    ]);

    // Önceki kayıtları temizle
    EmailSetting::truncate();

    $privateKey = '-----BEGIN PRIVATE KEY-----\ntest-private-key\n-----END PRIVATE KEY-----';

    $response = $this->actingAs($admin)
        ->post(route('system.email.update'), [
            'mailer' => 'smtp',
            'host' => 'smtp.example.com',
            'port' => 587,
            'username' => 'test@example.com',
            'password' => 'testpass',
            'from_address' => 'test@example.com',
            'dkim_private_key' => $privateKey,
        ]);

    $response->assertRedirect();

    // Veritabanında şifrelenmiş olarak saklandığını kontrol et
    $setting = EmailSetting::first(); // İlk kaydı bul
    expect($setting)->not->toBeNull();
    expect($setting->dkim_private_key)->not->toBe($privateKey); // Şifrelenmiş olmalı
    expect($setting->getDkimPrivateKeyAttribute($setting->dkim_private_key))->toBe($privateKey); // Decrypt edilince aynı olmalı
});