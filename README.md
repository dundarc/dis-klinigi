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
