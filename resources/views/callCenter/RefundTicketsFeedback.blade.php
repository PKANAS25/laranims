@extends('master') 
 
<?php session(['title' => 'CallCenter']);
session(['subtitle' => 'feedbacksPending']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Unassigned</a></li>
				<li class="active"><a href="javascript:;">Refund Tickets</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Refund <small> Tickets</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Refund Tickets Unassigned</h4>
                        </div>
                        
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" >
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="panel-body">
                              
                               @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}   
                                        </div>
                                    @endif
                                @if($viewer=="noreview") 
                                

                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ticket No</th>
                                        <th>Branch</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Subscription</th>
                                        <th>Last Date</th>
                                        <th>Non Refundable</th>
                                        <th>Refundable</th>
                                        <th>Issued By</th>
                                        <th>FeedBack</th>
                                    </tr>
                                    </thead>
                                    <?php $i=1;?>
                                    @foreach($refundTickets as $refundTicket)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>RF{{$refundTicket->request_id}}</td>
                                        <td>{{$refundTicket->branchName}}</td>
                                        <td>{{$refundTicket->student_id}}</td>
                                        <td>{{$refundTicket->full_name}}</td>
                                        <td>{{$refundTicket->group_name}}</td>
                                        <td>{{$refundTicket->last_date}}</td>
                                        <td>{{$refundTicket->non_refundable_amount}}</td>
                                        <td>{{$refundTicket->refundable_amount}}</td>
                                        <td>{{$refundTicket->name}}</td>
                                        <td><a href="{!! action('CallCenterController@feedbackForm', array(base64_encode($refundTicket->student_id),base64_encode($refundTicket->request_id)) ) !!}" class="btn btn-primary btn-xs m-r-5"><i class="fa fa-comment-o"></i></a></td>
                                    </tr>
                                    <?php $i++;?>
                                    @endforeach
                                 
                                
                            </table>
                            @endif

                            <!------------------------------------------------------------------------------------------------------------------------------------ -->
                                @if($viewer=="reviewed") 
                                

                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ticket No</th>
                                        <th>Branch</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Subscription</th>
                                        <th>Last Date</th>
                                        <th>Non Refundable</th>
                                        <th>Refundable</th>                                         
                                        <th>Feedback</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <?php $i=1;?>
                                    @foreach($refundTickets as $refundTicket)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>RF{{$refundTicket->request_id}}</td>
                                        <td>{{$refundTicket->branchName}}</td>
                                        <td>{{$refundTicket->student_id}}</td>
                                        <td>{{$refundTicket->full_name}}</td>
                                        <td>{{$refundTicket->group_name}}</td>
                                        <td>{{$refundTicket->last_date}}</td>
                                        <td>{{$refundTicket->non_refundable_amount}}</td>
                                        <td>{{$refundTicket->refundable_amount}}</td>
                                         
                                        <td>{{$refundTicket->review}}</td>
                                        <td><a href="{!! action('CallCenterController@feedbackForm', array(base64_encode($refundTicket->student_id),base64_encode($refundTicket->request_id)) ) !!}" class="btn btn-primary btn-xs m-r-5"><i class="fa fa-comment-o"></i></a></td>
                                    </tr>
                                    <?php $i++;?>
                                    @endforeach
                                
                                
                            </table>
                            @endif  
                            <span class="text-success">Showing {{$viewer}} </span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-warning" href="/refunds/agents/tickets/noreview">Show not reviewed</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-success" href="/refunds/agents/tickets/reviewed">Show reviewed</a>                         
                        </div>  

                             </form>
                        
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection