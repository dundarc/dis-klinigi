<?php

namespace Database\Seeders;

use App\Models\EmailSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailSetting::updateOrCreate(
            ['id' => 1],
            [
                'mailer' => 'smtp',
                'host' => 'smtp.example.com',
                'port' => 587,
                'username' => 'noreply@example.com',
                'password' => 'dummy_password',
                'encryption' => 'tls',
                'from_address' => 'noreply@example.com',
                'from_name' => 'Diş Kliniği',
                'dkim_domain' => null,
                'dkim_selector' => null,
                'dkim_private_key' => null,
                'spf_record' => null,
            ]
        );
    }
}
