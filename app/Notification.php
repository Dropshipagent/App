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
        'user_id', 'item_id', 'user_role', 'notifications', 'read_by', 'notification_by', 'notification_type',
    ];

    public function userdetail() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function senduserdetail() {
        return $this->belongsTo('App\User', 'notification_by');
    }

    static function addNotificationFromAllPanel($user_id, $notifications, $notification_by, $item_id = 0, $notification_type = 'DEFAULT') {
        self::create([
            'user_id' => $user_id,
            'notifications' => $notifications,
            'notification_by' => $notification_by,
            'item_id' => $item_id,
            'notification_type' => $notification_type
        ]);
    }

    static function createNotificationUrlForAdmin($nt_id) {
        $notification = self::where('id', $nt_id)->first();
        if ($notification && $notification->notification_type == "DEFAULT")
            return "";

        if ($notification->notification_type == "NEW_STORE_CREATED" || $notification->notification_type == "NEW_PRODUCT_REQUEST" || $notification->notification_type == "PRODUCT_ACCEPTED_BY_STORE") {
            $storeUserName = helGetUsernameById($notification->item_id);
            return 'admin/products/index/' . $storeUserName;
        } else if ($notification->notification_type == "ORDERS_EXPORTED") {
            $storeUserName = helGetUsernameById($notification->item_id);
            return 'admin/users/set_store_session/' . $storeUserName;
        } else if ($notification->notification_type == "INVOICE_CREATED" || $notification->notification_type == "INVOICE_PAID") {
            return 'admin/showinvoicedetail/' . $notification->item_id;
        }
        return "";
    }

    static function createNotificationUrlForStore($nt_id) {
        $notification = self::where('id', $nt_id)->first();
        if ($notification && $notification->notification_type != "DEFAULT") {
            if ($notification->notification_type == "PRODUCT_ACCEPTED_BY_ADMIN") {
                $storeUserName = helGetUsernameById($notification->item_id);
                return 'storeproducts/index/' . $storeUserName;
            } else if ($notification->notification_type == "ASSIGNED_TO_STORE") {
                return 'supplier/set_store_session/' . $notification->item_id;
            } else if ($notification->notification_type == "NEW_STORE_ACCEPTED") {
                return 'home';
            } else if ($notification->notification_type == "INVOICE_CREATED" || $notification->notification_type == "INVOICE_PAID") {
                return 'showinvoicedetail/' . $notification->item_id;
            }
        }
        return "";
    }

    static function createNotificationUrlForSupplier($nt_id) {
        $notification = self::where('id', $nt_id)->first();
        if ($notification && $notification->notification_type != "DEFAULT") {
            if ($notification->notification_type == "INVOICE_PAID") {
                return 'supplier/showinvoicedetail/' . $notification->item_id;
            }
        }
        return "";
    }

}
