<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Traits\UseAutoIncrementID;

class Income extends Model
{
    use UseAutoIncrementID;

    protected $connection = 'mongodb';
    protected $collection = 'incomes';
    protected $dates = ['date'];

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

    /**
     * Generate and return a new readable id.
     */
    public function generateReadableId()
    {
        return $this->getID($this->collection);
    }
}
