# Sermify · Admin panel & API

Laravel 10 backend for [Sermify](../README.md). It serves two things:

- **Admin panel** (Jetstream + Livewire): create and edit tests, questions, answers, matching options and media
- **REST API** for the React app in [`english-app/`](../english-app)

## API

All routes are under `/api`.

| Method | Route | What it does |
| --- | --- | --- |
| `POST` | `/user/register`, `/user/login`, `/user/logout` | Accounts (login is rate limited) |
| `GET` | `/auth`, `/auth/callback` | Google sign-in |
| `POST` | `/forgot-password`, `/reset-password` | Password reset by email |
| `GET` | `/test-list`, `/test-list/{slug}` | Tests and their questions |
| `GET` | `/video-list` | Video questions |
| `POST` | `/check-answers`, `/check-video-answer` | Score answers and record results |
| `GET` | `/user/{uuid}`, `/user-test-results/{userId}` | Profile and test history |
| `PUT` / `POST` | `/profile/update-password`, `/profile/update-photo/{userId}` | Profile settings |

## Setup

```bash
composer install
cp .env.example .env        # set DB_* values
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve
```

Never commit `.env`; it is ignored by `.gitignore`.
