<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreInvoice extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipper_id', 'store_domain', 'order_id', 'order_number', 'commission_data', 'invoice_data', 'admin_price_total', 'admin_commission_total', 'invoice_total', 'fulfillment_status', 'tracking_number', 'tracking_url', 'tracking_company', 'paid_status', 'auth_code', 'trans_id', 'notes',
    ];

    public function orderdetail() {
        return $this->belongsTo('App\Order', 'order_id', 'order_id');
    }

}
