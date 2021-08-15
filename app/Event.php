<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';
    protected $dates = ['start_date', 'end_date'];

    /**
     * Get the payments for the event.
     */
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    /**
     * Get the incomes for the event.
     */
    public function incomes()
    {
        return $this->hasMany('App\Income');
    }
}
