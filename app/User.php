<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','gender','country','phone','rewardpoint_balance','lname','address','status','role_id','verify_key','device_token','device_type','favourite','share','verify_otp','fav_products','share_products'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getBookings(){
        return $this->hasMany('TCG\Voyager\Models\Booking', 'user_id')->whereNotNull('status');
    }

    public function orders(){
        return $this->hasMany('TCG\Voyager\Models\Order', 'order_user_id');
    }

    public function salon_orders(){
        return $this->hasMany('TCG\Voyager\Models\Order', 'provider_id');
    }

    public function reviews(){
        return $this->hasMany('TCG\Voyager\Models\Review', 'record_id')->whereType('service');
    }

    public function user_reviews(){
        return $this->hasMany('TCG\Voyager\Models\Review', 'user_id')->whereType('booking');
    }

    public function bookingreviews(){
        return $this->hasMany('TCG\Voyager\Models\Review', 'record_id')->whereType('booking');
    }

    public function getSalonBookings(){
        return $this->hasMany('TCG\Voyager\Models\Booking', 'salon_id');
    }

    public function getRewards(){
        return $this->hasMany('TCG\Voyager\Models\Reward', 'user_id');
    }

    // public function getReports(){
    //     return $this->hasMany('TCG\Voyager\Models\Report', 'user_id');
    // }
}
