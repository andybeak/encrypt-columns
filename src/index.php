<?php

require('vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;
use \App\Domain\User;

// prepare our ORM
$dbConfig = require('config/database.php');
$capsule = new Capsule;
$capsule->addConnection($dbConfig);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// create a user table (drop it if it already exists)
Capsule::schema()->dropIfExists('users');
Capsule::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});

// create a new user and save to the database
// the model will automatically encrypt the protected fields
$user = new User();
$user->email = 'demo@example.com';
$user->password = password_hash('password!"£$', PASSWORD_DEFAULT);
$user->save();

// retrieve the raw result from the database
$results = Capsule::select('select * from users WHERE id=1');
$user = $results[0];
echo "Raw email address for user [{$user->id}] is [{$user->email}]" . PHP_EOL;

// retrieve the result using the model which decrypts the value for you
$decryptedUser = User::where('id', '=', 1)->first();
echo "When fetched from ORM the value is [{$decryptedUser->email}]" . PHP_EOL;