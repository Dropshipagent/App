<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExportOrderCsvLog extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'shipper_id', 'store_domain', 'csv_file_name',
    ];

    static function createNewLog($store_id, $shipper_id, $store_domain, $csv_file_name) {
        $orderexportcsv_log = new ExportOrderCsvLog;
        $orderexportcsv_log->store_id = $store_id;
        $orderexportcsv_log->shipper_id = $shipper_id;
        $orderexportcsv_log->store_domain = $store_domain;
        $orderexportcsv_log->csv_file_name = $csv_file_name;
        $orderexportcsv_log->save();
    }

}
