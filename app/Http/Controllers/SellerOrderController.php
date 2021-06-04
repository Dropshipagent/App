<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Redirect;
use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\OrderItem;
use App\Product;
use App\Store;
use App\UserProvider;
use App\Invoice;
use App\StoreInvoice;
use App\AdminSetting;
use App\News;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class SellerOrderController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {
        $this->middleware('auth', ['except' => ['app_login', 'createOrder', 'updateOrder', 'uninstall_app']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //cehck condition and get recent 49 orders
        if (auth()->user()->get_order == 0) {
            //Sync Orders
            Order::syncorders(auth()->user()->username);
            if (User::where('id', auth()->user()->id)->update(['get_order' => '1'])) {
                //echo "condition true";
            }
        }

        // Get location id of logged in store
        if (auth()->user()->location_id == 0) {
            $cUser = auth()->user()->providers->where('provider', 'shopify')->first();
            $shopify = \Shopify::retrieve(auth()->user()->username, $cUser->provider_token);
            $storeLocation = $shopify->get('locations');
            $locationID = $storeLocation["locations"][0]['id'];
            $user = auth()->user();
            $input = ['location_id' => $locationID];
            if ($user->fill($input)->save()) {
                //echo "condition true";   
            }
        }
        /**
         * Method user for show list of all created invoices by logged in supplier
         *
         * @return \Illuminate\Http\Response
         */
        $login_user = auth()->user()->username;
        $storeInvoices = Invoice::where(['store_domain' => $login_user])->count();
        $uploadedTracking = StoreInvoice::where(['store_domain' => $login_user])->where('tracking_number', '!=', null)->count();
        $orders = Order::where('store_domain', $login_user)->count();
        $products = Product::where('store_domain', $login_user)->where('product_status', '!=', 0)->count();
        $flagProducts = Product::where(['store_domain' => $login_user, 'product_status' => 3])->count();
        $adminAcceptedProducts = Product::where(['store_domain' => $login_user, 'product_status' => 2])->count();
        $adminSettings = AdminSetting::select('store_news')->first();
        $news = News::orderBy("id", "desc")->get();
        return view('home', ['storeInvoices' => $storeInvoices, 'uploadedTracking' => $uploadedTracking, 'orders' => $orders, 'flagProducts' => $flagProducts, 'adminAcceptedProducts' => $adminAcceptedProducts, 'adminSettings' => $adminSettings, 'news_data' => $news]);
    }

    /**
     * app_login
     */
    public function app_login(Request $request) {
        if ($request->shop) {
            $shopDomainArr = explode(".", $request->shop);
            $shopDomain = $shopDomainArr[0];
            return redirect()->route('login.shopify', ['domain' => $shopDomain])->send();
        }
    }

    /**
     * Webhook to add orders from shopify
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrder(Request $request) {
//dd($request->all());

        $webhook = new \App\Webhook;
        $webhook->header_data = json_encode($request->headers->all());
        $webhook->data = json_encode($request->all());
        $webhook->save();

        $webhook_header = $request->headers->all();
        $store_domain = str_replace(array('["', '"]'), '', $webhook_header['x-shopify-shop-domain'][0]);
        $webhook_content = $request->all();
        if (!empty($webhook_content['billing_address']['name'])) {

            $order = new \App\Order;
//            $order->store_domain = json_encode($webhook_header['x-shopify-shop-domain']);
            $order->store_domain = $store_domain;
            $order->order_id = $webhook_content['id'];
            $order->order_number = $webhook_content['name'];
            $order->email = $webhook_content['email'];
            $order->cust_fname = $webhook_content['billing_address']['name'];
            $order->payment_gateway = $webhook_content['gateway'];
            $order->financial_status = $webhook_content['financial_status'];
            $order->order_value = $webhook_content['total_price'];
            $order->order_status = 'NULL';
            $order->ship_to = (isset($webhook_content['shipping_address']['province']) && $webhook_content['shipping_address']['province'] !== '') ? $webhook_content['shipping_address']['province'] : null;
// I wanted to insert the variant_id's and quantity as a string in one column. With this i can unserialise and use when needed 
            $items = [];
            foreach ($webhook_content["line_items"] as $item) {
//print_r($item); die;
//insert a new item
                $orderItem = new \App\OrderItem;
                $orderItem->store_domain = $store_domain;
                $orderItem->order_id = $webhook_content['id'];
                $orderItem->item_id = $item['id'];
                $orderItem->variant_id = $item['variant_id'];
                $orderItem->title = $item['title'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->sku = $item['sku'];
                $orderItem->variant_title = $item['variant_title'];
                $orderItem->vendor = $item['vendor'];
                $orderItem->fulfillment_service = $item['fulfillment_service'];
                $orderItem->product_id = $item['product_id'];
                $orderItem->requires_shipping = $item['requires_shipping'];
                $orderItem->taxable = $item['taxable'];
                $orderItem->gift_card = $item['gift_card'];
                $orderItem->name = $item['name'];
                $orderItem->variant_inventory_management = $item['variant_inventory_management'];
                $orderItem->properties = json_encode($item['properties']);
                $orderItem->product_exists = $item['product_exists'];
                $orderItem->fulfillable_quantity = $item['fulfillable_quantity'];
                $orderItem->grams = $item['grams'];
                $orderItem->price = $item['price'];
                $orderItem->total_discount = $item['total_discount'];
                $orderItem->fulfillment_status = $item['fulfillment_status'];
                $orderItem->price_set = json_encode($item['price_set']);
                $orderItem->total_discount_set = json_encode($item['total_discount_set']);
                $orderItem->discount_allocations = json_encode($item['discount_allocations']);
                $orderItem->admin_graphql_api_id = $item['admin_graphql_api_id'];
                $orderItem->tax_lines = json_encode($item['tax_lines']);
                $orderItem->save();

                $items[$item["variant_id"]]['quantity'] = $item["quantity"];
            }
            unset($webhook_content['line_items']);
            $order->items = json_encode($webhook_content);
            $order->shipping_method = (isset($webhook_content['shipping_lines'][0]['title'])) ? $webhook_content['shipping_lines'][0]['title'] : "";

            $order->save();
        }
        $response['status'] = true;
        $response['message'] = "Order Created Successfully";
        return $response;
    }

    /**
     * Webhook to update orders from shopify
     *
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request) {
//dd($request->all());

        $webhook = new \App\Webhook;
        $webhook->header_data = json_encode($request->headers->all());
        $webhook->data = json_encode($request->all());
        $webhook->save();

        $webhook_header = $request->headers->all();
        $store_domain = str_replace(array('["', '"]'), '', $webhook_header['x-shopify-shop-domain'][0]);
        $webhook_content = $request->all();
        if (!empty($webhook_content['billing_address']['name'])) {

            $order = Order::where(['store_domain' => $store_domain, 'order_id' => $webhook_content['id'], 'order_number' => $webhook_content['name']])->first();
//            $order->store_domain = json_encode($webhook_header['x-shopify-shop-domain']);
            $order->store_domain = $store_domain;
            $order->order_id = $webhook_content['id'];
            $order->order_number = $webhook_content['name'];
            $order->email = $webhook_content['email'];
            $order->cust_fname = $webhook_content['billing_address']['name'];
            $order->payment_gateway = $webhook_content['gateway'];
            $order->financial_status = $webhook_content['financial_status'];
            $order->order_value = $webhook_content['total_price'];
            $order->order_status = 'NULL';
            $order->ship_to = (isset($webhook_content['shipping_address']['province']) && $webhook_content['shipping_address']['province'] !== '') ? $webhook_content['shipping_address']['province'] : null;
// I wanted to insert the variant_id's and quantity as a string in one column. With this i can unserialise and use when needed 
            $items = [];
            foreach ($webhook_content["line_items"] as $item) {
//print_r($item); die;
//insert a new item
                $orderItem = OrderItem::where(['store_domain' => $store_domain, 'order_id' => $webhook_content['id'], 'item_id' => $item['id']])->first();
                $orderItem->store_domain = $store_domain;
                $orderItem->order_id = $webhook_content['id'];
                $orderItem->item_id = $item['id'];
                $orderItem->variant_id = $item['variant_id'];
                $orderItem->title = $item['title'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->sku = $item['sku'];
                $orderItem->variant_title = $item['variant_title'];
                $orderItem->vendor = $item['vendor'];
                $orderItem->fulfillment_service = $item['fulfillment_service'];
                $orderItem->product_id = $item['product_id'];
                $orderItem->requires_shipping = $item['requires_shipping'];
                $orderItem->taxable = $item['taxable'];
                $orderItem->gift_card = $item['gift_card'];
                $orderItem->name = $item['name'];
                $orderItem->variant_inventory_management = $item['variant_inventory_management'];
                $orderItem->properties = json_encode($item['properties']);
                $orderItem->product_exists = $item['product_exists'];
                $orderItem->fulfillable_quantity = $item['fulfillable_quantity'];
                $orderItem->grams = $item['grams'];
                $orderItem->price = $item['price'];
                $orderItem->total_discount = $item['total_discount'];
                $orderItem->fulfillment_status = $item['fulfillment_status'];
                $orderItem->price_set = json_encode($item['price_set']);
                $orderItem->total_discount_set = json_encode($item['total_discount_set']);
                $orderItem->discount_allocations = json_encode($item['discount_allocations']);
                $orderItem->admin_graphql_api_id = $item['admin_graphql_api_id'];
                $orderItem->tax_lines = json_encode($item['tax_lines']);
                $orderItem->save();

                $items[$item["variant_id"]]['quantity'] = $item["quantity"];
            }
            unset($webhook_content['line_items']);
            $order->items = json_encode($webhook_content);
            $order->shipping_method = (isset($webhook_content['shipping_lines'][0]['title'])) ? $webhook_content['shipping_lines'][0]['title'] : "";

            $order->save();
        }
        $response['status'] = true;
        $response['message'] = "Order Updated Successfully";
        return $response;
    }

    /**
     * Webhook to add orders from shopify
     *
     * @return \Illuminate\Http\Response
     */
    public function uninstall_app(Request $request) {
        /* $webhook = new \App\Webhook;
          $webhook->data = $request->myshopify_domain;
          $webhook->save(); */
        $user = User::where('username', $request->myshopify_domain)->first();
        $user->is_deleted = 1;
        if ($user->status > 1) {
            $user->status = 1;
        }
        if ($user->save()) {
            //delete providers
            $tempAuth = UserProvider::where(['user_id' => $user->id])->get(['id']);
            UserProvider::destroy($tempAuth->toArray());
            //delete stores
            $tempStore = Store::where(['domain' => $user->username])->get(['id']);
            Store::destroy($tempStore->toArray());
        }
        $response['status'] = true;
        $response['message'] = "App Uninstall Successfully";
        return $response;
    }

}
