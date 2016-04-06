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

class EmployeesControllerHR extends Controller
{
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
                             ->where(function($query) use ($keyword){
                                $query->orwhere('fullname', 'like', '%'.$keyword.'%');
                                $query->orWhere('employee_id', 'like', '%'.$keyword.'%');
                                $query->orWhere('mobile', 'like', '%'.$keyword.'%');
                             })
                             ->orderByRaw(DB::raw("FIELD(deleted, $ids_ordered)")) 
                             ->orderBy('working_under')           
                            ->get();
 if(count($employees))
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
  

public function resignation($employeeId)
    {
        $employeeId = base64_decode($employeeId);

        $employee = Employee::where('employee_id',$employeeId)->first();  
        $employee->deleted=3;
        $employee->save();

        $mailingList = DB::table('hr_mailing_lists')->where('action',1)->get();
        $emails = array_pluck($mailingList, 'mail'); 
            
        $subject = "Employee Resignation Alert ";

        $body = "General details of the employee\n\nEmployee ID: ".$employeeId."\nName: ".$employee->fullname."\nJoining Date: ".$employee->joining_date."\n\n--Moved by: ".Auth::user()->name; 
             
            Mail::queue([], [], function ($message) use($subject,$emails,$body) 
            {
               $message->from('anas.acmg@gmail.com', 'NMS v3.0');
               $message->to($emails);
               $message->subject($subject);
               $message->setBody($body);
            });

        $action = "Employee status changed into  resigned by ";
        DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Employee status changed into resigned!');

    }
//----------------------------------------------------------------------------------------------------------------------------------------
 

 public function restore($employeeId)
    {
        $employeeId = base64_decode($employeeId);

        $employee = Employee::where('employee_id',$employeeId)->first();  
        $employee->deleted=0;
        $employee->save();

        $mailingList = DB::table('hr_mailing_lists')->where('action',1)->get();
        $emails = array_pluck($mailingList, 'mail'); 
            
        $subject = "Employee Restore Alert ";

        $body = "General details of the employee\n\nEmployee ID: ".$employeeId."\nName: ".$employee->fullname."\nJoining Date: ".$employee->joining_date."\n\n--Restored by: ".Auth::user()->name; 
             
            Mail::queue([], [], function ($message) use($subject,$emails,$body) 
            {
               $message->from('anas.acmg@gmail.com', 'NMS v3.0');
               $message->to($emails);
               $message->subject($subject);
               $message->setBody($body);
            });

        $action = "Employee has been restored by ";
        DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Employee restored successfully!');

    }
//----------------------------------------------------------------------------------------------------------------------------------------
  

public function terminate($employeeId)
    {
        $employeeId = base64_decode($employeeId);

        $employee = Employee::where('employee_id',$employeeId)->first();  
        $employee->deleted=2;
        $employee->save();

        $mailingList = DB::table('hr_mailing_lists')->where('action',1)->get();
        $emails = array_pluck($mailingList, 'mail'); 
            
        $subject = "Employee Termination Alert ";

        $body = "General details of the employee\n\nEmployee ID: ".$employeeId."\nName: ".$employee->fullname."\nJoining Date: ".$employee->joining_date."\n\n--Moved by: ".Auth::user()->name; 
             
            Mail::queue([], [], function ($message) use($subject,$emails,$body) 
            {
               $message->from('anas.acmg@gmail.com', 'NMS v3.0');
               $message->to($emails);
               $message->subject($subject);
               $message->setBody($body);
            });

        $action = "Employee status changed into  terminated by ";
        DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Employee status changed into terminated!');

    }    
//----------------------------------------------------------------------------------------------------------------------------------------

    public function remove($employeeId)
    {
        $employeeId = base64_decode($employeeId);

        $employee = Employee::where('employee_id',$employeeId)->first();  
        $employee->deleted=1;
        $employee->save();

        $mailingList = DB::table('hr_mailing_lists')->where('action',1)->get();
        $emails = array_pluck($mailingList, 'mail'); 
            
        $subject = "Employee Deletion Alert ";

        $body = "General details of the employee\n\nEmployee ID: ".$employeeId."\nName: ".$employee->fullname."\nJoining Date: ".$employee->joining_date."\n\n--Deleted by: ".Auth::user()->name; 
             
            Mail::queue([], [], function ($message) use($subject,$emails,$body) 
            {
               $message->from('anas.acmg@gmail.com', 'NMS v3.0');
               $message->to($emails);
               $message->subject($subject);
               $message->setBody($body);
            });

        $action = "Employee deleted by ";
        DB::table('employees_changes')->insert(['employee_id' => $employeeId,'changer_id' => Auth::id(),'action' => $action,'date_time' => Carbon::now()]);

        return redirect()->action('EmployeesController@profile',base64_encode($employeeId))->with('status', 'Employee deleted!');

    }   

//----------------------------------------------------------------------------------------------------------------------------------------

    public function transfer($employeeId)
    {
        $employeeId = base64_decode($employeeId);
        echo $employeeId;
    }
//----------------------------------------------------------------------------------------------------------------------------------------

}
