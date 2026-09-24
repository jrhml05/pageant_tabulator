#how to install

Note: requires PHP 8.3+ (Laravel 13, Livewire 4)

- composer install

- copy .env.example and rename it to .env

- create database "mr_and_ms_lcuaa" or choose what you want

- php artisan key:generate

# migrate tables and initialize everything

- php artisan migrate --seed || php artisan migrate:fresh --seed (if not new)

- npm install && npm run build

# running it for the event (tablets on the venue WLAN, no internet)

1. In `.env`, set `APP_ENV=production`, `APP_DEBUG=false`, and `APP_TIMEZONE` to the venue's timezone (for example `APP_TIMEZONE=Asia/Manila`) so printed sheets show the right time, then run `php artisan optimize` (run it again after any `.env` change).
2. Run `npm run build` whenever the app is updated. Do not use `npm run dev` at the event: it points pages at a dev server the tablets can't reach.
3. Start the server:

- php artisan event:serve

It makes web-sized copies of the candidate photos (`php artisan candidates:photos`, about 80 KB each instead of 2 MB), starts 4 PHP workers so judges don't wait on each other, and prints the address to open on the tablets (for example `http://192.168.1.10`).

If port 80 is already used (Herd, XAMPP, IIS), stop that program or pick another port: `php artisan event:serve --port=8080`, then open `http://<ip>:8080` on the tablets. On Windows, PHP's built-in server runs one worker only.

The app loads nothing from the internet: fonts and icons are bundled by `npm run build`, and photos, CSS and JS are cached by the tablets after the first load.

#credentials

# Admin

email: admin@mail.com
password: mrmslcuaa

# 3 Judges (sample) you can set new

Judge 1
email: judge1@mail.com
password: mrmslcuaajudge1

Judge 2
email: judge2@mail.com
password: mrmslcuaajudge2

Judge 3
email: judge3@mail.com
password: mrmslcuaajudge3

Judge 4
email: judge4@mail.com
password: mrmslcuaajudge4

Judge 5
email: judge5@mail.com
password: mrmslcuaajudge5

# Login artwork

Put the event key art at `public/assets/img/event-art.jpg` to show it beside the sign-in form. Without it, the panel shows "Mr. & Ms. LCUAA 2026" as text.

# Candidate photos

JPEG files named by candidate number (eg. 1.jpg). After adding or replacing photos, `php artisan event:serve` (or `php artisan candidates:photos`) makes the small copies in `web/`; until then the app falls back to the original file.

- public/assets/img/mr
- public/assets/img/ms
