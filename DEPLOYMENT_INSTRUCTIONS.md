# Welkar E-Commerce Platform — Production Deployment Guide

This package contains the complete, production-ready source code, database dumps, and assets for the **Welkar** e-commerce platform.

---

## 1. Quick Specifications
- **Runtime**: PHP 8.2 or 8.3+ (Extensions: `pdo_mysql`, `mbstring`, `curl`, `gd`, `bcmath`, `json`, `openssl`)
- **Database**: MySQL 8.0+ or MariaDB 10.11+
- **Web Server**: Apache (with `mod_rewrite`) or Nginx or LiteSpeed
- **Payment Gateway**: Razorpay (Pre-integrated)
- **Currency**: Indian Rupees (`INR ₹`) with full GST compliance (CGST, SGST, IGST)

---

## 2. Shared Hosting / cPanel Deployment (5 Minutes)

### Step 1: Upload Files
1. Extract the contents of `welkar_production_website.zip`.
2. Upload the files to your server:
   - **Recommended**: Set the Web Root / Document Root to the `public/` folder.
   - **Alternative (Default `public_html`)**: If your host doesn't let you change the document root, upload everything into `public_html/`. The included root `.htaccess` will automatically and securely route all incoming requests to `/public/` while protecting backend directories.

### Step 2: Import the Database
1. Open **cPanel > phpMyAdmin** (or MySQL CLI).
2. Create a new database, e.g. `welkar_db` (Collation: `utf8mb4_unicode_ci`).
3. Click **Import** and select:
   ```
   database/welkar_production_backup.sql
   ```
   *(This single SQL file includes all 75 relational tables, seed catalogs, categories, admin users, settings, and verified brand data)*.

### Step 3: Configure `.env`
1. Rename `.env.example` to `.env` (or update `.env`):
   ```env
   APP_NAME="Welkar"
   APP_ENV="production"
   APP_DEBUG="false"
   APP_URL="https://yourdomain.com"
   
   DB_HOST="localhost"
   DB_PORT="3306"
   DB_DATABASE="your_database_name"
   DB_USERNAME="your_database_user"
   DB_PASSWORD="your_database_password"
   
   # Live Payment Gateway (Razorpay)
   RAZORPAY_MODE="live"
   RAZORPAY_KEY_ID="rzp_live_your_actual_key"
   RAZORPAY_KEY_SECRET="your_actual_secret"
   ```

### Step 4: Verify Folder Permissions
Ensure the `storage/` directory and subdirectories are writable:
```bash
chmod -R 755 storage
chmod -R 755 public/uploads
```

---

## 3. VPS / Dedicated Server Deployment (Ubuntu / Debian / Nginx)

### Nginx Virtual Host Configuration:
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/welkar/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Default Admin Credentials

- **Admin Login URL**: `https://yourdomain.com/admin`
- **Username**: `admin`
- **Default Password**: `admin@welkar123`
*(Please change this password immediately in Admin > Store Settings > Staff Roles after initial login)*.

---

## 5. Automated Verification Test Suite

To verify that your server configuration and database connectivity are 100% sound:
```bash
php tests/run_tests.php
```
You should see:
```text
TOTAL TESTS: 18
PASSED:      18
FAILED:      0
```

---

## 6. Pre-Launch Checklist for Business Owner
Before accepting real live customer transactions:
1. **GST Details**: Enter your registered GSTIN and company address in `/admin/settings`.
2. **Razorpay Live API**: Set `RAZORPAY_MODE=live` and input verified keys in `.env`.
3. **Email SMTP**: Add your transactional email credentials in `.env` for order confirmation receipts.
4. **Custom Domain**: Point DNS `A` records to your server IP and enable SSL certificate (Let's Encrypt / Cloudflare).
