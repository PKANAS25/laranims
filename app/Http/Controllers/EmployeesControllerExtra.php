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
    public function uploadBonus($bonusId, $employeeId, Request $request)
    {
        $bonusId = base64_decode($bonusId); 

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
            $imageName = $bonusId.'.jpg';

            if (File::exists(base_path().'/public/uploads/hrx/bonus/'.$imageName))
                 File::delete(base_path().'/public/uploads/hrx/bonus/'.$imageName);

            
            $request->file('fileToUpload')->move(base_path().'/public/uploads/hrx/bonus/', $imageName);
        }

        return redirect()->action('EmployeesController@profile',$employeeId)->with('status', 'Bonus document uploaded!'); 
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