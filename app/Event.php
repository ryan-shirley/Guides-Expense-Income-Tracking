<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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
