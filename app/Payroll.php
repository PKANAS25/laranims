<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
     public $timestamps = false;
     
      
     protected $guarded = ['payroll_id'];
     protected $primaryKey = 'payroll_id';
     protected $table = 'payroll';
}
