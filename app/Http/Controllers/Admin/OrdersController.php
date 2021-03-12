<?php

namespace App\Http\Controllers\Admin;

use Auth;
use View;
use App\User;
use App\Order;
use App\Store;
use App\OrderItem;
use App\Invoice;
use App\StoreInvoice;
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
            //dd($request->all());
            $extraSearch = array();
            $assign_supplier = 1;
            $q = Order::select('store_invoices.fulfillment_status', 'orders.*')->leftjoin('store_invoices', 'store_invoices.order_id', 'orders.order_id')->with(['itemsarr']);
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
            $extraSearch = array('date' => $request->date, 'financial_status' => $request->financial_status, 'order_status' => $request->order_status, 'store_domain' => $request->store_domain, 'fulfillmentstatus' => $request->fulfillment_status);
            if ($extraSearch && !empty($extraSearch)) {
                $q->where(function($query) use ($extraSearch) {
                    if ($extraSearch['financial_status']) {
                        $query->where('orders.financial_status', $extraSearch['financial_status']);
                    }
                    if ($extraSearch['store_domain']) {
                        $query->where('orders.store_domain', $extraSearch['store_domain']);
                    }
                    if ($extraSearch['fulfillmentstatus']) {
                        $query->where('store_invoices.fulfillment_status', $extraSearch['fulfillmentstatus']);
                    }
                    if ($extraSearch['order_status']) {
                        $query->where('orders.order_status', $extraSearch['order_status']);
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

            //$orderby = 'order_number';
            $columnindex = $request['order']['0']['column'];
            $orderby = $request['columns'][$columnindex]['data'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            //$order = "desc";
            $draw = $request['draw'];
            //dd($request->all());
            $response = $responsedata->orderBy($orderby, $order)->offset($start)->limit($limit)->get();
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
                $u['order_id'] = $order->order_id;
                $u['order_number'] = $order->order_number;
                $u['email'] = $order->email;
                $u['cust_fname'] = $order->cust_fname;
                $u['payment_gateway'] = $order->payment_gateway;
                $u['financial_status'] = ucfirst($order->financial_status);
                $u['order_value'] = "$" . $order->order_value;
                $u['ship_to'] = $order->ship_to;
                $u['fulfill_status'] = (!empty($order->fulfillment_status)) ? $order->fulfillment_status : "Invoice not Created";
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
                unset($itesmText);
            }
            //dd($Data);

            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($TotalOrderData),
                "recordsTotal" => intval($TotalOrderData),
                "data" => $Data
            ];
            return $return;
        }
        $store = Store::all();
        return view('admin.orderlist', ['store' => $store]);
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
     * Method to show upload tracking log
     *
     * @return \Illuminate\Http\Response
     */
    public function trackinglogs(Request $request) {
        if ($request->ajax()) {
            $extraSearch = array();
            $q = StoreInvoice::where('tracking_number', '!=', "");
            $TotalOrderData = $q->count();

            $responsedata = $q;
            $search = $request['search']['value'];
            if ($search && !empty($search)) {
                $q->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', '%' . $search . '%');
                    $query->orWhere('store_domain', 'LIKE', '%' . $search . '%');
                    $query->orWhere('order_id', 'LIKE', '%' . $search . '%');
                    $query->orWhere('tracking_number', 'LIKE', '%' . $search . '%');
                    $query->orWhere('tracking_url', 'LIKE', '%' . $search . '%');
                    $query->orWhere('tracking_company', 'LIKE', '%' . $search . '%');
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

            $response = $responsedata->orderBy($orderby, $order)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            if (!$response) {
                $trackingData = [];
                $paging = [];
            } else {
                $trackingData = $response;
                $paging = $response;
            }

            $Data = array();
            $i = 1;
            foreach ($trackingData as $tracking) {


                $u['id'] = $tracking->id;
                $u['store_domain'] = $tracking->store_domain;
                $u['order_id'] = $tracking->order_id;
                $u['tracking_number'] = $tracking->tracking_number;
                $u['tracking_url'] = '<a href="' . $tracking->tracking_url . '" target="_balnk">' . $tracking->tracking_url . '</a>';
                $u['tracking_company'] = $tracking->tracking_company;
                $u['created_at'] = date('M d, Y H:i:s', strtotime($tracking->created_at));

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
        return view('admin.trackinglogs');
    }

    /**
     * Method user for show list of all created invoices by logged in supplier
     *
     * @return \Illuminate\Http\Response
     */
    public function showinvoiceslog(Request $request) {
        if ($request->ajax()) {
            $paid_status = $request['paid_status'];
            $q = Invoice::select();
            $q->where('paid_status', $request['paid_status']);
            if ($request['store_domain']) {
                $q->where('store_domain', $request['store_domain']);
            }
            $TotalData = $q->count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $columnindex = $request['order']['0']['column'];
            $orderby = $request['columns'][$columnindex]['data'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $response = $q->orderBy($orderby, $order)->offset($start)->limit($limit)->get();
            if (!$response) {
                $InvoiceData = [];
                $paging = [];
            } else {
                $InvoiceData = $response;
                $paging = $response;
            }

            $Data = array();
            $i = 1;
            foreach ($InvoiceData as $Invoice) {
                $u['id'] = $Invoice->id;
                $u['store_domain'] = $Invoice->store_domain;
                $u['admin_price_total'] = '$' . $Invoice->admin_price_total;
                $u['admin_commission_total'] = '$' . $Invoice->admin_commission_total;
                if ($paid_status == 1) {
                    $u['paid_status'] = '<input type="button" class="btn btn-primary supplierpaidbtn" value="Mark Paid" data-id="' . $Invoice->id . '" />';
                }
                $u['created_at'] = date('M d, Y H:i:s', strtotime($Invoice->created_at));
                $u['action'] = '<a href="' . url('admin/showinvoicedetail/' . $Invoice->id) . '" class="btn btn-warning margin2px" title="View Invoice Detail"><i class="fa fa-eye"></i> View Invoice Detail</a>';

                $Data[] = $u;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($TotalData),
                "recordsTotal" => intval($TotalData),
                "data" => $Data
            ];
            return $return;
        }
        $store = Store::all();
        return view('admin.showinvoiceslog', ['store' => $store]);
    }

    /**
     * Method to show invoice details
     *
     * @return \Illuminate\Http\Response
     */
    public function showinvoicedetail(Request $request, $invoiceID) {
        $mainInvoice = Invoice::find($invoiceID);
        $storeData = User::where('username', $mainInvoice->store_domain)->first();
        $invoiceIDs = json_decode($mainInvoice->store_invoice_ids, true);
        $invoice_items = Invoice::show_invoice_data(helGetSupplierID($storeData->id), $invoiceIDs);
        return view('common.showinvoicedetail', ['storeData' => $storeData, 'invoice_items' => $invoice_items, 'mainInvoice' => $mainInvoice]);
    }

    public function supplier_paid_status_change(Request $request) {
        $invoice = Invoice::findOrFail($request->invoice_id);
        $invoice->paid_status = $request->status;

        //mark all store invoices paid
        $invoiceIDs = json_decode($invoice->store_invoice_ids, true);
        StoreInvoice::whereIn('id', $invoiceIDs)->update(['paid_status' => $request->status]);

        //send payment status email to supplier
        $getSupplierData = helGetSupplierDATA($invoice->store_domain);
        $data = [];
        $data['receiver_name'] = $getSupplierData->name;
        $data['receiver_message'] = "Admin has changed invoice status paid of invoice id :: " . $invoice->id;
        $data['sender_name'] = env('MAIL_FROM_NAME');

        $email_data['message'] = $data;
        $email_data['subject'] = 'Invoice paid successfully :: ' . $invoice->id;
        $email_data['layout'] = 'emails.sendemail';
        try {
            Mail::to($getSupplierData->email)->send(new SendMailable($email_data));
        } catch (\Exception $e) {
            // Never reached
        }


        return response()->json([
                    'data' => [
                        'success' => $invoice->save(),
                    ]
        ]);
    }

    public function export_csv() {
        $orderItems = OrderItem::with(['orderdetail'])->orderBy('created_at', 'desc')->get();
        //dd($orderItems);
        //return view('admin.csvorderlist',['orderItems'=>$orderItems]);

        $view = View::make('admin.csvorderlist', ['orderItems' => $orderItems]);
        $htmlString = $view->render();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="orders_export.csv"');
        $writer->save("php://output");

        //$writer->save('orders_export.csv');
        //return view('admin.csvorderlist', ['orderItems' => $orderItems]);
        //echo "<meta http-equiv='refresh' content='0;" . env('APP_URL') . "orders_export.csv'/>";
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
