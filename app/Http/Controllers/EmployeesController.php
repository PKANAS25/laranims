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
use App\Http\Requests\EmployeeEditRequest;

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
 
        $history = DB::table('employees_changes')
                     ->select('employees_changes.*','users.name')
                     ->leftjoin('users','employees_changes.changer_id','=','users.id')
                     ->where('employee_id',$employeeId)
                     ->orderBy('change_id','DESC')
                     ->get();
        foreach ($history as $row) {  $row->date_time = Carbon::parse($row->date_time);  }
        

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

       
         
        $salary = EmployeesSalary::select('employees_salary.*','branches.name AS wps')
                                ->leftjoin('branches','employees_salary.labour_card_under','=','branches.id')
                                ->where('employee_id',$employeeId)
                                ->first();

        return view('employees.profile',compact('employee','profile_pic','age','assestsAssigned','salary','history','bonuses'));                  
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

        $count = Employee::whereRAW("fullname LIKE '%".$fullname."%'")->where('deleted','!=',1)->count();
            

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
    public function edit($employeeId)
    {
        
        $employeeId = base64_decode($employeeId);
        $nations = Nationality::orderBy('nationality')->get();
        $religions = DB::table('religion')->orderBy('religion')->get();
        $branches = Branch::orderBy('name')->get();
        $specialisations = DB::table('specializations')->get(); 

        $employee = Employee::select('employees.*','nationality.nationality AS nation', 'religion.religion AS rel', 'visa_branch.name AS visa_in' , 'work_branch.name AS working_for')
                            ->leftjoin('nationality','employees.nationality', '=', 'nationality.nation_id')
                            ->leftjoin('religion','employees.religion', '=', 'religion.religion_id')
                            ->leftjoin('branches AS visa_branch','employees.visa_under', '=', 'visa_branch.id')
                            ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                            ->where('employees.employee_id',$employeeId)
                            ->first();
        if($employee->visa_under==-1) $employee->visa_in = "Under Process"; elseif($employee->visa_under==-2) $employee->visa_in = "Spouse"; 
        elseif($employee->visa_under==-3) $employee->visa_in = "Guardian"; elseif($employee->visa_under==0) $employee->visa_under='';              

        if(File::exists(base_path().'/public/uploads/employee_pics/'.$employeeId.'.jpg'))
           $profile_pic = '/uploads/employee_pics/'.$employeeId.'.jpg' ;  
        else
            $profile_pic="";


         return view('employees.editEmployee',compact('nations','religions','branches','specialisations','employee','profile_pic'));
    }  

//----------------------------------------------------------------------------------------------------------------------------------------   
     public function employeeEditCheck(Request $request)
    {
        $fullname = $request->get('fullname');  
        $employeeId = $request->get('employeeId');

        $count = Employee::whereRAW("fullname LIKE '%".$fullname."%'")->where('employee_id','!=',$employeeId)->where('deleted','!=',1)->count();
            

        if($count)
        return response()->json(['valid' => 'true', 'message' => 'Name exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
    }
//----------------------------------------------------------------------------------------------------------------------------------------     
    public function editSave(EmployeeEditRequest $request,$employeeId)
    {
        $employeeId = base64_decode($employeeId);

        $employee = Employee::where('employee_id',$employeeId)->first();    
        
        $salary = EmployeesSalary::where('employee_id',$employeeId)->first();

        $edit_reason = "";
        if($salary && ($employee->joining_date != $request->joining_date || $employee->bonus_category != $request->bonus_category || $employee->person_code != $request->person_code))
             {  
                $edit_reason = "";
                 
                 if($employee->joining_date != $request->joining_date)
                 $edit_reason = $edit_reason."Joining Date - ";
                 
                 if($employee->bonus_category != $request->bonus_category)
                 $edit_reason = $edit_reason."Bonus Category - ";
                 
                 if($employee->person_code != $request->person_code)
                 $edit_reason = $edit_reason."Person Code - ";
                 
                 $edit_reason = $edit_reason."Changed.";

                 $salary->verification1=0; $salary->verification2=0; $salary->verification3=0; $salary->edit_reason=$edit_reason;
                 $salary->save();
             } 
             
        
        if($employee->passport_expiry != $request->passport_expiry)
        {
            $passportDoc = DB::table('staff_docs_details')->where('emp_id',$employeeId)->where('doc_id',1)->first();
            
            if($passportDoc) 
                DB::table('staff_docs_details')->where('emp_id',$employeeId)->where('doc_id',1)->update(['expiry_date' => $request->passport_expiry]); 
            else
                DB::table('staff_docs_details')->insert(['emp_id' => $employeeId,'doc_id' => 1,'expiry_date' => $request->passport_expiry]);
        }

        if($employee->visa_expiry != $request->visa_expiry)
        {
            $visaDoc = DB::table('staff_docs_details')->where('emp_id',$employeeId)->where('doc_id',2)->first();
            
            if($visaDoc) 
                DB::table('staff_docs_details')->where('emp_id',$employeeId)->where('doc_id',2)->update(['expiry_date' => $request->visa_expiry]); 
            else
                DB::table('staff_docs_details')->insert(['emp_id' => $employeeId,'doc_id' => 2,'expiry_date' => $request->visa_expiry]);
        }

        if($employee->labour_card_expiry != $request->labour_card_expiry)
        {
            $labourDoc = DB::table('staff_docs_details')->where('emp_id',$employeeId)->where('doc_id',14)->first();
            
            if($labourDoc) 
                DB::table('staff_docs_details')->where('emp_id',$employeeId)->where('doc_id',14)->update(['expiry_date' => $request->labour_card_expiry]); 
            else
                DB::table('staff_docs_details')->insert(['emp_id' => $employeeId,'doc_id' => 14,'expiry_date' => $request->labour_card_expiry]);
        }

        $action = "Details changed by "; 
        if($employee->start_time != $request->start_time || $employee->end_time != $request->end_time)
           $action = "Details,worktime changed by ";     

        $action = $edit_reason.$action;


        $employee->fullname = ucwords(strtolower($request->fullname));
        $employee->bonus_category = $request->bonus_category;
        $employee->designation = $request->designation;
        $employee->designation_mol = $request->designation_mol;
        $employee->visa_under = $request->visa_under; 
        $employee->qualification = $request->qualification;
        $employee->specialization = $request->specialization;
        $employee->joining_date = $request->joining_date;
        $employee->start_time = $request->start_time;
        $employee->end_time = $request->end_time;
        $employee->passport_no = $request->passport_no;
        $employee->passport_expiry = $request->passport_expiry;
        $employee->person_code = $request->person_code;
        $employee->labour_card_no = $request->labour_card_no;
        $employee->labour_card_expiry = $request->labour_card_expiry;
        $employee->visa_issue = $request->visa_issue;
        $employee->visa_expiry = $request->visa_expiry;
        $employee->gender = $request->gender;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->nationality = $request->nationality;
        $employee->religion = $request->religion;
        $employee->mobile = $request->mobile;
        $employee->email = $request->email;
        $employee->personal_email = $request->personal_email; 
        $employee->biometric = $request->biometric; 
        $employee->save();

        //history  
        DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Employee details changed!');

    }    
//---------------------------------------------------------------------------------------------------------------------------------------- 

    public function specialDays($employeeId)
    {
        $employeeId = base64_decode($employeeId);
        
        $assignedDays = DB::table('special_working_days')
                          ->select('special_working_days.*' , 'adminer.name AS adder', 'deleter.name AS deleteman')
                          ->leftjoin('users AS adminer','adminer.id','=','special_working_days.admin')
                          ->leftjoin('users AS deleter','deleter.id','=','special_working_days.deleted_by')
                          ->where('emp_id',$employeeId) 
                          ->orderBy('dated','DESC')
                          ->get();

        return view('employees.specialDays',compact('assignedDays'));                  
    }  


//---------------------------------------------------------------------------------------------------------------------------------------- 

    public function specialDaysSave($employeeId,Request $request)
    {
        $employeeId = base64_decode($employeeId);
        
        if($request->day1)
        {
            $day1 =  DB::table('special_working_days')->where('emp_id',$employeeId)->where('dated',$request->day1)->where('deleted',0)->count();  
            
            if(!$day1)
                DB::table('special_working_days')->insert(['emp_id'=>$employeeId,'dated'=>$request->day1,'admin'=>Auth::id(),'added_on'=> Carbon::now()->toDateString()]); 
        }

        if($request->day2)
        {
            $day2 =  DB::table('special_working_days')->where('emp_id',$employeeId)->where('dated',$request->day2)->where('deleted',0)->count();  
            
            if(!$day2)
                DB::table('special_working_days')->insert(['emp_id'=>$employeeId,'dated'=>$request->day2,'admin'=>Auth::id(),'added_on'=> Carbon::now()->toDateString()]); 
        }

        if($request->day3)
        {
            $day3 =  DB::table('special_working_days')->where('emp_id',$employeeId)->where('dated',$request->day3)->where('deleted',0)->count();  
            
            if(!$day3)
                DB::table('special_working_days')->insert(['emp_id'=>$employeeId,'dated'=>$request->day3,'admin'=>Auth::id(),'added_on'=> Carbon::now()->toDateString()]); 
        }

        if($request->day4)
        {
            $day4 =  DB::table('special_working_days')->where('emp_id',$employeeId)->where('dated',$request->day4)->where('deleted',0)->count();  
            
            if(!$day4)
                DB::table('special_working_days')->insert(['emp_id'=>$employeeId,'dated'=>$request->day4,'admin'=>Auth::id(),'added_on'=> Carbon::now()->toDateString()]); 
        }

        if($request->day5)
        {
            $day5 =  DB::table('special_working_days')->where('emp_id',$employeeId)->where('dated',$request->day5)->where('deleted',0)->count();  
            
            if(!$day5)
                DB::table('special_working_days')->insert(['emp_id'=>$employeeId,'dated'=>$request->day5,'admin'=>Auth::id(),'added_on'=> Carbon::now()->toDateString()]); 
        }

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Saturdays Added!');                  
    } 
//---------------------------------------------------------------------------------------------------------------------------------------- 

    public function addSalary($employeeId)
    {
        $employeeId = base64_decode($employeeId);
        $employee = Employee::select('fullname')->where('employee_id',$employeeId)->first();
        $branches = Branch::orderBy('name')->get();

        return view('employees.addSalary',compact('branches','employee'));  
    }     
//----------------------------------------------------------------------------------------------------------------------------------------
     public function saveSalary($employeeId,Request $request)
    {
        $employeeId = base64_decode($employeeId);

        $this->validate($request, [
        'labour_card_under' => 'required',
        'basic' => 'required|numeric',
        'accomodation' => 'required|numeric',
        'travel' => 'required|numeric',
        'other' => 'required|numeric',
        'iban' => 'required',]); 

        $salary = new EmployeesSalary(array( 
                                'employee_id'=>$employeeId,
                                'basic'=>$request->basic,
                                'accomodation'=>$request->accomodation,
                                'travel'=>$request->travel,
                                'other'=>$request->other,
                                'iban'=>$request->iban,
                                'labour_card_under'=>$request->labour_card_under,
                                'edit_reason' => "Adding First Time",
                                ));
        $salary->save();

        $action = "Salary details added by ";  
        DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);  

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Salary Details Added!');  
    }     
//----------------------------------------------------------------------------------------------------------------------------------------

    public function editSalary($employeeId)
    {
        $employeeId = base64_decode($employeeId);
        $employee = Employee::select('fullname')->where('employee_id',$employeeId)->first();
        $branches = Branch::orderBy('name')->get();
        
        $salary = EmployeesSalary::select('employees_salary.*','branches.name AS wps')
                                ->leftjoin('branches','employees_salary.labour_card_under','=','branches.id')
                                ->where('employee_id',$employeeId)
                                ->first();

       if($salary->labour_card_under==0)
          $salary->wps = "No WPS";    

       $salary->totalT =   $salary->basic+$salary->accomodation+$salary->travel+$salary->other;                   

        return view('employees.editSalary',compact('branches','employee','salary'));  
    }     
//----------------------------------------------------------------------------------------------------------------------------------------
     public function editSaveSalary($employeeId,Request $request)
    {
        $employeeId = base64_decode($employeeId);

        $this->validate($request, [
        'labour_card_under' => 'required',
        'basic' => 'required|numeric',
        'accomodation' => 'required|numeric',
        'travel' => 'required|numeric',
        'other' => 'required|numeric',
        'iban' => 'required',
        'edit_reason' => 'required',]); 

        $salary = EmployeesSalary::where('employee_id',$employeeId)->first();

        if($salary->basic != $request->basic || $salary->accomodation != $request->accomodation || $salary->travel != $request->travel || 
           $salary->other != $request->other || $salary->iban != $request->iban)
        { 
            $salary->basic = $request->basic;
            $salary->accomodation = $request->accomodation; 
            $salary->travel = $request->travel; 
            $salary->other = $request->other; 
            $salary->iban = $request->iban;
            $salary->edit_reason = $request->edit_reason;
            $salary->verification1 = 0;
            $salary->verification2 = 0;
            $salary->verification3 = 0;

            $salary->save();

            $action = "Salary details changed by ";  
            DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);  

            return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Salary Details Changed!'); 

        }

        else
            return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('warningStatus', 'No edits made!');

       
         
    }

//---------------------------------------------------------------------------------------------------------------------------------------------            

public function searchBind(Request $request)
    {
        $keyword = $request->keyword;

        $ids = array(0,2,3,1); 
        $ids_ordered = implode(',', $ids);  

        if($keyword=='noLabourCardCheck')
         $employees = Employee::select('employees.*', 'visa_branch.name AS visa_in' , 'work_branch.name AS working_for','employees_salary.labour_card_under')
                            ->leftjoin('branches AS visa_branch','employees.visa_under', '=', 'visa_branch.id')
                            ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                            ->leftjoin('employees_salary','employees.employee_id', '=', 'employees_salary.employee_id')
                            ->where(function($query){
                                     $query->where('employees.working_under', Auth::user()->branch);
                                     $query->where('employees.deleted', 0);
                             }) 
                             ->where(function($query) use ($keyword){
                                     $query->where('employees_salary.labour_card_under', 0);
                                     $query->orWhereNull('employees_salary.labour_card_under');
                             })            
                            ->get();

         else               
          $employees = Employee::select('employees.*', 'visa_branch.name AS visa_in' , 'work_branch.name AS working_for')
                            ->leftjoin('branches AS visa_branch','employees.visa_under', '=', 'visa_branch.id')
                            ->leftjoin('branches AS work_branch','employees.working_under', '=', 'work_branch.id')
                            ->where(function($query){
                                     $query->where('employees.working_under', Auth::user()->branch);
                             }) 
                             ->where(function($query) use ($keyword){
                                $query->orwhere('fullname', 'like', '%'.$keyword.'%');
                                $query->orWhere('employee_id', 'like', '%'.$keyword.'%');
                                $query->orWhere('mobile', 'like', '%'.$keyword.'%');
                             }) 
                             ->orderByRaw(DB::raw("FIELD(deleted, $ids_ordered)"))           
                            ->get();
 if($employees)
 {
?>
<table class="table ">
            <thead><th> Id</th><th>Name</th><th>Visa Under</th><th>Working For</th><th>Bonus Category</th><th>Mobile</th><th></th></thead>
  <?php  
  foreach ($employees as $employee) {
    if($employee->visa_under==-1) $employee->visa_in = "Under Process"; elseif($employee->visa_under==-2) $employee->visa_in = "Spouse"; 
    elseif($employee->visa_under==-3) $employee->visa_in = "Guardian"; 
    ?>
<tr>
    <td><?php echo str_ireplace($keyword,"<span class=\"text-danger\">".$keyword."</span>",$employee->employee_id);?></td>
    <td><?php echo str_ireplace($keyword,"<span class=\"text-danger\">".$keyword."</span>",$employee->fullname);?></td>
    <td><?php echo $employee->visa_in;?></td>
    <td>
        <span class="<?php if($employee->deleted==1) echo "badge badge-inverse"; 
                      else if($employee->deleted==2) echo "badge badge-danger"; 
                      else if($employee->deleted==3) echo "badge badge-warning"; ?>">
                      <?php echo $employee->working_for;?>
        </span>
    </td>
    <td><?php echo $employee->bonus_category;?></td>
    <td><?php echo str_ireplace($keyword,"<span class=\"text-danger\">".$keyword."</span>",$employee->mobile);?></td>
    <td><a href="/employees/<?php echo base64_encode($employee->employee_id);?>/profile"><i class="ion-person fa-lg"></i></a></td>
</tr>
<?php
         } //foreach ($students as $students   
     }//if($students)
     else echo "<br><span class=\"badge badge-danger\"><strong>No matching results found....</strong></span>";
}         
//----------------------------------------------------------------------------------------------------------------------------------------

}
