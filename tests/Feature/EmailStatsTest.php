<?php

use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

uses(RefreshDatabase::class);

it('displays email statistics for authenticated admin', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.stats.index'));

    $response->assertStatus(200)
        ->assertViewIs('system.email.stats.index');
});

it('calculates correct statistics for last 30 days', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Son 30 gün içinde farklı tarihlerde loglar oluştur
    $today = now();
    $tenDaysAgo = now()->subDays(10);
    $twentyDaysAgo = now()->subDays(20);
    $fortyDaysAgo = now()->subDays(40);

    // Son 30 gün içinde
    EmailLog::factory()->count(5)->create([
        'status' => 'sent',
        'created_at' => $today,
    ]);

    EmailLog::factory()->count(2)->create([
        'status' => 'failed',
        'created_at' => $tenDaysAgo,
    ]);

    EmailLog::factory()->count(3)->create([
        'status' => 'sent',
        'created_at' => $twentyDaysAgo,
    ]);

    // Son 30 gün dışında
    EmailLog::factory()->count(10)->create([
        'status' => 'sent',
        'created_at' => $fortyDaysAgo,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.stats.index'));

    $response->assertStatus(200);

    $stats = $response->viewData('stats');

    // Toplam gönderilen: 5 (today) + 3 (20 days ago) = 8
    expect($stats['total_sent'])->toBe(8);

    // Toplam başarısız: 2 (10 days ago)
    expect($stats['total_failed'])->toBe(2);

    // Başarı oranı: (8 / (8 + 2)) * 100 = 80%
    expect($stats['success_rate'])->toBe(80.0);

    // Günlük istatistikler kontrolü
    expect($stats['daily_stats'])->toBeArray();
    expect(count($stats['daily_stats']))->toBe(30); // Son 30 gün
});

it('calculates statistics for last 24 hours', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Son 24 saat içinde
    EmailLog::factory()->count(3)->create([
        'status' => 'sent',
        'created_at' => now()->subHours(2),
    ]);

    EmailLog::factory()->count(1)->create([
        'status' => 'failed',
        'created_at' => now()->subHours(6),
    ]);

    // Son 24 saat dışında
    EmailLog::factory()->count(5)->create([
        'status' => 'sent',
        'created_at' => now()->subHours(30),
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.stats.index'));

    $response->assertStatus(200);

    $stats = $response->viewData('stats');

    expect($stats['last_24h_sent'])->toBe(3);
    expect($stats['last_24h_failed'])->toBe(1);
});

it('calculates statistics for last 7 days', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Son 7 gün içinde
    EmailLog::factory()->count(10)->create([
        'status' => 'sent',
        'created_at' => now()->subDays(3),
    ]);

    EmailLog::factory()->count(2)->create([
        'status' => 'failed',
        'created_at' => now()->subDays(5),
    ]);

    // Son 7 gün dışında
    EmailLog::factory()->count(8)->create([
        'status' => 'sent',
        'created_at' => now()->subDays(10),
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.stats.index'));

    $response->assertStatus(200);

    $stats = $response->viewData('stats');

    expect($stats['last_7d_sent'])->toBe(10);
    expect($stats['last_7d_failed'])->toBe(2);
});

it('shows most recent email send time', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $recentTime = now()->subHours(1);
    $olderTime = now()->subDays(2);

    EmailLog::factory()->create([
        'status' => 'sent',
        'sent_at' => $olderTime,
    ]);

    EmailLog::factory()->create([
        'status' => 'sent',
        'sent_at' => $recentTime,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.stats.index'));

    $response->assertStatus(200);

    $stats = $response->viewData('stats');

    expect($stats['last_sent_at']->format('Y-m-d H:i'))->toBe($recentTime->format('Y-m-d H:i'));
});

it('handles empty statistics correctly', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.stats.index'));

    $response->assertStatus(200);

    $stats = $response->viewData('stats');

    expect($stats['total_sent'])->toBe(0);
    expect($stats['total_failed'])->toBe(0);
    expect($stats['success_rate'])->toBe(0.0);
    expect($stats['last_sent_at'])->toBeNull();
});

it('displays daily statistics chart data correctly', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Farklı günlerde loglar oluştur
    $yesterday = now()->subDay();
    $twoDaysAgo = now()->subDays(2);

    EmailLog::factory()->count(3)->create([
        'status' => 'sent',
        'created_at' => $yesterday,
    ]);

    EmailLog::factory()->count(1)->create([
        'status' => 'failed',
        'created_at' => $yesterday,
    ]);

    EmailLog::factory()->count(2)->create([
        'status' => 'sent',
        'created_at' => $twoDaysAgo,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.stats.index'));

    $response->assertStatus(200);

    $stats = $response->viewData('stats');

    // Günlük istatistikler array kontrolü
    expect($stats['daily_stats'])->toBeArray();

    // Dünün istatistikleri kontrolü
    $yesterdayStats = collect($stats['daily_stats'])->firstWhere('date', $yesterday->format('Y-m-d'));
    expect($yesterdayStats['sent'])->toBe(3);
    expect($yesterdayStats['failed'])->toBe(1);

    // 2 gün önceki istatistikler kontrolü
    $twoDaysAgoStats = collect($stats['daily_stats'])->firstWhere('date', $twoDaysAgo->format('Y-m-d'));
    expect($twoDaysAgoStats['sent'])->toBe(2);
    expect($twoDaysAgoStats['failed'])->toBe(0);
});

it('requires admin authentication for email statistics', function () {
    $user = User::factory()->create([
        'role' => 'dentist',
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user)
        ->get(route('system.email.stats.index'));

    $response->assertForbidden();
});