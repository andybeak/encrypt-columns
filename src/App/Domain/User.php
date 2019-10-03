<?php

namespace App\Domain;

use App\Traits\Encryptable;

class User extends \Illuminate\Database\Eloquent\Model
{
    use Encryptable;

    protected $encryptable = ['email', 'password'];
}

