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

       
         
        $students = DB::table('subscriptions') 
            ->leftjoin('students', 'subscriptions.student_id', '=', 'students.student_id')
            ->select('subscriptions.*','students.full_name','students.full_name_arabic')
            //->selectRaw("(SELECT COUNT(*)  FROM attendance WHERE attendance.student_id = students.student_id AND dated=?) AS saved ")->setBindings([$today])
            ->where('subscriptions.deleted',0 )
            ->where('refunded',0) 
            ->where('students.current_grade',base64_decode($classId)) 
            ->where('students.deleted',0) 
            ->whereRaw("'$today' Between subscriptions.start_date AND subscriptions.end_date")
            ->orderBy('current_standard', 'ASC')
            ->orderBy('students.student_id')            
            ->get(); 
             
            foreach ($students as $index=>$student) {
                $att = DB::table('attendance')
                            ->select('reason')
                            ->selectRaw("COUNT(*) AS saved")
                             ->where('student_id',$student->student_id) 
                             ->where('dated',Carbon::now()->toDateString())
                             ->first(); 
                     
                             if($att)
                                {$student->reason=$att->reason; $student->saved=$att->saved;}
                             else  
                             {$student->reason='';$student->saved=0;}                   
                                
            } 
            //print_r($students);
            return view('students.attendanceEdit',compact('students','classId','grade'));
    }
 //-----------------------------------------------------------------------------------------------------------------------------------
    
    public function saveAttendance(Request $request,$classId)
    {
        $studentIds = $request->get('studentIds');
        $attends = $request->get('attend');
        $reasons = $request->get('reason');

         foreach($studentIds as $studentId)
            {
                              
                
                $exists = DB::table('attendance')
                            ->select('reason','attendance_id')
                            ->selectRaw("COUNT(*) AS saved")
                             ->where('student_id',$studentId) 
                             ->where('dated',Carbon::now()->toDateString())
                             ->first(); 

                             
                
                if($exists->saved && $attends[$studentId]==0)
                  {
                            DB::table('attendance')
                             ->where('student_id',$studentId) 
                             ->where('dated',Carbon::now()->toDateString())
                             ->delete(); 
                  }

                  elseif(!$exists->saved && $attends[$studentId]) 
                  { 
                    DB::table('attendance')->insert(
                        ['student_id' => $studentId, 
                         'dated' => Carbon::now()->toDateString(),
                         'reason' =>$reasons[$studentId]]
                        ); 

                    $student = Student::where('student_id',$studentId)->first(); 
                    $msg = "Your+child+".ucwords($student->full_name)."+is+absent+from+nursery+today+".Carbon::now()->toDateString().".+Please+call+8006877+for+further+enquiries.+".Auth::user()->branch_name;
                                             $msg = str_replace(" ", "+", $msg);
                                             $msg = str_replace("++", "+", $msg);
                                            
                                            
    ///////////////////////////////////////////////Sending SMS to mother/////////////////////////////////////////////////////  
                                              $mobile_no = $student->mother_mob;
                                              $mobile_no = preg_replace('/\D/', '', $mobile_no);
                                              $mobile_no =  ltrim($mobile_no, '0');
                                              $mobile_no = "971".$mobile_no;    
                                              
                                              
                                      
                                            $url="http://94.56.94.242/api/api_http.php?username=aldana&password=dana960a&senderid=ALDANA&to=$mobile_no&text=$msg&route=6564-Du&type=text";
                                            $ch = curl_init($url);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            $curl_scraped_page = curl_exec($ch);
                                            curl_close($ch); 
                                            
///////////////////////////////////////////////Sending SMS to father/////////////////////////////////////////////////////  
                                              $mobile_no = $student->father_mob;
                                              $mobile_no = preg_replace('/\D/', '', $mobile_no);
                                              $mobile_no =  ltrim($mobile_no, '0');
                                              $mobile_no = "971".$mobile_no;                                          
                                              
                                              $url="http://94.56.94.242/api/api_http.php?username=aldana&password=dana960a&senderid=ALDANA&to=$mobile_no&text=$msg&route=6564-Du&type=text";
                                            $ch = curl_init($url);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            $curl_scraped_page = curl_exec($ch);
                                            curl_close($ch);
                  } 

                   elseif($exists->saved && $attends[$studentId]==1 && $reasons[$studentId]!=$exists->reason) 
                  {  //echo "herer";
                    
                    DB::table('attendance')
                    ->where('attendance_id', $exists->attendance_id)
                    ->update(['reason' => $reasons[$studentId]]); 
                  }     //echo  $exists->saved." _ ".  $attends[$studentId]." _ ". $exists->reason." _ ".$reasons[$studentId]."<br>";

            }//foreach

             Session::flash('status', 'Attendance saved & SMS has been sent to the parents!');
             return redirect()->back();
         
    }


    //-----------------------------------------------------------------------------------------------------------------------------------

    public function attendanceReportView()
    {
        $submit = 0;
       return view('students.attendanceReport',compact('submit'));
    }

  //-----------------------------------------------------------------------------------------------------------------------------------

    public function attendanceReport(Request $request)
    {
       
       $startDate = $request->startDate;
       $endDate = $request->endDate;

        $students =    DB::table('attendance')
                          ->select('attendance.*','students.full_name' ,'students.full_name_arabic' , 'students.branch', 'students.current_grade','classrooms.standard','classrooms.division')                                      
                          ->leftjoin('students','attendance.student_id','=','students.student_id')
                          ->leftjoin('classrooms','classrooms.class_id','=','students.current_grade')
                          ->where('students.branch',Auth::user()->branch)
                          ->whereBetween('attendance.dated', [$startDate, $endDate])
                          ->orderBy('attendance.dated', 'ASC')
                          ->orderByRaw("CAST(classrooms.standard as UNSIGNED) ,classrooms.standard , classrooms.division")
                          ->get();
       $submit = 1;
       return view('students.attendanceReport',compact('submit','students','startDate','endDate'));

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
