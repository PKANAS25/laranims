<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHour extends Model
{
     public $timestamps = false;
     
     protected $guarded = ['hour_id']; 
     protected $table = 'subscriptions_hour';
}
