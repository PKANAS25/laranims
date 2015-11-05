<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Subscription;
use App\SubscriptionHour;
use App\Invoice;
use Carbon\Carbon;
use Auth;
use Session;
 
use  Validator;

use DateTime;
use DateInterval;
use DatePeriod;

class subscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($studentId,$standard)
    {
        $studentId = base64_decode($studentId);
        $standard = base64_decode($standard);
        $groups = DB::table('payment_groups') 
                             ->where('group_id','!=',6)
                             ->orderBy('group_id','DESC')
                             ->get();

        
        $offers = DB::table('offers')
                    ->leftjoin('offer_assigns','offer_assigns.offer_id','=','offers.offer_id') 
                    ->select('offers.*','offer_assigns.offer_id','offer_assigns.nursery_id')
                    ->where('offers.expiry_date','>',Carbon::now()->toDateString())
                    ->where('offers.type',1)
                    ->where('offers.deleted',0)
                    ->where('offer_assigns.nursery_id',Auth::user()->branch) 
                    ->get();                    


//$start_date='2015-10-13'; $end_date='2016-10-24';

                          


        return view('students.subscriptionAdd',compact('studentId','groups','offers','standard'));
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
    public function store(Request $request,$studentId,$standard)
    {    
         
         $start_date =$request->get('start_date');
         $subscription_type =$request->get('subscription_type');
         $studentId = base64_decode($studentId);
         $standard = base64_decode($standard);

         list($conflict,$end_date) = $this->conflictCheck($studentId,$subscription_type,$start_date);
         
         //$end_date = $conflicter(2);

         $discount =$request->get('discount');
         $discount_reason =$request->get('discount_reason'); 

         $trans =$request->get('trans'); 
         $offer =$request->get('offer'); 
         


        $validator = Validator::make($request->all(),[
            'subscription_type' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'trans' => 'required',
         ]    
        );

        $validator->after(function($validator) use($conflict,$discount,$discount_reason) { 
 
            if ($conflict) {
                $validator->errors()->add('start_date', 'Date Conflicts. Please check previous subscriptions and proceed');
            }

            if($discount>0 && $discount_reason==""){
                $validator->errors()->add('discount', 'You must provide a reason for discount');
            }
        });


        if($validator->fails()){
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else{

            $registration =$request->get('registration');
            $other =$request->get('other');

            $settings = DB::table('payment_settings') 
            ->select('payment_settings.*') 
            ->where('standard',$standard ) 
            ->where('group_id',$subscription_type ) 
            ->where('branch',Auth::user()->branch ) 
            ->first();

            ($registration) ? $registrationFee=$settings->{'Registration fee'}:$registrationFee=0;
            ($other) ? $otherFee=$settings->Other:$otherFee=0;

            $tuitionFee = $settings->{'Tuition Fee'};

            if($offer){
             
             $offering = DB::table('offers') 
            ->select('offers.*') 
            ->where('offer_id',$offer) 
            ->first();

            $discount = $tuition_fee*($offering->percentage/100);
            $discount_reason = "Offer - ".$offering->title;
            }

            

            $bookFee = $settings->{'Book Fee'};
            $busFee =  $settings->{'Bus Fee'};
            $busFeeOneway = $settings->{'Bus Fee Oneway'};

            $thisSubscriptionPay =  $registrationFee+($tuitionFee-$discount)+$bookFee+$otherFee;
                        
                        
                        $transportationFee =0;
                        
                        if($trans==1)
                        {$thisSubscriptionPay = $thisSubscriptionPay+$busFee; $transportationFee = $busFee; }
                        
                        else if($trans==2)
                        { $thisSubscriptionPay = $thisSubscriptionPay+$busFeeOneway; $transportationFee = $busFeeOneway; }


                        if($thisSubscriptionPay>=0){
                            $subscription = new Subscription(array(
                               'student_id' => $studentId,
                               'subscription_type' => $subscription_type,
                               'enroll_date' => Carbon::now()->toDateString(),
                               'start_date' => $start_date,
                               'end_date' => $end_date,
                               'registration_fee' => $registrationFee,
                               'other_fee' => $otherFee,
                               'transportation_fee' => $transportationFee,
                               'trans' => $trans,
                               'discount' => $discount,
                               'discount_reason' => $discount_reason,
                               'offer_id' => $offer,
                               'subscribed_by' => Auth::id(),
                               'amount' => $thisSubscriptionPay,
                               'current_standard' => $standard
                               ));

                            $subscription->save();

                            Session::flash('status', 'New subscription saved!');
                            return redirect()->action('StudentsController@profile', [base64_encode($studentId)]) ;
                }

            
            }  
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






    public function subsCheck(Request $request)
    {
         //$subscription = 
         $start_date =$request->get('start_date');
         $subscription_type =$request->get('subscription_type');
         $studentId =$request->get('studentId');
         

         if($subscription_type=="" || $start_date=="")
            return response()->json(['valid' => 'false', 'message' => 'Please chose subscription type and starting date','available'=>'false']);
        else
        {
            if($subscription_type==1)
            {  
                                
                $end_date = (new Carbon($start_date))->addDays(330)->toDateString();
                
            } 

            else if($subscription_type==2)
                {
                    $end_date = (new Carbon($start_date))->addDays(90)->toDateString();
                } 
                
            else if($subscription_type==3)
                {
                    $end_date = (new Carbon($start_date))->addDays(30)->toDateString();
                } 
                
            else if($subscription_type==4)
                {
                   $end_date = (new Carbon($start_date))->addDays(7)->toDateString();
                }

                if($subscription_type==5)
                { 
                   $end_date = $start_date;
                   $conflict = Subscription::whereRaw('? BETWEEN `start_date` AND `end_date`')->setBindings([$start_date])
                                            ->where('deleted',0)
                                            ->where('refunded',0)
                                            ->where('student_id',$studentId)
                                            ->count(); 
                   
                    
                }//if($subscription_type==5)
                
                else{

                     $conflict = Subscription::where('end_date','>=',$start_date)
                                               ->where('start_date','<=',$end_date)
                                               ->where('deleted',0)
                                               ->where('refunded',0)
                                               ->where('student_id',$studentId)
                                               ->count(); 
                 
                
                }

        if($conflict==0)
           return response()->json(['valid' => 'true', 'message' => " ",'available'=>'true']);
        
        else
           return response()->json(['valid' => 'false', 'message' => "Date conflicts. Please check previous subscriptions and proceed",'available'=>'false']);

        }
        
    }

//-----------------------------------------------------------------------------------------------
    public static function conflictCheck($studentId,$subscription_type,$start_date){


          $conflict=0;
          $end_date="";

         if($subscription_type!="" && $start_date!="")
          {
            if($subscription_type==1)
            {  
                                
                $end_date = (new Carbon($start_date))->addDays(330)->toDateString();
                
            } 

            else if($subscription_type==2)
                {
                    $end_date = (new Carbon($start_date))->addDays(90)->toDateString();
                } 
                
            else if($subscription_type==3)
                {
                    $end_date = (new Carbon($start_date))->addDays(30)->toDateString();
                } 
                
            else if($subscription_type==4)
                {
                   $end_date = (new Carbon($start_date))->addDays(7)->toDateString();
                }

                if($subscription_type==5)
                { 
                   $end_date = $start_date;
                   $conflict = Subscription::whereRaw('? BETWEEN `start_date` AND `end_date`')->setBindings([$start_date])
                                            ->where('deleted',0)
                                            ->where('refunded',0)
                                            ->where('student_id',$studentId)
                                            ->count(); 
                   
                    
                }//if($subscription_type==5)
                
                else{

                     $conflict = Subscription::where('end_date','>=',$start_date)
                                               ->where('start_date','<=',$end_date)
                                               ->where('deleted',0)
                                               ->where('refunded',0)
                                               ->where('student_id',$studentId)
                                               ->count(); 
                 
                
                }
            }
             
            return array($conflict,$end_date);
    }

//-----------------------------------------------------------------------------------------------

public function delete(Request $request,$studentId)
    {
        $this->validate($request, [
        'delete_reason' => 'required|min:4',
        
    ]);

        $subscriptionId =  base64_decode($request->subscriptionId);         

        $subscription = Subscription::where('subscription_id',$subscriptionId)->where('locked',0)->first();
        if($subscription)
        {
            $subscription->deleted=1;
            $subscription->deleted_by=Auth::id();
            $subscription->delete_reason=$request->delete_reason;
            $subscription->delete_date=Carbon::now()->toDateString();
            $subscription->save();

            Session::flash('status', 'Subscription deleted!');              
          return redirect()->action('StudentsController@profile', [$studentId]) ; 
        }
        else{return redirect()->intended('profile/student/'.$studentId)->withErrors(['Technical Error. Contact Administrator']);}
    }


//-----------------------------------------------------------------------------------------------

public function lockUnlock(Request $request)
    {
         
        
        $subscriptionId =  $request->subscriptionId;         

        $subscription = Subscription::where('subscription_id',$subscriptionId)->first();
        if($subscription && $request->action=="unlock")
        {
            $subscription->locked=0;            
            $subscription->save();
            ?>
            &nbsp;&nbsp; &nbsp;&nbsp; <button class="btn btn-sm" id="subsLock<?php echo $subscriptionId;?>" value="<?php echo $subscriptionId;?>"> <i class="fa fa-unlock"></i></button>
            <?php
          
        }

        else if($subscription && $request->action=="lock")
        {
            $subscription->locked=1;            
            $subscription->save();
            ?>
            &nbsp;&nbsp; &nbsp;&nbsp; <button class="btn btn-sm" id="subsUnlock<?php echo $subscriptionId;?>" value="<?php echo $subscriptionId;?>"> <i class="fa fa-lock"></i></button>
            <?php
          
        }

    }

//-----------------------------------------------------------------------------------------------
 public function addHours($studentId,$standard)
    {
         
         
        return view('students.hoursAdd',compact('studentId','standard'));
    }
//-----------------------------------------------------------------------------------------------   

public function saveHours(Request $request,$studentId,$standard)
    {    
         
         $dated =$request->get('dated');
         $no_of_hours =$request->get('no_of_hours');
         $studentId = base64_decode($studentId);
         $standard = base64_decode($standard);

      

         $discount =$request->get('discount');
         $discount_reason =$request->get('discount_reason'); 

       
         


        $validator = Validator::make($request->all(),[
            'no_of_hours' => 'required|integer|between:1,24',
            'dated' => 'required|date_format:Y-m-d',
             
         ]    
        );

        $validator->after(function($validator) use($discount,$discount_reason) { 
  
            if($discount>0 && $discount_reason==""){
                $validator->errors()->add('discount', 'You must provide a reason for discount');
            }
        });


        if($validator->fails()){
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else{
            $settings = DB::table('payment_settings') 
            ->select('payment_settings.*') 
            ->where('standard',$standard ) 
            ->where('group_id',6) 
            ->where('branch',Auth::user()->branch ) 
            ->first();

            $tuition_fee = $no_of_hours*$settings->{'Tuition Fee'};
                         
            $thisSubscriptionPay = $tuition_fee-$discount;                        
            
            if($thisSubscriptionPay>=0)
            {
                

                                $subscriptionHour = new SubscriptionHour(array(
                               'student_id' => $studentId,
                               'enroll_date' => Carbon::now()->toDateString(),
                               'dated' => $dated,
                               'no_of_hours' => $no_of_hours,
                               'notes' => $request->notes,
                               'discount' => $discount,
                               'discount_reason' => $discount_reason,
                               'subscribed_by' => Auth::id(),
                               'amount' => $thisSubscriptionPay,
                               'current_standard' => $standard
                               ));

                            $subscriptionHour->save();

                            Session::flash('status', 'Extra hours stay saved!');
                            return redirect()->action('StudentsController@profile', [base64_encode($studentId)]) ;
                            
            }
        }//else
    }
 //-----------------------------------------------------------------------------------------------   

 public function refund($studentId,$standard)
    {
        $studentId = base64_decode($studentId);
        $standard = base64_decode($standard);

        
        $notOk = Invoice::where('deleted',0)->where('cheque',1)->where('student_id',$studentId)->count();
        $posted = 0;

        return view('students.refund',compact('studentId','standard','notOk','posted'));
    }
 //-----------------------------------------------------------------------------------------------   

public function subsCheckRefund(Request $request)
    {
         //$subscription = 
         $last_date =$request->get('last_date');
         $studentId =$request->get('studentId');


         
        $exists = Subscription::whereRaw('? BETWEEN `start_date` AND `end_date`')->setBindings([$last_date])
                                            ->where('deleted',0)
                                            ->where('refunded',0)
                                            ->where('student_id',$studentId)
                                            ->count(); 
         

        if($exists>0)
           return response()->json(['valid' => 'true', 'message' => " ",'available'=>'true']);
        
        else
           return response()->json(['valid' => 'false', 'message' => "Date conflicts. No subscriptions in the selected date",'available'=>'false']);

        }
 //-----------------------------------------------------------------------------------------------   

public function refundPost($studentId,$standard,Request $request)
    {
        $studentId = base64_decode($studentId);
        $standard = base64_decode($standard);

        $last_date =$request->get('last_date');
        
        $notOk = Invoice::where('deleted',0)->where('cheque',1)->where('student_id',$studentId)->count();

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

            $balance = $totalPayable-$totalPaid;

             

        $subscription = DB::table('subscriptions') 
                         ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                         ->select('subscriptions.*', 'payment_groups.group_name')
                         ->where('student_id','?')
                         ->where('deleted','?')
                         ->where('refunded','?')                          
                         ->whereRaw('? BETWEEN `start_date` AND `end_date`')->setBindings([$studentId,0,0,$last_date])
                         ->first();
      
       $start_date = $subscription->start_date;
       $stayed_days = ((strtotime($last_date) - strtotime($start_date)) / (60 * 60 * 24))+1;
       
       
       $stayed_days = round($stayed_days);
        
       $holidays_count = 0;
        
       $start = date('Y-m-d',strtotime($start_date));
       $end =  date('Y-m-d',strtotime($last_date));
        
       $start = new DateTime($start);
       $end = new DateTime($end);
       $interval = DateInterval::createFromDateString('1 day');
       $period = new DatePeriod($start, $interval, $end);

       foreach ($period as $dt)
        {
            if ($dt->format("N") == 5 || $dt->format("N") == 6)
            {
                $holidays_count++;
            }
        }
        
        $stayed_days = $stayed_days-$holidays_count;
    
        $settings = DB::table('payment_settings') 
          ->select('payment_settings.*') 
          ->where('standard',$standard ) 
          ->where('group_id',5 ) 
          ->where('branch',Auth::user()->branch ) 
          ->first();

            
                      
                         
                         
        $tuition_fee = $settings->{'Tuition Fee'};                                                
        $bus_fee = $settings->{'Bus Fee'};
        $bus_fee_oneway = $settings->{'Bus Fee Oneway'};
                        
                         
        $thisSubscriptionPay =  $tuition_fee;
                        
        if($subscription->trans==1)
        $thisSubscriptionPay = $thisSubscriptionPay+$bus_fee;
        else if($subscription->trans==2)
        $thisSubscriptionPay = $thisSubscriptionPay+$bus_fee_oneway;                        
                        
        $non_refundable_amount = $stayed_days*$thisSubscriptionPay;

        ////////////////////////////////////Added Transportation//////////////////////////////////////////////////

    $non_refundable_trans = 0;
    if($subscription->subscription_type<3 && $subscription->trans==3) 
    { 
    


    $addedTrans = DB::table('added_transportations') 
                         ->where('subscription_id',$subscription->subscription_id) 
                         ->whereRaw('? BETWEEN `start_date` AND `end_date`')->setBindings([$last_date])
                         ->where('deleted',0)
                         ->first();
    
        if($addedTrans)
        {
              
            $start_trans = date('Y-m-d',strtotime($addedTrans->start_date));
              
            $stayed_days_trans = ((strtotime($last_date) - strtotime($addedTrans->start_date)) / (60 * 60 * 24))+1; 
            $stayed_days_trans = round($stayed_days_trans);
            
            $start_trans = new DateTime($start_trans);
            $holidays_count_trans = 0;
            
            
            
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_trans, $interval, $end);
            
            foreach ($period as $dt)
            {
                if ($dt->format("N") == 5 || $dt->format("N") == 6)
                {  
                    $holidays_count_trans++;
                }
            }
             
             $stayed_days_trans-=$holidays_count_trans;
            
            
             if($addedTrans->trans==1)
                $non_refundable_trans = $stayed_days_trans*$bus_fee;
             
             else if($addedTrans->trans==2)
                $non_refundable_trans = $stayed_days_trans*$bus_fee_oneway;
          
        }//if($addedTrans)
//////////////////////////////// Finished added trans/////////////////////////////////////////////////////
 

    $addedTrans2 = DB::table('added_transportations') 
                         ->where('subscription_id',$subscription->subscription_id) 
                         ->whereRaw('? > `end_date`')->setBindings([$last_date])
                         ->where('deleted',0)
                         ->first();
    
    if($addedTrans2)
    {
          foreach($addedTrans2 as $added2)
          $non_refundable_trans+=$added2->amount;
    }
    
}//if($subscription->subscription_type<3 && $subscription->trans==3) 

    $posted = 1;
    $today = Carbon::now()->toDateString();
    $non_refundable_grand = ($non_refundable_amount+$non_refundable_trans)+$subscription->registration_fee+$subscription->other_fee;
    $refundable_grand = ($subscription->amount - ($non_refundable_amount+$non_refundable_trans))-($subscription->registration_fee+$subscription->other_fee);

    $refundable_final = $refundable_grand-$balance;

    $fullRefund =  $FullRefundable_grand = $subscription->amount-($subscription->registration_fee+$subscription->other_fee) ;
    $fullRefundFinal = $FullRefundable_grand - $balance;
    $fullNonRefund = ($subscription->registration_fee+$subscription->other_fee);

    if($notOk)
    {
        $posted = 0;
        return view('students.refund',compact('studentId','standard','notOk','posted'));
    }

    return view('students.refund',compact('studentId','standard','posted','notOk','today','totalPayable','totalPaid','balance','last_date','subscription','stayed_days','non_refundable_grand','refundable_grand','fullRefundFinal','fullNonRefund','refundable_final'));
}     
 //-----------------------------------------------------------------------------------------------   
public function ticketSave($studentId,$standard,Request $request)
    {
        $studentId = base64_decode($studentId);
        $standard = base64_decode($standard);

        $last_date = $request->refund_date;
        $ticket = $request->ticket;

        $subscription_chosen = $request->subscription_chosen;

        echo $ticket;

        if($ticket=="Issue refund ticket")
        {
            $refundable_amount = $request->refundable_amount;
            $non_refundable = $request->non_refundable; 
             
        }
        elseif($ticket=="Issue Full Refund")
        {
            $refundable_amount = $request->full_refundable_amount;
            $non_refundable = $request->full_non_refundable; 
        }

        DB::table('refund_tickets')->where('store',0)->where('student_id',$studentId)->delete();
 

        DB::table('refund_tickets')->insert([
                  'student_id' => $studentId,
                  'subscription_id' => $subscription_chosen,
                  'non_refundable_amount' => $non_refundable,
                  'refundable_amount' => $refundable_amount,
                  'last_date' => $last_date,
                  'admin' => Auth::id()
                  ]);

         return redirect(action('StudentsController@profile', base64_encode($studentId)))->with('status', 'Refund Ticket Issued!. Contact Finance dept. for updates');
    }
 //-----------------------------------------------------------------------------------------------   



}


