# Council [![Build Status](https://travis-ci.org/JeffreyWay/council.svg?branch=master)](https://travis-ci.org/JeffreyWay/council)

This is a fork of an open source forum that was built and maintained at Laracasts.com.

## Installation

### Prerequisites

* To run this project, you must have PHP 7 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet. 
* If you want use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on a Mac or compile from source (https://redis.io/topics/quickstart). 

### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:JeffreyWay/council.git
cd council && composer install && npm install
php artisan council:install
npm run dev
```

### Step 2

Next, boot up a server and visit your forum. If using a tool like Laravel Valet, of course the URL will default to `http://council.test`. 

1. Visit: `http://council.test/register` to register a new forum account.
2. Edit `config/council.php`, and add any email address that should be marked as an administrator.
3. Visit: `http://council.test/admin/channels` to seed your forum with one or more channels.
