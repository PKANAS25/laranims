<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

use Carbon\Carbon;
 

 
//use Session;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
         
       
        $today =  Carbon::now()->toDateString();

        //$selectedBranch = session('currentBranch'); 
        $selectedBranch = Auth::user()->branch;  

        $subscriptions = DB::table('subscriptions') 
        ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
        ->leftjoin('classrooms', 'students.current_grade', '=', 'classrooms.class_id')
        ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
            ->select('subscriptions.*', 'students.full_name','students.branch','students.current_grade','payment_groups.group_name','classrooms.standard','classrooms.division',
                DB::raw("(SELECT count(added_transportations.trans_id) FROM added_transportations  
                    WHERE added_transportations.deleted=0 AND added_transportations.subscription_id=subscriptions.subscription_id )AS added,
                    DATEDIFF(`end_date`,?) AS remaining_days ")) ->setBindings([$today]) 
            ->where('subscriptions.deleted',0 )
            ->where('refunded',0) 
            ->where('students.branch',$selectedBranch) 
            ->where('students.deleted',0) 
            ->whereRaw("'$today' Between subscriptions.start_date AND subscriptions.end_date")
             ->orderByRaw("CAST(classrooms.standard as UNSIGNED) ,classrooms.standard , classrooms.division")
            ->orderBy('students.student_id')
            
            ->get();
          
        $cashInHand = DB::table('invoices')->where('bank_ok',0)->where('deleted',0)->where('branch',$selectedBranch)->sum('amount_paid');
        $cashInHand = number_format($cashInHand);

        $yearly = DB::table('subscriptions') 
                    ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
                    ->where('subscriptions.deleted',0 )
                    ->where('refunded',0) 
                    ->where('subscription_type',1) 
                    ->where('students.branch',$selectedBranch)
                    ->whereRaw("enroll_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()")
                    ->count(); 


        $otherSubs = DB::table('subscriptions') 
                    ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
                    ->where('subscriptions.deleted',0 )
                    ->where('refunded',0) 
                    ->where('subscription_type','!=',1) 
                    ->where('students.branch',$selectedBranch)
                    ->whereRaw("enroll_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()")
                    ->count(); 

        $enrollments = DB::table('students')   
                         ->where('deleted',0)
                         ->where('branch',$selectedBranch) 
                         ->whereRaw("enrolled_on BETWEEN CURDATE() - INTERVAL 90 DAY AND CURDATE()")   
                         ->count();   

        $expiring = DB::table('subscriptions') 
                      ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
                      ->where('subscriptions.deleted',0 )
                      ->where('refunded',0) 
                      ->where('students.branch',$selectedBranch) 
                      ->where('students.deleted',0)  
                      ->whereRaw("'$today' Between subscriptions.start_date AND subscriptions.end_date")  
                      ->whereRaw("(DATEDIFF(end_date,CURDATE())<8)") 
                      ->count();                 

        return view('home',compact('subscriptions','cashInHand','yearly','otherSubs','enrollments','expiring'));
    }

    
}
