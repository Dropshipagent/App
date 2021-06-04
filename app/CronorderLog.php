<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CronorderLog extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id', 'supplier_id', 'store_domain', 'cron_last_order', 'csv_file_name',
    ];

}
