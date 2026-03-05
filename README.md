# Harris Cars Service Center — Laravel Application

A complete, production-ready Laravel 11 website for Harris Cars Service Center, an auto repair shop located in Stallings, NC.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 (PHP 8.2) |
| Frontend | Tailwind CSS 3.x, Alpine.js 3.x, Vite 5 |
| Database | MySQL 8.0 |
| Cache / Queue / Session | Redis 7 |
| Web Server | Nginx 1.25 |
| Container | Docker + Docker Compose |
| Admin Panel | Custom Blade + Tailwind |
| Forms Integration | Zoho Web Forms (embed + webhook) |
| Email | Laravel Mail (SMTP configurable) |

---

## Quick Start (Docker — Recommended)

### Prerequisites
- Docker Desktop installed and running
- Git

### Step 1 — Clone and configure

```bash
git clone <repo-url> harris-cars
cd harris-cars
cp .env.example .env
```

Edit `.env` and set your desired database password and any API keys.

### Step 2 — Start containers

```bash
docker-compose up -d
```

This starts:
- `harris_app`        — PHP 8.2-FPM on port 9000
- `harris_nginx`      — Nginx on http://localhost:8080
- `harris_db`         — MySQL 8.0 on port 3306
- `harris_redis`      — Redis on port 6379
- `harris_phpmyadmin` — phpMyAdmin on http://localhost:8081

### Step 3 — Install dependencies

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan storage:link
```

### Step 4 — Run migrations and seed

```bash
docker-compose exec app php artisan migrate --seed
```

### Step 5 — Build frontend assets

```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

For development with hot-reload:
```bash
docker-compose exec app npm run dev
```

### Step 6 — Visit the site

| URL | Description |
|---|---|
| http://localhost:8080 | Public website |
| http://localhost:8080/admin | Admin panel |
| http://localhost:8081 | phpMyAdmin |

---

## Admin Panel Credentials

```
URL:      http://localhost:8080/admin
Email:    admin@harriscars.com
Password: password
```

**Change this password immediately after first login.**

---

## Project Structure

```
harris-cars/
├── app/
│   ├── Helpers/helpers.php          # Global helper functions (setting(), site_phone(), etc.)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/               # Admin CRUD controllers
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ServicesController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── TestimonialController.php
│   │   │   ├── GalleryController.php
│   │   │   ├── PageController.php
│   │   │   └── ZohoWebhookController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── VerifyCsrfToken.php
│   ├── Mail/
│   │   ├── AppointmentConfirmation.php
│   │   └── AppointmentNotification.php
│   └── Models/
│       ├── Appointment.php
│       ├── Gallery.php
│       ├── Service.php
│       ├── ServiceCategory.php
│       ├── Setting.php
│       ├── Special.php
│       ├── Testimonial.php
│       ├── User.php
│       └── ZohoFormSubmission.php
├── database/
│   ├── migrations/                  # 9 migration files
│   └── seeders/                     # 6 seeder files
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/
│       ├── layouts/                 # app.blade.php, admin.blade.php
│       ├── partials/                # nav, footer, flash
│       ├── pages/                   # All public pages
│       ├── admin/                   # Admin CRUD views
│       ├── auth/                    # Login
│       └── emails/                  # Email templates
├── routes/
│   ├── web.php                      # Public routes
│   └── admin.php                    # Admin routes
├── docker/
│   ├── php/Dockerfile
│   ├── php/local.ini
│   ├── nginx/default.conf
│   └── mysql/init.sql
├── docker-compose.yml
├── tailwind.config.js
├── vite.config.js
└── .env
```

---

## Key Features

### Public Website
- Homepage with hero, featured services, why choose us, CTA, specials, testimonials, and map
- All Services page organized by category
- Individual service detail pages with testimonials
- Appointment booking form with email confirmation
- Customer reviews page with rating breakdown + submit form
- Photo gallery with lightbox and category filtering
- Current specials / coupons page
- Vehicles serviced page (domestic + foreign)
- About us page
- Contact page with form and map embed

### Admin Panel (`/admin`)
- Dashboard with stats cards and recent activity
- Appointment management with status tracking
- Service & category CRUD with image uploads
- Review moderation (approve, feature, delete)
- Gallery management (bulk upload, categories)
- Settings CMS (phone, address, hours, Zoho embeds, maps, social links)

### Zoho Integration
- Embed codes for 4 form types stored in database settings
- Webhook endpoint (`POST /zoho/webhook`) logs every submission
- Automatically creates Appointment or Testimonial records from Zoho submissions

---

## Zoho Web Forms Setup

1. Log in to your Zoho Forms account at forms.zoho.com
2. Create forms for: Contact Us, Appointment Booking, Leave a Review, Get a Quote
3. For each form: click **Share** > **Embed** > copy the iframe HTML
4. In the admin panel, go to **Settings**
5. Paste each embed code into the corresponding Zoho form field
6. Save settings

### Zoho Webhook (Optional — for CRM sync)
1. In Zoho Forms, go to **Integrations** > **Webhooks**
2. Set the webhook URL to: `https://yourdomain.com/zoho/webhook`
3. Set the secret header: `X-Zoho-Webhook-Secret: your_secret`
4. Update `ZOHO_WEBHOOK_SECRET` in `.env` to match

---

## Email Configuration

For production, update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org    # or your SMTP provider
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@harriscarsservicecenter.com
MAIL_TO_ADDRESS=info@harriscarsservicecenter.com
```

---

## Deployment Checklist

### Environment
- [ ] Copy `.env.example` to `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_URL=https://yourdomain.com`
- [ ] Configure real database credentials
- [ ] Configure real Redis/cache settings
- [ ] Configure SMTP mail settings
- [ ] Set `ZOHO_WEBHOOK_SECRET` if using Zoho webhooks
- [ ] Add real `GOOGLE_MAPS_EMBED_URL`

### Build
```bash
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
npm install && npm run build
php artisan migrate --force
php artisan db:seed --class=SettingsSeeder
```

### Server
- [ ] PHP 8.2+ with extensions: pdo_mysql, mbstring, gd, zip, redis
- [ ] Nginx with `try_files $uri $uri/ /index.php?$query_string`
- [ ] Document root points to `/public`
- [ ] SSL certificate installed (Let's Encrypt recommended)
- [ ] `storage/` and `bootstrap/cache/` directories are writable
- [ ] Redis running for cache + sessions + queues
- [ ] Queue worker running: `php artisan queue:work --daemon`
- [ ] Cron job for scheduler: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

### Post-Deploy
- [ ] Change admin password from default
- [ ] Add real content (services, photos, testimonials)
- [ ] Configure Zoho form embeds in Admin > Settings
- [ ] Update Google Maps embed URL in Admin > Settings
- [ ] Test appointment booking form end-to-end
- [ ] Test admin login and all CRUD operations

---

## Useful Commands

```bash
# Clear all caches
docker-compose exec app php artisan optimize:clear

# Re-run database migrations + seed
docker-compose exec app php artisan migrate:fresh --seed

# Tinker (interactive REPL)
docker-compose exec app php artisan tinker

# List all routes
docker-compose exec app php artisan route:list

# Watch logs
docker-compose exec app tail -f storage/logs/laravel.log

# Stop containers
docker-compose down

# Stop and remove volumes (WARNING: deletes database)
docker-compose down -v
```

---

## Default Credentials After Seeding

| Resource | Value |
|---|---|
| Admin Email | admin@harriscars.com |
| Admin Password | password |
| DB Host (Docker) | db |
| DB Name | harris_cars |
| DB User | harris_user |
| DB Password | harris_password |
| phpMyAdmin | http://localhost:8081 |
# harris-cars-agent
