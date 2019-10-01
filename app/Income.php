<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    /**
     * Get the user that owns the income.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
