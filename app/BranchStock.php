<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchStock extends Model
{
    public $timestamps = false;
     
     protected $guarded = ['stock_id']; 
     protected $table = 'branch_stocks';
}
