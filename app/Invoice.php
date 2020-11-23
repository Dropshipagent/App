<?php

namespace App;

use App\StoreInvoice;
use App\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipper_id', 'store_domain', 'order_ids', 'store_invoice_ids', 'admin_price_total', 'admin_commission_total', 'invoice_total', 'paid_status', 'auth_code', 'trans_id', 'notes'];

    static function show_invoice_data($shipper_id, $invoiceIDs = NULL) {
        $InvoiceData = StoreInvoice::with(['orderdetail'])->where('shipper_id', $shipper_id)->whereIn('id', $invoiceIDs)->get();
        $variant_id_arr = [];
        $invoice_items = [];
        foreach ($InvoiceData as $Invoice) {
            $orderItems = OrderItem::with(['productdetail'])->where("order_id", $Invoice->orderdetail->order_id)->get();
            foreach ($orderItems as $item) {
                $basePriceArr = json_decode($Invoice->invoice_data, true);
                $variantPriceByAdmin = ($basePriceArr[$item->variant_id] > 0) ? $basePriceArr[$item->variant_id] : 0;

                $adminComisonArr = json_decode($Invoice->commission_data, true);
                $variantCommissionByAdmin = ($adminComisonArr[$item->variant_id] > 0) ? $adminComisonArr[$item->variant_id] : 0;

                if ($variantPriceByAdmin > 0) {
                    if (in_array($item->variant_id, $variant_id_arr)) {
                        $invoice_items[$item->variant_id]['product_title'] = $item->productdetail->title;
                        $invoice_items[$item->variant_id]['product_price'] = $item->price;
                        $invoice_items[$item->variant_id]['product_admin_price'] = ($invoice_items[$item->variant_id]['product_admin_price'] + $variantPriceByAdmin);
                        $invoice_items[$item->variant_id]['product_admin_commission'] = ($invoice_items[$item->variant_id]['product_admin_commission'] + $variantCommissionByAdmin);
                        $invoice_items[$item->variant_id]['product_quantity'] = ($invoice_items[$item->variant_id]['product_quantity'] + $item->quantity);
                    } else {
                        $invoice_items[$item->variant_id]['product_title'] = $item->productdetail->title;
                        $invoice_items[$item->variant_id]['product_price'] = $item->price;
                        $invoice_items[$item->variant_id]['product_admin_price'] = $variantPriceByAdmin;
                        $invoice_items[$item->variant_id]['product_admin_commission'] = $variantCommissionByAdmin;
                        $invoice_items[$item->variant_id]['product_quantity'] = $item->quantity;
                    }
                    $variant_id_arr[] = $item->variant_id;
                }
            }
        }
        return $invoice_items;
    }

}
