<?php

use App\StoreMapping;
use App\User;
use App\AdminSetting;
use Twilio\Rest\Client;

if (!function_exists('helGetAdminID')) {

    function helGetAdminID() {
        $admin_ID = 1;
        return $admin_ID;
    }

}

if (!function_exists('helGetSupplierID')) {

    function helGetSupplierID($storeID) {
        $supplierID = StoreMapping::select("supplier_id")->where(['store_id' => $storeID])->first();
        if (isset($supplierID->supplier_id)) {
            $supplier_ID = $supplierID->supplier_id;
        } else {
            $supplier_ID = 0;
        }
        return $supplier_ID;
    }

}

if (!function_exists('helGetSupplierDATA')) {

    function helGetSupplierDATA($store_domain) {
        $supplierID = StoreMapping::select("supplier_id")->where(['store_domain' => $store_domain])->first();
        $supplierDATA = User::where(['id' => $supplierID->supplier_id])->first();
        return $supplierDATA;
    }

}

if (!function_exists('helGetStoreDATA')) {

    function helGetStoreDATA($storeDomain) {
        $storeDATA = User::where(['username' => $storeDomain])->first();
        return $storeDATA;
    }

}

if (!function_exists('helGetUsernameById')) {

    function helGetUsernameById($id) {
        $user = User::select('username')->where(['id' => $id])->first();
        return $user->username;
    }

}

if (!function_exists('helSendSMS')) {

    function helSendSMS($number, $message) {
        // Your Account SID and Auth Token from twilio.com/console
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $client = new Client($sid, $token);
        return $client->messages->create(
                        $number, [
                    'from' => env('TWILIO_FROM'),
                    'body' => $message,
                        ]
        );
    }

}

if (!function_exists('helGetMappedStores')) {

    function helGetMappedStores($supplier_id) {
        $mapped_stores = StoreMapping::where('supplier_id', $supplier_id)->orderBy('created_at', 'desc')->get();
        return $mapped_stores;
    }

}

if (!function_exists('helGetClockMessage')) {

    function helGetClockMessage() {
        $defaultMessage = '';
        /* This sets the $time variable to the current hour in the 24 hour clock format */
        $time = date("H");
        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");
        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            $defaultMessage = "Good morning";
        } else
        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            $defaultMessage = "Good afternoon";
        } else
        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        if ($time >= "17" && $time < "19") {
            $defaultMessage = "Good evening";
        } else
        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        if ($time >= "19") {
            $defaultMessage = "Good night";
        }
        return $defaultMessage;
    }

}

if (!function_exists('helGetShippingTimeOption')) {

    function helGetShippingTimeOption() {
        return ['5-7 Days' => '5-7 Days', 'Regular 7-12 Days' => 'Regular 7-12 Days'];
    }

}

if (!function_exists('getAdminSettingData')) {

    function getAdminSettingData() {
        $setting = AdminSetting::where('setting_id', 1)->first()->toArray();
        return $setting;
    }

}