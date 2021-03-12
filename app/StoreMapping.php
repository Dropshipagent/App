<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreMapping extends Model {


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'supplier_id', 'store_domain',
    ];
}
