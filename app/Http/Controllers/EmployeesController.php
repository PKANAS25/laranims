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

use File;
use Image;
use Validator;

class EmployeesController extends Controller
{
    
    public function index()
    {
       $employees = Employee::where('working_under',Auth::user()->branch)->where('deleted',0)->orderBy('fullname')->get();

       return view('employees.index',compact('employees'));
    }

//----------------------------------------------------------------------------------------------------------------------------------------
    public function profile($employeeId)
    {

        $employeeId = base64_decode($employeeId); 

        $employee = Employee::select('employees.*','nationality.nationality AS nation', 'religion.religion AS rel', 'visa_branch.name AS visa_in' , 'work_branch.name AS working_for')
                            ->leftjoin('nationality','employees.nationality', '=', 'nationality.nation_id')
                            ->leftjoin('religion','employees.religion', '=', 'religion.religion_id')
                            ->leftjoin('branches AS visa_branch','employees.visa_under', '=', 'visa_branch.id')
                            ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                            ->where('employees.employee_id',$employeeId)
                            ->first();

        if($employee->visa_under==-1) $employee->visa_in = "Under Process"; elseif($employee->visa_under==-2) $employee->visa_in = "Spouse"; elseif($employee->visa_under==-3) $employee->visa_in = "Guardian";                    
         
        if($employee->deleted==0) $employee->status = "Active"; elseif($employee->deleted==1) $employee->status = "Deleted"; elseif($employee->deleted==2) $employee->status = "Terminated"; elseif($employee->deleted==3) $employee->status = "Resigned";

        $age = Carbon::parse($employee->date_of_birth)->age;                    
        
        if (File::exists(base_path().'/public/uploads/employee_pics/'.$employeeId.'.jpg'))
            $profile_pic = '/uploads/employee_pics/'.$employeeId.'.jpg' ;

          else
          $profile_pic = '/uploads/student_pics/no_image.jpg';  
          

        return view('employees.profile',compact('employee','profile_pic','age'));                  
    }
//----------------------------------------------------------------------------------------------------------------------------------------    
   
}
