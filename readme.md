# Council

This is an open source forum that was built and maintained at Laracasts.com.

## Installation

### Step 1.

> To run this project, you must have PHP 7 installed as a prerequisite.

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:JeffreyWay/council.git
cd council && composer install && npm install
php artisan council:install
npm run dev
```

### Step 2.

Until an administration portal is available, manually insert any number of "channels" (think of these as forum categories) into the "channels" table in your database.

Once finished, clear your server cache, and you're all set to go!

```
php artisan cache:clear
```

### Step 3.

Use your forum! Visit `http://council.test` to create a new account and publish your first thread.
