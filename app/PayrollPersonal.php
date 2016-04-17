<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollPersonal extends Model
{
    public $timestamps = false;
     
     protected $guarded = ['personal_pay_id']; 
     protected $primaryKey = 'personal_pay_id';
     protected $table = 'payroll_personal';
}
