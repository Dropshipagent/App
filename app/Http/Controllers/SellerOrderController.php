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
            Order::createUpdateorder($store_domain, $webhook_content);
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
            Order::createUpdateorder($store_domain, $webhook_content);
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
        $user->get_order = 0;
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
