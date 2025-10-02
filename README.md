# 🦷 Dental Clinic Management System

This project is a **Dental Clinic Management System** built with **Laravel 11, PHP 8.2, MySQL 8, TailwindCSS, Alpine.js, and Vite**.  
It provides modules for patient management, appointments, treatment plans, invoices, stock/medical supplies, e-archive invoice integration, and role-based access control.

---

## 🚫 License & Usage Restrictions

This software is **NOT licensed for commercial use**.  
- You may **not** use this codebase to provide services, sell products, or deploy for paying customers.  
- You may **not** use this project as a base for development of derivative commercial software.  
- You may **not** redistribute or sublicense this project in any form.  

This project is provided **strictly for personal reference, learning, and educational purposes only**.  

All rights are reserved by the original author. Unauthorized use for commercial or development purposes is prohibited.

---

## ⚙️ Features (Educational Only)

- Patient management with appointments and encounters
- Treatment plan creation and tracking
- Invoice and payment tracking (installment, overdue, paid, credit)
- Stock and expense tracking
- Reporting and analytics dashboards
- PDF export for treatment and invoice details
- Role-based access (Admin, Dentist, Assistant, Receptionist)
- **Database-based email system** with templates, logging, and bounce handling

---

## 📚 Requirements

- PHP 8.2+  
- Laravel 11  
- MySQL 8  
- Node.js 18+ (for Vite & asset compilation)  

---
⚠️ Disclaimer

This system is provided as-is without warranty of any kind.
The author assumes no liability for damages or misuse.

🚫 Do not use this system in production or for real patient data.
🚫 Do not use commercially.
🚫 Do not use as a base for further software development.


👤 Author

Dündar Can ÖZTEKİN
All rights reserved.

## 🛠️ Installation (For Learning Only)

```bash
# Clone repository
git clone https://github.com/your-repo/clinic-system.git

# Enter project folder
cd clinic-system

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Build assets
npm run build

# Start local server
php artisan serve

# Start queue worker (for email sending)
php artisan queue:work
```

---

## 📧 Email System

This system includes a comprehensive database-based email management system with the following features:

### Features
- **Database-based SMTP settings** (no .env configuration needed)
- **Email templates** with WYSIWYG editor (TinyMCE)
- **Email logging** with status tracking (queued, sent, failed)
- **Bounce handling** with webhook support
- **DKIM signing** for email authentication
- **Queue-based sending** for high performance
- **Statistics and reporting** dashboard

### Database Tables
- `email_settings` - SMTP configuration stored in database
- `email_templates` - Reusable email templates with placeholders
- `email_logs` - Complete log of all sent emails
- `email_bounces` - Bounce tracking and management

### Usage Examples

#### Send email using template:
```php
use App\Facades\EmailFacade;

EmailFacade::sendTemplate('appointment_reminder', [
    'to' => 'patient@example.com',
    'to_name' => 'John Doe',
    'data' => [
        'patient_name' => 'John Doe',
        'appointment_date' => '2025-01-15 14:00',
        'clinic_name' => 'Diş Kliniği',
    ],
    'attachments' => [storage_path('app/invoice.pdf')],
]);
```

#### Manual email sending:
```php
use App\Services\EmailService;

EmailService::queue(
    'recipient@example.com',
    'Recipient Name',
    'Subject',
    '<h1>HTML Content</h1>',
    'Text content'
);
```

### DKIM Setup
1. Generate DKIM key pair
2. Add public key to DNS as TXT record
3. Configure domain, selector, and private key in email settings
4. System automatically signs outgoing emails

### Bounce Webhook
Configure webhook endpoint at `/system/email/webhooks/bounce` in your email provider (Mailgun, SES, etc.) to automatically track bounces.

### Queue Configuration
- Default queue driver: `database`
- Run `php artisan queue:work` to process email jobs
- Failed jobs are logged with detailed error messages

### Access
Email management is available at `/system/email` with admin-only access.
