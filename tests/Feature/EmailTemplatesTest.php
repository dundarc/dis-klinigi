<?php

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('displays email templates list for authenticated admin', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // Test şablonları oluştur
    EmailTemplate::factory()->count(3)->create();

    $response = $this->actingAs($admin)
        ->get(route('system.email.templates.index'));

    $response->assertStatus(200)
        ->assertViewIs('system.email.templates.index')
        ->assertViewHas('templates');
});

it('displays create template form', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($admin)
        ->get(route('system.email.templates.create'));

    $response->assertStatus(200)
        ->assertViewIs('system.email.templates.create');
});

it('creates new email template with valid data', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $templateData = [
        'key' => 'test_template',
        'name' => 'Test Template',
        'subject' => 'Test Subject {{ patient_name }}',
        'body_html' => '<h1>Hello {{ patient_name }}</h1><p>This is a test email.</p>',
        'body_text' => 'Hello {{ patient_name }}, This is a test email.',
        'is_active' => true,
    ];

    $response = $this->actingAs($admin)
        ->post(route('system.email.templates.store'), $templateData);

    $response->assertRedirect(route('system.email.templates.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('email_templates', [
        'key' => 'test_template',
        'name' => 'Test Template',
        'is_active' => true,
    ]);
});

it('validates required fields when creating template', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $invalidData = [
        'key' => '', // Required
        'name' => '', // Required
        'subject' => '', // Required
        'body_html' => '', // Required
    ];

    $response = $this->actingAs($admin)
        ->post(route('system.email.templates.store'), $invalidData);

    $response->assertRedirect()
        ->assertSessionHasErrors(['key', 'name', 'subject', 'body_html']);
});

it('validates unique key when creating template', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    // İlk şablonu oluştur
    EmailTemplate::factory()->create(['key' => 'existing_key']);

    $duplicateData = [
        'key' => 'existing_key', // Duplicate
        'name' => 'New Template',
        'subject' => 'Subject',
        'body_html' => '<p>Content</p>',
        'is_active' => true,
    ];

    $response = $this->actingAs($admin)
        ->post(route('system.email.templates.store'), $duplicateData);

    $response->assertRedirect()
        ->assertSessionHasErrors(['key']);
});

it('displays edit template form', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $template = EmailTemplate::factory()->create();

    $response = $this->actingAs($admin)
        ->get(route('system.email.templates.edit', $template));

    $response->assertStatus(200)
        ->assertViewIs('system.email.templates.edit')
        ->assertViewHas('template', $template);
});

it('updates email template with valid data', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $template = EmailTemplate::factory()->create([
        'key' => 'old_key',
        'name' => 'Old Name',
    ]);

    $updateData = [
        'name' => 'Updated Name',
        'subject' => 'Updated Subject {{ patient_name }}',
        'body_html' => '<h1>Updated content {{ patient_name }}</h1>',
        'body_text' => 'Updated text content {{ patient_name }}',
        'is_active' => false,
    ];

    $response = $this->actingAs($admin)
        ->put(route('system.email.templates.update', $template), $updateData);

    $response->assertRedirect(route('system.email.templates.index'))
        ->assertSessionHas('success');

    $template->refresh();
    expect($template->name)->toBe('Updated Name');
    expect($template->subject)->toBe('Updated Subject {{ patient_name }}');
    expect($template->is_active)->toBe(false);
});

it('deletes email template', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    $template = EmailTemplate::factory()->create();

    $response = $this->actingAs($admin)
        ->delete(route('system.email.templates.destroy', $template));

    $response->assertRedirect(route('system.email.templates.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('email_templates', ['id' => $template->id]);
});

it('renders template with placeholder variables correctly', function () {
    $template = EmailTemplate::factory()->create([
        'subject' => 'Appointment for {{ patient_name }}',
        'body_html' => '<h1>Hello {{ patient_name }}</h1><p>Your appointment is on {{ appointment_date }}</p>',
        'body_text' => 'Hello {{ patient_name }}, Your appointment is on {{ appointment_date }}',
    ]);

    $data = [
        'patient_name' => 'John Doe',
        'appointment_date' => '2025-01-15 14:00',
    ];

    $rendered = $template->render($data);

    expect($rendered['subject'])->toBe('Appointment for John Doe');
    expect($rendered['body_html'])->toContain('Hello John Doe');
    expect($rendered['body_html'])->toContain('2025-01-15 14:00');
    expect($rendered['body_text'])->toContain('Hello John Doe');
    expect($rendered['body_text'])->toContain('2025-01-15 14:00');
});

it('requires admin authentication for template management', function () {
    $user = User::factory()->create([
        'role' => 'dentist',
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user)
        ->get(route('system.email.templates.index'));

    $response->assertForbidden();
});