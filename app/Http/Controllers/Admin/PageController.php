<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\StoreInvoice;
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
        $stores = User::where('role', '2')->count();
        $suppliers = User::where('role', '3')->count();
        $orders = Order::where('store_domain', '!=', '')->count();
        $storeInvoices = StoreInvoice::where('store_domain', '!=', '')->count();
        $uploadedTrackings = StoreInvoice::where('tracking_number', '!=', "")->count();
        return view('admin.home', ['stores' => $stores, 'suppliers' => $suppliers, 'orders' => $orders, 'storeInvoices' => $storeInvoices, 'uploadedTrackings' => $uploadedTrackings]);
    }

}
