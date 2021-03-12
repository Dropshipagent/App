<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Product;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class ProductsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($store_domain, Request $request) {
        if ($request->ajax()) {
            $extraSearch = array();
            $product_action = $request['product_action'];

            $product_status = $request['product_status'];
            if ($product_status == 2) {
                $q = Product::where(['store_domain' => $store_domain])->where('product_status', '>=', $product_status);
            } else {
                $q = Product::where(['store_domain' => $store_domain, 'product_status' => $product_status]);
            }

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
                $productItems = view('admin.products.productitems', ['store_product' => $product]);
                $u['product_price'] = $productItems->render();
                $imageArr = json_decode($product->image);
                if ($imageArr) {
                    $imageData = '<img src="' . $imageArr->src . '" height="100px"  width="100px" />';
                } else {
                    $imageData = '';
                }
                $u['product_image'] = $imageData;
                $editUrl = URL::to('admin/products/' . $product->id . '/edit');
                if ($product_action == 1) {
                    $u['product_action'] = '<a href="javascript:void(0)" data-id="' . $editUrl . '" class="btn btn-success btnAcceptProduct" title="Accept Product"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" data-id="' . $product->id . '" class="btn btn-danger reject_product" title="Reject Product"><i class="fa fa-times"></i></a>';
                } else {
                    $u['product_action'] = '<a href="javascript:void(0)" data-id="' . $editUrl . '" class="btn btn-success btnAcceptProduct" title="Accept Product"><i class="fa fa-edit"></i></a>';
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
        return view('admin.products.index', ['store_domain' => $store_domain]);
    }

    /**
     * Reject product by admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function product_status(Request $request) {
        $product = Product::findOrFail($request->product_id);

        $getStoreData = helGetStoreDATA($product->store_domain);
        //send email when admin update any product price after accept
        $data = [];
        $data['receiver_name'] = "Dear " . $getStoreData->name;
        $data['receiver_message'] = "Admin has rejected one of your requested products, now wait for other requests or request a new product.";
        $data['sender_name'] = "Dropship Agent.";

        $email_data['message'] = $data;
        $email_data['subject'] = 'Product "' . $product->title . '" rejected by admin';
        $email_data['layout'] = 'emails.sendemail';
        try {
            Mail::to($getStoreData->email)->send(new SendMailable($email_data));
        } catch (\Exception $e) {
            // Never reached
        }
        //send notification to store owner for product rejection
        Notification::addNotificationFromAllPanel($getStoreData->id, 'Product "' . $product->title . '" rejected by admin', auth()->user()->id);

        return response()->json([
                    'data' => [
                        'success' => $product->delete(),
                    ]
        ]);
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
        return view('admin.products.edit', ['product' => $product]);
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
        $adminComisonPriceArr = [];
        foreach ($input['variant_id'] as $key => $val) {
            $basePriceArr[$val] = $input['base_price_' . $val];
            $adminComisonPriceArr[$val] = number_format(1, 2);
            /* if (($input['variant_price'][$key] - $input['base_price_' . $val]) >= 2) {
              $percentage = 70;
              $totalDifference = ($input['variant_price'][$key] - $input['base_price_' . $val]);
              $adminComisonPrice = ($percentage / 100) * $totalDifference;
              $adminComisonPriceArr[$val] = number_format($adminComisonPrice, 2);
              } else {
              $adminComisonPriceArr[$val] = number_format(1, 2);
              } */

            if (($basePriceArr[$val] <= 0) || ($adminComisonPriceArr[$val] < 0)) {
                return redirect('admin/products/index/' . $input['store_domain'])->with('error', 'Product "' . $input['title'] . '", all price should be greater than "0"!');
            }
            $vrintTotalPrice = $input['variant_price'][$key];
            $vrintAdminTotal = ($basePriceArr[$val] + $adminComisonPriceArr[$val]);
            if ($vrintAdminTotal >= $vrintTotalPrice) {
                return redirect('admin/products/index/' . $input['store_domain'])->with('error', 'Product "' . $input['title'] . '", total of admin price and admin commission should be lower than the product sell price!');
            }
        }
        $input['base_price'] = json_encode($basePriceArr);
        $input['admin_commission'] = json_encode($adminComisonPriceArr);
        if ($product->fill($input)->save()) {
            $getStoreData = helGetStoreDATA($input['store_domain']);
            if ($input['current_product_status'] == 1) {
                //send email when first time approved the product by the admin
                $data = [];
                $data['receiver_name'] = "Dear " . $getStoreData->name;
                $data['receiver_message'] = "Admin has reviewed your sourced product request and have suggested a best price for you. Please check products section of the app for more details.";
                $data['sender_name'] = "Dropship Agent.";

                $email_data['message'] = $data;
                $email_data['subject'] = 'Product "' . $input['title'] . '" best price submitted by admin';
                $email_data['layout'] = 'emails.sendemail';
                try {
                    Mail::to($getStoreData->email)->send(new SendMailable($email_data));
                } catch (\Exception $e) {
                    // Never reached
                }

                //notification for store owner
                Notification::addNotificationFromAllPanel($getStoreData->id, 'Product "' . $input['title'] . '" best price submitted by admin', auth()->user()->id);
            } else {
                //send email when admin update any product price after accept
                $data = [];
                $data['receiver_name'] = "Dear " . $getStoreData->name;
                $data['receiver_message'] = "Admin has updated the price of the sourced product. Please check the products section of the app for more details.";
                $data['sender_name'] = "Dropship Agent.";

                $email_data['message'] = $data;
                $email_data['subject'] = 'Product "' . $input['title'] . '" update price submitted by admin';
                $email_data['layout'] = 'emails.sendemail';
                try {
                    Mail::to($getStoreData->email)->send(new SendMailable($email_data));
                } catch (\Exception $e) {
                    // Never reached
                }

                //notification for store owner
                Notification::addNotificationFromAllPanel($getStoreData->id, 'Product "' . $input['title'] . '" updated price submitted by admin', auth()->user()->id);
            }

            return redirect('admin/products/index/' . $input['store_domain'])->with('success', 'Product "' . $input['title'] . '" update successfully!');
        }
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

}
