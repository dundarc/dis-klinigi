<?php

use App\Models\EmailBounce;
use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('processes bounce webhook from Mailgun provider', function () {
    // Mailgun webhook payload örneği
    $mailgunPayload = [
        'event-data' => [
            'event' => 'failed',
            'severity' => 'permanent',
            'recipient' => 'bounce@example.com',
            'description' => 'smtp; 550 5.1.1 The email account that you tried to reach does not exist',
            'reason' => 'bounce',
            'timestamp' => time(),
        ],
        'signature' => [
            'timestamp' => time(),
            'token' => 'token123',
            'signature' => 'signature123',
        ],
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $mailgunPayload);

    $response->assertStatus(200)
        ->assertJson(['status' => 'success']);

    // Veritabanında bounce kaydının oluşturulduğunu kontrol et
    $this->assertDatabaseHas('email_bounces', [
        'email' => 'bounce@example.com',
        'bounce_type' => 'hard',
        'provider' => 'mailgun',
    ]);
});

it('processes bounce webhook from SES provider', function () {
    // Amazon SES webhook payload örneği
    $sesPayload = [
        'Type' => 'Notification',
        'Message' => json_encode([
            'notificationType' => 'Bounce',
            'bounce' => [
                'bounceType' => 'Permanent',
                'bounceSubType' => 'General',
                'bouncedRecipients' => [
                    [
                        'emailAddress' => 'bounce@example.com',
                        'action' => 'failed',
                        'status' => '5.1.1',
                        'diagnosticCode' => 'smtp; 550 5.1.1 The email account that you tried to reach does not exist',
                    ]
                ],
            ],
        ]),
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $sesPayload);

    $response->assertStatus(200)
        ->assertJson(['status' => 'success']);

    $this->assertDatabaseHas('email_bounces', [
        'email' => 'bounce@example.com',
        'bounce_type' => 'hard',
        'provider' => 'ses',
    ]);
});

it('processes bounce webhook from SendGrid provider', function () {
    // SendGrid webhook payload örneği
    $sendgridPayload = [
        [
            'email' => 'bounce@example.com',
            'event' => 'bounce',
            'reason' => 'bounce',
            'status' => '5.0.0',
            'type' => 'bounce',
            'timestamp' => time(),
        ]
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $sendgridPayload);

    $response->assertStatus(200)
        ->assertJson(['status' => 'success']);

    $this->assertDatabaseHas('email_bounces', [
        'email' => 'bounce@example.com',
        'bounce_type' => 'hard',
        'provider' => 'sendgrid',
    ]);
});

it('associates bounce with related email log when possible', function () {
    // Önce bir email log oluştur
    $emailLog = EmailLog::factory()->create([
        'to_email' => 'bounce@example.com',
        'subject' => 'Test Subject',
        'message_id' => 'test-message-id@example.com',
    ]);

    // Bounce webhook payload
    $payload = [
        'event-data' => [
            'event' => 'failed',
            'severity' => 'permanent',
            'recipient' => 'bounce@example.com',
            'description' => 'Hard bounce',
        ],
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $payload);

    $response->assertStatus(200);

    // Bounce kaydının email_log_id ile ilişkilendirildiğini kontrol et
    $this->assertDatabaseHas('email_bounces', [
        'email' => 'bounce@example.com',
        'email_log_id' => $emailLog->id,
    ]);
});

it('handles complaint notifications as soft bounces', function () {
    $complaintPayload = [
        'event-data' => [
            'event' => 'complained',
            'recipient' => 'complainer@example.com',
            'description' => 'User marked email as spam',
        ],
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $complaintPayload);

    $response->assertStatus(200);

    $this->assertDatabaseHas('email_bounces', [
        'email' => 'complainer@example.com',
        'bounce_type' => 'complaint',
    ]);
});

it('handles temporary bounces as soft bounces', function () {
    $softBouncePayload = [
        'event-data' => [
            'event' => 'failed',
            'severity' => 'temporary',
            'recipient' => 'tempbounce@example.com',
            'description' => 'Mailbox full',
        ],
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $softBouncePayload);

    $response->assertStatus(200);

    $this->assertDatabaseHas('email_bounces', [
        'email' => 'tempbounce@example.com',
        'bounce_type' => 'soft',
    ]);
});

it('stores raw payload for debugging purposes', function () {
    $payload = [
        'custom_field' => 'custom_value',
        'event-data' => [
            'event' => 'failed',
            'recipient' => 'debug@example.com',
        ],
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $payload);

    $response->assertStatus(200);

    // Raw payload'ın saklandığını kontrol et
    $bounce = EmailBounce::where('email', 'debug@example.com')->first();
    expect($bounce->raw_payload)->toBeArray();
    expect($bounce->raw_payload)->toHaveKey('custom_field', 'custom_value');
});

it('handles invalid webhook payload gracefully', function () {
    $invalidPayload = [
        'invalid' => 'payload',
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $invalidPayload);

    // Hata dönse bile 200 döndürmeli (webhook'lar için)
    $response->assertStatus(200);

    // Hiç bounce kaydı oluşturulmamalı
    expect(EmailBounce::count())->toBe(0);
});

it('prevents duplicate bounce records for same email and event', function () {
    $payload = [
        'event-data' => [
            'event' => 'failed',
            'recipient' => 'duplicate@example.com',
            'description' => 'Duplicate bounce',
        ],
    ];

    // İlk webhook
    $this->postJson(route('system.email.webhooks.bounce'), $payload);
    // İkinci webhook (aynı)
    $this->postJson(route('system.email.webhooks.bounce'), $payload);

    // Sadece bir kayıt olmalı
    expect(EmailBounce::where('email', 'duplicate@example.com')->count())->toBe(1);
});

it('accepts bounce webhooks without authentication', function () {
    // Webhook'lar genellikle authentication gerektirmez
    $payload = [
        'event-data' => [
            'event' => 'failed',
            'recipient' => 'noauth@example.com',
        ],
    ];

    $response = $this->postJson(route('system.email.webhooks.bounce'), $payload);

    $response->assertStatus(200);

    $this->assertDatabaseHas('email_bounces', [
        'email' => 'noauth@example.com',
    ]);
});