<?php

namespace App\Http\Middleware;

use App\Product;
use Closure;
use Request;

class Front {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (isset(auth()->user()->role) && auth()->user()->role == 1) {
            return redirect('/admin/home')->with('error', 'You have only admin access');
        } elseif (isset(auth()->user()->role) && auth()->user()->role == 3) {
            return redirect('/shipper/home')->with('error', 'You have only shipper access');
        } elseif (isset(auth()->user()->status) && auth()->user()->status < 0 && !Request::is('my-account')) {
            return redirect('my-account');
        } elseif (isset(auth()->user()->status) && auth()->user()->status == 0 && !Request::is('profile-status')) {
            return redirect('profile-status');
        } elseif (isset(auth()->user()->status) && auth()->user()->status == 1) {
            $getProduct = Product::where(['store_domain' => auth()->user()->username, 'product_status' => 3])->count();
            if ($getProduct > 0) {
                return redirect('checkout');
            } else {
                return redirect('storeproducts/index/' . auth()->user()->username . '?pdstatus=1');
            }
        } else {
            if (isset(auth()->user()->is_deleted) && auth()->user()->is_deleted == 1) {
                return redirect('/logout');
            } else {
                return $next($request);
            }
        }
    }

}
