<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public $timestamps = false;
    protected $guarded = ['stock_id'];
    protected $primaryKey = 'stock_id';
}
