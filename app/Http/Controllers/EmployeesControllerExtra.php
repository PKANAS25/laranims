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

use File;
use Image;
use Validator;

use Mail;
 

class EmployeesControllerExtra extends Controller
{
   
   public function payContentHistory($employeeId,$stuff)
   {
      $employeeId = base64_decode($employeeId);  

      $employee = Employee::where('employees.employee_id',$employeeId)->first();

      if($stuff=='bonus')
      {
      
          $bonuses = DB::table('bonus')
                         ->select('bonus.*','adminer.name AS admn', 'approver.name AS hrm')
                         ->leftjoin('users AS adminer','bonus.admin','=','adminer.id')
                         ->leftjoin('users AS approver','bonus.decided_by','=','approver.id')
                         ->where('emp_id',$employeeId)
                         ->where('absent_correction',0)
                         ->orderBy('dated','DESC')
                         ->get();

            foreach ($bonuses as $bonus) 
            { 
                  if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$bonus->bonus_id.'.jpg'))
                     $bonus->file=1; 
                 
                  else  
                  $bonus->file=0;                  
            }
      } 
     
      elseif($stuff=='deduction')
      {
            $deductions = DB::table('deductions_xtra')
                         ->select('deductions_xtra.*','adminer.name AS admn', 'approver.name AS hrm')
                         ->leftjoin('users AS adminer','deductions_xtra.admin','=','adminer.id')
                         ->leftjoin('users AS approver','deductions_xtra.decided_by','=','approver.id')
                         ->where('emp_id',$employeeId) 
                         ->orderBy('dated','DESC')
                         ->get();

            foreach ($deductions as $deduction) 
            { 
                  if (File::exists(base_path().'/public/uploads/hrx/deduction/'.$deduction->dedXtra_id.'.jpg'))
                     $deduction->file=1; 
                 
                  else  
                  $deduction->file=0;                  
            } 
       }

      elseif($stuff=='loan')
      {
            $loans = DB::table('loans')
                         ->select('loans.*','adminer.name AS admn', 'approver.name AS hrm')
                         ->leftjoin('users AS adminer','loans.admin','=','adminer.id')
                         ->leftjoin('users AS approver','loans.decided_by','=','approver.id')
                         ->where('emp_id',$employeeId) 
                         ->orderBy('payment_date','DESC')
                         ->get();

            foreach ($loans as $loan) 
            { 
                  if (File::exists(base_path().'/public/uploads/hrx/loan/'.$loan->loan_id.'.jpg'))
                     $loan->file=1; 
                 
                  else  
                  $loan->file=0;                  
            } 
       } 

      elseif($stuff=='vacation')
      {
            $vacations = DB::table('vacation')
                         ->select('vacation.*','adminer.name AS admn', 'approver.name AS hrm')
                         ->leftjoin('users AS adminer','vacation.admin','=','adminer.id')
                         ->leftjoin('users AS approver','vacation.decided_by','=','approver.id')
                         ->where('emp_id',$employeeId) 
                         ->orderBy('start_date','DESC')
                         ->get(); 
            
            $paid[-1] = "By cheque(Prepaid)";$paid[0] = "Unpaid"; $paid[1] = "Paid With Salary";

            foreach ($vacations as $vacation) 
            {
               $starter = new Carbon($vacation->start_date); 
               $ender = new Carbon($vacation->end_date);
               $vacation->days = $starter->diffInDays($ender) + 1; 
               $vacation->category = $paid[$vacation->paid];
            }
            
       }    
     

    return view('employees.paymentHistory',compact('employee','stuff','bonuses','deductions','loans','vacations')); 
   }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   public function payrollContentUnapprove(Request $request)
   {
      $stuff = $request->stuff;
       
        switch ($stuff) 
       {
            case "bonus":
            DB::table('bonus')->where('bonus_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
            echo "<i class=\"fa fa-check-square-o\"></i>";
            break;

            case "deduction":
            DB::table('deductions_xtra')->where('dedXtra_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
            echo "<i class=\"fa fa-check-square-o\"></i>";
            break;

            case "loan":
            DB::table('loans')->where('loan_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
            echo "<i class=\"fa fa-check-square-o\"></i>";
            break;

            case "vacation":
            DB::table('vacation')->where('vacation_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
            echo "<i class=\"fa fa-check-square-o\"></i>";
            break;

            default:
             echo "??";
       }
         
   }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

   public function payrollContentDelete(Request $request)
   {  
       $stuff = base64_decode($request->stuff);
       $id = base64_decode($request->Id);
       $employeeId = $request->employeeId;
       $imageName = $id.".jpg";

       switch ($stuff) 
       {
                case "bonus": 
                    DB::table('bonus')->where('bonus_id',$id)->where('approved',0)->delete(); 
                    if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$imageName))
                         File::delete(base_path().'/public/uploads/hrx/bonus/'.$imageName);
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Bonus removed!');  
                    break;

                case "deduction": 
                    DB::table('deductions_xtra')->where('dedXtra_id',$id)->where('approved',0)->delete(); 
                    if (File::exists(base_path().'/public/uploads/hrx/deduction/'.$imageName))
                         File::delete(base_path().'/public/uploads/hrx/deduction/'.$imageName);
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Deduction removed!');  
                    break;

                case "loan": 
                    DB::table('loans')->where('loan_id',$id)->where('approved',0)->delete(); 
                    if (File::exists(base_path().'/public/uploads/hrx/loan/'.$imageName))
                         File::delete(base_path().'/public/uploads/hrx/loan/'.$imageName);
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Loan removed!');  
                    break;

                
                case "vacation": 
                    DB::table('vacation')->where('vacation_id',$id)->where('approved',0)->delete(); 
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Vacation removed!');  
                    break;                   
                
                default:
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('warningStatus', 'Something wrong happened!');  
        }
   }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     public function uploadFormHRXDoc($doc,$id,$employeeId, Request $request)
     {
      return view('employees.uploadHrxDocument')->with('type',base64_decode($doc));
     }

     public function uploadHRXDoc($doc,$Id,$employeeId, Request $request)
     {
        $id = base64_decode($Id); 
        $doc = base64_decode($doc); 

        $imageName = $id.'.jpg';

        $validator = Validator::make($request->all(),[
            'fileToUpload'=>'required|image|max:615|mimes:jpeg,jpg',
         ]    
        );

        
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {
            

            switch ($doc) 
               {
                        case "bonus":
                           if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$imageName))
                               File::delete(base_path().'/public/uploads/hrx/bonus/'.$imageName);  
                            $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/bonus/', $imageName); 

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',$employeeId)->with('status', 'Bonus document uploaded!');
                            break;

                        case "deduction":
                           if (File::exists(base_path().'/public/uploads/hrx/deduction/'.$imageName))
                               File::delete(base_path().'/public/uploads/hrx/deduction/'.$imageName);  
                            $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/deduction/', $imageName); 

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$doc])->with('status', 'Deduction document uploaded!');
                            break;

                        case "loan":
                           if (File::exists(base_path().'/public/uploads/hrx/loan/'.$imageName))
                               File::delete(base_path().'/public/uploads/hrx/loan/'.$imageName);  
                            $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/loan/', $imageName); 

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$doc])->with('status', 'Loan document uploaded!');
                            break;          

                        
                        default:
                            return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$doc])->with('warningStatus', 'Something wrong happened!');  
                } 
        }

         
     } 
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function addBonus($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         return view('employees.addBonus',compact('employee'));    
    } 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function saveBonus($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 

         $this->validate($request, [
        'dated' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'notes' => 'required',]); 

        DB::table('bonus')->insert(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $request->notes, 'admin' => Auth::id()]);  

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Bonus Added!');  
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  

    public function addDeduction($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         return view('employees.addDeduction',compact('employee'));    
    } 

    public function saveDeduction($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 

         $this->validate($request, [
        'dated' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'reason' => 'required',]); 

        DB::table('deductions_xtra')->insert(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'reason' => $request->reason, 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $request->notes, 'admin' => Auth::id()]);  

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Deduction Added!');  
    } 
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

     public function addLoan($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         return view('employees.addLoan',compact('employee'));    
    } 

    public function saveLoan($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 

         $this->validate($request, [
        'payment_date' => 'required|date_format:Y-m-d',
        'deduction_start' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'max_rounds' => 'required|numeric',]); 

        DB::table('loans')->insert(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'payment_date' => $request->payment_date, 'deduction_start' => $request->deduction_start, 
            'loaned_amount' => $request->amount, 'deduction_amount' => $request->per_round, 'admin' => Auth::id()]);  

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Loan Added!');  
    } 
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function addVacation($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         return view('employees.addVacation',compact('employee'));    
    } 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
    public function saveVacation($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 

         $this->validate($request, [
        'starter' => 'required|date_format:Y-m-d',
        'ender' => 'required|date_format:Y-m-d',
        'paid' => 'required',]); 

         $count = DB::table('vacation')->where('end_date','>=',$request->starter)->where('start_date','<=',$request->ender)->where('emp_id',$employeeId)->count();
         
         if($count==0 && $request->starter<=$request->ender)
         {
            DB::table('vacation')->insert(['emp_id' => $employeeId, 'start_date' => $request->starter, 'end_date' => $request->ender, 'paid' => $request->paid, 'admin' => Auth::id()]);  

            return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Vacation Added!'); 
         }
         else
           return redirect()->back()->withErrors('Duplication error. Try again')->withInput();

          
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
    public function employeeVacationCheck(Request $request)
    {
        $starter = $request->get('starter');  
        $ender = $request->get('ender');
        $employeeId = $request->get('employeeId');

        $count = 1; 
        $count = DB::table('vacation')->where('end_date','>=',$starter)->where('start_date','<=',$ender)->where('emp_id',$employeeId)->count();
            

        if($count || $starter>$ender)
        return response()->json(['valid' => 'false', 'message' => 'Date Conflicts. Vacation saved in the given date range','available'=>'false']);

        else
        {
          $start = new Carbon($starter);  $end = new Carbon($ender); $days=$start->diffInDays($end) + 1;
          return response()->json(['valid' => 'true', 'message' => $days.' days','available'=>'true']);   
        }
             
    }  
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function addSicks($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         
         $now = Carbon::now();
         $joining_date = new Carbon($employee->joining_date);
         $currentYearStart = new Carbon($employee->joining_date); 
         $currentYearEnd = new Carbon($employee->joining_date); 

         $workedDays = $joining_date->diffInDays($now) + 1;

         if($workedDays > 90)
           {
            $yearScale =   intval($workedDays/364);  
            
            $currentYearStart->addYears($yearScale);
 
            $currentYearEnd->addYears($yearScale+1);  
            $currentYearEnd->subDays(1);

            $currentYearStart=$currentYearStart->toDateString();
            $currentYearEnd=$currentYearEnd->toDateString();

           
            $sickFull = DB::table('staff_attendance')
                           ->where('attendance_type',2)
                           ->whereBetween('dated',[$currentYearStart,$currentYearEnd])
                           ->where('employee_id',$employeeId)
                           ->count();

            $sickHalf = DB::table('staff_attendance')
                           ->where('attendance_type',10)
                           ->whereBetween('dated',[$currentYearStart,$currentYearEnd])
                           ->where('employee_id',$employeeId)
                           ->count();
            //echo $joining_date." current start".$currentYearStart." current end".$currentYearEnd;
           }
         
         return view('employees.addSicks',compact('employee','currentYearStart','currentYearEnd','sickFull','sickHalf'));    
    }  

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function saveSicks($employeeId)
    {
    }       
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
} 