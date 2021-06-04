<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('webhook/shopify/uninstall', 'SellerOrderController@uninstall_app');
Route::any('webhook/shopify/orders/create', 'SellerOrderController@createOrder');
Route::any('webhook/shopify/orders/updated', 'SellerOrderController@updateOrder');
Route::any('shopify/seller/orders/csvexportcron', 'ExportController@csvexportcron');
