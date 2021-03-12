<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationStatus extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "notification_status";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'not_id',
    ];

}
