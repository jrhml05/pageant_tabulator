#how to install

Note: requires PHP 8.3+ (Laravel 13, Livewire 4)

- composer install

- copy .env.example and rename it to .env

- create database "mr_and_ms_lcuaa" (MySQL), or point `DB_DATABASE` at a SQLite file

- php artisan key:generate

# migrate tables and initialize everything

- php artisan migrate:fresh --seed

After a rehearsal, `php artisan scores:clear` deletes every score, lock and finalist mark and closes all segments, keeping candidates and accounts.

- npm install && npm run build

# running it for the event (tablets on the venue WLAN, no internet)

1. In `.env`, set `APP_ENV=production`, `APP_DEBUG=false`, and `APP_TIMEZONE` to the venue's timezone (for example `APP_TIMEZONE=Asia/Manila`) so printed sheets show the right time, then run `php artisan optimize` (run it again after any `.env` change).
2. Run `npm run build` whenever the app is updated. Do not use `npm run dev` at the event: it points pages at a dev server the tablets can't reach.
3. Start the server:

- php artisan event:serve

It makes web-sized copies of the candidate photos (`php artisan candidates:photos`, about 80 KB each instead of 2 MB), starts 4 PHP workers so judges don't wait on each other, and prints the address to open on the tablets (for example `http://192.168.1.10`).

If port 80 is already used (Herd, XAMPP, IIS), stop that program or pick another port: `php artisan event:serve --port=8080`, then open `http://<ip>:8080` on the tablets. On Windows, PHP's built-in server runs one worker only.

The app loads nothing from the internet: fonts and icons are bundled by `npm run build`, and photos, CSS and JS are cached by the tablets after the first load.

# Scoring

The rules live in `config/pageant.php`.

- Round 1 (out of 100): Talent 15 and Thematic wear 15 are scored at the pre-pageant by 3 judges; Swim wear 15, Formal / evening wear 15, Beauty of face, poise, bearing & personality 20, and Wit & verbal ability (Q&A) 20 are scored on pageant night by 5 judges.
- Each segment's result is the average of its judges' totals. The Round 1 total adds the six averages.
- The top 5 of each division go to Round 2, scored fresh by the 5 pageant night judges: Beauty of face, poise, grace & overall impression 50 and Intelligence 50. Each judge ranks the five by their total (equal totals share a rank); the ranks from all judges are added up, and the lowest sum wins.
- Tied candidates share a rank and are flagged for the board to decide.

On the night: open a segment on **Scoring control** when it starts on stage, and close it when every judge has locked in. After Round 1, save each division's finalists on its **Round 1** results page before opening Round 2.

Saving finalists also draws the order the emcee calls them in: random, never the ranking and never the candidate numbers. **Print announcement sheet** (on either Round 1 page) prints both divisions' top 5 in that order, with no scores. The order stays the same on reprints until you press **Reshuffle**.

# Accounts

Sign in with a username. Each rehearsal password is the same as the username; change them on the Judges page before the event.

- Admins: `admin1`, `admin2`
- Pre-pageant panel: `prejudge1`, `prejudge2`, `prejudge3`
- Pageant night panel: `judge1` to `judge5`

# Login artwork

Put the event key art at `public/assets/img/event-art.jpg` to show it beside the sign-in form. Without it, the panel shows "Mr. & Ms. LCUAA 2026" as text.

# Candidate photos

JPEG files named by candidate number (eg. 1.jpg). After adding or replacing photos, `php artisan event:serve` (or `php artisan candidates:photos`) makes the small copies in `web/`; until then the app falls back to the original file.

- public/assets/img/mr
- public/assets/img/ms
