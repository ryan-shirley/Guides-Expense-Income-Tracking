<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class BankAccount extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bank_accounts';

    //
}
