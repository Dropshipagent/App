<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\Invoice;
use App\StoreInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class PageController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request) {
        $request->session()->put('selected_store_id', '');
        $stores = User::where('role', '2')->where('status', 2)->count();
        $suppliers = User::where('role', '3')->count();
        $orders = Order::join('users', 'username', '=', 'store_domain')->where('store_domain', '!=', '')->where('status', 2)->count();
        $storeInvoices = Invoice::where('store_domain', '!=', '')->count();
        $uploadedTrackings = StoreInvoice::where('tracking_number', '!=', "")->count();
        return view('admin.home', ['stores' => $stores, 'suppliers' => $suppliers, 'orders' => $orders, 'storeInvoices' => $storeInvoices, 'uploadedTrackings' => $uploadedTrackings]);
    }

}
