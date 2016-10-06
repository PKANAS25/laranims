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
use App\PayrollExpanded;
use App\AttendanceType;
use App\PayrollPersonal;
use App\StaffAttendance;
use App\Vacation;

 
use Validator;



class PayrollControllerMain extends Controller
{
     
    public function branchFilter(Request $request)
    {
       $pMonth = $request->pMonth; 
       
       $branches = Branch::WhereNotExists(function($query) use ($pMonth){
                                      $query->select(DB::raw('*'))
                                      ->from('payroll')
                                      ->whereRaw('payroll.company_id = branches.id')
                                      ->where('payroll.month_year',$pMonth);
                             })
                            ->orderBy('non_nursery','asc')
                            ->orderBy('name')                         
                            ->get();
         
        $selectString =  "<select name=\"company\"  id=\"branchSel\" class=\"form-control\"  data-fv-notempty=\"true\"><option value=\"\">Select</option>";  

        foreach ($branches as $branch) 
        {
            $selectString = $selectString."<option value=\"".$branch->id."\">".$branch->name."</option>";
        }
        $selectString = $selectString."</select>  "; 

        echo $selectString;
   
    }

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   
    public function step2(Request $request)
    {
       $this->validate($request, [
        'payroll_month' => 'required|date_format:Y-m',
        'company' => 'required',
        'start_date' => 'required||date_format:Y-m-d',
        'end_date' => 'required||date_format:Y-m-d',]); 

       $payroll_month = $request->payroll_month; 
       $company = $request->company;;
       $start_date = $request->start_date;
       $end_date = $request->end_date; 

       $existingPayroll = Payroll::where('month_year',$payroll_month)->where('company_id',$company)->first();
       if($existingPayroll)
       { 
        return redirect()->action('PayrollControllerMain@payrollView',base64_encode($existingPayroll->payroll_id));  
       }

        
       $noSave=0; $bankRejections=0; $pendingApprovals=0; $salriesNotVerified=0; $pendingAppeals=0;

       $AttendanceTypes = AttendanceType::all();
    
       $team = DB::table('salary_verification_team')->where('team_id',1)->first(); 


       foreach ($AttendanceTypes as $AttendanceType)
        {
         $t_id = $AttendanceType->type_id;
         $type_val[$t_id][1] = $AttendanceType->first;  $type_val[$t_id][2] = $AttendanceType->second; 
         $type_val[$t_id][3] = $AttendanceType->third;  $type_val[$t_id][4] = $AttendanceType->fourth;  
        }

        

        $employees = Employee::select('employees.*','work_branch.name AS working_for')
                             ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                             ->where('employees.working_under',$company)
                             ->where('employees.deleted',0)
                             ->where('employees.joining_date','<=',$end_date)
                             ->get();

        foreach ($employees as $employee) 
        {
            $personalPayroll = PayrollPersonal::where('emp_id',$employee->employee_id)->where('month_year',$payroll_month)->first();
            
            if($personalPayroll)
            $employee->paidPersonal = 1;
//************************************************************Attendance Calculation***************************************************************************
            $employee->deduction_days = 0;

            
            $attStart = $start_date;
            if(strtotime($employee->joining_date) > strtotime($start_date))
                $attStart = $employee->joining_date;
            $attendances = StaffAttendance::whereBetween('dated', [$attStart, $end_date])->where('employee_id',$employee->employee_id)->get(); 
                
                if($attendances)
                {
                    $att_array = array();
                    $i=0;
                    foreach ($attendances as $attendance) 
                    {
                        if($attendance->attendance_type!=0){ $att_array[$i]=$attendance->attendance_type; $i++;}
                        if($attendance->attendance_type2!=0){ $att_array[$i]=$attendance->attendance_type2; $i++;}
                    }//foreach ($attendances as $attendance) 
                    
                    $newArray = array_count_values($att_array);

                    foreach ($newArray as $key => $value)
                     { 
                            if($key!=1 && $key!=2)
                            { 
                                for($k=1;$k<=$value;$k++)
                                {
                                    $t = $k;
                                    if($k>4) $t = $k % 4;
                                    if($t==0) $t=4;
                                     $employee->deduction_days = $employee->deduction_days + ($type_val[$key][$t]/100);
                                }
                            }//if($key!=1 && $key!=2)
                     }  
                }//if($attendance)

                if(strtotime($employee->joining_date) > strtotime($start_date) && strtotime($employee->joining_date) <= strtotime($end_date))
                {  
                    $notJoinedDays =   (strtotime($employee->joining_date)-strtotime($start_date)) / (60 * 60 * 24);
                    $employee->deduction_days=$employee->deduction_days+$notJoinedDays; 
                }

                 
//************************************************************Non-paid Vacation  Calculation************************************************************

                $vacations = Vacation::where('end_date','>=',$start_date)
                                     ->where('start_date','<=',$end_date)
                                     ->where('emp_id',$employee->employee_id)
                                     ->where('paid','<',1)
                                     ->get(); 

                foreach ($vacations as $vacation) 
                {
                    $vacStart = $vacation->start_date; $vacEnd = $vacation->end_date; $date_looper = $vacStart;
                    
                    while(strtotime($date_looper)<=strtotime($vacEnd))
                    {
                        if(strtotime($date_looper)>=strtotime($start_date) && strtotime($date_looper)<=strtotime($end_date))
                        { 
                            $duplicateAttendance = StaffAttendance::where('dated', $date_looper)->where('employee_id',$employee->employee_id)->count(); 
                            if(!$duplicateAttendance)
                                $employee->deduction_days++;  
                        }
                        
                        $date_looper = date ("Y-m-d", strtotime ("+1 day", strtotime($date_looper))); 
                    }
                }
//**************************************************************Checking absence in a Paid vacation*************************************************

                $vacations = Vacation::where('end_date','>=',$start_date)
                                     ->where('start_date','<=',$end_date)
                                     ->where('emp_id',$employee->employee_id)
                                     ->where('paid',1)
                                     ->get(); 

                foreach ($vacations as $vacation) 
                {
                    $vacStart = $vacation->start_date; $vacEnd = $vacation->end_date; $date_looper = $vacStart;
                    
                    while(strtotime($date_looper)<=strtotime($vacEnd))
                    {
                        if(strtotime($date_looper)>=strtotime($start_date) && strtotime($date_looper)<=strtotime($end_date))
                        { 
                            $duplicateAttendance = StaffAttendance::where('dated', $date_looper)->where('employee_id',$employee->employee_id)->count(); 
                            if($duplicateAttendance)
                                $employee->deduction_days--;  
                        }
                        
                        $date_looper = date ("Y-m-d", strtotime ("+1 day", strtotime($date_looper))); 
                    }
                }  
//*************************************************************************************************************************************************************** 
                if($employee->deduction_days>30) 
                    $employee->deduction_days=30; 

                if(date('M',strtotime($payroll_month))=='Feb')
                {
                    $daysofFeb =(strtotime($end_date)-strtotime($start_date)) / (60 * 60 * 24);
                    $daysofFeb+=1;
                    if($employee->deduction_days>=$daysofFeb) 
                        $employee->deduction_days=30;
                }
//****************************************************************LOAN Calculation********************************************************************************
                $employee->loanDeduction = 0; 

                
               
                if($employee->paidPersonal)
                 $employee->loanDeduction = $personalPayroll->loan_deduction; 

                else
                {
                    $loans = DB::table('loans')
                            ->where('clearance',0)
                            ->where('emp_id',$employee->employee_id)
                            ->where('approved',1)
                            ->where('deduction_start','<=',$end_date)
                            ->get();

                     foreach ($loans as $loan) 
                     {
                        $loanBalance = $loan->loaned_amount - $loan->total_deducted; 
                        
                        if($loanBalance < $loan->deduction_amount) 
                            $employee->loanDeduction  = $employee->loanDeduction+$loanBalance; 
                        else 
                            $employee->loanDeduction = $employee->loanDeduction+$loan->deduction_amount;
                     } 
                }                
                
//**************************************************************** Benefits Calculation ****************************************************************
               
                $employee->totalBenefits=0;

                if($employee->paidPersonal)
                 $employee->totalBenefits = $personalPayroll->benefits;

                else
                {
                    $benefits = DB::table('personal_benefits')
                                  ->where('emp_id',$employee->employee_id)
                                  ->whereRaw("`rounds_given` < `max_rounds` ")
                                  ->where('benefit_start','<=',$end_date)
                                  ->where('cancelled',0)
                                  ->where('approved',1)
                                  ->get(); 
                    foreach ($benefits as $benefit) 
                            $employee->totalBenefits = $employee->totalBenefits+$benefit->amount;    
                }
                   
//**************************************************************** Bonus Calculation *******************************************************************
               
                $employee->totalBonus=0;

 
                $bonuses = DB::table('bonus')
                              ->where('emp_id',$employee->employee_id)
                              ->whereBetween('dated',[$start_date,$end_date])
                              ->where('approved',1)
                              ->get();

                foreach ($bonuses as $bonus) 
                        $employee->totalBonus = $employee->totalBonus+$bonus->amount; 

//**************************************************************** Overtime Calculation *******************************************************************
               
                $employee->totalOverTimePay=0; 
  
                $overtimes = DB::table('over_time')
                              ->where('emp_id',$employee->employee_id)
                              ->whereBetween('dated',[$start_date,$end_date])
                              ->where('approved',1)
                              ->get();

                foreach ($overtimes as $overtime) 
                        $employee->totalOverTimePay = $employee->totalOverTimePay+$overtime->amount;  

//**************************************************************** Deduction Calculation *******************************************************************
           
                $employee->totalDeduction=0; 
  
                $deductions = DB::table('deductions_xtra')
                              ->where('emp_id',$employee->employee_id)
                              ->whereBetween('dated',[$start_date,$end_date])
                              ->where('approved',1)
                              ->get();

                foreach ($deductions as $deduction) 
                        $employee->totalDeduction = $employee->totalDeduction+$deduction->amount;      

//************************************************************************************************************************************************************

                 
                $employee->totalSalary = 0;
                $employee->salaryNotOk=0; 

                $salary = EmployeesSalary::where('employee_id',$employee->employee_id)->first();
                
                if($salary)
                $employee->totalSalary =  $salary->basic + $salary->accomodation+ $salary->travel + $salary->other; 

                if($employee->totalSalary == 0 || $salary->verification1 == 0 || $salary->verification2 == 0 || $salary->verification3 == 0)
                {
                   $employee->salaryNotOk=1;  
                   $salriesNotVerified=1;
                   $noSave=1;
                } 

              $first = DB::table('bonus')->select('bonus_id')->where('approved',0)->where('emp_id',$employee->employee_id)->whereBetween('dated',[$start_date,$end_date]);
              $second = DB::table('deductions_xtra')->select('dedXtra_id')->where('approved',0)->where('emp_id',$employee->employee_id)->whereBetween('dated',[$start_date,$end_date]);
              $third = DB::table('over_time')->select('over_id')->where('approved',0)->where('emp_id',$employee->employee_id)->whereBetween('dated',[$start_date,$end_date]);
              $fourth = DB::table('loans')->select('loan_id')->where('approved',0)->where('emp_id',$employee->employee_id)->where('deduction_start','<=',$end_date);
              $fifth = DB::table('personal_benefits')->select('benefit_id')->where('approved',0)->where('emp_id',$employee->employee_id)->where('benefit_start','<=',$end_date);
              $allPending= $first->union($second)->union($third)->union($fourth)->union($fifth)->get();

              
                if($allPending)
                {   
                   $pendingApprovals=1;
                   $noSave=1;
                } 

                 $rejections = DB::table('payroll_rejections')->where('closed',0)->get();
                 if($rejections)
                    {$noSave=1; $bankRejections=1; }

                  $pendingAppeals = DB::table('appeals')
                                  ->select('appeals.*','staff_attendance.dated')
                                  ->leftjoin('staff_attendance','appeals.matter_id','=','staff_attendance.attendance_id')
                                  ->where('status',0)
                                  ->where('dated','!=','')
                                  ->where('dated','>','2016-08-31')
                                  ->count();
                 if($pendingAppeals)
                 $noSave=1; 

//************************************************************************************************************************************************************

                $daySalary=0; $employee->absentDeduction=0; $employee->netAmount=0;
                $daySalary = ($employee->totalSalary /30);    

                $employee->absentDeduction=$employee->deduction_days*$daySalary;
                 
                $employee->netAmount=round( ($employee->totalSalary+$employee->totalBenefits+$employee->totalBonus+$employee->totalOverTimePay)-($employee->absentDeduction+$employee->loanDeduction+$employee->totalDeduction) );                                      

        } //foreach ($employees as $employee)                  
              
        return view('payroll.middler',compact('employees','noSave','payroll_month','company','start_date','end_date','bankRejections','pendingApprovals','salriesNotVerified','pendingAppeals'));
   
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------

 public function step3(Request $request)
    {
       $this->validate($request, [
        'payroll_month' => 'required|date_format:Y-m',
        'company' => 'required',
        'start_date' => 'required||date_format:Y-m-d',
        'end_date' => 'required||date_format:Y-m-d',]); 

       $payroll_month = $request->payroll_month; 
       $company = $request->company;
       $start_date = $request->start_date;
       $end_date = $request->end_date; 

       $month = $payroll_month."-10";$month=strtotime($month);

       $nextFirst = strtotime( '+1 day', strtotime($end_date));
       $nextFirst = date('Y-m-d' , $nextFirst);

       $existingPayroll = Payroll::where('month_year',$payroll_month)->where('company_id',$company)->first();
       if($existingPayroll)
       { 
        return redirect()->action('PayrollControllerMain@payrollView',base64_encode($existingPayroll->payroll_id));  
       }

        
      $payroll  = new Payroll(array( 
                                'month_year'=>$payroll_month,
                                'company_id'=>$company,
                                'start_date'=>$start_date,
                                'end_date'=>$end_date,
                                'accountant'=>Auth::id(), 
                               ));
            
       $payroll->save(); 
       $payroll_id = $payroll->payroll_id;   

       DB::table('date_lock')->insert(['company_id' => $company, 'date_from' => $start_date, 'date_to' => $end_date]);   

       $AttendanceTypes = AttendanceType::all();
    
       $team = DB::table('salary_verification_team')->where('team_id',1)->first(); 


       foreach ($AttendanceTypes as $AttendanceType)
        {
         $t_id = $AttendanceType->type_id;
         $type_val[$t_id][1] = $AttendanceType->first;  $type_val[$t_id][2] = $AttendanceType->second; 
         $type_val[$t_id][3] = $AttendanceType->third;  $type_val[$t_id][4] = $AttendanceType->fourth;  
        }

        

        $employees = Employee::select('employees.*','work_branch.name AS working_for')
                             ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                             ->where('employees.working_under',$company)
                             ->where('employees.deleted',0)
                             ->where('employees.joining_date','<=',$end_date)
                             ->get();

        foreach ($employees as $employee) 
        {
            $personalPayroll = PayrollPersonal::where('emp_id',$employee->employee_id)->where('month_year',$payroll_month)->first();
            
            if($personalPayroll)
            $employee->paidPersonal = 1;
//************************************************************Attendance Calculation***************************************************************************
            $employee->deduction_days = 0;

            
            $attStart = $start_date;
            if(strtotime($employee->joining_date) > strtotime($start_date))
                $attStart = $employee->joining_date;
            $attendances = StaffAttendance::whereBetween('dated', [$attStart, $end_date])->where('employee_id',$employee->employee_id)->get(); 
                
                if($attendances)
                {
                    $att_array = array();
                    $i=0;
                    foreach ($attendances as $attendance) 
                    {
                        if($attendance->attendance_type!=0){ $att_array[$i]=$attendance->attendance_type; $i++;}
                        if($attendance->attendance_type2!=0){ $att_array[$i]=$attendance->attendance_type2; $i++;}
                    }//foreach ($attendances as $attendance) 
                    
                    $newArray = array_count_values($att_array);

                    foreach ($newArray as $key => $value)
                     { 
                            if($key!=1 && $key!=2)
                            { 
                                for($k=1;$k<=$value;$k++)
                                {
                                    $t = $k;
                                    if($k>4) $t = $k % 4;
                                    if($t==0) $t=4;
                                     $employee->deduction_days = $employee->deduction_days + ($type_val[$key][$t]/100);
                                }
                            }//if($key!=1 && $key!=2)
                     }  
                }//if($attendance)

                if(strtotime($employee->joining_date) > strtotime($start_date) && strtotime($employee->joining_date) <= strtotime($end_date))
                {  
                    $notJoinedDays =   (strtotime($employee->joining_date)-strtotime($start_date)) / (60 * 60 * 24);
                    $employee->deduction_days=$employee->deduction_days+$notJoinedDays; 
                }

                 
//************************************************************Non-paid Vacation  Calculation************************************************************

                $vacations = Vacation::where('end_date','>=',$start_date)
                                     ->where('start_date','<=',$end_date)
                                     ->where('emp_id',$employee->employee_id)
                                     ->where('paid','<',1)
                                     ->get(); 

                foreach ($vacations as $vacation) 
                {
                    $vacStart = $vacation->start_date; $vacEnd = $vacation->end_date; $date_looper = $vacStart;
                    
                    while(strtotime($date_looper)<=strtotime($vacEnd))
                    {
                        if(strtotime($date_looper)>=strtotime($start_date) && strtotime($date_looper)<=strtotime($end_date))
                        { 
                            $duplicateAttendance = StaffAttendance::where('dated', $date_looper)->where('employee_id',$employee->employee_id)->count(); 
                            if(!$duplicateAttendance)
                                $employee->deduction_days++;  
                        }
                        
                        $date_looper = date ("Y-m-d", strtotime ("+1 day", strtotime($date_looper))); 
                    }
                } 
//*************************************************************************************************************************************************************** 
                if($employee->deduction_days>30) 
                    $employee->deduction_days=30; 

                if(date('M',strtotime($payroll_month))=='Feb')
                {
                    $daysofFeb =(strtotime($end_date)-strtotime($start_date)) / (60 * 60 * 24);
                    $daysofFeb+=1;
                    if($employee->deduction_days>=$daysofFeb) 
                        $employee->deduction_days=30;
                }
//****************************************************************LOAN Calculation********************************************************************************
                $employee->loanDeduction = 0; 

                
               
                if($employee->paidPersonal)
                 $employee->loanDeduction = $personalPayroll->loan_deduction; 

                else
                {
                    $loans = DB::table('loans')
                            ->where('clearance',0)
                            ->where('emp_id',$employee->employee_id)
                            ->where('approved',1)
                            ->where('deduction_start','<=',$end_date)
                            ->get();

                     foreach ($loans as $loan) 
                     {
                        $loanBalance = $loan->loaned_amount - $loan->total_deducted; 
                        
                        if($loanBalance < $loan->deduction_amount) 
                           {
                            $employee->loanDeduction  = $employee->loanDeduction+$loanBalance; 
                            $dedAmount=$loanBalance;
                            } 
                        else 
                           { 
                             $employee->loanDeduction = $employee->loanDeduction+$loan->deduction_amount;
                             $dedAmount=$loan->deduction_amount;
                           }

                       
                        DB::table('loan_deductions')->insert(['loan_id' => $loan->loan_id, 'emp_id' => $employee->employee_id, 'amount' => $dedAmount, 'payroll_id' => $payroll_id]);   
                
                        $modified_pay = $loan->total_deducted+$dedAmount;
                    
                        if($modified_pay>=$loan->loaned_amount) 
                            DB::table('loans')->where('loan_id',$loan->loan_id)->update(['total_deducted' => $modified_pay, 'clearance' => 1]);  
                        else
                            DB::table('loans')->where('loan_id',$loan->loan_id)->update(['total_deducted' => $modified_pay]);  
                     } 
                }                
                
//**************************************************************** Benefits Calculation ****************************************************************
               
                $employee->totalBenefits=0;

                if($employee->paidPersonal)
                 $employee->totalBenefits = $personalPayroll->benefits;

                else
                {
                    $benefits = DB::table('personal_benefits')
                                  ->where('emp_id',$employee->employee_id)
                                  ->whereRaw("`rounds_given` < `max_rounds` ")
                                  ->where('benefit_start','<=',$end_date)
                                  ->where('cancelled',0)
                                  ->where('approved',1)
                                  ->get(); 
                    foreach ($benefits as $benefit) 
                    {
                        $employee->totalBenefits = $employee->totalBenefits+$benefit->amount;  
                        $rg = $benefit->rounds_given; $rg++; 
                        DB::table('personal_benefits')->where('benefit_id',$benefit->benefit_id)->update(['rounds_given' => $rg]); 
                        DB::table('benefits_expanded')->insert(['benefit_id' => $benefit->benefit_id, 'emp_id' => $employee->employee_id, 'payroll_id' => $payroll_id]);   
                    }
                            
                            
                }    
                   
//**************************************************************** Bonus Calculation *******************************************************************
               
                $employee->totalBonus=0;

 
                $bonuses = DB::table('bonus')
                              ->where('emp_id',$employee->employee_id)
                              ->whereBetween('dated',[$start_date,$end_date])
                              ->where('approved',1)
                              ->get();

                foreach ($bonuses as $bonus) 
                        $employee->totalBonus = $employee->totalBonus+$bonus->amount; 

//**************************************************************** Overtime Calculation *******************************************************************
               
                $employee->totalOverTimePay=0; 
  
                $overtimes = DB::table('over_time')
                              ->where('emp_id',$employee->employee_id)
                              ->whereBetween('dated',[$start_date,$end_date])
                              ->where('approved',1)
                              ->get();

                foreach ($overtimes as $overtime) 
                        $employee->totalOverTimePay = $employee->totalOverTimePay+$overtime->amount;  

//**************************************************************** Deduction Calculation *******************************************************************
           
                $employee->totalDeduction=0; 
  
                $deductions = DB::table('deductions_xtra')
                              ->where('emp_id',$employee->employee_id)
                              ->whereBetween('dated',[$start_date,$end_date])
                              ->where('approved',1)
                              ->get();

                foreach ($deductions as $deduction) 
                        $employee->totalDeduction = $employee->totalDeduction+$deduction->amount;      

//************************************************************************************************************************************************************

                 
                $employee->totalSalary = 0;
                $employee->salaryNotOk=0; 

                $salary = EmployeesSalary::where('employee_id',$employee->employee_id)->first();
                
                if($salary)
                $employee->totalSalary =  $salary->basic + $salary->accomodation+ $salary->travel + $salary->other;  

                $daySalary=0; $employee->absentDeduction=0; $employee->netAmount=0;
                $daySalary = ($employee->totalSalary /30);    

                $employee->absentDeduction=$employee->deduction_days*$daySalary;
                 
                $employee->netAmount=round( ($employee->totalSalary+$employee->totalBenefits+$employee->totalBonus+$employee->totalOverTimePay)-($employee->absentDeduction+$employee->loanDeduction+$employee->totalDeduction) );    

                if($employee->paidPersonal)
                {
                    
                     $personalNet= $personalPayroll->net_amount;

                     
                    
                    if($employee->netAmount > $personalNet)
                    {
                        $compensation = $employee->netAmount - $personalNet;
                        $noteText ="Compensation for ".date("M-Y",$month)." less payment";
                        DB::table('bonus')->insert(['emp_id' => $employee->employee_id, 'entry_date' => Carbon::now(), 'dated' => $nextFirst, 'amount' => $compensation, 'notes' => $noteText, 'admin' => Auth::id()]);   
                    }

                    else if($employee->netAmount < $personalNet)
                    {  
                        $compensation = $personalNet - $employee->netAmount; 
                        $noteText ="Deduction for ".date("M-Y",$month)." over payment";
                        $reasonText = "Overpaid payroll";
                        DB::table('deductions_xtra')->insert(['emp_id' => $employee->employee_id, 'entry_date' => Carbon::now(), 'dated' => $nextFirst, 'reason' => $reasonText, 'amount' => $compensation, 'notes' => $noteText, 'admin' => Auth::id()]);    
                    }
                    
                }

                else
                {
                    if($employee->netAmount<0)
                    {
                    $nextDeduct = abs($employee->netAmount);
                    $netAmount=0;
                    
                    $noteText ="Deduction for ".date("M-Y",$month)." minus balance";
                    $reasonText = "Minus Balance previous payroll";
                    DB::table('deductions_xtra')->insert(['date' => $employee->employee_id, 'entry_date' => Carbon::now(), 'dated' => $nextFirst, 'reason' => $reasonText, 'amount' => $nextDeduct, 'notes' => $noteText, 'admin' => Auth::id()]);  
                    }

                    
                    $payrollExpanded  = new PayrollExpanded(array( 
                                'payroll_id'=>$payroll_id,
                                'emp_id'=>$employee->employee_id,
                                'days_absent'=>$employee->deduction_days,
                                'loan_deduction'=>$employee->loanDeduction,                                 
                                'benefits'=>$employee->totalBenefits, 
                                'bonus'=>$employee->totalBonus, 
                                'overtime'=>$employee->totalOverTimePay,
                                'deductions_xtra'=>$employee->totalDeduction,
                                'salary'=>$employee->totalSalary,
                                'net_amount'=>$employee->netAmount, 
                               ));
                    $payrollExpanded->save();
                    
                
                }                                  

        } //foreach ($employees as $employee)                  
              
        return redirect()->action('PayrollControllerMain@payrollView',base64_encode($payroll_id));
   
    }

//------------------------------------------------------------------------------------------------------------------------------------------------------
public function payrollView($id)
    {
            $id = base64_decode($id);

            $payroll = Payroll::select('payroll.*','branches.name AS branch_name')
                              ->leftjoin('branches','payroll.company_id','=','branches.id')
                              ->where('payroll_id',$id) 
                              ->first();

            $payrollExpandeds = PayrollExpanded::select('payroll_expanded.*','employees.fullname','employees.working_under','employees.designation','employees.joining_date')
                                              ->leftjoin('employees','payroll_expanded.emp_id','=','employees.employee_id')
                                              ->where('payroll_id',$id)
                                              ->orderBy('emp_id')
                                              ->get();

            return view('payroll.view',compact('payroll','payrollExpandeds'));                                              
    }   
//------------------------------------------------------------------------------------------------------------------------------------------------------
}