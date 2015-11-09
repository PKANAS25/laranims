@extends('master') 
 
<?php session(['title' => 'CallCenter']);
session(['subtitle' => 'unassigned']); ?> 

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
                              <div class="hidden-print">
                                <span class="text-success">Showing {{$viewer}} </span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/refunds/tickets/unassigned">Show Unassigned</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/refunds/tickets/assigned">Show Assigned</a><hr><br/></div>
                               @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}   
                                        </div>
                                    @endif
                                @if($viewer=="unassigned") 
                                

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
                                        <th>CallCenterAgent</th>
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
                                        <td>  
                                        <input type="hidden" name="ticketIds[]" value="{{$refundTicket->request_id}}">
                                            <select name="newAgents[]">
                                                <option value="0">Please Choose</option>
                                                @foreach($agents AS $agent)
                                                <option value="{{$agent->id}}">{{$agent->name}}</option>
                                                @endforeach
                                            </select>                                       
                                        </td>
                                    </tr>
                                    <?php $i++;?>
                                    @endforeach
                                <tfoot>
                                    <tr><td colspan="10"></td>
                                        <td><button type="submit" class="btn btn-primary">Save</button></td></tr>
                                </tfoot>
                                
                            </table>
                            @endif

                            <!------------------------------------------------------------------------------------------------------------------------------------ -->
                                @if($viewer=="assigned") 
                                

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
                                        <th>CallCenterAgent</th>
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
                                         
                                        <td>  
                                        <input type="hidden" name="ticketIds[]" value="{{$refundTicket->request_id}}">
                                            <select name="newAgents[]">
                                                <option value="{{$refundTicket->call_center_agent}}">{{$refundTicket->name}}</option>
                                                @foreach($agents AS $agent)
                                                <option value="{{$agent->id}}">{{$agent->name}}</option>
                                                @endforeach
                                            </select>                                       
                                        </td>
                                    </tr>
                                    <?php $i++;?>
                                    @endforeach
                                <tfoot>
                                    <tr><td colspan="9"></td>
                                        <td><button type="submit" class="btn btn-primary">Save</button></td></tr>
                                </tfoot>
                                
                            </table>
                            @endif                           
                        </div>  

                             </form>
                        
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection