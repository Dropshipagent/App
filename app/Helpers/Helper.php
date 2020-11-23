<?php

use App\StoreMapping;
use App\User;
use Twilio\Rest\Client;

if (!function_exists('helGetShipperID')) {

    function helGetShipperID($storeID) {
        $shipperID = StoreMapping::select("shipper_id")->where(['store_id' => $storeID])->first();
        if (isset($shipperID->shipper_id)) {
            $shipper_ID = $shipperID->shipper_id;
        } else {
            $shipper_ID = 0;
        }
        return $shipper_ID;
    }

}

if (!function_exists('helGetShipperDATA')) {

    function helGetShipperDATA($store_domain) {
        $shipperID = StoreMapping::select("shipper_id")->where(['store_domain' => $store_domain])->first();
        $shipperDATA = User::where(['id' => $shipperID->shipper_id])->first();
        return $shipperDATA;
    }

}

if (!function_exists('helGetStoreDATA')) {

    function helGetStoreDATA($storeDomain) {
        $storeDATA = User::where(['username' => $storeDomain])->first();
        return $storeDATA;
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

    function helGetMappedStores($shipper_id) {
        $mapped_stores = StoreMapping::where('shipper_id', $shipper_id)->orderBy('created_at', 'desc')->get();
        return $mapped_stores;
    }

}