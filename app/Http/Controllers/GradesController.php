<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Classroom;
use App\Student;

use Auth;
use DB;
use Carbon\Carbon;

use Session;

class GradesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
                
                return view('students.grades',compact('grades'));
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

         $transferGrades = Classroom::where('class_id','!=',base64_decode($classId))
                                      ->where('branch',Auth::user()->branch)
                                      ->orderByRaw("CAST(classrooms.standard as UNSIGNED) ,classrooms.standard , classrooms.division")
                                      ->get();

        if($filter=='active')
         $students = DB::table('subscriptions') 
            ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
            ->leftjoin('nationality', 'students.nationality', '=', 'nationality.nation_id')
            ->select('subscriptions.*', 'nationality.nationality as nation','students.full_name','students.full_name_arabic','students.dob','students.gender','students.father_mob','students.mother_mob','students.branch')
            ->selectRaw("DATEDIFF(?, students.dob)/365.25 as age")->setBindings([$today]) 
             ->selectRaw("DATEDIFF(subscriptions.end_date, subscriptions.start_date)  as remainingDays") 
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

             return view('students.studentsGrade',compact('students','classId','grade','filter','transferGrades'));
    }

  //-----------------------------------------------------------------------------------------------------------------------------------

    public function gradeTransfer(Request $request)
    {
        $studentIds = $request->get('studentIds');
        $newGrade = $request->get('newGrade');

        if($studentIds && $request->get('newGrade'))
        {
            Student::whereIn("student_id", $studentIds)->update(array('current_grade' => $newGrade));
            
            Session::flash('status', 'Selected students are successfully transfered !');
            return redirect()->back();
        }

         else
                return redirect()->back()->withErrors('Invalid operation!');
    }

 //-----------------------------------------------------------------------------------------------------------------------------------


    public function editAttendance($classId)
    {
        $today = Carbon::now()->toDateString();
        
        $grade = Classroom::where('class_id',base64_decode($classId))->first();

      //  SELECT * FROM attendance where student_id = '$student' and dated='".$_SESSION['current_date']."'");
         
        $students = DB::table('subscriptions') 
            ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
            ->select('subscriptions.*','students.full_name','students.full_name_arabic')
            ->selectRaw("(SELECT COUNT(*)  FROM attendance WHERE attendance.student_id = students.student_id AND dated=?) AS saved ")->setBindings([$today])
            ->where('subscriptions.deleted',0 )
            ->where('refunded',0) 
            ->where('students.current_grade',base64_decode($classId)) 
            ->where('students.deleted',0) 
            ->whereRaw("'$today' Between subscriptions.start_date AND subscriptions.end_date")
            ->orderBy('current_standard', 'ASC')
            ->orderBy('students.student_id')            
            ->get();

            return view('students.attendanceEdit',compact('students','classId','grade'));
    }
 //-----------------------------------------------------------------------------------------------------------------------------------
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
