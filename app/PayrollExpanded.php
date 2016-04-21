<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollExpanded extends Model
{
     public $timestamps = false;
     
      
     protected $guarded = ['expand_id'];
     protected $primaryKey = 'expand_id';
     protected $table = 'payroll_expanded';
}
