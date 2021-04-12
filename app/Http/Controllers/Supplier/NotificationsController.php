<?php

namespace App\Http\Controllers\Supplier;

use App\User;
use App\Notification;
use App\NotificationStatus;
use App\Message;
use App\StoreMapping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $supplier_id = auth()->user()->id;
        $userCreDate = auth()->user()->created_at;
        $notifications = Notification::with(["userdetail"])->where(['notification_by' => $supplier_id])->orderBy('created_at', 'desc')->get();

        $recNotifications = Notification::with(["userdetail", "senduserdetail"])->where('user_id', $supplier_id)
                ->orWhere(function($query) use($userCreDate) {
                    $query->where('user_role', 3);
                    $query->where('created_at', '>=', $userCreDate);
                })
                ->orWhere(function($query) use($userCreDate) {
                    $query->where('user_role', 1);
                    $query->where('created_at', '>=', $userCreDate);
                })
                ->orderBy('created_at', 'desc');
        $notMaxID = $recNotifications->max('id');
        $recNotifications = $recNotifications->get();
        $recNotifications->map(function ($recNotifications) {
            $recNotifications['notification_url'] = Notification::createNotificationUrlForSupplier($recNotifications->id);
        });

        return view('supplier.notifications.index', ['notifications' => $notifications, 'notMaxID' => $notMaxID, 'recNotifications' => $recNotifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $supplier_id = auth()->user()->id;
        $user = User::with(['supplierstores'])->find($supplier_id);
        $storeArr = [];
        foreach ($user->supplierstores as $supplierstore) {
            $storeArr[$supplierstore->store_id] = $supplierstore->store_domain;
        }
        return view('supplier.notifications.create', ['mappedstores' => $storeArr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $notifySent = false;
        foreach ($request->stores as $key => $val) {
            $data = [];
            $data['notification_by'] = $request->notification_by;
            $data['notifications'] = $request->notifications;
            $data['user_id'] = $val;
            if (Notification::create($data)) {
                $notifySent = true;
            } else {
                $notifySent = false;
            }
        }
        if ($notifySent) {
            return redirect('supplier/suppliernotifications')->with('success', 'Notification send successfully!');
        } else {
            return redirect()->back()->with('danger', 'Failed to Send , Please try again!');
        }
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

    /**
     * Set updated date of read notifications
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function read_notifications(Request $request) {
        $notification_status = NotificationStatus::firstOrNew(['user_id' => $request->user_id]);
        if ($request->not_id) {
            $notification_status->not_id = $request->not_id;
        } else {
            $notification_status->not_id = 0;
        }

        return response()->json([
                    'data' => [
                        'success' => $notification_status->save(),
                    ]
        ]);
    }

    /**
     * Get count of unread notifications
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notifications_count(Request $request) {
        $notificationsCount = 0;
        $messagesCount = 0;
        $userID = $request->user_id;
        $notification_status = NotificationStatus::where('user_id', $userID)->orderBy('created_at', 'desc')->first();
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
        $messagesCount = 0;
        if ($request->store_id > 0) {
            $messages = Message::where(['receiver_id' => $userID, 'user_id' => $request->store_id, 'status' => 0]);
            $messagesCount = $messages->count();
        }

        return response()->json([
                    'data' => [
                        'success' => TRUE,
                        'not_count' => $notificationsCount,
                        'msg_count' => $messagesCount,
                    ]
        ]);
    }

    /**
     * Get unread notifications Data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notifications_unread(Request $request) {
        $notificationsData = [];
        $userID = $request->user_id;
        $notification_status = NotificationStatus::where('user_id', $userID)->orderBy('created_at', 'desc')->first();
        if ($notification_status) {
            $userCreDate = $notification_status->updated_at;
            $notifications = Notification::where('user_id', $userID)
                            ->where('created_at', '>=', $userCreDate)->orderBy('id', 'desc');
            $notificationsData = $notifications->limit(5)->get();
            $notMaxID = $notifications->max('id');
            $notification_status->not_id = $notMaxID;
            $notification_status->save();
            $notificationsData->map(function ($notificationsData) {
                $notificationsData['notification_url'] = Notification::createNotificationUrlForAdmin($notificationsData->id);
            });
            $notificationData = $notificationsData->toArray();
        }
        return response()->json([
                    'data' => [
                        'success' => TRUE,
                        'notifications' => $notificationsData,
                    ]
        ]);
    }

}
