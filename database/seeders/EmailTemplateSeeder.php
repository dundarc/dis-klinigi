<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'key' => 'appointment_reminder',
                'name' => 'Randevu Hatırlatma',
                'subject' => 'Randevu Hatırlatma - {{ appointment_date }}',
                'body_html' => '<h1>Merhaba {{ patient_name }}</h1><p>Randevunuz {{ appointment_date }} tarihinde {{ clinic_name }} kliniğinde gerçekleşecek.</p>',
                'body_text' => 'Merhaba {{ patient_name }}, Randevunuz {{ appointment_date }} tarihinde {{ clinic_name }} kliniğinde gerçekleşecek.',
                'is_active' => true,
            ],
            [
                'key' => 'invoice_mail',
                'name' => 'Fatura Gönderimi',
                'subject' => 'Faturanız - {{ clinic_name }}',
                'body_html' => '<h1>Fatura Bilgileri</h1><p>Değerli müşterimiz, faturanız ekte yer almaktadır.</p>',
                'body_text' => 'Değerli müşterimiz, faturanız ekte yer almaktadır.',
                'is_active' => true,
            ],
            [
                'key' => 'password_reset',
                'name' => 'Şifre Sıfırlama',
                'subject' => 'Şifre Sıfırlama Talebi',
                'body_html' => '<h1>Şifre Sıfırlama</h1><p>Şifrenizi sıfırlamak için aşağıdaki bağlantıyı kullanın.</p>',
                'body_text' => 'Şifrenizi sıfırlamak için bağlantıyı kullanın.',
                'is_active' => true,
            ],
            [
                'key' => 'kvkk_consent',
                'name' => 'KVKK Onay Bildirimi',
                'subject' => 'KVKK Aydınlatma Metni ve Onay',
                'body_html' => '<h1>KVKK Aydınlatma Metni</h1><p>Kişisel verilerinizin işlenmesi hakkında bilgilendiriliyorsunuz.</p>',
                'body_text' => 'Kişisel verilerinizin işlenmesi hakkında bilgilendiriliyorsunuz.',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}
