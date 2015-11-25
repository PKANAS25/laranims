<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeesSalary extends Model
{
    public $timestamps = false;
    protected $table='employees_salary';
    protected $guarded = ['salary_id'];
    protected $primaryKey = 'salary_id';
}
