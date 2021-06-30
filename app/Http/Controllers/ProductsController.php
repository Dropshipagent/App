<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($store_domain, Request $request) {
        if (auth()->user()->status == -1) {
            return redirect('my-account');
        }
        if ($request->ajax()) {
            $extraSearch = array();
            $product_action = $request['product_action'];

            $product_status = $request['product_status'];
            $q = Product::where(['store_domain' => $store_domain, 'product_status' => $product_status]);

            $TotalProductData = $q->count();
            $responsedata = $q;

            $limit = $request->input('length');
            $start = $request->input('start');

            $columnindex = $request['order']['0']['column'];
            //$orderby = $request['columns'][$columnindex]['data'];
            $orderby = "product_id";
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            //db($request);

            $response = $responsedata->orderBy($orderby, $order)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            if (!$response) {
                $productData = [];
                $paging = [];
            } else {
                $productData = $response;
                $paging = $response;
            }

            $Data = array();
            $i = 1;
            foreach ($productData as $product) {
                $u['product_id'] = $product->product_id;
                $u['product_name'] = $product->title;
                $u['shipping_time'] = ($product->shipping_time) ? $product->shipping_time : "-";
                $productItems = view('products.productitems', ['store_product' => $product]);
                $u['product_price'] = $productItems->render();
                $imageArr = json_decode($product->image);
                if ($imageArr) {
                    $imageData = '<img src="' . $imageArr->src . '" height="100px"  width="100px" />';
                } else {
                    $imageData = '';
                }
                $u['product_image'] = $imageData;
                if ($product_action == 1) {
                    if (auth()->user()->status == 1) {
                        $u['product_action'] = '<a href="' . url('checkout') . '" class="btn btn-block btn-danger btn-sm">Proceed</a>';
                    } else {
                        $u['product_action'] = '<a href="javascript:void(0)" data-id="' . $product->id . '" class="btn btn-block btn-danger btn-sm accept_product">Accept Product</a>';
                    }
                } else {
                    $u['product_action'] = "";
                }
                $Data[] = $u;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($TotalProductData),
                "recordsTotal" => intval($TotalProductData),
                "data" => $Data
            ];
            return $return;
        }
        $adminProductQuery = Product::where(['store_domain' => $store_domain, 'product_status' => 2])->orderBy('updated_at', 'desc');
        $adminApprovedProductsCount = $adminProductQuery->count();
        $adminApprovedLastProduct = $adminProductQuery->first();
        return view('products.index', ['store_domain' => $store_domain, 'adminApprovedLastProduct' => $adminApprovedLastProduct, 'adminApprovedProductsCount' => $adminApprovedProductsCount]);
    }

    /**
     * Method to sync shopify products
     *
     * @return \Illuminate\Http\Response
     */
    public function syncproducts($store_domain) {
        Product::syncproducts($store_domain);
        $storeProducts = Product::where(['store_domain' => $store_domain, 'product_status' => 0])->orderBy('created_at', 'desc')->get();

        if (auth()->user()->status < 0) {
            return view('products.profile_syncproducts', ['store_products' => $storeProducts, 'store_domain' => $store_domain]);
        } else {
            return view('products.syncproducts', ['store_products' => $storeProducts, 'store_domain' => $store_domain]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Request to admin for their flag products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function productflag(Request $request) {
        if (isset($request->product_status)) {
            foreach ($request->product_status as $key => $val) {
                //change product status
                Product::where('id', $val)->update([
                    'product_status' => 1,
                    'aliexpress_url' => $request->aliexpress_url[$val],
                    'orders_per_day' => $request->orders_per_day[$val],
                    'variants_you_sell' => $request->variants_you_sell[$val],
                    'countries_you_ship' => $request->countries_you_ship[$val],
                    'cost_per_unit' => $request->cost_per_unit[$val],
                    'shipping_time' => $request->shipping_time[$val],
                ]);
            }
        }
        //send notification to admin 
        Notification::addNotificationFromAllPanel(helGetAdminID(), "You have a new quote request", auth()->user()->id, auth()->user()->id, 'NEW_PRODUCT_REQUEST');
        //get the all remainig temp product and delete based on id array
        $tempProducts = Product::where(['store_domain' => auth()->user()->username, 'product_status' => 0])->get(['id']);
        Product::destroy($tempProducts->toArray());
        return redirect('storeproducts/index/' . auth()->user()->username)->with('success', 'Products source request to administration send successfully!');
    }

    /**
     * Give final approval to admin accepted products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function product_status(Request $request) {
        $product = Product::findOrFail($request->product_id);
        $product->product_status = 3;
        //send notification to admin 
        Notification::addNotificationFromAllPanel(helGetAdminID(), 'Quoted product [' . $product->title . '] accepted!', auth()->user()->id, auth()->user()->id, 'PRODUCT_ACCEPTED_BY_STORE');

        return response()->json([
                    'data' => [
                        'success' => $product->save(),
                    ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $product = Product::find($id);
        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        $input = $request->all();
        $basePriceArr = [];
        foreach ($input['variant_id'] as $key => $val) {
            $basePriceArr[$val] = $input[$val];
        }
        $input['base_price'] = json_encode($basePriceArr);
        $product->fill($input)->save();
        return redirect('storeproducts/index/' . $input['store_domain'])->with('success', 'Product "' . $input['title'] . '" update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    /**
     * Import product on shopify store from system
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import_shopify($id) {
        //die;
        $cUser = auth()->user()->providers->where('provider', 'shopify')->first();
        $shopify = \Shopify::retrieve(auth()->user()->username, $cUser->provider_token);
        $productData = [
            'product' => [
                'body_html' => '<p>Some text</p><p><b>more text<br />break</b></p>',
                'id' => 0,
                'product_type' => 'JEWELLERY - Bracelets',
                'options' => [
                    ['name' => 'Article',],
                    ['name' => 'Option',],
                    ['name' => 'Size',],
                ],
                'published_scope' => 'global',
                'title' => 'Goldplated Bracelet Jack',
                'vendor' => 'Vendor',
                'tags' => '',
                'variants' => [
                    ['fulfillment_service' => 'manual',
                        'grams' => 0,
                        'id' => 0,
                        'inventory_management' => 'shopify',
                        'inventory_policy' => 'Deny',
                        'option1' => ' SKU',
                        'option2' => 'Black',
                        'option3' => '-',
                        'position' => 1,
                        'price' => '65.00',
                        'product_id' => 0,
                        'requires_shipping' => true,
                        'sku' => 'SKU1',
                        'taxable' => true,
                        'inventory_quantity' => 7,
                        'weight' => 0,]
                ],
                'published' => 'true',
            ]
        ];
        $productResponse = $shopify->create('products', $productData);
        dd($productResponse);
    }

}
