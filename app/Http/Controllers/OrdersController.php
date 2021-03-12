<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Config;
use URL;
use Storage;
use App\Order;
use App\OrderItem;
use App\CronorderLog;
use App\ExportOrderCsvLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class OrdersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user = Auth::user();
        if ($request->ajax()) {
            $extraSearch = array();
            $assign_supplier = $request['assign_supplier'];
            if ($assign_supplier == "all") {
                $q = Order::select('store_invoices.fulfillment_status', 'orders.*')->leftjoin('store_invoices', 'store_invoices.order_id', 'orders.order_id')->with(['itemsarr'])->where(['orders.store_domain' => $user->username]);
            } else {
                $q = Order::select('store_invoices.fulfillment_status', 'orders.*')->leftjoin('store_invoices', 'store_invoices.order_id', 'orders.order_id')->with(['itemsarr'])->where(['orders.store_domain' => $user->username, 'orders.assign_supplier' => $assign_supplier]);
            }

            $TotalOrderData = $q->count();

            $responsedata = $q;
            $search = $request['search']['value'];
            if ($search && !empty($search)) {
                $q->where(function($query) use ($search) {
                    $query->where('orders.order_id', 'LIKE', '%' . $search . '%');
                    $query->orWhere('orders.order_number', 'LIKE', '%' . $search . '%');
                    $query->orWhere('orders.email', 'LIKE', '%' . $search . '%');
                    $query->orWhere('orders.cust_fname', 'LIKE', '%' . $search . '%');
                    $query->orWhere('orders.financial_status', 'LIKE', '%' . $search . '%');
                    $query->orWhere('orders.order_value', 'LIKE', '%' . $search . '%');
                });
                $responsedata = $q;
                $TotalOrderData = $q->count();
            }
            $extraSearch = array('date' => $request->date, 'financial_status' => $request->financial_status, 'order_status' => $request->order_status, 'fulfillmentstatus' => $request->fulfillment_status);
            if ($extraSearch && !empty($extraSearch)) {
                $q->where(function($query) use ($extraSearch) {
                    if ($extraSearch['financial_status']) {
                        $query->where('orders.financial_status', $extraSearch['financial_status']);
                    }
                    if ($extraSearch['order_status']) {
                        $query->where('orders.order_status', $extraSearch['order_status']);
                    }
                    if ($extraSearch['fulfillmentstatus']) {
                        $query->where('store_invoices.fulfillment_status', $extraSearch['fulfillmentstatus']);
                    }
                    if ($extraSearch['date']) {
                        $dates = explode(" - ", $extraSearch['date']);
                        $query->whereBetween('orders.created_at', [$dates[0] . " 00:00:00", $dates[1] . " 23:59:59"]);
                    }
                });
                $responsedata = $q;
                $TotalOrderData = $q->count();
            }

            $limit = $request->input('length');
            $start = $request->input('start');

            $columnindex = $request['order']['0']['column'];
            $orderby = $request['columns'][$columnindex]['data'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            //db($request);

            $response = $responsedata->orderBy($orderby, $order)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            if (!$response) {
                $orderData = [];
                $paging = [];
            } else {
                $orderData = $response;
                $paging = $response;
            }

            $Data = array();
            $i = 1;
            foreach ($orderData as $order) {
                $u['id'] = $order->id;
                if ($order->assign_supplier == 1) {
                    $u['assign_supplier'] = "Already Assigned";
                } else {
                    $u['assign_supplier'] = '<input type="checkbox" class="flag_checkbox" name="flag[]" data-id="flagData" value="' . $order->order_id . '" />';
                }

                $u['order_id'] = $order->order_id;
                $u['order_number'] = $order->order_number;
                $u['email'] = $order->email;
                $u['cust_fname'] = $order->cust_fname;
                $u['payment_gateway'] = $order->payment_gateway;
                $u['financial_status'] = ucfirst($order->financial_status);
                $u['order_value'] = currency($order->order_value, 'USD', currency()->getUserCurrency());
                $u['ship_to'] = $order->ship_to;
                $u['created_at'] = date('M d, Y H:i:s', strtotime($order->created_at));
                //$actionsStatus = view('seller.orderitems', ['order' => $order]);
                //$u['items'] = $actionsStatus->render();
                $totalItem = 0;
                foreach ($order->itemsarr as $item) {
                    $totalItem += $item->quantity;
                }
                $itesmText = ($totalItem) ? "Items" : "Item";
                $u['items'] = '<a href="javascript:void(0)" data-id="' . $order->order_id . '" class="btn btn-success viewOrderDetail" title="View Detail"><i class="fa fa-eye"></i> View ' . $totalItem . " " . $itesmText . '</a>';
                $Data[] = $u;
                $i++;
                unset($u);
                unset($totalItem);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($TotalOrderData),
                "recordsTotal" => intval($TotalOrderData),
                "data" => $Data
            ];
            return $return;
        }
        return view('seller.order', ['user' => $user]);
    }

    /**
     * Show all export csv logs
     *
     * @return \Illuminate\Http\Response
     */
    public function showcsvlogs(Request $request) {
        $login_user = auth()->user()->id;

        if ($request->ajax()) {
            $extraSearch = array();
            $q = ExportOrderCsvLog::where(['store_id' => $login_user]);

            $search = $request['search']['value'];
            $limit = $request->input('length');
            $start = $request->input('start');

            $columnindex = $request['order']['0']['column'];
            $orderby = $request['columns'][$columnindex]['data'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $TotalOrderData = $q->count();
            $response = $q->orderBy($orderby, $order)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            if (!$response) {
                $exportCsvData = [];
                $paging = [];
            } else {
                $exportCsvData = $response;
                $paging = $response;
            }

            $Data = array();
            $i = 1;
            foreach ($exportCsvData as $csv_data) {

                $fileURL = URL::to('/') . Storage::url('ordercsv/' . $csv_data->csv_file_name);
                $u['log_file'] = '<a target="_blank" href="' . $fileURL . '">' . $csv_data->csv_file_name . '</a>';
                $u['created_at'] = date('M d, Y H:i:s', strtotime($csv_data->created_at));

                $Data[] = $u;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($TotalOrderData),
                "recordsTotal" => intval($TotalOrderData),
                "data" => $Data
            ];
            return $return;
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
     * Store a product flag for list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderflag(Request $request) {
        $order = Order::findOrFail($request->order_id);
        $order->assign_supplier = $request->flag_val;

        return response()->json([
                    'data' => [
                        'success' => $order->save(),
                    ]
        ]);
    }

    /**
     * Export csv and set flag for submit order to supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export_csv_flag(Request $request) {
        $fileNameCsv = "orders_export_" . time() . ".csv";
        //comma seprated order ids
        $odrIdArr = [];
        if ($request->result == "b") {
            $orderIdLists = Order::where(['assign_supplier' => 0])->pluck('order_id');
            foreach ($orderIdLists as $orderIdList) {
                $odrIdArr[] = $orderIdList;
            }
        } else {
            $odrIdArr = explode("#", $request->order_ids);
        }
        $odrIdArr = array_filter($odrIdArr);

        //get order list based on comma separated values
        $orderItems = OrderItem::whereIn('order_id', $odrIdArr)->with(['orderdetail'])->get();
        Order::whereIn('order_id', $odrIdArr)->update(['assign_supplier' => 1]);

        //create csv file code
        $view = View::make('export.csvorderlist', ['orderItems' => $orderItems]);
        $htmlString = $view->render();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
        $csvstorepath = Config::get('filesystems.csv_storage_path');
        $writer->save($csvstorepath . '/' . $fileNameCsv);

        //send email to supplier
        $getSupplierData = helGetSupplierDATA(Auth::user()->username);
        $attachFileURL = url('/storage/ordercsv/' . $fileNameCsv);

        //save order to csv logs
        ExportOrderCsvLog::createNewLog(Auth::user()->id, $getSupplierData->id, Auth::user()->username, $fileNameCsv);

        $data = [];
        $data['supplier_name'] = $getSupplierData->name;
        $data['message_body'] = "New order assigned by store owner. Please check the attached CSV.";
        $data['file_url'] = $attachFileURL;

        $email_data['message'] = $data;
        $email_data['subject'] = 'New order assigned by store :: ' . Auth::user()->username;
        $email_data['layout'] = 'emails.assignorder';
        try {
            Mail::to($getSupplierData->email)->send(new SendMailable($email_data));
        } catch (\Exception $e) {
            // Never reached
        }

        //echo "<meta http-equiv='refresh' content='0;" . env('APP_URL') . "orders_export.csv'/>";
        return redirect()->back()->with('success', 'Orders are successfully assigned to supplier');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
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
