<?php

namespace App\Http\Controllers\Admin;

use App\Notification;
use App\NotificationStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $admin_id = auth()->user()->id;
        $notifications = Notification::with(["userdetail"])->where(['notification_by' => $admin_id])->orderBy('created_at', 'desc')->get();

        $recNotifications = Notification::with(["userdetail", "senduserdetail"])
                ->orWhere(function($query) {
                    $query->where('user_id', 1);
                })
                ->orderBy('created_at', 'desc');
        $notMaxID = $recNotifications->max('id');
        $recNotifications = $recNotifications->get();
        $recNotifications->map(function ($recNotifications) {
            $recNotifications['notification_url'] = Notification::createNotificationUrlForAdmin($recNotifications->id);
        });
        return view('admin.notifications.index', ['notifications' => $notifications, 'notMaxID' => $notMaxID, 'recNotifications' => $recNotifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (Notification::create($request->all())) {
            return redirect('admin/notifications')->with('success', 'Notification send successfully!');
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
        $userID = $request->user_id;
        $notification_status = NotificationStatus::where('user_id', $userID)->orderBy('created_at', 'desc')->first();
        if ($notification_status) {
            $userCreDate = $notification_status->updated_at;
            $notifications = Notification::where('user_id', $userID)
                    ->where('created_at', '>=', $userCreDate);
            $notificationsCount = $notifications->count();
        }

        return response()->json([
                    'data' => [
                        'success' => TRUE,
                        'not_count' => $notificationsCount,
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
