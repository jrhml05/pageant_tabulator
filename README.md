#how to install

Note: this is suitable for php 8.0

- composer install

- copy .env.example and rename it to .env

- create database "mr_and_ms" or choose what you want

- php artisan key:generate

# migrate tables and initialize everything

- php artisan migrate --seed || php artisan migrate:fresh --seed (if not new)

- npm install && npm run dev

# to be able to connect IPAD/Tablet in one network, make sure you already set up your local ip first

- php artisan serve --host <local ip> --port 80

#credentials

# Admin

email: admin@mail.com
password: mrmsuep

# 3 Judges (sample) you can set new

Judge 1
email: judge1@mail.com
password: mrmsuepjudge1

Judge 2
email: judge2@mail.com
password: mrmsuepjudge2

Judge 3
email: judge3@mail.com
password: mrmsuepjudge3

Judge 4
email: judge4@mail.com
password: mrmsuepjudge4

Judge 5
email: judge5@mail.com
password: mrmsuepjudge5

# For candidates image, please follow the size and format been used in this app, and rename it to 1-24.

- public/assets/img/mb
