# Council

This is an open source forum that was built and maintained at Laracasts.com.

## Installation

### Step 1.

> To run this project, you must have PHP 7 installed as a prerequisite.

Begin by cloning this repository to your machine, and installing all Composer dependencies.

```bash
git clone git@github.com:JeffreyWay/council.git
cd council && composer install
mv .env.example .env
php artisan key:generate
```

### Step 2.

Next, create a new database and reference its name and username/password within the project's `.env` file. In the example below, we've named the database, "council."

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=council
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3.

reCAPTCHA is a Google tool to help prevent forum spam. You'll need to create a free account (don't worry, it's quick). 

[https://www.google.com/recaptcha/intro/](https://www.google.com/recaptcha/intro/)

Choose reCAPTCHA V2, and specify your local (and eventually production) domain name, as illustrated in the image below.

![recaptcha example](https://photos-2.dropbox.com/t/2/AAD0oUp45M_jCBaogaf-bMudZEX6rjtDf8kRF0OtfMD4EQ/12/774859/png/32x32/3/1515013200/0/2/Screenshot%202018-01-03%2011.11.02.png/ENqvYBiOvfHGASAHKAc/Vk2xX4J2ADXnunB9_47pmBAU23j_QVDVgHjxD5rEfTI?dl=0&preserve_transparency=1&size=2048x1536&size_mode=3)

Once submitted, you'll see two important keys that should be referenced in your `.env` file. 

```
RECAPTCHA_KEY=PASTE_KEY_HERE
RECAPTCHA_SECRET=PASTE_SECRET_HERE
```

### Step 4.

Until an administration portal is available, manually insert any number of "channels" (think of these as forum categories) into the "channels" table in your database.

Once finished, clear your server cache, and you're all set to go!

```
php artisan cache:clear
```

### Step 5.

Use your forum! Visit `http://council.dev/threads` to create a new account and publish your first thread.