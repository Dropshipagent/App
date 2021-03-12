<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentProfile extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "payment_profiles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'store_domain', 'profile_id', 'payment_profile_id'];

}
