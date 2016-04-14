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

use App\Payroll;


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
                         foreach ($bonuses as $bonus) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$bonus->bonus_id.'.jpg'))
                                         $bonus->file=1; 
                                     
                                      else  
                                      $bonus->file=0;                  
                                }

        $deductions = DB::table('deductions_xtra')
                         ->select('deductions_xtra.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','deductions_xtra.admin','=','users.id')
                         ->leftjoin('employees','deductions_xtra.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->where('employees.working_under',$working_under)  
                         ->orderBy('dated','DESC')
                         ->get(); 

                         foreach ($deductions as $deduction) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/deduction/'.$deduction->dedXtra_id.'.jpg'))
                                         $deduction->file=1; 
                                     
                                      else  
                                      $deduction->file=0;                  
                                } 

        $loans = DB::table('loans')
                         ->select('loans.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','loans.admin','=','users.id')
                         ->leftjoin('employees','loans.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->where('employees.working_under',$working_under)  
                         ->orderBy('payment_date','DESC')
                         ->get();  

                         foreach ($loans as $loan) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/loan/'.$loan->loan_id.'.jpg'))
                                         $loan->file=1; 
                                     
                                      else  
                                      $loan->file=0;                  
                                } 

        $overtimes = DB::table('over_time')
                         ->select('over_time.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','over_time.admin','=','users.id')
                         ->leftjoin('employees','over_time.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->where('employees.working_under',$working_under)  
                         ->orderBy('dated','DESC')
                         ->get();

                         foreach($overtimes as $overtime) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/overtime/'.$overtime->over_id.'.jpg'))
                                         $overtime->file=1; 
                                     
                                      else  
                                      $overtime->file=0;                  
                                }   

        $benefits = DB::table('personal_benefits')
                         ->select('personal_benefits.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','personal_benefits.admin','=','users.id')
                         ->leftjoin('employees','personal_benefits.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->where('employees.working_under',$working_under)  
                         ->orderBy('benefit_start','DESC')
                         ->get(); 

                        foreach ($benefits as $benefit) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/benefit/'.$benefit->benefit_id.'.jpg'))
                                         $benefit->file=1; 
                                     
                                      else  
                                      $benefit->file=0;                  
                                } 

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

                         foreach ($bonuses as $bonus) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$bonus->bonus_id.'.jpg'))
                                         $bonus->file=1; 
                                     
                                      else  
                                      $bonus->file=0;                  
                                }

        $deductions = DB::table('deductions_xtra')
                         ->select('deductions_xtra.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','deductions_xtra.admin','=','users.id')
                         ->leftjoin('employees','deductions_xtra.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->orderBy('employees.working_under') 
                         ->orderBy('dated','DESC')
                         ->get(); 

                         foreach ($deductions as $deduction) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/deduction/'.$deduction->dedXtra_id.'.jpg'))
                                         $deduction->file=1; 
                                     
                                      else  
                                      $deduction->file=0;                  
                                }

        $loans = DB::table('loans')
                         ->select('loans.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','loans.admin','=','users.id')
                         ->leftjoin('employees','loans.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->orderBy('employees.working_under')  
                         ->orderBy('payment_date','DESC')
                         ->get(); 

                         foreach ($loans as $loan) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/loan/'.$loan->loan_id.'.jpg'))
                                         $loan->file=1; 
                                     
                                      else  
                                      $loan->file=0;                  
                                } 

        $overtimes = DB::table('over_time')
                         ->select('over_time.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','over_time.admin','=','users.id')
                         ->leftjoin('employees','over_time.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->orderBy('employees.working_under')
                         ->orderBy('dated','DESC')
                         ->get(); 

                         foreach($overtimes as $overtime) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/overtime/'.$overtime->over_id.'.jpg'))
                                         $overtime->file=1; 
                                     
                                      else  
                                      $overtime->file=0;                  
                                } 

        $benefits = DB::table('personal_benefits')
                         ->select('personal_benefits.*','users.name AS admn', 'employees.employee_id', 'employees.fullname', 'employees.working_under','branches.name AS branch_name')
                         ->leftjoin('users','personal_benefits.admin','=','users.id')
                         ->leftjoin('employees','personal_benefits.emp_id','=','employees.employee_id')
                         ->leftjoin('branches','branches.id','=','employees.working_under')
                         ->where('approved',0) 
                         ->orderBy('employees.working_under')  
                         ->orderBy('benefit_start','DESC')
                         ->get(); 

                         foreach ($benefits as $benefit) 
                                { 
                                      if (File::exists(base_path().'/public/uploads/hrx/benefit/'.$benefit->benefit_id.'.jpg'))
                                         $benefit->file=1; 
                                     
                                      else  
                                      $benefit->file=0;                  
                                }    
 
                                                                                          
        $payrolls = Payroll::select('payroll.*', 'branches.name', 'users.name AS acc')
                           ->leftjoin('branches','branches.id','=','payroll.company_id')
                           ->leftjoin('users','payroll.accountant','=','users.id')
                           ->where('approved',0) 
                           ->get(); 
      }
      

     return view('payroll.salaryApprovals',compact('branches','working_under','bonuses','deductions','loans','overtimes','benefits','payrolls'));
   }

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
   public function approvePayrollContent(Request $request)
   {
      $id = $request->id;
      $item = $request->item;  
      $action = $request->action;
      $reason = $request->reason;  

        
      if($item=='bonus')
      { 
        if($action==1)
        {
           DB::table('bonus')->where('bonus_id',$id)->update(['approved'=>1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now()]);
           echo "<i class=\"fa fa-check-circle-o  text-success\"></i>"; 
        }

        else if($action==-1 && $reason)
        { 
           DB::table('bonus')->where('bonus_id',$id)->update(['approved'=>-1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now(),'reject_reason'=>$reason,'reject_read'=>0]);
           echo "<i class=\"fa fa-minus-circle text-danger \"></i>";
        } 
      }

      elseif($item=='deduction')
      { 
        if($action==1)
        {
           DB::table('deductions_xtra')->where('dedXtra_id',$id)->update(['approved'=>1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now()]);
           echo "<i class=\"fa fa-check-circle-o  text-success\"></i>"; 
        }

        else if($action==-1 && $reason)
        { 
           DB::table('deductions_xtra')->where('dedXtra_id',$id)->update(['approved'=>-1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now(),'reject_reason'=>$reason,'reject_read'=>0]);
           echo "<i class=\"fa fa-minus-circle text-danger \"></i>";
        } 
      }

      elseif($item=='loan')
      { 
        if($action==1)
        {
           DB::table('loans')->where('loan_id',$id)->update(['approved'=>1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now()]);
           echo "<i class=\"fa fa-check-circle-o  text-success\"></i>"; 
        }

        else if($action==-1 && $reason)
        { 
           DB::table('loans')->where('loan_id',$id)->update(['approved'=>-1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now(),'reject_reason'=>$reason,'reject_read'=>0]);
           echo "<i class=\"fa fa-minus-circle text-danger \"></i>";
        } 
      }


      elseif($item=='overtime')
      { 
        if($action==1)
        {
           DB::table('over_time')->where('over_id',$id)->update(['approved'=>1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now()]);
           echo "<i class=\"fa fa-check-circle-o  text-success\"></i>"; 
        }

        else if($action==-1 && $reason)
        { 
           DB::table('over_time')->where('over_id',$id)->update(['approved'=>-1,'decided_by'=>Auth::id(),'approved_date'=>Carbon::now(),'reject_reason'=>$reason,'reject_read'=>0]);
           echo "<i class=\"fa fa-minus-circle text-danger \"></i>";
        } 
      }

      elseif($item=='payroll')
      { 
        if($action==1)
        {
           Payroll::where('payroll_id',$id)->update(['approved' => 1,'decided_by' => Auth::id(),'locked'=> 1]);
 
           echo "<i class=\"fa fa-check-circle-o  text-success\"></i>"; 
        } 
      } 
   

   }


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
}


 