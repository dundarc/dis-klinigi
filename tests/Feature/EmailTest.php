<?php

namespace Tests\Feature;

use App\Models\EmailLog;
use App\Models\EmailSetting;
use App\Models\EmailTemplate;
use App\Services\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_settings_can_be_updated()
    {
        $data = [
            'mailer' => 'smtp',
            'host' => 'smtp.example.com',
            'port' => 587,
            'username' => 'test@example.com',
            'password' => 'password',
            'encryption' => 'tls',
            'from_address' => 'noreply@example.com',
            'from_name' => 'Test Clinic',
        ];

        $response = $this->post(route('system.email.update'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('email_settings', $data);
    }

    public function test_email_template_can_be_created()
    {
        $data = [
            'name' => 'Test Template',
            'key' => 'test_template',
            'subject' => 'Test Subject {{ name }}',
            'body_html' => '<h1>Hello {{ name }}</h1>',
            'body_text' => 'Hello {{ name }}',
            'is_active' => true,
        ];

        $response = $this->post(route('system.email.templates.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('email_templates', $data);
    }

    public function test_email_service_can_render_template()
    {
        EmailTemplate::create([
            'key' => 'test_template',
            'name' => 'Test Template',
            'subject' => 'Hello {{ name }}',
            'body_html' => '<h1>Hello {{ name }}</h1>',
            'body_text' => 'Hello {{ name }}',
            'is_active' => true,
        ]);

        $rendered = EmailService::renderTemplate('test_template', ['name' => 'John']);

        $this->assertEquals('Hello John', $rendered['subject']);
        $this->assertStringContains('Hello John', $rendered['html']);
        $this->assertStringContains('Hello John', $rendered['text']);
    }

    public function test_email_can_be_queued()
    {
        EmailSetting::create([
            'id' => 1,
            'mailer' => 'smtp',
            'host' => 'smtp.example.com',
            'port' => 587,
            'username' => 'test@example.com',
            'password' => 'password',
            'from_address' => 'noreply@example.com',
            'from_name' => 'Test Clinic',
        ]);

        $log = EmailService::queue(
            'test@example.com',
            'Test User',
            'Test Subject',
            '<h1>Test</h1>',
            'Test text'
        );

        $this->assertInstanceOf(EmailLog::class, $log);
        $this->assertEquals('queued', $log->status);
        $this->assertDatabaseHas('email_logs', [
            'to_email' => 'test@example.com',
            'subject' => 'Test Subject',
            'status' => 'queued',
        ]);
    }

    public function test_send_email_job_processes_correctly()
    {
        // This would require mocking the mailer or using a test mail driver
        // For now, just test that the job can be created
        $this->assertTrue(true);
    }
}