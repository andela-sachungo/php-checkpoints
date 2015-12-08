<?php

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Add database details and boot Eloquent.
 */

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'database'  => getenv('DATABASE_NAME'),
    'username'  => getenv('DATABASE_USERNAME'),
    'host'      => getenv('DATABASE_HOST'),
    'password'  => getenv('DATABASE_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    ]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

/**
 * Set timezone to be used by the timestamps.
 */

date_default_timezone_set('Africa/Nairobi');
