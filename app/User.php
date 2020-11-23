<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $rules = [
        'name' => 'required|string|max:255',
        'username' => 'required|string|min:3|max:255|unique:users|alpha_dash',
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:6|confirmed',
        'tags' => 'required',
    ];
    protected $fillable = [
        'name', 'username', 'email', 'phone_code', 'phone', 'tags', 'role', 'status', 'get_order', 'password', 'auth_code', 'trans_id', 'charge_id', 'currency_code', 'location_id', 'cron_options', 'city', 'state', 'country', 'zip_code', 'billing_address', 'products_data', 'is_deleted'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the assigned shipper
     */
    public function get_shipper() {
        return $this->belongsTo('App\StoreMapping', 'username', 'store_domain');
    }

    /**
     * Get the assigned store
     */
    public function get_store() {
        return $this->belongsTo('App\StoreMapping', 'id', 'shipper_id');
    }

    /**
     * Get the store for the user
     */
    public function store() {
        return $this->belongsTo('App\Store', 'domain', 'username');
    }

    /**
     * Get the stores for the user
     */
    public function stores() {
        return $this->belongsToMany('App\Store', 'store_users');
    }

    /**
     * Get the providers for the user
     */
    public function providers() {
        return $this->hasMany('App\UserProvider');
    }

    /**
     * Get the shippers
     */
    public function storemap() {
        return $this->hasMany('App\StoreMapping', 'store_id');
    }

    /**
     * Get the shipper stores
     */
    public function shipperstores() {
        return $this->hasMany('App\StoreMapping', 'shipper_id');
    }

    public function setPasswordAttribute($pass) {

        $this->attributes['password'] = Hash::make($pass);
    }

    /**
     * A user can have many messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages() {
        return $this->hasMany(Message::class);
    }

}
