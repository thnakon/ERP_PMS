# Oboun ERP - Pharmacy Management System

Oboun ERP is a powerful, modern, and intuitive pharmacy management system designed to streamline operations, track inventory, and prevent profit loss through smart expiry alerts. Built with a premium "Apple-style" aesthetic, it provides a seamless experience for both administrators and staff.

[![Bilingual](https://img.shields.io/badge/Language-English%2FThai-blue)](#)
[![Laravel](https://img.shields.io/badge/Framework-Laravel-red)](#)
[![Tailwind](https://img.shields.io/badge/CSS-Tailwind-38B2AC)](#)

## 💊 Key Features

### 🚀 Smart POS & Sales
- **Fast Checkout:** Scan barcodes and process sales in seconds.
- **Customer Insights:** Track purchase history and allergy records.
- **Shift Management:** Easily open/close shifts and track daily revenue.

### 📦 Inventory & Stock Control
- **Real-time Tracking:** Know exactly what you have in stock at any moment.
- **Smart Categories:** Organize medicine by type, supplier, or shelving location.
- **Bulk Import:** Seamlessly import product data via CSV/Excel.

### ⚠️ Predictive Expiry Alerts
- **Loss Prevention:** Automatic notifications months before products expire.
- **Custom Triggers:** Set your own warning periods for different categories.
- **Dashboard Overview:** View upcoming expirations at a glance.

### 🔐 Secure Registration & Access
- **Multi-Step Flow:** Comprehensive pharmacy onboarding process.
- **Email Verification:** Secure OTP (One-Time Password) verification before activation.
- **Role-Based Access:** Distinct permissions for Pharmacists (Admin) and Assistants (Staff).

### 🌏 Localization & UI
- **Full Bilingual Support:** Seamlessly switch between **English** and **Thai**.
- **Modern Aesthetics:** Premium "Glassmorphism" UI with dynamic Light and Dark modes.
- **Responsible Design:** Fully functional across desktop and tablets.

## 🛠️ Technology Stack

- **Backend:** Laravel 11.x
- **Frontend:** Tailwind CSS, Alpine.js
- **Icons:** Phosphor Icons
- **Database:** MySQL / PostgreSQL
- **Email:** SMTP / Gmail Integration for OTP

## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/thnakon/ERP_PMS.git
   cd ERP_PMS
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Update your `.env` with your database and mail credentials.*

4. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the Server**
   ```bash
   php artisan serve
   ```

## 📄 License

This project is proprietary software. All rights reserved.

---
Developed with ❤️ by the Oboun ERP Team.
