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
use App\Nationality; 


use File;
use Image;
use Validator;

use Mail;

use App\Http\Requests\EmployeeAddRequest;

class EmployeesController extends Controller
{
    
    public function index()
    {
       $employees = Employee::where('working_under',Auth::user()->branch)->where('deleted',0)->orderBy('fullname')->get();

       return view('employees.index',compact('employees'));
    }

//----------------------------------------------------------------------------------------------------------------------------------------
    public function profile($employeeId)
    {

        $employeeId = base64_decode($employeeId); 

        $employee = Employee::select('employees.*','nationality.nationality AS nation', 'religion.religion AS rel', 'visa_branch.name AS visa_in' , 'work_branch.name AS working_for')
                            ->leftjoin('nationality','employees.nationality', '=', 'nationality.nation_id')
                            ->leftjoin('religion','employees.religion', '=', 'religion.religion_id')
                            ->leftjoin('branches AS visa_branch','employees.visa_under', '=', 'visa_branch.id')
                            ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                            ->where('employees.employee_id',$employeeId)
                            ->first();

        if($employee->visa_under==-1) $employee->visa_in = "Under Process"; elseif($employee->visa_under==-2) $employee->visa_in = "Spouse"; elseif($employee->visa_under==-3) $employee->visa_in = "Guardian";                    
         
        if($employee->deleted==0) $employee->status = "Active"; elseif($employee->deleted==1) $employee->status = "Deleted"; elseif($employee->deleted==2) $employee->status = "Terminated"; elseif($employee->deleted==3) $employee->status = "Resigned";

        $age = Carbon::parse($employee->date_of_birth)->age;                    
        
        if (File::exists(base_path().'/public/uploads/employee_pics/'.$employeeId.'.jpg'))
            $profile_pic = '/uploads/employee_pics/'.$employeeId.'.jpg' ;

        else
          $profile_pic = '/uploads/student_pics/no_image.jpg';  
          
        $assestsAssigned = DB::table('asset_assigns')->where('staff_id',$employeeId)->where('deleted',0)->count();
         
        $salary = EmployeesSalary::select('employees_salary.*','branches.name AS wps')
                                ->leftjoin('branches','employees_salary.labour_card_under','=','branches.id')
                                ->where('employee_id',$employeeId)
                                ->first();

        return view('employees.profile',compact('employee','profile_pic','age','assestsAssigned','salary'));                  
    }
//----------------------------------------------------------------------------------------------------------------------------------------
    public function add()
    {
        $nations = Nationality::orderBy('nationality')->get();
        $religions = DB::table('religion')->orderBy('religion')->get();
        $branches = Branch::orderBy('name')->get();
        $specialisations = DB::table('specializations')->get(); 

         return view('employees.addEmployee',compact('nations','religions','branches','specialisations'));
    }
//----------------------------------------------------------------------------------------------------------------------------------------   
     public function employeeAddCheck(Request $request)
    {
        $fname = $request->get('fname'); 
        $mname = $request->get('mname');
        $lname = $request->get('lname');

        $fullname = $fname." ".$mname." ".$lname;

        $count = Employee::whereRAW("fullname LIKE '%".$fullname."%'")->count();
            

        if($count)
        return response()->json(['valid' => 'true', 'message' => 'Name exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
    }
//----------------------------------------------------------------------------------------------------------------------------------------     
    public function save(EmployeeAddRequest $request)
    {
         $fullname = ucwords(strtolower($request->fname." ".$request->mname." ".$request->lname));

       
         $employee = new Employee(array( 
                                'fullname'=>$fullname,
                                'bonus_category'=>$request->bonus_category,
                                'designation'=>$request->designation,
                                'designation_mol'=>$request->designation_mol,
                                'visa_under'=>$request->visa_under,
                                'working_under'=>$request->working_under,
                                'qualification'=>$request->qualification,
                                'specialization'=>$request->specialization,
                                'joining_date'=>$request->joining_date,
                                'start_time'=>$request->start_time,
                                'end_time'=>$request->end_time,
                                'passport_no'=>$request->passport_no,
                                'passport_expiry'=>$request->passport_expiry,
                                'person_code'=>$request->person_code,
                                'labour_card_no'=>$request->labour_card_no,
                                'labour_card_expiry'=>$request->labour_card_expiry,
                                'visa_issue'=>$request->visa_issue,
                                'visa_expiry'=>$request->visa_expiry,
                                'gender'=>$request->gender,
                                'date_of_birth'=>$request->dob,
                                'nationality'=>$request->nationality,
                                'religion'=>$request->religion,
                                'mobile'=>$request->mobile,
                                'email'=>$request->email,
                                'personal_email'=>$request->personal_email, 
                               ));
            
            $employee->save();
            $employeeId = $employee->employee_id; 

            
            
            
            $date_time = date('Y-m-d H:i');
            
            
             
            //pasport expiry
            DB::table('staff_docs_details')->insert(['emp_id' => $employeeId,'doc_id' => 1,'expiry_date' => $request->passport_expiry]); 

            //visa expiry
            DB::table('staff_docs_details')->insert(['emp_id' => $employeeId,'doc_id' => 2,'expiry_date' => $request->visa_expiry]); 

            //labour card expiry
            DB::table('staff_docs_details')->insert(['emp_id' => $employeeId,'doc_id' => 14,'expiry_date' => $request->labour_card_expiry]);  
           
            //history 
            $action = "Added by ";  
            DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);   
            
            //mailing list 
            $mailingList = DB::table('hr_mailing_lists')->where('action',1)->get();
            $emails = array_pluck($mailingList, 'mail'); 
            
              $subject = "Employee Add Notification - ".Auth::user()->branch_name;

              $body = "General details of the employee\n\nEmployee ID: ".$employeeId."\nName: ".$fullname."\nPosition: ".$request->bonus_category.
                      "\nJoining Date: ".$request->joining_date."\nWorking Hours: ".$request->start_time." to ".$request->end_time."\n\n--Added by: ".Auth::user()->name; 
             
            Mail::queue([], [], function ($message) use($subject,$emails,$body) 
            {
               $message->from('anas.acmg@gmail.com', 'Al Dana NMS');
               $message->to($emails);
               $message->subject($subject);
               $message->setBody($body);
            });

            if($request->file('fileToUpload') && $employeeId)
            {
             $imageName = $employeeId.'.jpg';  
             Image::make($request->file('fileToUpload'))->save(base_path().'/public/uploads/employee_pics/'.$imageName); 
            } 

           return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'New Employee added!');
    } 
//----------------------------------------------------------------------------------------------------------------------------------------    
}
