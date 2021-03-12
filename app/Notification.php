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

    static function addNotificationFromAllPanel($user_id, $notifications, $notification_by) {
        self::create([
            'user_id' => $user_id,
            'notifications' => $notifications,
            'notification_by' => $notification_by
        ]);
    }

}
