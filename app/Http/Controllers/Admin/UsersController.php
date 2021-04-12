<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Config;
use View;
use App\User;
use App\Order;
use App\OrderItem;
use App\StoreMapping;
use App\CronorderLog;
use App\Currency;
use App\Product;
use App\Notification;
use App\ExportOrderCsvLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class UsersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //$pending_reqData = User::where(['role' => 2, 'status' => 0])->with(['storemap'])->get();
        //$app_and_unpaidData = User::where(['role' => 2, 'status' => 1])->with(['storemap'])->get();
        //$app_and_paidData = User::where(['role' => 2, 'status' => 2])->with(['storemap'])->get();
        //$suppliers = User::where('role', 3)->get();
        //return view('admin.users.index', ['pending_reqData' => $pending_reqData, 'app_and_unpaidData' => $app_and_unpaidData, 'app_and_paidData' => $app_and_paidData, 'suppliers' => $suppliers]);
        if ($request->ajax()) {
            $role = $request['role'];
            $status = $request['status'];
            $tab = $request['tab'];
            if ($role == 3) {
                $q = User::with(['get_store'])->where(['role' => $role]);
            } else {
                $q = User::with(['get_supplier'])->where(['role' => $role, 'status' => $status]);
            }

            $TotalUsersData = $q->count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $columnindex = $request['order']['0']['column'];
            $orderby = $request['columns'][$columnindex]['data'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $response = $q->orderBy($orderby, $order)->offset($start)->limit($limit)->get();
            if (!$response) {
                $userData = [];
                $paging = [];
            } else {
                $userData = $response;
                $paging = $response;
            }

            $Data = array();
            $i = 1;
            foreach ($userData as $user) {
                if (isset($user->get_store->store_domain)) {
                    $supplierStore = $user->get_store->store_domain;
                } else {
                    $supplierStore = '';
                }
                $u['id'] = $user->id;
                $u['name'] = $user->name;
                $u['username'] = $user->username;
                $u['email'] = $user->email;
                $u['created_at'] = date('M d, Y H:i:s', strtotime($user->created_at));
                $u['updated_at'] = date('M d, Y H:i:s', strtotime($user->updated_at));
                $action = view('admin.users.indexAction', ['tab' => $tab, 'role' => $role, 'user' => $user, 'supplierStore' => $supplierStore]);
                $u['action'] = $action->render();



                $Data[] = $u;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($TotalUsersData),
                "recordsTotal" => intval($TotalUsersData),
                "data" => $Data
            ];
            return $return;
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $user = new User; /// create model object
        $validator = Validator::make($request->all(), $user->rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            if (User::create($request->all())) {
                return redirect('admin/users')->with('success', 'User added successfully!');
            } else {
                return redirect()->back()->with('danger', 'Failed to Add , Please try again!');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::find($id);
        $orders = Order::with(['itemsarr'])->where('store_domain', $user->username)->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.show', ['user' => $user, 'orders' => $orders]);
    }

    public function userProfile($id) {
        $user = User::find($id);
        $currencies_list = Currency::select('code', 'name')->where(['active' => 1])->get();
        $products = Product::where(['store_domain' => $user->username, 'product_status' => 1])->get();
        return view('admin.users.profile', ['products' => $products, 'user' => $user, 'currencies_list' => $currencies_list]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::find($id);
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $input = $request->all();
        $user->fill($input)->save();
        if ($user->fill($input)->save()) {
            return redirect('admin/users')->with('success', 'User updated successfully!');
        } else {
            return redirect()->back()->with('danger', 'Failed to Add , Please try again!');
        }
    }

    /**
     * Approve requested user status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function user_status(Request $request) {
        if (isset($request->user_id)) {
            $user = User::findOrFail($request->user_id);
            if ($request->user_status == 1) {
                //code when will accept any user
                $getProduct = Product::where(['store_domain' => $user->username])->where('product_status', '>=', 2)->count();
                if ($getProduct > 0) {
                    $user->status = 1;
                    if ($user->save()) {
                        //send email to store owner with updated status
                        $data = [];
                        $data['receiver_name'] = "<strong>Welcome:</strong> Hello " . $user->name . "!";
                        $data['receiver_message'] = "My name is " . env('FOUNDER_NAME') . ", one of the founders of Dropship Agent Co. Now that you have been approved, I want to personally welcome you on board! Before we can complete your onboarding process, we require a quick video/phone call to familiarize ourselves with your business. We take great appreciation for our clients and want to make sure every client is 100% satisfied when signing up.<br><br>Please let me know when we can schedule a call to talk about your business and complete the registration process.";

                        $data['sender_name'] = "Talk soon,<br>" . env('FOUNDER_NAME') . "<br>Founder";
                        $email_data['message'] = $data;
                        $email_data['subject'] = 'Your Dropship Agent Co. Application';
                        $email_data['layout'] = 'emails.sendemail';
                        try {
                            Mail::to($user->email)->send(new SendMailable($email_data));
                        } catch (\Exception $e) {
                            // Never reached
                        }
                        Notification::addNotificationFromAllPanel($user->id, 'Store [' . $user->username . ']  approved by admin', auth()->user()->id, $user->id, 'NEW_STORE_ACCEPTED');
                        $response = ['success' => true, 'message' => 'Store approve successfully!'];
                    } else {
                        $response = ['success' => false, 'message' => 'Error to approve store!'];
                    }
                } else {
                    $response = ['success' => false, 'message' => 'First approve at least one requested product by Store!'];
                }
            } else {
                //code when we will reject any user
                $user->status = -1;
                if ($user->save()) {
                    $data = [];
                    $data['receiver_name'] = "<strong>Welcome:</strong> Hello " . $user->name . "!";
                    $data['receiver_message'] = "My name is " . env('FOUNDER_NAME') . ", one of the founders of Dropship Agent Co. Now that you have been rejected.";

                    $data['sender_name'] = "Talk soon,<br>" . env('FOUNDER_NAME') . "<br>Founder";
                    $email_data['message'] = $data;
                    $email_data['subject'] = 'Your Dropship Agent Co. Application';
                    $email_data['layout'] = 'emails.sendemail';
                    try {
                        Mail::to($user->email)->send(new SendMailable($email_data));
                    } catch (\Exception $e) {
                        // Never reached
                    }
                    $response = ['success' => true, 'message' => 'Store reject successfully!'];
                } else {
                    $response = ['success' => false, 'message' => 'Error to reject store!'];
                }
            }
        }
        return response()->json([
                    'data' => $response
        ]);
    }

    public function searchUsers(Request $request) {
        $getStr = $request->term;
        $users = User::select('id', 'name AS label', 'name AS value')->where('tags', 'like', "%$getStr%")->get();
        return json_encode($users);
    }

    /**
     * Method use for assign user to a store and create csv file of all orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function email_csv(Request $request) {
        $storeMapping = StoreMapping::where(['store_id' => $request->store_id, 'supplier_id' => $request->supplier_id, 'store_domain' => $request->store_domain])->first();
        if (!$storeMapping) {
            if (StoreMapping::create($request->all())) {
                $fileNameResponse = Order::create_orders_csv($request->store_domain, null, 0);
                $fileNameRespoArr = json_decode($fileNameResponse);
                if ($fileNameRespoArr) {
                    //Get Supplier Data
                    $getSupplierData = User::find($request->supplier_id);

                    //Get Store Data
                    $getStoreData = User::find($request->store_id);

                    /* $cronorder_log = new CronorderLog;
                      $cronorder_log->store_id = $request->store_id;
                      $cronorder_log->supplier_id = $request->supplier_id;
                      $cronorder_log->store_domain = $request->store_domain;
                      if ($fileNameRespoArr->maxIDVal) {
                      $cronorder_log->cron_last_order = $fileNameRespoArr->maxIDVal;
                      } else {
                      $cronorder_log->cron_last_order = 0;
                      }
                      $cronorder_log->csv_file_name = "$fileNameRespoArr->csvFileName";
                      $cronorder_log->save();

                      //save order to csv logs
                      ExportOrderCsvLog::createNewLog($request->store_id, $request->supplier_id, $request->store_domain, $fileNameRespoArr->csvFileName); */

                    //send email to supplier
                    /* $attachFileURL = url('/storage/ordercsv/' . $fileNameRespoArr->csvFileName);

                      $data = [];
                      $data['supplier_name'] = $getSupplierData->name;
                      $data['message_body'] = "<strong>Export:</strong> Your orders have finished exporting and are ready to download.";
                      $data['file_url'] = $attachFileURL;

                      $email_data['message'] = env('MAIL_FROM_NAME');
                      $email_data['subject'] = 'Your export is ready';
                      $email_data['layout'] = 'emails.assignorder';
                      try {
                      Mail::to($getSupplierData->email)->send(new SendMailable($email_data));
                      } catch (\Exception $e) {
                      // Never reached
                      } */

                    //send notification to admin 
                    Notification::addNotificationFromAllPanel($getSupplierData->id, "You have been assigned to (" . $getStoreData->username . ")", helGetAdminID(), $getStoreData->id, 'ASSIGNED_TO_STORE');

                    //send email to new signup store
                    $data = [];
                    $data['receiver_name'] = $getStoreData->name;
                    $data['receiver_message'] = "A new supplier assign by admin for your store :: " . $getStoreData->username;
                    $data['sender_name'] = "DSA Team";

                    $email_data['message'] = $data;
                    $email_data['subject'] = 'New supplier assigned :: ' . $getSupplierData->name;
                    $email_data['layout'] = 'emails.sendemail';
                    try {
                        Mail::to($getStoreData->email)->send(new SendMailable($email_data));
                    } catch (\Exception $e) {
                        // Never reached
                    }
                }

                return redirect('admin/users')->with('success', 'Store ' . $request->store_domain . ' mapped successfully!');
            } else {
                return redirect()->back()->with('danger', 'Failed to Add , Please try again!');
            }
        } else {
            return redirect('admin/users')->with('warning', 'Store ' . $request->store_domain . ' already mapped with selected supplier!');
        }
    }

    /**
     * Cron method to create a csv for latest orders for all stores and send to suppliers
     *
     * @return \Illuminate\Http\Response
     */
    public function showcsvlogs($id) {
        $cronorder_logs = CronorderLog::where(['store_id' => $id])->orderBy('created_at', 'desc')->get();
        return view('admin.users.showcsvlogs', ['cronorder_logs' => $cronorder_logs]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::find($id);
        if ($user->delete()) {
            return redirect('admin/users')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->back()->with('danger', 'Failed to Delete, Please try again!');
        }
    }

    /**
     * method use to set session for selected store in admin panel store listing section
     * @param Request $request
     * @param type $storeDomain
     * @return type
     */
    public function setStoreSession(Request $request, $storeDomain) {
        $request->session()->put('selected_store_id', $storeDomain);
        return redirect('admin/orders');
    }

}
