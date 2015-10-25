<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
	public $timestamps = false;
    protected $guarded = ['subscription_id'];
    protected $primaryKey = 'subscription_id';
}
