<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Subscription;
use App\SubscriptionHour;
use Carbon\Carbon;
use Auth;
use Session;
 
use  Validator;

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
               // INSERT INTO `subscriptions_hour` (`student_id`,  `enroll_date`, `dated`,`no_of_hours`,`notes`, `discount`, `discount_reason`, `subscribed_by`,`amount`,`current_standard`) VALUES (?,?,?,?,?,?,?,?,?,?)"))
                       //   
                                   // $insert_stmt->bind_param('ssssssssss', $student , $_SESSION['current_date'] , $dated, $no_of_hours,$notes,$discount,$discount_reason, $_SESSION['intLoggerIdAdmin'],$thisSubscriptionPay,$standard);  


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
}


