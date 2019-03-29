<?php

namespace Sandeep\Maskfront\Models;

use Illuminate\Database\Eloquent\Model;

class FCMUser extends Model
{
	protected $table='fcm_users';

    protected $fillable = [
         'user_id','user_type','fcm_token','device_type','device_id'
    ];

    public function user(){
		  return $this->belongsTo('App\User','user_id');
    }
}
