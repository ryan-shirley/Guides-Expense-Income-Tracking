<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class BankTransaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bank_transactions';
    protected $dates = ['date'];

    //
}
