<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Product extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_domain', 'product_id', 'title', 'body_html', 'vendor', 'product_type', 'created_at', 'handle', 'updated_at', 'published_at', 'template_suffix', 'tags', 'published_scope', 'admin_graphql_api_id', 'variants', 'options', 'images', 'image', 'base_price', 'admin_commission', 'source_flag', 'product_status'];

    static function syncproducts($store_domain) {
        $cUser = User::select('user_providers.provider_token')->join('user_providers', 'user_providers.user_id', 'users.id')->where('users.username', $store_domain)->where('user_providers.provider', 'shopify')->first();
        $shopify = \Shopify::retrieve($store_domain, $cUser->provider_token);
        $products = $shopify->get('products');
//        echo "<pre>";
//        print_r($products);
//        die;
        foreach ($products['products'] as $product) {
            $prodData = Product::where(['store_domain' => $store_domain, 'product_id' => $product['id']])->first();
            if (empty($prodData)) {
                $nProduct = new Product;
                $nProduct->store_domain = (isset($store_domain) && $store_domain !== '') ? $store_domain : null;
                $nProduct->product_id = (isset($product['id']) && $product['id'] !== '') ? $product['id'] : 0;
                $nProduct->title = (isset($product['title']) && $product['title'] !== '') ? $product['title'] : null;
                $nProduct->body_html = (isset($product['body_html']) && $product['body_html'] !== '') ? $product['body_html'] : null;
                $nProduct->vendor = (isset($product['vendor']) && $product['vendor'] !== '') ? $product['vendor'] : null;
                $nProduct->product_type = (isset($product['product_type']) && $product['product_type'] !== '') ? $product['product_type'] : null;
                $nProduct->p_created_at = (isset($product['created_at']) && $product['created_at'] !== '') ? $product['created_at'] : null;
                $nProduct->handle = (isset($product['handle']) && $product['handle'] !== '') ? $product['handle'] : null;
                $nProduct->p_updated_at = (isset($product['updated_at']) && $product['updated_at'] !== '') ? $product['updated_at'] : null;
                $nProduct->p_published_at = (isset($product['published_at']) && $product['published_at'] !== '') ? $product['published_at'] : null;
                $nProduct->template_suffix = (isset($product['template_suffix']) && $product['template_suffix'] !== '') ? $product['template_suffix'] : null;
                $nProduct->tags = (isset($product['tags']) && $product['tags'] !== '') ? $product['tags'] : null;
                $nProduct->published_scope = (isset($product['published_scope']) && $product['published_scope'] !== '') ? $product['published_scope'] : null;
                $nProduct->admin_graphql_api_id = (isset($product['admin_graphql_api_id']) && $product['admin_graphql_api_id'] !== '') ? $product['admin_graphql_api_id'] : null;
                $nProduct->variants = (isset($product['variants']) && $product['variants'] !== '') ? json_encode($product['variants']) : null;
                $basePriceArr = [];
                foreach ($product['variants'] as $key => $val) {
                    $basePriceArr[$val['id']] = '0.00';
                }
                $nProduct->base_price = (isset($basePriceArr) && $basePriceArr !== '') ? json_encode($basePriceArr) : null;
                $nProduct->admin_commission = (isset($basePriceArr) && $basePriceArr !== '') ? json_encode($basePriceArr) : null;
                $nProduct->options = (isset($product['options']) && $product['options'] !== '') ? json_encode($product['options']) : null;
                $nProduct->images = (isset($product['images']) && $product['images'] !== '') ? json_encode($product['images']) : null;
                $nProduct->image = (isset($product['image']) && $product['image'] !== '') ? json_encode($product['image']) : null;

                $nProduct->save();
            }
        }
        return true;
    }

}
