<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;
    protected $guarded = ['employee_id'];
    protected $primaryKey = 'employee_id';
}
