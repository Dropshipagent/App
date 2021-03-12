<?php

namespace App\Http\Controllers;

use Auth;
use Config;
use View;
use App\User;
use App\Order;
use App\OrderItem;
use App\StoreMapping;
use App\CronorderLog;
use App\ExportOrderCsvLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller {

    /**
     * Cron method to create a csv for latest orders for all stores and send to suppliers
     *
     * @return \Illuminate\Http\Response
     */
    public function csvexportcron(Request $request) {
        //dd($request->all());
        $storeMappings = StoreMapping::get();
        foreach ($storeMappings as $storeMapping) {
            $crrDay = strtolower(date('l'));
            $storedata = User::where('username', $storeMapping->store_domain)->first();
            if ($storedata && $storedata->cron_options != "") {
                $cronjson = json_decode($storedata->cron_options, true);
                //dd($cronjson);
                if ((array_key_exists('daily', $cronjson) && $cronjson['daily'] == 'yes') || (array_key_exists($crrDay, $cronjson) && $cronjson[$crrDay] == 'yes')) {
                    //get highest orderitem id from CronorderLog table
                    $cronorder_log = CronorderLog::where(['supplier_id' => $storeMapping->supplier_id, 'store_domain' => $storeMapping->store_domain])->max('cron_last_order');
                    //get max id which will save into export csv log
                    $maxIDVal = OrderItem::where(['store_domain' => $storeMapping->store_domain])->max('id');
                    if ($maxIDVal > $cronorder_log) {
                        $fileNameResponse = Order::create_orders_csv($storeMapping->store_domain, $cronorder_log);
                        $fileNameRespoArr = json_decode($fileNameResponse);
                        if ($fileNameRespoArr) {
                            $cronorder_log = new CronorderLog;
                            $cronorder_log->store_id = $storeMapping->store_id;
                            $cronorder_log->supplier_id = $storeMapping->supplier_id;
                            $cronorder_log->store_domain = $storeMapping->store_domain;
                            $cronorder_log->cron_last_order = $fileNameRespoArr->maxIDVal;
                            $cronorder_log->csv_file_name = "$fileNameRespoArr->csvFileName";
                            $cronorder_log->save();

                            //save order to csv logs
                            ExportOrderCsvLog::createNewLog($storeMapping->store_id, $storeMapping->supplier_id, $storeMapping->store_domain, $fileNameRespoArr->csvFileName);

                            //send email to supplier
                            $getSupplierData = User::find($storeMapping->supplier_id);
                            $attachFileURL = url('/storage/ordercsv/' . $fileNameRespoArr->csvFileName);

                            $data = [];
                            $data['supplier_name'] = $getSupplierData->name;
                            $data['message_body'] = "New order assigned by assigned order CRON. Please check the attached CSV.";
                            $data['file_url'] = $attachFileURL;

                            $email_data['message'] = $data;
                            $email_data['subject'] = 'Assign order to supplier cron';
                            $email_data['layout'] = 'emails.assignorder';
                            try {
                                Mail::to($getSupplierData->email)->send(new SendMailable($email_data));
                            } catch (\Exception $e) {
                                // Never reached
                            }
                        }
                    } else {
                        //echo "false condition";
                    }
                }
            }
        }
        echo "Cron run successfully";
    }

}
