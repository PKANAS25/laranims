<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Classroom;
use App\Nationality;
use App\Student;
use DB;

use File;

use App\Http\Requests\EnrollFormRequest;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enroll()
    {
        $branch = Auth::user()->branch;
        $grades = Classroom::where('branch',$branch)
                             ->orderByRaw('CAST(`classrooms`.`standard` as UNSIGNED) ,`classrooms`.`standard` , classrooms.division')
                             ->get();
        $nations = Nationality::all();
 

        return view('students.enroll',compact('grades','nations'));
    }

     public function enrollCheck(Request $request)
    {
          $branch = Auth::user()->branch;

          if($request->get('fullname'))
          {
          $fullname = $request->get('fullname'); 

          $count = Student::where('branch',$branch)
                            ->where('deleted',0)
                            ->whereRAW("full_name LIKE '%".$fullname."%'")
                            ->count();
            }

        elseif($request->get('full_name_arabic'))
          {
          $fullname_arabic =  ($request->get('full_name_arabic')); 

          $count = Student::where('branch',$branch)
                            ->where('deleted',0)
                            ->whereRAW("full_name_arabic LIKE '%".$fullname_arabic."%'")
                            ->count();
            }

        if($count)
        return response()->json(['valid' => 'true', 'message' => 'Name exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
         
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EnrollFormRequest $request)
    {
        $branch = Auth::user()->branch;
        $enrolledBy = $id = Auth::id();
        $today =  date('Y-m-d'); 

        $student = new Student(array(
            'full_name' => $request->get('fullname'),
            'full_name_arabic' => $request->get('full_name_arabic'),
            'current_grade' => $request->get('current_grade'),
            'branch' => $branch,
            'gender' => $request->get('gender'),
            'dob' => $request->get('dob'),
            'joining_date' => $request->get('joining_date'),
            'nationality' => $request->get('nationality'),
            'address' => $request->get('address'),
            'map' => $request->get('map'),
            'father_name' => $request->get('father_name'),
            'father_tel' => $request->get('father_tel'),
            'father_mob' => $request->get('father_mob'),
            'father_email' => $request->get('father_email'),
            'father_job' => $request->get('father_job'),
            'father_workplace' => $request->get('father_workplace'),
            'mother_name' => $request->get('mother_name'),
            'mother_tel' => $request->get('mother_tel'),
            'mother_mob' => $request->get('mother_mob'),
            'mother_email' => $request->get('mother_email'),
            'mother_job' => $request->get('mother_job'),
            'mother_workplace' => $request->get('mother_workplace'),
            'emergency_phone' => $request->get('emergency_phone'),
            'enrolled_by' => $enrolledBy,
            'enrolled_on' => $today
            
        ));
        
        $student->save();
        $studentId = $student->id;

        if($request->file('fileToUpload') && $studentId){

             $imageName = $studentId.'.jpg';

            $request->file('fileToUpload')->move(base_path().
                 '/public/uploads/student_pics/', $imageName
            );

        }


        return redirect('/enroll')->with(['status' => 'Successfully enrolled!', 'student'=>$studentId]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($studentId)
    {
        $studentId = base64_decode($studentId);
        $today =  date('Y-m-d'); 

        $student = DB::table('students') 
            ->leftjoin('nationality', 'students.nationality', '=', 'nationality.nation_id')
            ->leftjoin('classrooms','students.current_grade','=','classrooms.class_id')
            ->leftjoin('bus_students','students.student_id','=','bus_students.student_id')
            ->leftjoin('buses','bus_students.bus_id','=','buses.bus_id')
            ->select('students.*', 'nationality.nationality as nation','classrooms.standard', 'classrooms.division','bus_students.bus_id','buses.bus_name') 
            ->selectRaw("DATEDIFF(?, students.dob)/365.25 as age")->setBindings([$today]) 
            ->where('students.student_id',$studentId ) 
            ->first();

           
          if (File::exists(base_path().'/public/uploads/student_pics/'.$studentId.'.jpg'))
            $profile_pic = '/uploads/student_pics/'.$studentId.'.jpg' ;

          else
          $profile_pic = '/uploads/student_pics/no_image.jpg';         
//---------------------------------------------------------------------------------------------------------------------------------------------
         $payable1 = DB::table('subscriptions')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->where('refunded',0 )
            ->sum('amount');

         $payable2 = DB::table('subscriptions')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->where('refunded',1 )
            ->sum('non_refundable_amount');   

        $payable3 = DB::table('subscriptions_hour')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->sum('amount');

        $totalPaid  = DB::table('invoices')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->whereRaw("NOT(cheque='1' AND cheque_bounce='1')")
            ->sum('subscriptions_amount');  

            $totalPayable = $payable1+$payable2+$payable3; 
//---------------------------------------------------------------------------------------------------------------------------------------------
        $subscriptions = DB::table('subscriptions') 
                         ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                         ->leftjoin('users AS subscribe','subscriptions.subscribed_by','=','subscribe.id')
                         ->leftjoin('users AS refunding','subscriptions.refunded_by','=','refunding.id')
                         ->leftjoin('users AS deletion','subscriptions.deleted_by','=','deletion.id')
                         ->select('subscriptions.*', 'payment_groups.group_name','subscribe.name AS subscriber','refunding.name AS refunder','deletion.name AS deleter',
                            DB::raw("(SELECT count(added_transportations.trans_id) FROM added_transportations  
                            WHERE added_transportations.deleted=0 AND added_transportations.subscription_id=subscriptions.subscription_id )AS added "))
                         ->where('student_id',$studentId) 
                         ->orderBy('start_date','DESC')
                         ->get();

//---------------------------------------------------------------------------------------------------------------------------------------------
        $subscriptions_hour = DB::table('subscriptions_hour') 
                         ->leftjoin('users AS subscribe','subscriptions_hour.subscribed_by','=','subscribe.id')
                         ->leftjoin('users AS deletion','subscriptions_hour.deleted_by','=','deletion.id')
                         ->select('subscriptions_hour.*','subscribe.name AS subscriber','deletion.name AS deleter')
                         ->where('student_id',$studentId) 
                         ->orderBy('dated','DESC')
                         ->get();                  
//---------------------------------------------------------------------------------------------------------------------------------------------
                 $invoices = DB::table('invoices')
                         ->leftjoin('users AS issued','invoices.admin','=','issued.id')
                         ->leftjoin('users AS deletion','invoices.deleted_by','=','deletion.id')
                         ->select('invoices.*','issued.name AS issuer','deletion.name AS deleter')
                         ->where('student_id',$studentId)  
                         ->get();  
//---------------------------------------------------------------------------------------------------------------------------------------------  

                $events = DB::table('invoices_custom_items')
                         ->leftjoin('invoices','invoices_custom_items.invoice_id','=','invoices.invoice_id')
                         ->leftjoin('students','invoices.student_id','=','students.student_id')
                         ->leftjoin('events','invoices_custom_items.event_id','=','events.event_id')
                         ->select('invoices_custom_items.event_id' , 'invoices.invoice_id', 'events.title','events.start_date','events.end_date','events.description','students.student_id')
                         ->where('students.student_id',$studentId)
                         ->where('invoices.deleted',0) 
                         ->where('invoices_custom_items.event_id','!=',0)   
                         ->get();
//---------------------------------------------------------------------------------------------------------------------------------------------            
 
        return view('students.profile',compact('student','profile_pic','totalPayable','totalPaid','subscriptions','subscriptions_hour','invoices','events'));
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
