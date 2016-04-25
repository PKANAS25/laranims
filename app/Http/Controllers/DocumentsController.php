<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Carbon\Carbon; 

use App\Employee;

use Response;

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
     $validator = Validator::make($request->all(),[
                'fileToUpload' => 'required|max:800|mimes:jpeg,jpg,pdf', ]  ); 
    if ($validator->passes())
    {
         $ext =  $request->file('fileToUpload')->getClientOriginalExtension();

         if($ext!='pdf')
            {
                $ext = 'jpg'; 
                $ext2='pdf';
            }
        else
            $ext2='jpg';

        if($request->number==1)
            {
                $imageName = $request->docId."_".$request->employee.".".$ext; 
                $imageName2 = $request->docId."_".$request->employee.".".$ext2; 
            }
        else
            {
                $imageName = $request->docId."_".$request->employee."_2.".$ext;
                $imageName2 = $request->docId."_".$request->employee."_2.".$ext2;
            }

        if (File::exists(base_path().'/public/uploads/staff_docs/'.$imageName))
            File::delete(base_path().'/public/uploads/staff_docs/'.$imageName);
        
        if (File::exists(base_path().'/public/uploads/staff_docs/'.$imageName2))
            File::delete(base_path().'/public/uploads/staff_docs/'.$imageName2);       
                            
        $request->file('fileToUpload')->move(base_path().'/public/uploads/staff_docs/', $imageName); 

        return "ok";
    }

         
  }
//---------------------------------------------------------------------------------------------------------------------------------------------
 
   public function staffDocShow($docId,$employeeId,$number)
   {
         $docId=base64_decode($docId); 
         $employeeId=base64_decode($employeeId); 
         $number=base64_decode($number);
         
         $pdfFile=0;   
         $document = DB::table('staff_docs')->select('doc_name')->where('doc_id',$docId)->first();
         $employee = Employee::select('fullname')->where('employee_id',$employeeId)->first();
         
        if($number==1)
        { 
            if(File::exists(base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'.jpg')) 
            {
             $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'.jpg'; 
              $filename = $document->doc_name."-".$employee->fullname.".jpg";
            }
          
          else 
          {
             $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'.pdf';  
             $filename = $document->doc_name."-".$employee->fullname.".pdf"; 
             $pdfFile=1;
          }
        }
        
        elseif($number==2)
        {
            if(File::exists(base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'_2.jpg'))
            {
               $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'_2.jpg'; 
               $filename = $document->doc_name."-".$employee->fullname." file 2.jpg";
            }
            
            else
            {
                $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'_2.pdf';
                $filename = $document->doc_name."-".$employee->fullname." file 2.pdf";
                $pdfFile=1;
            } 
            
        }
        

     
          if( !File::exists($storagePath))
            return view('errors.404'); 
        
           else
           {
            if($pdfFile)
            return Response::make(file_get_contents($storagePath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
                ]);
            else 
             return Response::make(file_get_contents($storagePath), 200, [
            'Content-Type' => 'image/jpg',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
            ]);
           }
            

         
    }
    
//---------------------------------------------------------------------------------------------------------------------------------------------
 
   public function staffDocDownload($docId,$employeeId,$number)
   {
         $docId=base64_decode($docId); 
         $employeeId=base64_decode($employeeId); 
         $number=base64_decode($number);
       
         $document = DB::table('staff_docs')->select('doc_name')->where('doc_id',$docId)->first();
         $employee = Employee::select('fullname')->where('employee_id',$employeeId)->first();


        
        if($number==1)
        {
            if(File::exists(base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'.jpg'))
            {
             $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'.jpg'; 
             $filename = $document->doc_name."-".$employee->fullname.".jpg";
             return Response::download($storagePath, $filename, ['Content-Type: image/jpeg']); 
            }
          
          else 
          {
             $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'.pdf';  
             $filename = $document->doc_name."-".$employee->fullname.".pdf";
             return Response::download($storagePath, $filename, ['Content-Type: application/pdf']); 
          }

            
        }
        elseif($number==2)
        {
            if(File::exists(base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'_2.jpg'))
            {
               $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'_2.jpg'; 
               $filename = $document->doc_name."-".$employee->fullname." file 2.jpg";
               return Response::download($storagePath, $filename, ['Content-Type: image/jpeg']); 
            }
            
            else
            {
             $storagePath = base_path().'/public/uploads/staff_docs/'.$docId.'_'.$employeeId.'_2.pdf';
             $filename = $document->doc_name."-".$employee->fullname." file 2.pdf";
             return Response::download($storagePath, $filename, ['Content-Type: application/pdf']);
            }

            
        }


     
          if( !File::exists($storagePath))
            return view('errors.404'); 
         
    }
    

//---------------------------------------------------------------------------------------------------------------------------------------------

}


 