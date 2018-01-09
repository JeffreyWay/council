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

Then, migrate your database to create tables.

```
php artisan migrate
```

### Step 3.

Finally, add one or more channels.

1. Visit: http://council.test/register and register an account.
1. Edit `config/council.php`, adding the email address of the account you just created.
1. Visit: http://council.test/admin/channels and add at least one channel.  

### Step 4.

Use your forum! Visit `http://council.test/threads` to create a new account and publish your first thread.
