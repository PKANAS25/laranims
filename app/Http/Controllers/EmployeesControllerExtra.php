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
   
   public function payContentHistory($employeeId)
   {
      $employeeId = base64_decode($employeeId);  

      $employee = Employee::where('employees.employee_id',$employeeId)->first();

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

    return view('employees.paymentHistory',compact('employee','bonuses','deductions')); 
   }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   public function payrollContentUnapprove(Request $request)
   {
      if($request->stuff=='bonus')
      {
        DB::table('bonus')->where('bonus_id',$request->id)->update(['approved'=>0,'decided_by'=>0]);
        echo "<i class=\"fa fa-check-square-o\"></i>";
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
                    //echo $stuff."<br>".$id;
                    DB::table('bonus')->where('bonus_id',$id)->where('approved',0)->delete(); 
                    if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$imageName))
                         File::delete(base_path().'/public/uploads/hrx/bonus/'.$imageName);
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',$employeeId)->with('status', 'Bonus removed!');  
                    break;

                
                default:
                    return redirect()->action('EmployeesControllerExtra@payContentHistory',$employeeId)->with('warningStatus', 'Something wrong happened!');  
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

                            return redirect()->action('EmployeesControllerExtra@payContentHistory',$employeeId)->with('status', 'Deduction document uploaded!');
                            break;    

                        
                        default:
                            return redirect()->action('EmployeesControllerExtra@payContentHistory',$employeeId)->with('warningStatus', 'Something wrong happened!');  
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

        DB::table('bonus')->insert(['emp_id' => $employeeId, 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $request->notes, 'admin' => Auth::id()]);  

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

        DB::table('deductions_xtra')->insert(['emp_id' => $employeeId, 'reason' => $request->reason, 'dated' => $request->dated, 'amount' => $request->amount, 'notes' => $request->notes, 'admin' => Auth::id()]);  

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

        DB::table('loans')->insert(['emp_id' => $employeeId, 'payment_date' => $request->payment_date, 'deduction_start' => $request->deduction_start, 
            'loaned_amount' => $request->amount, 'deduction_amount' => $request->per_round, 'admin' => Auth::id()]);  

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Loan Added!');  
    } 
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
} 