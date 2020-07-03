<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sm_fees_group extends Model
{
    protected $table="sm_fee_group";

    public function fees()
    {
    	return $this->HasMany('App\sm-fees','fees_group','id');
    }

    public function years()
    {
    	return $this->HasMany('App\years','id_group','id');
    }
}
