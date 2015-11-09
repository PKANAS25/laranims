<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\User;

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
                if($refundTicket->subscription_id>0)
                {
                   $subscription = DB::table('subscriptions')
                                        ->select('subscriptions.*','payment_groups.group_name')
                                        ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                                        ->where('subscriptions.subscription_id',$refundTicket->subscription_id)
                                        ->first() ;

                    $refundTicket->group_name = $subscription->group_name;
                    
                                                         
                }
                else
                {
                    $refundTicket->group_name = "Excess Refund";
                     
                    
                }

                     
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
                if($refundTicket->subscription_id>0)
                {
                   $subscription = DB::table('subscriptions')
                                        ->select('subscriptions.*','payment_groups.group_name')
                                        ->leftjoin('payment_groups','subscriptions.subscription_type','=','payment_groups.group_id')
                                        ->where('subscriptions.subscription_id',$refundTicket->subscription_id)
                                        ->first() ;

                    $refundTicket->group_name = $subscription->group_name;
                    
                                                         
                }
                else
                {
                    $refundTicket->group_name = "Excess Refund";
                     
                    
                }

                     
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
