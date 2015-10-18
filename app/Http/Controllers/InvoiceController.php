<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Carbon\Carbon;
use Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($studentId)
    {
        $studentId = base64_decode($studentId);
        $today = Carbon::now()->toDateString();

        DB::statement('DELETE temp_invoices,temp_invoices_custom_items FROM temp_invoices INNER JOIN temp_invoices_custom_items ON 
            temp_invoices.temp_id=temp_invoices_custom_items.invoice_id WHERE staff_id='.Auth::id()) ;
            

       $branch = DB::table('branches')
            ->where('id',Auth::user()->branch)
            ->first();

       if($branch->card_service_charge) 
            $serviceChargeFlag =$branch->card_service_charge;
       else
         $serviceChargeFlag =0;

    $student = DB::table('students') 
            
            ->select('students.*')              
            ->where('students.student_id',$studentId ) 
            ->first();
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
            


    $tempInvoice = DB::table('temp_invoices')->insertGetId(
        ['student_id' => $studentId, 'branch' => Auth::user()->branch, 'staff_id' => Auth::id()]
    );
        
      return view('students.invoiceAdd',compact('student','totalPayable','totalPaid','tempInvoice','serviceChargeFlag','today')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
