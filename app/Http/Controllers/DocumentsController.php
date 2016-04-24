<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Carbon\Carbon; 

use App\Employee;

use File;
use Image;
use Validator; 

class DocumentsController extends Controller
{
    
 public function staffDocExpiryChange(Request $request)
   {
      $employee = $request->employee;
      $dated = $request->dated;  
      $docId = $request->id;  

      if($docId==1)       $queryField ="passport_expiry";   
      elseif($docId==2)   $queryField ="visa_expiry";
      elseif($docId==14)  $queryField ="labour_card_expiry";


      $exists = DB::table('staff_docs_details')
                  ->where('emp_id',$employee)
                  ->where('doc_id',$docId)
                  ->first();

      if($exists && $dated)
      {
        DB::table('staff_docs_details')->where('id',$exists->id)->update(['expiry_date'=>$dated]); 
        
        if($docId==1 || $docId==2 || $docId==14) 
           Employee::where('employee_id',$employee)->update([$queryField=>$dated]); 

        echo "<i class=\"fa fa-check-circle-o  text-success\"></i>";
      }

      elseif(!$exists && $dated)
      {
        DB::table('staff_docs_details')->insert(['emp_id' => $employee,'doc_id' => $docId,'expiry_date' => $dated]); 

        if($docId==1 || $docId==2 || $docId==14) 
           Employee::where('employee_id',$employee)->update([$queryField=>$dated]); 

        echo "<i class=\"fa fa-check-circle-o  text-success\"></i>";
      }

   }

//---------------------------------------------------------------------------------------------------------------------------------------------

   public function staffDocUpload(Request $request)
   {
$imageName='ppps.jpg';
    if($request->file('fileToUpload'))
        $request->file('fileToUpload')->move(base_path().'/public/uploads/staff_docs/', $imageName);

       return $request->docId;
    //   if($request->file('file1_g'))
    //     return "Yes"
    // else 
    //     return "no";
    
  }
//---------------------------------------------------------------------------------------------------------------------------------------------


}


 