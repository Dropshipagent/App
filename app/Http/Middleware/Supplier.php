<?php

namespace App\Http\Middleware;

use Closure;

class Supplier {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (auth()->user()->role == 3) {
            $storeIDVal = $request->session()->get('selected_store_id');
            if (!$storeIDVal) {
                $request->session()->put('selected_store_id', 0);
            }
            return $next($request);
        } else {
            return redirect('/')->with('error', 'you do not have supplier panel access');
        }
    }

}
