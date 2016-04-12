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

            return view('employees.paymentHistory',compact('employee','stuff','bonuses')); 
      }

      if($stuff=='absentCorrection')
      {
      
          $bonuses = DB::table('bonus')
                         ->select('bonus.*','adminer.name AS admn', 'approver.name AS hrm')
                         ->leftjoin('users AS adminer','bonus.admin','=','adminer.id')
                         ->leftjoin('users AS approver','bonus.decided_by','=','approver.id')
                         ->where('emp_id',$employeeId)
                         ->where('absent_correction',1)
                         ->orderBy('dated','DESC')
                         ->get();

            foreach ($bonuses as $bonus) 
            { 
                  if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$bonus->bonus_id.'.jpg'))
                     $bonus->file=1; 
                 
                  else  
                  $bonus->file=0;                  
            }

            return view('employees.paymentHistory',compact('employee','stuff','bonuses')); 
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

            return view('employees.paymentHistory',compact('employee','stuff','deductions')); 
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

            return view('employees.paymentHistory',compact('employee','stuff','loans')); 
       }

      elseif($stuff=='personal benefits')
      {
      
          $benefits = DB::table('personal_benefits')
                         ->select('personal_benefits.*','adminer.name AS admn', 'approver.name AS hrm')
                         ->leftjoin('users AS adminer','personal_benefits.admin','=','adminer.id')
                         ->leftjoin('users AS approver','personal_benefits.decided_by','=','approver.id')
                         ->where('emp_id',$employeeId) 
                         ->orderBy('benefit_start','DESC')
                         ->get();

            foreach ($benefits as $benefit) 
            { 
                  if (File::exists(base_path().'/public/uploads/hrx/benefit/'.$benefit->benefit_id.'.jpg'))
                     $benefit->file=1; 
                 
                  else  
                  $benefit->file=0;                  
            }

            return view('employees.paymentHistory',compact('employee','stuff','benefits')); 
      }

      elseif($stuff=='overtime')
      {
      
          $overtimes = DB::table('over_time')
                         ->select('over_time.*','adminer.name AS admn', 'approver.name AS hrm')
                         ->leftjoin('users AS adminer','over_time.admin','=','adminer.id')
                         ->leftjoin('users AS approver','over_time.decided_by','=','approver.id')
                         ->where('emp_id',$employeeId) 
                         ->orderBy('dated','DESC')
                         ->get();

            foreach($overtimes as $overtime) 
            { 
                  if (File::exists(base_path().'/public/uploads/hrx/overtime/'.$overtime->over_id.'.jpg'))
                     $overtime->file=1; 
                 
                  else  
                  $overtime->file=0;                  
            }

            return view('employees.paymentHistory',compact('employee','stuff','overtimes')); 
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

            return view('employees.paymentHistory',compact('employee','stuff','vacations','expenses'));             
       }    
      
      elseif($stuff=='expenses')
      {
            $expenses = DB::table('employee_expenses')
                         ->select('employee_expenses.*','users.name AS pro','pro_companies.company AS service_from')
                         ->leftjoin('users','employee_expenses.pro_id','=','users.id')
                         ->leftjoin('pro_companies','employee_expenses.company','=','pro_companies.id')
                         ->where('employee_id',$employeeId) 
                         ->orderBy('dated_on','DESC')
                         ->get();  

            return view('employees.paymentHistory',compact('employee','stuff','expenses'));              
       }

    
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

            case "personal benefits":
            DB::table('personal_benefits')->where('benefit_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
            echo "<i class=\"fa fa-check-square-o\"></i>";
            break;

            case "overtime":
            DB::table('over_time')->where('over_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
            echo "<i class=\"fa fa-check-square-o\"></i>";
            break;

            case "vacation":
            DB::table('vacation')->where('vacation_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
            echo "<i class=\"fa fa-check-square-o\"></i>";
            break;

            case "expenses":
            DB::table('employee_expenses')->where('id',$request->id)->update(['locked'=>0,'decided_by'=>0]);
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

                case "absentCorrection": 
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

                case "personal benefits": 
                    DB::table('personal_benefits')->where('benefit_id',$id)->where('approved',0)->delete(); 
                    if (File::exists(base_path().'/public/uploads/hrx/benefit/'.$imageName))
                         File::delete(base_path().'/public/uploads/hrx/benefit/'.$imageName);
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Benefit removed!');  
                    break;

               case "overtime": 
                    DB::table('over_time')->where('over_id',$id)->where('approved',0)->delete(); 
                    if (File::exists(base_path().'/public/uploads/hrx/overtime/'.$imageName))
                         File::delete(base_path().'/public/uploads/hrx/overtime/'.$imageName);
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Overtime removed!');  
                    break;

                case "vacation": 
                    DB::table('vacation')->where('vacation_id',$id)->where('approved',0)->delete(); 
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Vacation removed!');  

                 case "expenses": 
                    DB::table('employee_expenses')->where('id',$id)->where('locked',0)->where('pro_id',Auth::id())->delete(); 
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$stuff])->with('status', 'Expense removed!');       
                
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

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$doc])->with('status', 'Bonus document uploaded!');
                            break;

                        case "absentCorrection":
                           if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$imageName))
                               File::delete(base_path().'/public/uploads/hrx/bonus/'.$imageName);  
                            $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/bonus/', $imageName); 

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$doc])->with('status', 'Bonus document uploaded!');
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

                        case "personal benefits":
                           if (File::exists(base_path().'/public/uploads/hrx/benefit/'.$imageName))
                               File::delete(base_path().'/public/uploads/hrx/benefit/'.$imageName);  
                            $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/benefit/', $imageName); 

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$doc])->with('status', 'Personal benefit document uploaded!');
                            break;             

                      case "overtime":
                           if (File::exists(base_path().'/public/uploads/hrx/overtime/'.$imageName))
                               File::delete(base_path().'/public/uploads/hrx/overtime/'.$imageName);  
                            $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/overtime/', $imageName); 

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',[$employeeId,$doc])->with('status', 'Overtime document uploaded!');
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
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         return view('employees.addBonus',compact('employee'));    
    } 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function saveBonus($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 

         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $this->validate($request, [
        'dated' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'notes' => 'required',
        'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',]); 

        $id = DB::table('bonus')->insertGetId(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $request->notes, 'admin' => Auth::id()]);  

        $imageName = $id.'.jpg';
        if($request->file('fileToUpload'))
        $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/bonus/', $imageName); 

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Bonus Added!');  
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  

    public function addDeduction($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         return view('employees.addDeduction',compact('employee'));    
    } 

    public function saveDeduction($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $this->validate($request, [
        'dated' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'reason' => 'required',
        'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',]); 

        $id = DB::table('deductions_xtra')->insertGetId(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'reason' => $request->reason, 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $request->notes, 'admin' => Auth::id()]);  

        $imageName = $id.'.jpg';
        if($request->file('fileToUpload'))
        $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/deduction/', $imageName); 

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Deduction Added!');  
    } 
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

     public function addLoan($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         return view('employees.addLoan',compact('employee'));    
    } 

    public function saveLoan($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $this->validate($request, [
        'payment_date' => 'required|date_format:Y-m-d',
        'deduction_start' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'max_rounds' => 'required|numeric',
        'per_round' => 'required|numeric|min:1',
        'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',]); 

       $id = DB::table('loans')->insertGetId(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'payment_date' => $request->payment_date, 'deduction_start' => $request->deduction_start, 'loaned_amount' => $request->amount, 'deduction_amount' => $request->per_round, 'admin' => Auth::id()]);  

       $imageName = $id.'.jpg';
        if($request->file('fileToUpload'))
        $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/loan/', $imageName); 

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Loan Added!');  
    } 

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  

    public function addBenefit($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         return view('employees.addBenefit',compact('employee'));    
    } 

    public function saveBenefit($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $this->validate($request, [
        'benefit' => 'required', 
        'benefit_start' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',
        'max_rounds' => 'required|numeric',
        'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',]); 

        $id = DB::table('personal_benefits')->insertGetId(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'benefit_start' => $request->benefit_start, 'benefit' => $request->benefit, 'amount' => $request->amount, 'max_rounds' => $request->max_rounds, 'admin' => Auth::id()]);  

        $imageName = $id.'.jpg';
        if($request->file('fileToUpload'))
        $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/benefit/', $imageName); 

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Benefit Added!');  
    } 

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function addVacation($employeeId)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         return view('employees.addVacation',compact('employee'));    
    } 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
    public function saveVacation($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

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
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 
         
         $now = Carbon::now();
         $joining_date = new Carbon($employee->joining_date);
         $currentYearStart = new Carbon($employee->joining_date); 
         $currentYearEnd = new Carbon($employee->joining_date); 

         $sickFull = $sickHalf = 0;

         $workedDays = $joining_date->diffInDays($now) + 1;

         if($workedDays > 90)
           {
            $yearScale =   intval($workedDays/366);  
            
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
             //echo "year scale: ".$yearScale."workedDays: ".$workedDays." joining_date: ".$joining_date." current start".$currentYearStart." current end".$currentYearEnd."<br>";
           }
         
         return view('employees.addSicks',compact('employee','currentYearStart','currentYearEnd','sickFull','sickHalf','workedDays'));    
    }  

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function saveSicks($employeeId,Request $request)
    {
       $startDate = $request->starter;
       $endDate = $request->ender;

       $employeeId = base64_decode($employeeId); 
       $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

       
       $joining_date = new Carbon($employee->joining_date);
       $startDate = new Carbon($startDate);  
       $endDate = new Carbon($endDate); 
       

       $dated = $startDate;

       $inserted = 0;

       $workedDays = $joining_date->diffInDays($startDate) + 1;

       if($workedDays>90)
       {
        while($dated<=$endDate)   
         {
          $workedDays = $joining_date->diffInDays($dated) + 1;

          $currentYearStart = new Carbon($employee->joining_date); 
          $currentYearEnd = new Carbon($employee->joining_date); 

          $exists = DB::table('staff_attendance')
                      ->where('dated',$dated->toDateString())
                      ->count();

          if(!$exists)
          { 
              $yearScale =   intval($workedDays/366);  
            
              $currentYearStart->addYears($yearScale);
   
              $currentYearEnd->addYears($yearScale+1);  
              $currentYearEnd->subDays(1);

              $currentYearStart=$currentYearStart->toDateString();
              $currentYearEnd=$currentYearEnd->toDateString();
              
              //echo "year scale: ".$yearScale." dated: ".$dated."workedDays: ".$workedDays." joining_date: ".$joining_date." current start".$currentYearStart." current end".$currentYearEnd."<br>";
              
              $sickFull = DB::table('staff_attendance')
                           ->where('attendance_type',2)
                           ->whereBetween('dated',[$currentYearStart,$currentYearEnd])
                           ->where('employee_id',$employeeId)
                           ->count();
                            
              if($sickFull<15) 
              {
                 DB::table('staff_attendance')->insert(['dated' => $dated->toDateString(), 'employee_id' => $employeeId, 'attendance_type' => 2, 'admin' => Auth::id()]);   
                 $inserted=1;
              }
              
              elseif($sickFull>=15)  
              {
                $sickHalf = DB::table('staff_attendance')
                           ->where('attendance_type',10)
                           ->whereBetween('dated',[$currentYearStart,$currentYearEnd])
                           ->where('employee_id',$employeeId)
                           ->count();
                
                if($sickHalf<30)
                {
                   DB::table('staff_attendance')->insert(['dated' => $dated->toDateString(), 'employee_id' => $employeeId, 'attendance_type' => 10, 'admin' => Auth::id()]);
                   $inserted=1; 
                }

              }//elseif($sickFull>=15)  

          }//if(!$exists)            

          $dated->addDays(1);
         }//while($dated<=$end_date)
         if($inserted)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Sick Leaves Added! Please confirm with attendance report'); 
         else
          return redirect()->back()->withErrors('Something went wrong. Please check this employee\'s previous data and sick leave qualification')->withInput();
       }//if($workedDays>90)
       return redirect()->back()->withErrors('Something went wrong. Please check this employee\'s previous data and sick leave qualification')->withInput();
    }     

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function addMaternity($employeeId)
    {
         $checked=0;
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $joiningDate = new Carbon($employee->joining_date);
         $now = Carbon::now();
         $months =  $now->diffInMonths($joiningDate);   

         return view('employees.addMaternity',compact('employee','months','checked'));    
    } 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function checkMaternity($employeeId,Request $request)
    {
         $checked=1;
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();

         $dated = new Carbon($request->dated);

         $joiningDate = new Carbon($employee->joining_date); 
         $months =  $dated->diffInMonths($joiningDate);   

         $salary = EmployeesSalary::where('employee_id',$employeeId)->first();
         $total = $salary->basic + $salary->accomodation + $salary->travel + $salary->other;
         $daySalary = $total/30;

         $previous = DB::table('bonus')
                       ->where('notes','Maternity Leave Bonus')
                       ->where('approved','!=',-1)
                       ->where('emp_id',$employeeId)
                       ->max('dated');

         if($previous) 
         {
          $previous = new Carbon($previous); 
          $monthdDiffPaid = $dated->diffInMonths($previous);   
         }
         else $monthdDiffPaid = 12;
      
       $dated = $dated->toDateString();

         return view('employees.addMaternity',compact('employee','months','monthdDiffPaid','checked','dated','daySalary'));  
    
    } 
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
      public function saveMaternity($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $this->validate($request, [
        'dated' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',]); 

         $notes = 'Maternity Leave Bonus';

         $previous = DB::table('bonus')
                       ->where('notes','Maternity Leave Bonus')
                       ->where('approved','!=',-1)
                       ->where('emp_id',$employeeId)
                       ->max('dated');

         if($previous) 
         {
          $previous = new Carbon($previous); 
          $bonus_date = new Carbon($request->dated);
          $monthdDiffPaid = $bonus_date->diffInMonths($previous);  
        }
        else $monthdDiffPaid = 12;
        

        if($monthdDiffPaid>=12)
        {
          DB::table('bonus')->insert(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $notes, 'admin' => Auth::id()]);  

          return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Maternity Bonus Added!'); 
        }
        else
          return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'Date manipulations found. Exit with error');
         
    }

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function absentCorrection($employeeId)
    { 
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         return view('employees.absentCorrection',compact('employee'));    
    } 

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function absentCorrectionCheck(Request $request)
    {
        $starter = $request->get('starter');  
        $ender = $request->get('ender');
        $employeeId = $request->get('employeeId');

        
        $count = 1; 
        $count = DB::table('bonus')->where('end_date','>=',$starter)->where('start_date','<=',$ender)->where('emp_id',$employeeId)->where('approved','!=',-1)->count();
            

        if($count || $starter>$ender)
        return response()->json(['valid' => 'false', 'message' => 'Date Conflicts. Please check previous absent correction bonus','available'=>'false']);

        else
         return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);

         
             
    }  
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function absentCorrectionSave($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $this->validate($request, [
        'starter' => 'required|date_format:Y-m-d',
        'ender' => 'required|date_format:Y-m-d',
        'dated' => 'required|date_format:Y-m-d',
        'amount' => 'required|numeric',]); 

         $count = DB::table('bonus')->where('end_date','>=',$request->starter)->where('start_date','<=',$request->ender)->where('emp_id',$employeeId)->where('approved','!=',-1)->count();
         
         if($count==0 && $request->starter<=$request->ender)
         {
            DB::table('bonus')->insert(['emp_id' => $employeeId, 'entry_date' => Carbon::now(), 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $request->notes, 'absent_correction' => 1, 'start_date' => $request->starter, 'end_date' => $request->ender, 'admin' => Auth::id()]);  

           return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Absent correction bonus added!');  
         }

         else
           return redirect()->back()->withErrors('Something went wrong. Try again')->withInput();

          
    }

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   public function proPayment($employeeId)
    { 
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!');  

         $services = DB::table('employee_pro_services')
                           ->where('removed',0)
                           ->orderBy('service')
                           ->get();
         
         $companies = DB::table('pro_companies')
                           ->where('deleted',0)
                           ->orderBy('company')
                           ->get();                           

         return view('employees.addProPayment',compact('employee','services','companies'));    
    } 

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
    public function employeeExpenseCheck(Request $request)
    {
        $company = $request->get('company');  
        $bill_no = $request->get('bill_no');
        $employeeId = $request->get('employeeId');

        $count = $count2 = 1; 
        
        $count = DB::table('employee_expenses')->where('company',$company)->where('bill_no',$bill_no)->count();
        $count2 = DB::table('company_expenses')->where('company',$company)->where('bill_no',$bill_no)->count();
            

        if($count || $count2)
        return response()->json(['valid' => 'false', 'message' => 'Duplicate bill','available'=>'false']);

        else 
          return response()->json(['valid' => 'true', 'message' => '  ','available'=>'true']);   
         
             
    }  
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

   public function proPaymentSave($employeeId,Request $request)
    {
         $employeeId = base64_decode($employeeId); 
         $employee = Employee::where('employee_id',$employeeId)->first();
         if($employee->deleted!=0)
         return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'This employee is not active!'); 

         $this->validate($request, [
        'being' => 'required',
        'company' => 'required',
        'amount' => 'required|numeric',  
        'bill_no' => 'required',      
        'bill_date' => 'required|date_format:Y-m-d',]); 

         
        $count = $count2 = 1; 
        
        $count = DB::table('employee_expenses')->where('company',$request->company)->where('bill_no',$request->bill_no)->count();
        $count2 = DB::table('company_expenses')->where('company',$request->company)->where('bill_no',$request->bill_no)->count();
         
         if($count==0 && $count2==0)
         { 
            DB::table('employee_expenses')->insert(['being' => $request->being, 'dated_on' => Carbon::now(), 'employee_id' => $employeeId, 'amount' => $request->amount, 'company' => $request->company, 'bill_no' => $request->bill_no, 'bill_date' => $request->bill_date, 'notes' => $request->notes, 'pro_id' => Auth::id()]);  

           return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Employee expense added!');  
         }

         else
           return redirect()->back()->withErrors('Something went wrong. Try again')->withInput();

          
    }

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
} 

 