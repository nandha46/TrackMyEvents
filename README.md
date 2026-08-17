# TrackMyEvents

[![Laravel 11](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)

**TrackMyEvents** (Tell Me When) is a sleek, versatile web application designed to help you track both past milestones and upcoming events. With real-time second-by-second countdowns, customizable gradients, recurring milestone automation, checklist progress tracking, calendar integration (iCal & Google Calendar), and seamless CSV/JSON data portability, TrackMyEvents gives you a tailored canvas for every important moment.

---

## 🚀 Key Features

- ⏱️ **Real-Time Live-Ticking Countdowns**: Smooth second-by-second countdown timers on single event views, dashboard cards, and public share links.
- 🔄 **Recurring Milestones**: Automatic recurring schedules for yearly birthdays/anniversaries, monthly reviews, and weekly goals.
- 📅 **Multiple Views**: Switch seamlessly between **Grid Cards**, **Timeline Stream**, and **Monthly Calendar Grouping**.
- 📋 **Milestone Checklists**: Break events into sub-tasks with interactive check-offs and visual completion progress percentages.
- 🔗 **Public Shareable Links & QR**: Generate secure share links with rich OpenGraph and Twitter cards preview.
- 📆 **iCal & Google Calendar Integration**: One-click download of `.ics` calendar files and direct "Add to Google Calendar" links.
- 🎨 **Visual Themes & Dropzone**: Pick curated gradient colorways or upload custom photo backgrounds with drag-and-drop client preview.
- 🌓 **Dark Mode**: High-contrast dark theme with persistent local storage and zero flash of unstyled content (FOUC).
- 📦 **Data Import & Export**: Export filtered CSV and JSON backups; download template files and import in bulk.
- ⚡ **Batch Actions**: Bulk delete, bulk category change, and bulk export directly from the dashboard.
- ⌨️ **Keyboard Shortcuts**: `N` to create event, `/` to focus search, `Esc` to close modals, `?` for shortcuts cheat sheet.
- 🔔 **Notifications & Reminders**: Automated queue-driven email reminders, in-app notification dropdown, and Slack alerts.

---

## 🛠️ Tech Stack & Architecture

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: SQLite (default / testing) or MySQL 8.0
- **Frontend**: Blade Components, Tailwind CSS (forms & typography), Alpine.js, Vite
- **Authentication**: Laravel Breeze + Google OAuth (Socialite)
- **Monitoring**: Laravel Telescope, Laravel Pulse, Sentry Error Tracking

---

## ⚡ Quick Start & Local Setup

### 1. Prerequisites
- PHP >= 8.2 (with `pdo`, `sqlite`, `curl`, `mbstring`, `fileinfo` extensions)
- Composer
- Node.js >= 18 & npm

### 2. Installation Steps

```bash
# Clone the repository
git clone https://github.com/nandha46/TrackMyEvents.git
cd TrackMyEvents

# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install

# Copy environment config
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Build frontend assets
npm run build
```

### 3. Running Locally

```bash
# Terminal 1: Start Laravel development server
php artisan serve

# Terminal 2: Start Vite live reload (during development)
npm run dev

# Terminal 3: Run scheduled reminders / queue worker
php artisan schedule:work
```

---

## 🐳 Docker / Laravel Sail Setup

TrackMyEvents includes full Docker support via Laravel Sail:

```bash
# Start Docker containers
./vendor/bin/sail up -d

# Run migrations inside container
./vendor/bin/sail artisan migrate

# Run tests inside container
./vendor/bin/sail artisan test
```

---

## ⚙️ Environment Variables Breakdown

Key configuration variables in `.env`:

| Variable | Description | Default / Example |
|---|---|---|
| `APP_NAME` | Name of the application | `"Track My Events"` |
| `DB_CONNECTION` | Database driver | `sqlite` or `mysql` |
| `MAIL_MAILER` | Mail driver for reminders | `smtp` |
| `MAIL_HOST` | SMTP server host | `smtp.gmail.com` |
| `MAIL_USERNAME` | SMTP account email | `your-email@gmail.com` |
| `MAIL_PASSWORD` | App password for Gmail | `app-specific-password` |
| `GOOGLE_CLIENT_ID` | Google OAuth Client ID | `your-google-client-id` |
| `GOOGLE_CLIENT_SECRET` | Google OAuth Secret | `your-google-client-secret` |
| `GOOGLE_REDIRECT_URL` | OAuth Callback URL | `http://127.0.0.1:8000/api/auth/google/callback` |
| `SLACK_BOT_USER_OAUTH_TOKEN` | Slack Bot Token (optional) | `xoxb-...` |
| `SENTRY_LARAVEL_DSN` | Sentry DSN for error monitoring | `https://...@sentry.io/...` |

---

## 🧪 Running Tests

TrackMyEvents has automated unit and feature test suites:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=EventExtendedTest
php artisan test --filter=ProfileAvatarTest
php artisan test --filter=EventModelTest
```

---

## 📄 License

This project is open-source and licensed under the [MIT License](LICENSE).