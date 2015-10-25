<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchItem extends Model
{
    public $timestamps = false;
     
     protected $guarded = ['id']; 
     protected $table = 'branch_items';
}
