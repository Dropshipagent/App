<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\OrderItem;
use View;
use Config;

class Order extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_domain', 'order_id', 'order_number', 'email', 'cust_fname', 'payment_gateway', 'financial_status', 'order_value', 'order_status', 'ship_to', 'items', 'assign_supplier', 'shipping_method', 'created_at', 'updated_at',
    ];

    /**
     * Get the order items
     */
    public function itemsarr() {
        return $this->hasMany('App\OrderItem', 'order_id', 'order_id');
    }

    static function syncorders($store_domain) {
        $cUser = User::select('user_providers.provider_token')->join('user_providers', 'user_providers.user_id', 'users.id')->where('users.username', $store_domain)->where('user_providers.provider', 'shopify')->first();
        $shopify = \Shopify::retrieve($store_domain, $cUser->provider_token);
        $shopifyOrders = $shopify->get('orders');
        //dd($shopifyOrders);
        foreach ($shopifyOrders['orders'] as $webhook_content) {
            if (!empty($webhook_content['billing_address']['name'])) {
                $orderData = Order::where(['store_domain' => $store_domain, 'order_id' => $webhook_content['id']])->first();
                if (empty($orderData)) {
                    $order = new Order;
//            $order->store_domain = json_encode($webhook_header['x-shopify-shop-domain']);
                    $order->store_domain = $store_domain;
                    $order->order_id = (isset($webhook_content['id']) && $webhook_content['id'] !== '') ? $webhook_content['id'] : 0;
                    $order->order_number = (isset($webhook_content['name']) && $webhook_content['name'] !== '') ? $webhook_content['name'] : '';
                    $order->email = (isset($webhook_content['email']) && $webhook_content['email'] !== '') ? $webhook_content['email'] : null;
                    $order->cust_fname = (isset(['billing_address']['name']) && ['billing_address']['name'] !== '') ? ['billing_address']['name'] : null;
                    $order->payment_gateway = (isset($webhook_content['gateway']) && $webhook_content['gateway'] !== '') ? $webhook_content['gateway'] : null;
                    $order->financial_status = (isset($webhook_content['financial_status']) && $webhook_content['financial_status'] !== '') ? $webhook_content['financial_status'] : null;
                    $order->order_value = (isset($webhook_content['total_price']) && $webhook_content['total_price'] !== '') ? $webhook_content['total_price'] : 0;
                    $order->order_status = 'NULL';
                    $order->ship_to = (isset(['shipping_address']['province']) && ['shipping_address']['province'] !== '') ? ['shipping_address']['province'] : null;
// I wanted to insert the variant_id's and quantity as a string in one column. With this i can unserialise and use when needed 
                    $items = [];
                    if (count($webhook_content["line_items"]) > 0) {
                        foreach ($webhook_content["line_items"] as $item) {
//print_r($item); die;
//insert a new item
                            $orderItem = new \App\OrderItem;
                            $orderItem->store_domain = $store_domain;
                            $orderItem->order_id = (isset($webhook_content['id']) && $webhook_content['id'] !== '') ? $webhook_content['id'] : 0;
                            $orderItem->item_id = (isset($item['id']) && $item['id'] !== '') ? $item['id'] : 0;
                            $orderItem->variant_id = (isset($item['variant_id']) && $item['variant_id'] !== '') ? $item['variant_id'] : 0;
                            $orderItem->title = (isset($item['title']) && $item['title'] !== '') ? $item['title'] : null;
                            $orderItem->quantity = (isset($item['quantity']) && $item['quantity'] !== '') ? $item['quantity'] : 0;
                            $orderItem->sku = (isset($item['sku']) && $item['sku'] !== '') ? $item['sku'] : null;
                            $orderItem->variant_title = (isset($item['variant_title']) && $item['variant_title'] !== '') ? $item['variant_title'] : null;
                            $orderItem->vendor = (isset($item['vendor']) && $item['vendor'] !== '') ? $item['vendor'] : null;
                            $orderItem->fulfillment_service = (isset($item['fulfillment_service']) && $item['fulfillment_service'] !== '') ? $item['fulfillment_service'] : null;
                            $orderItem->product_id = (isset($item['product_id']) && $item['product_id'] !== '') ? $item['product_id'] : 0;
                            $orderItem->requires_shipping = (isset($item['requires_shipping']) && $item['requires_shipping'] !== '') ? $item['requires_shipping'] : 0;
                            $orderItem->taxable = (isset($item['taxable']) && $item['taxable'] !== '') ? $item['taxable'] : 0;
                            $orderItem->gift_card = (isset($item['gift_card']) && $item['gift_card'] !== '') ? $item['gift_card'] : null;
                            $orderItem->name = (isset($item['name']) && $item['name'] !== '') ? $item['name'] : null;
                            $orderItem->variant_inventory_management = (isset($item['variant_inventory_management']) && $item['variant_inventory_management'] !== '') ? $item['variant_inventory_management'] : null;
                            $orderItem->properties = (isset($item['properties']) && $item['properties'] !== '') ? json_encode($item['properties']) : null;
                            $orderItem->product_exists = (isset($item['product_exists']) && $item['product_exists'] !== '') ? $item['product_exists'] : 0;
                            $orderItem->fulfillable_quantity = (isset($item['fulfillable_quantity']) && $item['fulfillable_quantity'] !== '') ? $item['fulfillable_quantity'] : 0;
                            $orderItem->grams = (isset($item['grams']) && $item['grams'] !== '') ? $item['grams'] : 0;
                            $orderItem->price = (isset($item['price']) && $item['price'] !== '') ? $item['price'] : 0;
                            $orderItem->total_discount = (isset($item['total_discount']) && $item['total_discount'] !== '') ? $item['total_discount'] : 0;
                            $orderItem->fulfillment_status = (isset($item['fulfillment_status']) && $item['fulfillment_status'] !== '') ? $item['fulfillment_status'] : null;
                            $orderItem->price_set = (isset($item['price_set']) && $item['price_set'] !== '') ? json_encode($item['price_set']) : null;
                            $orderItem->total_discount_set = (isset($item['total_discount_set']) && $item['total_discount_set'] !== '') ? json_encode($item['total_discount_set']) : null;
                            $orderItem->discount_allocations = (isset($item['discount_allocations']) && $item['discount_allocations'] !== '') ? json_encode($item['discount_allocations']) : null;
                            $orderItem->admin_graphql_api_id = (isset($item['admin_graphql_api_id']) && $item['admin_graphql_api_id'] !== '') ? $item['admin_graphql_api_id'] : null;
                            $orderItem->tax_lines = (isset($item['tax_lines']) && $item['tax_lines'] !== '') ? json_encode($item['tax_lines']) : null;
                            $orderItem->save();

                            $items[$item["variant_id"]]['quantity'] = (isset($item['quantity']) && $item['quantity'] !== '') ? $item['quantity'] : 0;
                        }
                    }
                    unset($webhook_content['line_items']);
                    $order->items = json_encode($webhook_content);
                    $order->shipping_method = (isset($webhook_content['shipping_lines'][0]['title'])) ? $webhook_content['shipping_lines'][0]['title'] : "";

                    $order->save();
                }
            }
        }
        return true;
    }

    /**
     * Method use to create csv file of all recent orders
     *
     * @return \Illuminate\Http\Response
     */
    static function create_orders_csv($store_domain, $minlimit = NULL, $assign_supplier = 1) {
        $fileNameCsv = "orders_export_" . time() . ".csv";
        //get max id which will save into export csv log
        $maxIDVal = OrderItem::where(['store_domain' => $store_domain])->max('id');

        //get orders list for csv export
        if ($minlimit) {
            $orderItems = OrderItem::where('store_domain', $store_domain)->where('id', '>', $minlimit)->with(['orderdetail'])->get();
        } else {
            $orderItems = OrderItem::where(['store_domain' => $store_domain])->with(['orderdetail'])->get();
        }
        //update assign supplier 
        $odrIdArr = [];
        foreach ($orderItems as $orderIdList) {
            $odrIdArr[] = $orderIdList->order_id;
        }
        $odrIdArr = array_merge($odrIdArr);
        Order::whereIn('order_id', $odrIdArr)->update(['assign_supplier' => $assign_supplier]);

        //create csv file code
        $view = View::make('export.csvorderlist', ['orderItems' => $orderItems]);
        $htmlString = $view->render();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
        $csvstorepath = Config::get('filesystems.csv_storage_path');
        $writer->save($csvstorepath . '/' . $fileNameCsv);
        $response = [
            'maxIDVal' => $maxIDVal,
            'csvFilePath' => $csvstorepath . '/' . $fileNameCsv,
            'csvFileName' => $fileNameCsv
        ];
        return json_encode($response);
    }

    /**
     * Method user to show invoice data
     * @param type $storeData
     * @param type $ordersID
     */
    static function show_data_to_create_invoice($storeData, $ordersID = NULL) {
        $variant_id_arr = [];
        $invoice_items = [];
        foreach ($ordersID as $key => $val) {
            $order = Order::where("order_id", $val)->where("assign_supplier", 1)->where('store_domain', $storeData->username)->first();
            $orderItems = OrderItem::with(['productdetail'])->where("order_id", $val)->where('store_domain', $storeData->username)->get();
            foreach ($orderItems as $item) {
                if (isset($item->productdetail->base_price) && $item->productdetail->product_status == 3) {
                    $basePriceArr = json_decode($item->productdetail->base_price, true);
                    $variantPriceByAdmin = $basePriceArr[$item->variant_id];

                    $adminComisonArr = json_decode($item->productdetail->admin_commission, true);
                    $variantCommissionByAdmin = $adminComisonArr[$item->variant_id];
                } else {
                    $variantPriceByAdmin = 0;
                    $variantCommissionByAdmin = 0;
                }
                if ($variantPriceByAdmin > 0) {
                    if (in_array($item->variant_id, $variant_id_arr)) {
                        $invoice_items[$item->variant_id]['product_title'] = $item->productdetail->title;
                        $invoice_items[$item->variant_id]['product_price'] = $item->price;
                        $invoice_items[$item->variant_id]['product_admin_price'] = $variantPriceByAdmin;
                        $invoice_items[$item->variant_id]['product_admin_commission'] = $variantCommissionByAdmin;
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
