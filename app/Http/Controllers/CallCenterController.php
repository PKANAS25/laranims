<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\User;
use App\Student;

use Auth;

use Validator;
use Carbon\Carbon;

class CallCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unassigned($viewer)
    {   
         // /where('active',1)
        $agents = User::whereHas('roles' , function($q){
                        $q->where('name', 'CallCenterAgent');
                    })
                    ->get();


        if($viewer=="unassigned")
        {
            $refundTickets = DB::table('refund_tickets')
                                ->select('refund_tickets.*','students.full_name', 'users.name','branches.name AS branchName')
                                ->leftjoin('students','refund_tickets.student_id','=','students.student_id')
                                ->leftjoin('users','refund_tickets.admin','=','users.id')
                                ->leftjoin('branches','students.branch','=','branches.id')
                                ->where('processed',0)
                                ->where('call_center_agent',0)
                                ->where('subscription_id','>',0)
                                ->get();
            
            foreach($refundTickets as $refundTicket)
            {
                 
                   $subscription = DB::table('subscriptions')
                                        ->select('subscriptions.*','payment_groups.group_name')
                                        ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                                        ->where('subscriptions.subscription_id',$refundTicket->subscription_id)
                                        ->first() ;

                    $refundTicket->group_name = $subscription->group_name;
                   
                     
            }                    
       }
//--------------------------------************************************************************---------------------------------------
        elseif($viewer=="assigned")
        {
            $refundTickets = DB::table('refund_tickets')
                                ->select('refund_tickets.*','students.full_name', 'users.name','branches.name AS branchName')
                                ->leftjoin('students','refund_tickets.student_id','=','students.student_id')
                                ->leftjoin('users','refund_tickets.call_center_agent','=','users.id')
                                ->leftjoin('branches','students.branch','=','branches.id')
                                ->where('processed',0)
                                ->where('call_center_agent','>',0)
                                ->where('subscription_id','>',0)
                                ->get();
            
            foreach($refundTickets as $refundTicket)
            {
                 
                   $subscription = DB::table('subscriptions')
                                        ->select('subscriptions.*','payment_groups.group_name')
                                        ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                                        ->where('subscriptions.subscription_id',$refundTicket->subscription_id)
                                        ->first() ;

                    $refundTicket->group_name = $subscription->group_name;
                   
            }
        }
        

        return view('callCenter.unassignedRefundTickets',compact('refundTickets','viewer','agents'));
    }

  //------------------------------------------------------------------------------------------------------------------------------------------------------
    public function assignRefundAgents($viewer,Request $request)
    {
        $ticketIds = $request->get('ticketIds');
        $newAgents = $request->get('newAgents');

        foreach( $newAgents as $index => $newAgent ) 
        {
            
           if($newAgent>0)
           {
                DB::table('refund_tickets')
                    ->where('request_id', $ticketIds[$index])
                    ->update(['call_center_agent' => $newAgent]);
           }
        }
    
        return redirect(action('CallCenterController@unassigned', 'unassigned'))->with('status', 'Agents Assigned');

    }

    //------------------------------------------------------------------------------------------------------------------------------------------------------


    public function feedbacks($viewer)
    {
        

        if($viewer=="noreview")
        {
            $refundTickets = DB::table('refund_tickets')
                                ->select('refund_tickets.*','students.full_name', 'users.name','branches.name AS branchName')
                                ->leftjoin('students','refund_tickets.student_id','=','students.student_id')
                                ->leftjoin('users','refund_tickets.admin','=','users.id')
                                ->leftjoin('branches','students.branch','=','branches.id')
                                ->where('processed',0)
                                ->where('call_center_agent',Auth::id())
                                ->where('subscription_id','>',0)
                                ->whereNull('review')
                                ->get();
            
            foreach($refundTickets as $refundTicket)
            {
                 
                   $subscription = DB::table('subscriptions')
                                        ->select('subscriptions.*','payment_groups.group_name')
                                        ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                                        ->where('subscriptions.subscription_id',$refundTicket->subscription_id)
                                        ->first() ;

                    $refundTicket->group_name = $subscription->group_name;
                  
                     
            }                    
       }
//--------------------------------************************************************************---------------------------------------
        elseif($viewer=="reviewed")
        {
             $refundTickets = DB::table('refund_tickets')
                                ->select('refund_tickets.*','students.full_name', 'users.name','branches.name AS branchName')
                                ->leftjoin('students','refund_tickets.student_id','=','students.student_id')
                                ->leftjoin('users','refund_tickets.admin','=','users.id')
                                ->leftjoin('branches','students.branch','=','branches.id')
                                ->where('processed',0)
                                ->where('call_center_agent',Auth::id())
                                ->where('subscription_id','>',0)
                                ->whereNotNull('review')
                                ->get();
            
            foreach($refundTickets as $refundTicket)
            {
                 
                   $subscription = DB::table('subscriptions')
                                        ->select('subscriptions.*','payment_groups.group_name')
                                        ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                                        ->where('subscriptions.subscription_id',$refundTicket->subscription_id)
                                        ->first() ;

                    $refundTicket->group_name = $subscription->group_name;
                     
                     
            }
        }

        return view('callCenter.RefundTicketsFeedback',compact('refundTickets','viewer'));
    }
    //------------------------------------------------------------------------------------------------------------------------------------------------------
 
    public function feedbackForm($studentId,$ticketId)
    {
        $studentId = base64_decode($studentId);
        $ticketId = base64_decode($ticketId);

        $student = Student::where('student_id',$studentId)->first();

        $ticket = DB::table('refund_tickets')->where('request_id',$ticketId)->first();

        return view('callCenter.refundFeedbackForm',compact('student','ticket'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveFeedback($studentId,$ticketId,Request $request)
    {
        $validator = Validator::make($request->all(),[
            'review' => 'required',
            'cheque_name' => 'required',
            'cheque_account' => 'required',
            'cheque_bank' => 'required',
         ]    
        ); 


        if($validator->fails()){
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

       DB::table('refund_tickets')
                    ->where('request_id', base64_decode($ticketId))
                    ->update(['review' => $request->review,
                              'reviewed_by' =>Auth::id(),
                              'reviewed_on' =>Carbon::now()->toDateString(),
                              'cheque_name' =>$request->cheque_name,
                              'cheque_account' =>$request->cheque_account,
                              'cheque_bank' =>$request->cheque_bank]);

     return redirect()->action('CallCenterController@feedbacks', 'noreview')->with('status', 'Feedback Added');;
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
