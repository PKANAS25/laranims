<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel;
use Auth;
use Carbon\Carbon;
use DB;
use App\Classroom;

class ExcelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $today =  Carbon::now()->toDateString(); 
         
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

        Excel::create(Auth::user()->branch_name.' Dashboard', function($excel) use ($subscriptions)  {

        // Set the title
        $excel->setTitle('Al Dana NMS');

        // Chain the setters
        $excel->setCreator(Auth::user()->name)
              ->setCompany('Al Dana Nurseries');

        // Call them separately
        $excel->setDescription('Dashboard Import');

        $excel->sheet('First sheet', function($sheet) use ($subscriptions) {
        $sheet->loadView('excel.dashboard')->with(compact('subscriptions'));
        });
        })->export('xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function students($classId,$filter)
    {
        $today = Carbon::now()->toDateString();

         $grade = Classroom::where('class_id',base64_decode($classId))->first(); 

        if($filter=='active')
         $students = DB::table('subscriptions') 
            ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
            ->leftjoin('nationality', 'students.nationality', '=', 'nationality.nation_id')
            ->select('subscriptions.*', 'nationality.nationality as nation','students.full_name','students.full_name_arabic','students.dob','students.gender','students.father_mob','students.mother_mob','students.branch')
            ->selectRaw("DATEDIFF('$today', students.dob)/365.25 as age") 
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
            ->orderBy('students.student_id')            
            ->get();

        elseif($filter=='all' || $filter=='deleted')
        {
         ($filter=='all')?$deleted=0:$deleted=1;
           
         $students = DB::table('students') 
            ->leftjoin('nationality', 'students.nationality', '=', 'nationality.nation_id')
            ->select('students.*', 'nationality.nationality as nation')
            ->selectRaw("DATEDIFF(?, students.dob)/365.25 as age")->setBindings([$today]) 
            ->selectRaw("(SELECT SUM(amount) FROM subscriptions WHERE subscriptions.student_id = students.student_id AND refunded=0 AND deleted=0)AS totalSubs")
            ->selectRaw("(SELECT SUM(non_refundable_amount) FROM subscriptions WHERE subscriptions.student_id = students.student_id AND refunded=1 AND deleted=0)AS totalRefunded")
            ->selectRaw("(SELECT SUM(amount) FROM subscriptions_hour WHERE subscriptions_hour.student_id = students.student_id AND   deleted=0)AS totalHours")
            ->selectRaw("(SELECT SUM(subscriptions_amount) FROM invoices WHERE invoices.student_id = students.student_id   AND deleted=0 AND NOT(cheque=1 AND cheque_bounce=1))AS totalPaid")
            ->selectRaw("(SELECT COUNT(*) FROM subscriptions WHERE subscriptions.student_id = students.student_id   AND deleted=0)AS deleteFlag1")
            ->selectRaw("(SELECT COUNT(*) FROM subscriptions_hour WHERE subscriptions_hour.student_id = students.student_id   AND deleted=0)AS deleteFlag2")
            ->selectRaw("(SELECT COUNT(*) FROM invoices WHERE invoices.student_id = students.student_id   AND deleted=0)AS deleteFlag3")
            ->where('students.current_grade',base64_decode($classId)) 
            ->where('students.deleted',$deleted)            
            ->orderBy('students.student_id')            
            ->get();
        }  
                Excel::create(Auth::user()->branch_name.' ('.$grade->standard." - ".ucwords($grade->division).")", function($excel) use ($students,$classId,$grade,$filter)  {

                // Set the title
                $excel->setTitle('Al Dana NMS');

                // Chain the setters
                $excel->setCreator(Auth::user()->name)
                      ->setCompany('Al Dana Nurseries');

                // Call them separately
                $excel->setDescription('Students List');

                $excel->sheet('First sheet', function($sheet)  use ($students,$classId,$grade,$filter) {
                $sheet->loadView('excel.studentsList')->with(compact('students','classId','grade','filter'));
                });
                })->export('xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
