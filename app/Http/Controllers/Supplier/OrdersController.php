<?php

namespace App\Http\Controllers\Supplier;

use Auth;
use App\User;
use App\Order;
use App\OrderItem;
use App\CronorderLog;
use App\StoreMapping;
use App\Invoice;
use App\Notification;
use App\StoreInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use PDF;
use Session;

class OrdersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $u_id = \Auth::user()->id;
        $mapped_stores = StoreMapping::where('supplier_id', $u_id)->orderBy('created_at', 'desc')->paginate(15);
        return view('supplier.stores', ['mapped_stores' => $mapped_stores]);
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
    public function bluckinvoice(Request $request, $id) {
        $user = User::find($id);
        if ($request->ajax()) {
            $extraSearch = array();
            $assign_supplier = 1;
            $store_domain = $request->store_domain;

            $q = Order::select('store_invoices.order_id', 'orders.*')->leftjoin('store_invoices', 'store_invoices.order_id', 'orders.order_id')->with(['itemsarr'])->where(['orders.store_domain' => $store_domain, 'assign_supplier' => $assign_supplier])->whereRaw('store_invoices.order_id is NULL');

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
                $orderItems = OrderItem::with(['productdetail'])->where("order_id", $order->order_id)->get();
                $u['id'] = $order->id;
                $u['create_invoice'] = '<input type="checkbox" class="flag_checkbox" name="flag[]" data-id="flagData" value="' . $order->order_id . '" checked />';
                $u['order_id'] = $order->order_id;
                $u['order_number'] = $order->order_number;
                $u['email'] = $order->email;
                $u['cust_fname'] = $order->cust_fname;
                $u['financial_status'] = $order->financial_status;
                $u['order_value'] = $order->order_value;
                $u['ship_to'] = $order->ship_to;
                $u['created_at'] = date('M d, Y H:i:s', strtotime($order->created_at));
                $actionsStatus = view('supplier.orderitems', ['orderItems' => $orderItems]);
                $u['items'] = $actionsStatus->render();
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
        return view('supplier.orderlist', ['user' => $user]);
    }

    /**
     * Before creating the invoice show all invoice items
     *
     * @return \Illuminate\Http\Response
     */
    public function showbluckinvoice(Request $request, $id) {
        //dd($request->all());
        $storeData = User::find($id);
        $main_order_ids = $request->flag;
        $invoice_items = Order::show_data_to_create_invoice($storeData, $request->flag);
        return view('supplier.showinvoice', ['storeData' => $storeData, 'main_order_ids' => $main_order_ids, 'invoice_items' => $invoice_items]);
    }

    /**
     * Method use for create bluck invoices for a particular store
     *
     * @return \Illuminate\Http\Response
     */
    public function createbluckinvoice(Request $request, $id) {
        $user = User::find($id);
        //dd($request->all());
        $main_admin_price_total = 0;
        $main_admin_commission_total = 0;
        $main_invoice_total = 0;
        $main_order_ids = [];
        $main_store_invoice_ids = [];
        foreach ($request->flag as $key => $val) {
            $order = Order::where("order_id", $val)->where("assign_supplier", 1)->where('store_domain', $user->username)->orderBy('created_at', 'desc')->first();
            //dd($order);
            $orderItems = OrderItem::with(['productdetail'])->where("order_id", $val)->where('store_domain', $user->username)->get();
            if ($orderItems) {
                $itemsWithPriceArr = [];
                $commissionDataArr = [];

                $admin_price_total = 0;
                $admin_commission_total = 0;
                $invoice_total = 0;
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
                    $itemsWithPriceArr[$item->variant_id] = ($variantPriceByAdmin * $item->quantity);
                    $commissionDataArr[$item->variant_id] = ($variantCommissionByAdmin * $item->quantity);
                    $admin_price_total += ($variantPriceByAdmin * $item->quantity);
                    $admin_commission_total += ($variantCommissionByAdmin * $item->quantity);
                    $invoice_total += ($variantPriceByAdmin * $item->quantity) + ($variantCommissionByAdmin * $item->quantity);
                }
                $store_invoice = new StoreInvoice;
                $store_invoice->supplier_id = auth()->user()->id;
                $store_invoice->store_domain = $order->store_domain;
                $store_invoice->order_id = $order->order_id;
                $store_invoice->order_number = $order->order_number;
                $store_invoice->commission_data = json_encode($commissionDataArr);
                $store_invoice->invoice_data = json_encode($itemsWithPriceArr);
                $store_invoice->admin_price_total = $admin_price_total;
                $store_invoice->admin_commission_total = $admin_commission_total;
                $store_invoice->invoice_total = $invoice_total;
                $store_invoice->notes = null;
                if ($store_invoice->save()) {
                    //invoice created successfully for a order
                    //echo $store_invoice->id; die;
                    $main_order_ids[] = $order->order_id;
                    $main_store_invoice_ids[] = $store_invoice->id;
                }
            }
            $main_admin_price_total += $admin_price_total;
            $main_admin_commission_total += $admin_commission_total;
            $main_invoice_total += $invoice_total;
        }

        //save data into main invoice
        $main_invoice = new Invoice;
        $main_invoice->supplier_id = auth()->user()->id;
        $main_invoice->store_domain = $user->username;
        $main_invoice->order_ids = json_encode($main_order_ids);
        $main_invoice->store_invoice_ids = json_encode($main_store_invoice_ids);
        $main_invoice->admin_price_total = $main_admin_price_total;
        $main_invoice->admin_commission_total = $main_admin_commission_total;
        $main_invoice->invoice_total = $main_invoice_total;
        $main_invoice->other_charges_description = $request->other_charges_description;
        $main_invoice->other_charges = $request->other_charges;
        $main_invoice->notes = null;
        if ($main_invoice->save()) {
            //invoice created successfully for a order
            //echo $main_invoice->id; die;
            $getStoreData = helGetStoreDATA($user->username);
            $data = [];
            $data['receiver_name'] = "<strong>Invoice:</strong> Hello " . $getStoreData->name;
            $data['receiver_message'] = "Your supplier has created an invoice for you. Send payment to complete order fulfillment.<br><br>After payment is sent and received, tracking will automatically be uploaded. You will receive email confirmation when tracking is uploaded.";

            $data['sender_name'] = env('MAIL_FROM_NAME');
            $email_data['message'] = $data;
            $email_data['subject'] = 'Your invoice is ready';
            $email_data['layout'] = 'emails.sendemail';
            try {
                Mail::to($getStoreData->email)->send(new SendMailable($email_data));
            } catch (\Exception $e) {
                // Never reached
            }
            $adminNotification = "New Invoice Created for (" . $user->username . ").";
            if ($main_invoice->other_charges > 0) {
                $adminNotification .= " A supplier added on charges on the invoice Other charges:: " . $main_invoice->other_charges;
            }
            //send notification to store owner
            Notification::addNotificationFromAllPanel($user->id, "Invoice Received", auth()->user()->id, $main_invoice->id, 'INVOICE_CREATED');

            //send notification to admin 
            Notification::addNotificationFromAllPanel(helGetAdminID(), $adminNotification, auth()->user()->id, $main_invoice->id, 'INVOICE_CREATED');

            return redirect('supplier/bluckinvoice/' . $user->id)->with('success', 'Invoice created successfully of selected orders!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $user = User::find($id);
        if ($request->ajax()) {
            $extraSearch = array();
            $assign_supplier = 1;
            $store_domain = $request->store_domain;
            $q = Order::select('store_invoices.fulfillment_status', 'orders.*')->leftjoin('store_invoices', 'store_invoices.order_id', 'orders.order_id')->with(['itemsarr'])->where(['orders.store_domain' => $store_domain, 'assign_supplier' => $assign_supplier]);

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
        return view('supplier.show', ['user' => $user]);
    }

    /**
     * Cron method to create a csv for latest orders for all stores and send to suppliers
     *
     * @return \Illuminate\Http\Response
     */
    public function showcsvlogs($id) {
        $login_user = auth()->user()->id;
        $cronorder_logs = CronorderLog::where(['store_id' => $id, 'supplier_id' => $login_user])->orderBy('created_at', 'desc')->get();
        return view('supplier.showcsvlogs', ['cronorder_logs' => $cronorder_logs]);
    }

    /**
     * Method to show uploaded tracking log
     *
     * @return \Illuminate\Http\Response
     */
    /* public function trackinglogs($store_domain) {
      $login_user = auth()->user()->id;
      $store_invoices = StoreInvoice::where(['store_domain' => $store_domain, 'supplier_id' => $login_user])->where('tracking_number', '!=', "")->orderBy('created_at', 'desc')->paginate(15);
      return view('supplier.trackinglogs', ['store_invoices' => $store_invoices]);
      } */

    public function trackinglogs(Request $request, $storeId) {
        $login_user = auth()->user()->id;
        $mapped_stores = StoreMapping::select('store_domain')->where('store_id', $storeId)->first();
        $store_domain = $mapped_stores->store_domain;
        if ($request->ajax()) {
            $extraSearch = array();
            $q = StoreInvoice::where(['store_domain' => $store_domain, 'supplier_id' => $login_user])->where('tracking_number', '!=', "");
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

            $response = $responsedata->orderBy($orderby, $order)->offset($start)->limit($limit)->get();

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
        return view('supplier.trackinglogs');
    }

    /**
     * Method use for find orders for supplier
     *
     * @return \Illuminate\Http\Response
     */
    public function searchorder(Request $request) {
        //dd($request);
        $supplier_id = auth()->user()->id;
        $user = User::with(['supplierstores'])->find($supplier_id);
        $storeArr = [];
        foreach ($user->supplierstores as $supplierstore) {
            $storeArr[] = $supplierstore->store_domain;
        }
        $conditions = '';
        $order = [];
        $orderItems = [];
        if (!empty($request->search)) {
            $getStr = trim($request->search);
            $getInvoice = StoreInvoice::where("order_id", $getStr)->first();
            if (!$getInvoice) {
                $order = Order::where("order_id", $getStr)->where("assign_supplier", 1)->whereIn('store_domain', $storeArr)->orderBy('created_at', 'desc')->first();
                if (!$order) {
                    return redirect('supplier/searchorder')->with('warning', 'The order is not assigned to you, please wait until store assigned this order to you!');
                }

                $orderItems = OrderItem::with(['productdetail'])->where("order_id", $getStr)->whereIn('store_domain', $storeArr)->get();
            } else {
                return redirect('supplier/searchorder')->with('warning', 'Invoice already created for order =  ' . $getStr . '!');
            }
        }
        return view('supplier.searchorder', ['user' => $user, 'order' => $order, 'orderItems' => $orderItems]);
    }

    /**
     * Method use for create a invoice for store owner and for website admin
     *
     * @return \Illuminate\Http\Response
     */
    public function create_invoice(Request $request) {
        //dd($request);
        $getInvoice = StoreInvoice::where("order_id", $request->order_id)->first();
        if (!$getInvoice) {
            $itemsWithPriceArr = [];
            $invoiceTotal = 0;
            foreach ($request->variant_id as $key => $val) {
                $itemsWithPriceArr[$val] = $request->variant_supplier_price[$key];
                $commissionDataArr[$val] = $request->commission_data[$key];
                $invoiceTotal += $request->variant_supplier_price[$key];
            }
            $store_invoice = new StoreInvoice;
            $store_invoice->supplier_id = $request->supplier_id;
            $store_invoice->store_domain = $request->store_domain;
            $store_invoice->order_id = $request->order_id;
            $store_invoice->order_number = $request->order_number;
            $store_invoice->commission_data = json_encode($commissionDataArr);
            $store_invoice->invoice_data = json_encode($itemsWithPriceArr);
            $store_invoice->invoice_total = $invoiceTotal;
            $store_invoice->notes = $request->notes;
            if ($store_invoice->save()) {
                //send email to store on new invoice create
                $getStoreData = helGetStoreDATA($request->store_domain);
                $data = [];
                $data['receiver_name'] = "<strong>Invoice:</strong> Hello " . $getStoreData->name;
                $data['receiver_message'] = "Your supplier has created an invoice for you. Send payment to complete order fulfillment.<br><br>After payment is sent and received, tracking will automatically be uploaded. You will receive email confirmation when tracking is uploaded.";

                $data['sender_name'] = env('MAIL_FROM_NAME');
                $email_data['message'] = $data;
                $email_data['subject'] = 'Your invoice is ready';
                $email_data['layout'] = 'emails.sendemail';
                try {
                    Mail::to($getStoreData->email)->send(new SendMailable($email_data));
                } catch (\Exception $e) {
                    // Never reached
                }

                return redirect('supplier/searchorder')->with('success', 'Invoice for Order = ' . $request->order_id . ' created successfully!');
            } else {
                return redirect('supplier/searchorder')->with('warning', 'Error to create invoice for order =  ' . $request->order_id . '!');
            }
        } else {
            return redirect('supplier/searchorder')->with('warning', 'Invoice already created for order =  ' . $request->order_id . '!');
        }
    }

    /**
     * Method user for show list of all created invoices by logged in supplier
     *
     * @return \Illuminate\Http\Response
     */
    public function showinvoiceslog(Request $request, $storeId) {
        $login_user = auth()->user()->id;
        $mapped_stores = StoreMapping::select('store_domain')->where('store_id', $storeId)->first();
        $domain = $mapped_stores->store_domain;
        if ($request->ajax()) {
            $q = Invoice::where(['store_domain' => $domain, 'supplier_id' => $login_user]);
            if ($request['paid_status'] == 0) {
                $q->where('paid_status', '<=', 1);
            } else {
                $q->where('paid_status', $request['paid_status']);
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
                $u['action'] = '<a href="' . url('supplier/showinvoicedetail/' . $Invoice->id) . '" class="btn btn-warning margin2px" title="View Invoice Detail"><i class="fa fa-eye"></i> View Invoice Detail</a> <a href="' . url('supplier/downloadinvoice/' . $Invoice->id) . '" class="btn btn-success margin2px" title="View Invoice Detail"><i class="fa fa-download"></i> Download Invoice</a>';
                $u['created_at'] = date('M d, Y H:i:s', strtotime($Invoice->created_at));
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
        return view('supplier.showinvoiceslog', ['domain' => $domain]);
    }

    /**
     * Method user for show list of all created invoices by logged in supplier
     *
     * @return \Illuminate\Http\Response
     */
    public function showinvoicedetail(Request $request, $invoiceID) {
        $login_user = auth()->user()->id;
        $mainInvoice = Invoice::find($invoiceID);
        $storeData = User::where('username', $mainInvoice->store_domain)->first();
        $invoiceIDs = json_decode($mainInvoice->store_invoice_ids, true);
        $invoice_items = Invoice::show_invoice_data($login_user, $invoiceIDs);
        return view('common.showinvoicedetail', ['storeData' => $storeData, 'invoice_items' => $invoice_items, 'mainInvoice' => $mainInvoice]);
    }

    public function downloadinvoice(Request $request, $invoiceID) {
        $login_user = auth()->user()->id;
        $mainInvoice = Invoice::find($invoiceID);
        $storeData = User::where('username', $mainInvoice->store_domain)->first();
        $invoiceIDs = json_decode($mainInvoice->store_invoice_ids, true);
        $invoice_items = Invoice::show_invoice_data($login_user, $invoiceIDs);
        $pdf = PDF::loadView('common.downloadinvoice', ['storeData' => $storeData, 'invoice_items' => $invoice_items, 'mainInvoice' => $mainInvoice]);
        $pdf->setPaper('a4')->setWarnings(false);
        //$pdf->setOptions(['defaultFont' => 'Arial']);
        return $pdf->download($invoiceID . '.pdf');
    }

    public function uploadtracking() {
        //die;
        return view('supplier.uploadtracking');
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadtrackingPost(Request $request) {
        //dd($request);
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            /*
             * Get supplier assigned stores
             */
            $supplier_id = auth()->user()->id;
            $user = User::with(['supplierstores'])->find($supplier_id);
            $storeArr = [];
            foreach ($user->supplierstores as $supplierstore) {
                $storeArr[] = $supplierstore->store_domain;
            }

            foreach ($sheetData as $dataSingle) {
                if (!empty(trim($dataSingle[0])) && !empty(trim($dataSingle[1])) && !empty(trim($dataSingle[2])) && !empty(trim($dataSingle[3]))) {
                    //get invoice data of uploaded order id
                    $getInvoice = StoreInvoice::where("order_number", $dataSingle[0])->where("fulfillment_status", "!=", "fulfilled")->where("paid_status", 2)->whereIn('store_domain', $storeArr)->first();
                    if ($getInvoice) {
                        //get order data of uploaded order id
                        $orderDtl = Order::with(['itemsarr'])->where("order_number", $dataSingle[0])->whereIn('store_domain', $storeArr)->first();
                        //code to check which items will be able to fullfill
                        $line_items = [];
                        $supplierPriceArr = json_decode($getInvoice->invoice_data, true);
                        foreach ($orderDtl->itemsarr as $item) {
                            if ($supplierPriceArr[$item->variant_id] > 0) {
                                $line_items[] = ["id" => $item->item_id];
                            }
                        }
                        //update invoice status with tracking details
                        $data = [];
                        $data['fulfillment_status'] = "fulfilled";
                        $data['tracking_number'] = $dataSingle[1];
                        $data['tracking_url'] = $dataSingle[2];
                        $data['tracking_company'] = $dataSingle[3];
                        StoreInvoice::where('id', $getInvoice->id)->update($data);
                        $data['invoice_data'] = $getInvoice;

                        //update fulfillment status on sopify store with sms
                        $uUser = User::where('username', $orderDtl->store_domain)->first();
                        $cUser = $uUser->providers->where('provider', 'shopify')->first();
                        $shopify = \Shopify::retrieve($uUser->username, $cUser->provider_token);
                        $fulfillmentData = [
                            'fulfillment' => [
                                "location_id" => $uUser->location_id,
                                "tracking_company" => $dataSingle[3],
                                "tracking_number" => $dataSingle[1],
                                "tracking_url" => $dataSingle[2],
                                "line_items" => $line_items
                            ]
                        ];
                        $fulfillOrderID = $orderDtl->order_id;
                        $orderResponse = $shopify->create('orders/' . $fulfillOrderID . '/fulfillments', $fulfillmentData);
                        //dd($orderResponse);
                        //send sms code
                        if (!empty($uUser->phone_code) && !empty($uUser->phone)) {
                            $sNumber = $uUser->phone_code . $uUser->phone;
                            $sMessage = "Tracking uploaded for order number: " . $dataSingle[0];
                            try {
                                helSendSMS($sNumber, $sMessage);
                            } catch (\Exception $e) {
                                // Never reached
                            }
                        }

                        //Send notifiction to store owner
                        Notification::addNotificationFromAllPanel($uUser->id, "Tracking has been uploaded for order (" . $dataSingle[0] . ")", helGetAdminID(), 0, 'TRACKING_UPLOADED');

                        //send email code
                        $email_data['message'] = $data;
                        $email_data['subject'] = 'Tracking info for order :: ' . $dataSingle[0];
                        $email_data['layout'] = 'emails.tracking';
                        try {
                            Mail::to($orderDtl->email)->send(new SendMailable($email_data));
                        } catch (\Exception $e) {
                            // Never reached
                        }
                    }
                }
            }
            //Send notifiction to supplier ownself
            Notification::addNotificationFromAllPanel(auth()->user()->id, "Tracking uploaded successfully", Session::get('selected_store_id'), 0, 'TRACKING_UPLOADED');

            //Send notifiction to admin
            Notification::addNotificationFromAllPanel(helGetAdminID(), "Tracking uploaded for (" . helGetUsernameById(Session::get('selected_store_id')) . ")", auth()->user()->id, 0, 'TRACKING_UPLOADED');

            return redirect('supplier/uploadtracking')->with('success', 'Tracking Updated Successfully!');
        } else {
            return redirect('supplier/uploadtracking')->with('error', 'Accept only .csv/.xls file format!');
        }
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
