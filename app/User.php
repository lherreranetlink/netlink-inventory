<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public function checkins(){
        return $this->hasMany('App\CheckinLog','checkin_by');
    }
    public function checkouts(){
        return $this->hasMany('App\CheckoutLog','checkout_by');
    }
    
    public function inout_checkout(){
        return $this->hasMany('App\InOut','checkout_by');
    }
    
    public function inout_checkin(){
        return $this->hasMany('App\InOut','checkin_by');
    }
}
