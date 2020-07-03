<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class years extends Model
{
    protected $table = 'years';

     public function payment_option_A()
    {
    	return $this->HasMany('App\payment_option_A','id_year','id');
    }
}
