<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "currencies";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_domain', 'product_id', 'title', 'body_html', 'vendor', 'product_type', 'created_at', 'handle', 'updated_at', 'published_at', 'template_suffix', 'tags', 'published_scope', 'admin_graphql_api_id', 'variants', 'options', 'images', 'image', 'base_price', 'source_flag',
    ];

}
