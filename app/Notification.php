<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_role', 'notifications', 'read_by', 'notification_by',
    ];
    
    public function userdetail() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function senduserdetail() {
        return $this->belongsTo('App\User', 'notification_by');
    }

}
