<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Carbon\Carbon;

use App\Employee; 
use App\Branch;
use App\EmployeesSalary;  

use App\Payroll;
 
use Validator;



class PayrollControllerMain extends Controller
{
     
    public function generate()
    {
        //
    }
}
