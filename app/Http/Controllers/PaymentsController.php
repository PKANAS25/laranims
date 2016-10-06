<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use App\Classroom;
use App\Nationality;
use App\Student;
use App\Branch;
use App\Invoice;


use File;

use Carbon\Carbon;

use App\Http\Requests\EnrollFormRequest;
use Session;

class PaymentsController extends Controller
{
    
    public function balance()
    {
        $today = Carbon::now()->toDateString();

        $grades = DB::table('classrooms')
                ->leftjoin('employees','classrooms.counselor','=','employees.employee_id')
                ->select('classrooms.*','employees.fullname',
                    DB::raw("(SELECT count(subscription_id) FROM subscriptions 
                             LEFT JOIN students ON(subscriptions.student_id=students.student_id) 
                             WHERE students.current_grade=classrooms.class_id AND students.deleted=0  AND subscriptions.deleted=0 AND refunded=0
                             AND  ? Between subscriptions.start_date AND subscriptions.end_date) AS strength"))->setBindings([$today])
                ->where('classrooms.branch',Auth::user()->branch)
                ->orderByRaw("CAST(classrooms.standard as UNSIGNED) ,classrooms.standard , classrooms.division")
                ->get();
       
        foreach($grades AS $grade)    
        {
            $gradeBalance = 0; $totalPayable = 0; $totalPaid = 0;
            $students = DB::table('students')
                          ->where('students.branch',Auth::user()->branch)
                          ->where('students.current_grade',$grade->class_id)
                          ->where('students.deleted',0)
                          ->get();
            foreach ($students as $student) 
                    {
                         $payable1 = DB::table('subscriptions')->where('student_id',$student->student_id)->where('refunded',0)->where('deleted',0)->sum('amount');
                         $payable2 = DB::table('subscriptions')->where('student_id',$student->student_id)->where('refunded',1)->where('deleted',0)->sum('non_refundable_amount');
                         $payable3 = DB::table('subscriptions_hour')->where('student_id',$student->student_id)->where('deleted',0)->sum('amount');
                         $totalPayable += $payable1+$payable2+$payable3;

                         $paid = DB::table('invoices')
                                   ->where('student_id',$student->student_id)
                                   ->where('deleted',0)
                                   ->whereRaw("NOT(cheque='1' AND cheque_bounce='1')")
                                   ->sum('subscriptions_amount');
                         $totalPaid += $paid;             
                    } //foreach ($students as $student)    
            $gradeBalance = $totalPayable-$totalPaid;
            $grade->gradeBalance = $gradeBalance;     
        }//@foreach($grades AS $grade)     
         

       return view('payments.balance',compact('grades'));
    }

 ///-------------------------------------------------------------------------------------------------------------------------------
   
   public function balanceGrades($classId,$filter)
    {
        $grade = Classroom::where('class_id',base64_decode($classId))->first();
        $today = Carbon::now()->toDateString();

        if($filter=='active')
         $students = DB::table('subscriptions') 
            ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
            ->leftjoin('nationality', 'students.nationality', '=', 'nationality.nation_id')
            ->select('subscriptions.*', 'nationality.nationality as nation','students.full_name','students.full_name_arabic','students.dob','students.gender','students.father_mob','students.mother_mob','students.branch') 
            ->selectRaw("DATEDIFF(subscriptions.end_date,'$today')  as remainingDays") 
            ->selectRaw("(SELECT SUM(amount) FROM subscriptions WHERE subscriptions.student_id = students.student_id AND refunded=0 AND deleted=0)AS totalSubs")
            ->selectRaw("(SELECT SUM(non_refundable_amount) FROM subscriptions WHERE subscriptions.student_id = students.student_id AND refunded=1 AND deleted=0)AS totalRefunded")
            ->selectRaw("(SELECT SUM(amount) FROM subscriptions_hour WHERE subscriptions_hour.student_id = students.student_id AND   deleted=0)AS totalHours")
            ->selectRaw("(SELECT SUM(subscriptions_amount) FROM invoices WHERE invoices.student_id = students.student_id   AND deleted=0 AND NOT(cheque=1 AND cheque_bounce=1))AS totalPaid")
            ->where('subscriptions.deleted',0 )
            ->where('refunded',0) 
            ->where('students.current_grade',base64_decode($classId)) 
            ->where('students.deleted',0) 
            ->whereRaw("'$today' Between subscriptions.start_date AND subscriptions.end_date")
            ->orderBy('current_standard', 'ASC')
            ->orderBy('students.full_name')            
            ->get();

        elseif($filter=='all') 
         $students = DB::table('students') 
            ->leftjoin('nationality', 'students.nationality', '=', 'nationality.nation_id')
            ->select('students.*', 'nationality.nationality as nation') 
            ->selectRaw("(SELECT SUM(amount) FROM subscriptions WHERE subscriptions.student_id = students.student_id AND refunded=0 AND deleted=0)AS totalSubs")
            ->selectRaw("(SELECT SUM(non_refundable_amount) FROM subscriptions WHERE subscriptions.student_id = students.student_id AND refunded=1 AND deleted=0)AS totalRefunded")
            ->selectRaw("(SELECT SUM(amount) FROM subscriptions_hour WHERE subscriptions_hour.student_id = students.student_id AND   deleted=0)AS totalHours")
            ->selectRaw("(SELECT SUM(subscriptions_amount) FROM invoices WHERE invoices.student_id = students.student_id   AND deleted=0 AND NOT(cheque=1 AND cheque_bounce=1))AS totalPaid") 
            ->where('students.current_grade',base64_decode($classId)) 
            ->where('students.deleted',0)            
            ->orderBy('students.full_name')            
            ->get(); 
            $gradeBalance=0;
            foreach ($students as $student) 
                    {
                        $totalPayable = 0;  

                         $payable1 = DB::table('subscriptions')->where('student_id',$student->student_id)->where('refunded',0)->where('deleted',0)->sum('amount');
                         $payable2 = DB::table('subscriptions')->where('student_id',$student->student_id)->where('refunded',1)->where('deleted',0)->sum('non_refundable_amount');
                         $payable3 = DB::table('subscriptions_hour')->where('student_id',$student->student_id)->where('deleted',0)->sum('amount');
                         $totalPayable = $payable1+$payable2+$payable3;

                         $paid = DB::table('invoices')
                                   ->where('student_id',$student->student_id)
                                   ->where('deleted',0)
                                   ->whereRaw("NOT(cheque='1' AND cheque_bounce='1')")
                                   ->sum('subscriptions_amount');
                         
                         $student->totalPayable = $totalPayable;   
                         $student->studentBalance = $totalPayable-$paid;   
                         
                         if($student->studentBalance>0)  
                         $gradeBalance += $student->studentBalance;    
                    } //foreach ($students as $student) 
        
        $i=0;           
        return view('payments.balanceGrade',compact('grade','students','i','gradeBalance','filter'));
    }
 ///-------------------------------------------------------------------------------------------------------------------------------
   
   public function receiptBook()
    {
        $branches = Branch::where('non_nursery',0)->orderBy('name')->get();   
        $submit=0;

        return view('payments.receiptBook',compact('branches','submit'));
    }    
///-------------------------------------------------------------------------------------------------------------------------------    

     public function receiptBookGenerate(Request $request)
    {
        $branches = Branch::where('non_nursery',0)->orderBy('name')->get();
        $branchSelected = Branch::where('id',$request->branch)->first();  
        $submit=1;

        $receipts = Invoice::select('invoices.*','students.full_name','users.name AS receiver') 
                           ->leftjoin('students', 'invoices.student_id', '=', 'students.student_id')
                           ->leftjoin('users', 'invoices.admin', '=', 'users.id')
                           ->where('invoices.branch',$request->branch)
                           ->where('invoices.deleted',0)
                           ->where('invoices.amount_paid','>=',0)
                           ->whereBetween('invoices.dated', [$request->start_date, $request->end_date])
                           ->orderBy('invoices.invoice_id','DESC')
                           ->get();

        return view('payments.receiptBook',compact('branches','branchSelected','submit','receipts'));
    }    
///-------------------------------------------------------------------------------------------------------------------------------    


}
 
                                     

 
                                   