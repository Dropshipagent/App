<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
Route::get('/', function () {
    return Redirect::to('login');
});
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('login/shopify', 'Auth\LoginShopifyController@redirectToProvider')->name('login.shopify');
Route::get('login/shopify/callback', 'Auth\LoginShopifyController@handleProviderCallback');
Route::get('login/shopify/callback', 'Auth\LoginShopifyController@handleProviderCallback');
Route::any('shopify/store', 'SellerOrderController@app_login');
Route::get('privacy-policy', function () {

    return view('privacy_policy');
});
Route::get('my-account', 'HomeController@myaccount')->middleware('front');
Route::post('my-account', 'HomeController@updateaccount');
Route::get('profile-status', 'HomeController@profile_status')->middleware('front');
Route::get('order_detail/{orderID}', 'AuthorizeController@order_detail');
Route::get('checkout', 'AuthorizeController@index');
Route::post('checkout/{storeId}', 'AuthorizeController@chargeCreditCard')->middleware('subscribed');
Route::post('invoice_checkout', 'AuthorizeController@invoiceChargeCC');
Auth::routes();
/*
 * Product Controller routs
 */
//Route::get('storeproducts/index/{storeId}', 'ProductsController@index')->middleware('subscribed');
Route::get('storeproducts/index/{storeId}', 'ProductsController@index');
Route::post('storeproducts/product-status', 'ProductsController@product_status');
Route::post('storeproducts/productflag', 'ProductsController@productflag');
Route::get('storeproducts/syncproducts/{storeId}', 'ProductsController@syncproducts');
Route::get('storeproducts/import-shopify/{productId}', 'ProductsController@import_shopify');
Route::resource('storeproducts', 'ProductsController');

Route::view('/bulksms', 'bulksms'); //blucksms route view
Route::post('/bulksms', 'BulkSmsController@sendSms'); //blucksms post route
######################### chating routs ##########################
Route::get('chats/{receiverID}', 'ChatsController@index');
Route::get('messages/{receiverID}', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');
Route::post('messages_status', 'ChatsController@read_messages');

######################### notification routs ##########################
Route::post('user_not_status', 'NotificationsController@read_notifications');
Route::post('user_not_count', 'NotificationsController@notifications_count');
Route::resource('storenotifications', 'NotificationsController');


######################## sopify store related routs ###################################
Route::get('stores/{storeId}', function(\Illuminate\Http\Request $request, $storeId) {
// Show store dashboard
});

Route::get('stores/{storeId}/shopify/subscribe', function(\Illuminate\Http\Request $request, $storeId) {


    $store = \App\Store::find($storeId);
    $user = auth()->user()->providers->where('provider', 'shopify')->first();
    $shopify = \Shopify::retrieve($store->domain, $user->provider_token);

    $activated = \ShopifyBilling::driver('RecurringBilling')
            ->activate($store->domain, $user->provider_token, $request->get('charge_id'));

    $response = array_get($activated->getActivated(), 'recurring_application_charge');

    $uUser = \App\User::where('username', $store->domain)->first();
    if ($response['status'] == "active") {
        $userArr = [];
        $userArr['status'] = 2;
        $userArr['charge_id'] = $request->get('charge_id');
        if ($uUser->fill($userArr)->save()) {
            
        }
    }

    \App\Charge::create([
        'store_id' => $store->id,
        'name' => 'default',
        'shopify_charge_id' => $request->get('charge_id'),
        'shopify_plan' => array_get($response, 'name'),
        'quantity' => 1,
        'charge_type' => \App\Charge::CHARGE_RECURRING,
        'test' => array_get($response, 'test'),
        'trial_ends_at' => array_get($response, 'trial_ends_on'),
    ]);

    return redirect('/home');
})->name('shopify.subscribe');

/*
 * this method user for single time payment which is called by the checkout method into AuthorizeController
 */
Route::get('stores/{storeId}/shopify/one-time-subscribe', function(\Illuminate\Http\Request $request, $storeId) {
    // Handle app charge
    $uUser = \App\User::where('id', $storeId)->first();
    $cUser = $uUser->providers->where('provider', 'shopify')->first();
    $shopify = \Shopify::retrieve($uUser->username, $cUser->provider_token);
    $reqResponse = $shopify->get('application_charges/' . $request->get('charge_id'));
    if ($reqResponse['application_charge']['status'] == "accepted") {
        $userArr = [];
        $userArr['status'] = 2;
        $userArr['charge_id'] = $request->get('charge_id');
        if ($uUser->fill($userArr)->save()) {
            
        }
    }
    return redirect('/home');
})->name('shopify.one-time-subscribe');


Route::post('webhook/shopify/gdpr/customer-redact', function(\Illuminate\Http\Request $request) {
// Remove customer data
    $webhook = new \App\Webhook;
    $webhook->data = json_encode($request->all());
    $webhook->save();
    echo "SUCCESS";
})->middleware('webhook');

Route::post('webhook/shopify/gdpr/shop-redact', function(\Illuminate\Http\Request $request) {
// Remove shop data
    $webhook = new \App\Webhook;
    $webhook->data = json_encode($request->all());
    $webhook->save();
    echo "SUCCESS";
})->middleware('webhook');

Route::post('webhook/shopify/gdpr/customer-data', function(\Illuminate\Http\Request $request) {
// Provide data on customer
    $webhook = new \App\Webhook;
    $webhook->data = json_encode($request->all());
    $webhook->save();
    echo "SUCCESS";
})->middleware('webhook');


Route::get('faqs', function () {

    return view('seller.faqs');
});
Route::get('shipping-info', function () {

    return view('seller.shippinginfo');
});

Route::group(['prefix' => '', 'middleware' => ['auth', 'front']], function() {

    Route::get('home', 'SellerOrderController@index')->name('home');
    Route::get('showinvoiceslog', 'AuthorizeController@showinvoiceslog')->name('Invoices');
    Route::get('downloadinvoice/{invoiceID}', 'AuthorizeController@downloadinvoice'); //invoice download page
    Route::get('showinvoicedetail/{invoiceID}', 'AuthorizeController@showinvoicedetail')->name('Invoices');
    Route::get('showtrackinglog', 'HomeController@showtrackinglog');
    Route::get('showcsvlogs', 'OrdersController@showcsvlogs'); //csv list of export orders
    Route::get('orders', 'OrdersController@index');
    Route::post('orders/orderflag', 'OrdersController@orderflag');
    Route::post('orders/export_csv_flag', 'OrdersController@export_csv_flag');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    Route::get('home', 'Admin\PageController@home');
    Route::get('orders', 'Admin\OrdersController@index');
    Route::get('orders/export_csv', 'Admin\OrdersController@export_csv');
    Route::get('trackinglogs', 'Admin\OrdersController@trackinglogs');
    Route::get('showinvoiceslog', 'Admin\OrdersController@showinvoiceslog');
    Route::get('showinvoicedetail/{invoiceID}', 'Admin\OrdersController@showinvoicedetail');

    Route::post('supplierpaidstatus_change', 'Admin\OrdersController@supplier_paid_status_change');

    Route::get('users/set_store_session/{storeId}', 'Admin\UsersController@setStoreSession');
    Route::get('users/showcsvlogs/{storeId}', 'Admin\UsersController@showcsvlogs');
    Route::post('users/email_csv', 'Admin\UsersController@email_csv');
    Route::get('users/search_users', 'Admin\UsersController@searchUsers');
    Route::get('users/profile/{id}', 'Admin\UsersController@userProfile');
    Route::post('users/user-status', 'Admin\UsersController@user_status');
    Route::resource('users', 'Admin\UsersController');

    Route::resource('notifications', 'Admin\NotificationsController');
    Route::post('user_not_status', 'Admin\NotificationsController@read_notifications');
    Route::post('user_not_count', 'Admin\NotificationsController@notifications_count');

    Route::post('products/product-status', 'Admin\ProductsController@product_status');
    Route::get('products/index/{storeId}', 'Admin\ProductsController@index');
    Route::resource('products', 'Admin\ProductsController');

    Route::any('/setting', 'Admin\SettingController@index');
    Route::any('/setting/{id}', 'Admin\SettingController@update');
});

Route::group(['prefix' => 'supplier', 'middleware' => ['auth', 'supplier']], function() {
    Route::get('home', 'Supplier\OrdersController@index');
    Route::get('set_store_session/{storeId}', 'Supplier\PageController@setStoreSession');

    Route::get('showcsvlogs/{storeId}', 'Supplier\OrdersController@showcsvlogs'); //csv list of store orders which is created only for loggedin supplier
    Route::get('bluckinvoice/{storeId}', 'Supplier\OrdersController@bluckinvoice'); //show list of all pendig invoice orders
    Route::post('showbluckinvoice/{storeId}', 'Supplier\OrdersController@showbluckinvoice'); //show list of all pendig invoice orders
    Route::post('createbluckinvoice/{storeId}', 'Supplier\OrdersController@createbluckinvoice'); //show list of all pendig invoice orders
    Route::get('trackinglogs/{storeId}', 'Supplier\OrdersController@trackinglogs');
    Route::get('showinvoiceslog/{storeId}', 'Supplier\OrdersController@showinvoiceslog'); //invoices logs which is created by suppliers
    Route::get('showinvoicedetail/{invoiceID}', 'Supplier\OrdersController@showinvoicedetail'); //invoice detail page
    Route::get('downloadinvoice/{invoiceID}', 'Supplier\OrdersController@downloadinvoice'); //invoice download page
    Route::get('searchorder', 'Supplier\OrdersController@searchorder'); //search order behalf on order id
    Route::post('create_invoice', 'Supplier\OrdersController@create_invoice'); //invoice which is created for admin and for the store owner
    Route::get('uploadtracking', 'Supplier\OrdersController@uploadtracking');
    Route::post('uploadtracking', 'Supplier\OrdersController@uploadtrackingPost');
    //Route::get('stores', 'Supplier\OrdersController@index'); //showing list of store which is assign to logged in supplier
    Route::resource('/orders', 'Supplier\OrdersController');

    Route::resource('suppliernotifications', 'Supplier\NotificationsController');
    Route::post('user_not_status', 'Supplier\NotificationsController@read_notifications');
    Route::post('user_not_count', 'Supplier\NotificationsController@notifications_count');
});
