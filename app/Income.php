<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Income extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'incomes';

    /**
     * Get the user that owns the income.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the event associated with the income.
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
