<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
