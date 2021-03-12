<?php

namespace App\Http\Controllers\Supplier;

use App\User;
use App\Order;
use App\StoreInvoice;
use App\StoreMapping;
use App\NotificationStatus;
use App\Notification;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home() {
        $supplier_id = auth()->user()->id;
        $user = User::with(['supplierstores'])->find($supplier_id);
        $storeArr = [];
        foreach ($user->supplierstores as $supplierstore) {
            $storeArr[] = $supplierstore->store_domain;
        }

        $supplierstores = count($storeArr);
        $orders = Order::whereIn('store_domain', $storeArr)->where(['assign_supplier' => 1])->count();
        $invoicesLogs = StoreInvoice::with(['orderdetail' => function($query) {
                        $query->with(['itemsarr' => function($query) {
                                $query->with('productdetail');
                            }]);
                    }])->where(['supplier_id' => $supplier_id])->orderBy('created_at', 'desc')->count();
        return view('supplier.home', ['supplierstores' => $supplierstores, 'orders' => $orders, 'invoicesLogs' => $invoicesLogs]);
    }

    public function setStoreSession(Request $request, $storeId) {
        $request->session()->put('selected_store_id', $storeId);
        return redirect('supplier/bluckinvoice/' . $storeId);
    }

    /**
     * Get count of unread notifications for selected store by supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notifications_count(Request $request) {
        $notificationsCount = 0;
        $messagesCount = 0;
        $userID = $request->user_id;
        $notification_status = NotificationStatus::where('user_id', $userID)->first();
        if ($notification_status) {
            $userCreDate = $notification_status->updated_at;
            $notifications = Notification::where('user_id', $userID)
                    ->where('created_at', '>=', $userCreDate)
                    ->orWhere(function($query) use($userCreDate) {
                        $query->where('user_role', 2);
                        $query->where('created_at', '>=', $userCreDate);
                    })
                    ->orWhere(function($query) use($userCreDate) {
                        $query->where('user_role', 1);
                        $query->where('created_at', '>=', $userCreDate);
                    })
                    ->orderBy('created_at', 'desc');
            $notificationsCount = $notifications->count();
        }
        $messages = Message::where(['receiver_id' => $userID, 'status' => 0]);
        $messagesCount = $messages->count();

        return response()->json([
                    'data' => [
                        'success' => TRUE,
                        'not_count' => $notificationsCount,
                        'msg_count' => $messagesCount,
                    ]
        ]);
    }

}
