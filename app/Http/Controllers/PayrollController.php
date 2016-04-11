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
use App\Nationality; 


use File;
use Image;
use Validator;

use Mail;

class PayrollController extends Controller
{
    public function salaryVerification()
    {
        $team = DB::table('salary_verification_team')->where('team_id',1)->first(); 
        $teamUsers = DB::table('users')->whereIn('id', [$team->verifier1, $team->verifier2,$team->verifier3])->get(); 

        $employees = Employee::select('employees.*','employees_salary.basic','employees_salary.travel','employees_salary.accomodation', 'employees_salary.other','employees_salary.iban','employees_salary.verification1','employees_salary.verification2','employees_salary.verification3','employees_salary.reason1','employees_salary.reason2','employees_salary.reason3','employees_salary.edit_reason' ,'employees_salary.labour_card_under',  'lc_branch.name AS lc_in' , 'work_branch.name AS working_for')
                            ->leftjoin('employees_salary','employees.employee_id', '=', 'employees_salary.employee_id')
                            ->leftjoin('branches AS lc_branch','employees_salary.labour_card_under', '=', 'lc_branch.id')
                            ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                            ->where('employees.deleted',0)
                             ->where(function($query){
                                     $query->orWhere('employees_salary.verification1','!=','1');
                                     $query->orWhere('employees_salary.verification2','!=','1');
                                     $query->orWhere('employees_salary.verification3','!=','1');
                                     $query->orWhereNotExists(function ($query){
                                      $query->select(DB::raw('*'))
                                      ->from('employees_salary')
                                      ->whereRaw('employees_salary.employee_id = employees.employee_id');
                             }); 
                             }) 
                             
                             ->orderBy('employees.working_under')                           
                            ->get();

        return view('payroll.salaryVerification',compact('employees','team','teamUsers'));

    } 

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
   public function verifySalary(Request $request)
   {
      $employeeId = $request->employee;
      $verifier = $request->verifier;  
      $action = $request->action;
      $reason = $request->reason;  

       $salary = EmployeesSalary::where('employee_id',$employeeId)->first();
       $team = DB::table('salary_verification_team')->where('team_id',1)->first();  

      if($action==1)
      { 
         if($team->{'verifier'.$verifier}==Auth::id())
         { 
             $salary->{'verification'.$verifier}=1;
             $salary->save();

            echo "<i class=\"fa fa-check-circle-o  text-success\"></i>";
         }
      }

      elseif($action==-1 && $reason)
      {
        $salary->{'verification'.$verifier}=-1;
        $salary->{'reason'.$verifier}=$reason;
        $salary->save();

        echo "<i class=\"fa fa-minus-circle text-danger \"></i>";
      }
   }

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
   public function pendingApprovals(Request $request)
   { 
     $working_under = $request->branch;
     $branches = Branch::orderBy('non_nursery','asc')->orderBy('name')->get();

      if($working_under)
      {
        $bonuses = DB::table('bonus')
                         ->select('bonus.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','bonus.admin','=','users.id')
                         ->leftjoin('employees','bonus.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->where('employees.working_under',$working_under)  
                         ->orderBy('dated','DESC')
                         ->get(); 

      }

      else
      {

        $bonuses = DB::table('bonus')
                         ->select('bonus.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','bonus.admin','=','users.id')
                         ->leftjoin('employees','bonus.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0)                            
                         ->orderBy('employees.working_under')
                         ->orderBy('dated','DESC')
                         ->get(); 

      }
      

     return view('payroll.salaryApprovals',compact('branches','working_under','bonuses'));
   }

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
   public function approvePayrollContent(Request $request)
   {
      $id = $request->id;
      $item = $request->item;  
      $action = $request->action;
      $reason = $request->reason;  

        
      if($action==1 && $item=='bonus')
      { 
         DB::table('bonus')->where('bonus_id',$id)->update(['approved'=>1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now()]);
         echo "<i class=\"fa fa-check-circle-o  text-success\"></i>";
      }

      else if($action==-1 && $item=='bonus' && $reason)
      { 
         DB::table('bonus')->where('bonus_id',$id)->update(['approved'=>-1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now(),'reject_reason'=>$reason,'reject_read'=>0]);
         echo "<i class=\"fa fa-minus-circle text-danger \"></i>";
      }

      
   }


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
}


 