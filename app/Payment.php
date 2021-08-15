<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';
    protected $dates = ['purchase_date'];

    /**
     * Get the user that owns the payment.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the event associated with the payment.
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
