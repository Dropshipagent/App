<?php

namespace App\Http\Controllers;

use Auth;
use App\Store;
use App\User;
use App\Product;
use App\Currency;
use App\Notification;
use App\Message;
use App\OrderItem;
use App\Invoice;
use App\StoreInvoice;
use App\PaymentProfile;
use App\NotificationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile_status() {
        return view('profile_status');
    }

    /**
     * Show profile details.
     *
     * @return \Illuminate\Http\Response
     */
    public function myaccount() {
        $id = Auth::user()->id;
        $currencies_list = Currency::select('code', 'name')->where(['active' => 1])->get();
        $user = User::find($id);
        return view('seller.myaccount', ['user' => $user, 'currencies_list' => $currencies_list]);
    }

    /**
     * Update profile details.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateaccount(Request $request) {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        $input = $request->all();
        if (isset($request->cron_options)) {
            $cronData = [];
            foreach ($request->cron_options as $key => $val) {
                $cronData[$val] = 'yes';
            }
            $cron_options = json_encode($cronData);
        } else {
            $cron_options = NULL;
        }
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
        $input['cron_options'] = $cron_options;
        if (Auth::user()->status < 0) {
            $input['status'] = 0;
        }
        if ($user->fill($input)->save()) {

            if (Auth::user()->status == -1) {

                //send email to new signup store
                $data = [];
                $data['receiver_name'] = "Hello " . Auth::user()->name;
                $data['receiver_message'] = "<strong>Welcome:</strong> A warm welcome from the Dropship Agent Co Team! We are happy to say you have been approved to work with us and we are excited to support you and your business. We will be emailing you shortly to book a call to complete your onboarding process and introduce you to our team.";

                $data['sender_name'] = "Your Dropship Agent Co. Application";
                $email_data['message'] = $data;
                $email_data['subject'] = 'Your Dropship Agent Co. Application';
                $email_data['layout'] = 'emails.sendemail';
                try {
                    Mail::to(Auth::user()->email)->send(new SendMailable($email_data));
                } catch (\Exception $e) {
                    // Never reached
                }


                //send email to admin
                $data = [];
                $data['receiver_name'] = "Admin";
                $data['receiver_message'] = "A user completes their profile, please review and update their status.";
                $data['sender_name'] = "DSA Team";

                $email_data['message'] = $data;
                $email_data['subject'] = 'Profile upgrade by :: ' . Auth::user()->name;
                $email_data['layout'] = 'emails.sendemail';
                try {
                    Mail::to(env('ADMIN_MAIL_ADDRESS', 'info@dropshipagent.co'))->send(new SendMailable($email_data));
                } catch (\Exception $e) {
                    // Never reached
                }

                //send notification to admin 
                Notification::addNotificationFromAllPanel(helGetAdminID(), "You have a new quote request", auth()->user()->id, auth()->user()->id, 'NEW_STORE_CREATED');
                //get the all remainig temp product and delete based on id array
                $tempProducts = Product::where(['store_domain' => auth()->user()->username, 'product_status' => 0])->get(['id']);
                Product::destroy($tempProducts->toArray());
                NotificationStatus::create(['user_id' => Auth::user()->id, 'not_id' => 0]);
                return redirect('profile-status?currency=' . $request->currency_code);
            } else {
                return redirect('my-account?currency=' . $request->currency_code)->with('success', 'Account updated successfully!');
            }
        } else {
            return redirect()->back()->with('danger', 'Failed to Add , Please try again!');
        }
    }

    /**
     * Tracking log method.
     *
     * @return \Illuminate\Http\Response
     */
    public function showtrackinglog(Request $request) {
        $store_domain = auth()->user()->username;


        if ($request->ajax()) {
            $extraSearch = array();
            $q = StoreInvoice::where(['store_domain' => $store_domain])->where('tracking_number', '!=', "");
            $TotalOrderData = $q->count();

            $responsedata = $q;
            $search = $request['search']['value'];
            if ($search && !empty($search)) {
                $q->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', '%' . $search . '%');
                    $query->orWhere('store_domain', 'LIKE', '%' . $search . '%');
                    $query->orWhere('order_number', 'LIKE', '%' . $search . '%');
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
                $u['order_number'] = $tracking->order_number;
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
        return view('showtrackinglog');
    }

    /**
     * change intro video status
     *
     * @return \Illuminate\Http\Response
     */
    public function intro_video_status_change(Request $request) {
        $userID = $request->user_id;
        User::where('id', $userID)->update(['intro_video_status' => '1']);
        return response()->json([
                    'data' => [
                        'success' => TRUE,
                    ]
        ]);
    }

}
