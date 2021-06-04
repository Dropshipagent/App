<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_domain', 'order_id', 'item_id', 'variant_id', 'title', 'quantity', 'sku', 'variant_title', 'vendor', 'fulfillment_service', 'product_id', 'requires_shipping', 'taxable', 'gift_card', 'name', 'variant_inventory_management', 'properties', 'product_exists', 'fulfillable_quantity', 'grams', 'price', 'total_discount', 'fulfillment_status', 'price_set', 'total_discount_set', 'discount_allocations', 'admin_graphql_api_id', 'tax_lines',
    ];

    public function orderdetail() {
        return $this->belongsTo('App\Order', 'order_id', 'order_id');
    }

    public function productdetail() {
        return $this->belongsTo('App\Product', 'product_id', 'product_id');
    }

    public static function check_fulfillment_status($orderID) {
        return self::where(['order_id' => $orderID, 'fulfillment_status' => 'fulfilled'])->count();
    }

}
