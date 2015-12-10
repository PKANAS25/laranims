@extends('master') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
<link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script>   
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Employee</a></li>
				<li class="active"><a href="javascript:;">{{ucwords($stuff)}} History</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Employee <small> {{ucwords($stuff)}} History</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="row">
                    <!-- begin panel -->
                     @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}   
                                        </div>
                                    @endif
                                    @if (session('warningStatus'))
                                        <div class="alert alert-warning">
                                            {{ session('warningStatus') }}   
                                        </div>
                                    @endif  
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$employee->fullname}}</h4>
                        </div>
                      <div class="panel-body">
                      <br>&nbsp;&nbsp;&nbsp;&nbsp;
                      <span class="text-warning"><i class="fa fa-circle"></i> Pending</span>&nbsp;&nbsp;&nbsp;&nbsp;  
                      <span class="text-success"><i class="fa fa-circle"></i> Approved</span>&nbsp;&nbsp;&nbsp;&nbsp; 
                      <span class="text-danger"><i class="fa fa-circle"></i> Rejected</span>&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="{{ action('EmployeesController@profile',base64_encode($employee->employee_id)) }}"><i class="fa fa-arrow-left"></i> Back to Employee Profile</a>
                      <br>&nbsp;
                       
                                @if($stuff=='bonus')
                                   <table  id="data-table" class="table table-striped table-bordered">
                                       <thead>
                                           <tr>
                                               <th>Date</th>
                                               <th>Amount</th>
                                               <th>Notes</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th></th>
                                               <th></th>
                                               <th></th>
                                               <th></th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach($bonuses AS $index => $bonus)
                                           <tr class="@if($bonus->approved==0) text-warning @elseif($bonus->approved==-1) text-danger @elseif($bonus->approved==1) text-success @endif">
                                               <td>{{$bonus->dated}}</td>
                                               <td>{{$bonus->amount}}</td>
                                               <td>{{$bonus->notes}}</td>
                                               <td>{{$bonus->admn}}</td> 
                                               <td>{{$bonus->hrm}}</td>
                                               <td>{{$bonus->reject_reason}}</td>
                                               <td>@if($bonus->file)
                                               <a href="#modal-dialogBonus{{$bonus->bonus_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogBonus{{$bonus->bonus_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Bonus Document</h4> 
                                                                @if(Auth::user()->hasRole('HRAdmin') && $bonus->approved!=-1)
                                                                <a href="/employee/upload/hrx/{{base64_encode('bonus')}}/{{base64_encode($bonus->bonus_id)}}/{{base64_encode($employee->employee_id)}}" title="Click here to upload document">
                                                                @endif
                                                <i class="fa fa-upload text-inverse"></i> Change File</a>
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/bonus/{{$bonus->bonus_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                @if(Auth::user()->hasRole('HRAdmin') && $bonus->approved!=-1)
                                                <a href="/employee/upload/hrx/{{base64_encode('bonus')}}/{{base64_encode($bonus->bonus_id)}}/{{base64_encode($employee->employee_id)}}" title="Click here to upload document">
                                                <i class="fa fa-upload text-inverse"></i></a>
                                                @endif                                               
                                                @endif
                                               </td>
                                               <td>
                                                @if($bonus->admin==Auth::id() && $bonus->approved==0)
                                               <button id="bonusDel{{$index}}"><i  class="fa fa-trash text-danger"></i></button>
                                                 <script type="text/javascript">
                                                    $('#bonusDel{{$index}}').click(function(ev) {
                                                    
                                                      $.msgbox("<p>Are you sure you want to delete this?</p>", {
                                                        type    : "prompt",
                                                         inputs  : [
                                                          {type: "hidden", name: "no", value: "no"} 
                                                        ],
                                                         
                                                        buttons : [
                                                          {type: "submit", name: "delete", value: "Delete"},
                                                          {type: "cancel", value: "Cancel"}
                                                        ],
                                                        form : {
                                                          active: true,
                                                          method: 'get',
                                                          action: '{!! action('EmployeesControllerExtra@payrollContentDelete', [base64_encode($bonus->bonus_id),base64_encode('bonus'),base64_encode($employee->employee_id)]) !!}'
                                                        }
                                                      });
                                                      
                                                      ev.preventDefault();
                                                    
                                                    });
                                                 </script>
                                                 @endif
                                                 </td>
                                               <td>@if(Auth::user()->hasRole('Superman') && $bonus->approved==1) 
                                               <div id="bonus{{$bonus->bonus_id}}">
                                               <button class="btn btn-xs" id="bonusUnapprove{{$bonus->bonus_id}}" value="{{$bonus->bonus_id}}"> <i class="fa fa-refresh"></i></button>
                                               </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#bonusUnapprove{{$bonus->bonus_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();

                                                         $.get('/payrollContentUnapprove',{stuff:'bonus', id:id }, function(actionBlade){                      
                                                            $("#bonus{{$bonus->bonus_id}}").html(actionBlade);
                                                             
                                                        });
                                                    });
                                                </script>
                                               @endif</td>
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>
                               

    <!--------------------------------------------------------------Deductions------------------------------------------------------------------------------ -->  

                                    @elseif($stuff=='deduction')
                                    <table  id="data-table" class="table table-striped table-bordered">
                                       <thead>
                                           <tr>
                                               <th>Date</th>
                                               <th>Reason</th>
                                               <th>Amount</th>
                                               <th>Notes</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th></th>
                                               <th></th>
                                               <th></th>
                                               <th></th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach($deductions AS $index => $deduction)
                                           <tr class="@if($deduction->approved==0) text-warning @elseif($deduction->approved==-1) text-danger @elseif($deduction->approved==1) text-success @endif">
                                               <td>{{$deduction->dated}}</td>
                                               <td>{{$deduction->reason}}</td>
                                               <td>{{$deduction->amount}}</td>
                                               <td>{{$deduction->notes}}</td>
                                               <td>{{$deduction->admn}}</td> 
                                               <td>{{$deduction->hrm}}</td>
                                               <td>{{$deduction->reject_reason}}</td>
                                               <td>@if($deduction->file)
                                               <a href="#modal-dialogDedudction{{$deduction->dedXtra_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogDedudction{{$deduction->dedXtra_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Deduction Document</h4> 
                                                                @if(Auth::user()->hasRole('HRAdmin') && $deduction->approved!=-1)
                                                                <a href="/employee/upload/hrx/{{base64_encode('deduction')}}/{{base64_encode($deduction->dedXtra_id)}}/{{base64_encode($employee->employee_id)}}" title="Click here to upload document">
                                                                @endif
                                                <i class="fa fa-upload text-inverse"></i> Change File</a>
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/deduction/{{$deduction->dedXtra_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                @if(Auth::user()->hasRole('HRAdmin') && $deduction->approved!=-1)
                                                <a href="/employee/upload/hrx/{{base64_encode('deduction')}}/{{base64_encode($deduction->dedXtra_id)}}/{{base64_encode($employee->employee_id)}}" title="Click here to upload document">
                                                <i class="fa fa-upload text-inverse"></i></a>
                                                @endif                                               
                                                @endif
                                               </td>
                                               <td>
                                                @if($deduction->admin==Auth::id() && $deduction->approved==0)
                                               <button id="deductionDel{{$index}}"><i  class="fa fa-trash text-danger"></i></button>
                                                 <script type="text/javascript">
                                                    $('#deductionDel{{$index}}').click(function(ev) {
                                                    
                                                      $.msgbox("<p>Are you sure you want to delete this?</p>", {
                                                        type    : "prompt",
                                                         inputs  : [
                                                          {type: "hidden", name: "no", value: "no"} 
                                                        ],
                                                         
                                                        buttons : [
                                                          {type: "submit", name: "delete", value: "Delete"},
                                                          {type: "cancel", value: "Cancel"}
                                                        ],
                                                        form : {
                                                          active: true,
                                                          method: 'get',
                                                          action: '{!! action('EmployeesControllerExtra@payrollContentDelete', [base64_encode($deduction->dedXtra_id),base64_encode('deduction'),base64_encode($employee->employee_id)]) !!}'
                                                        }
                                                      });
                                                      
                                                      ev.preventDefault();
                                                    
                                                    });
                                                 </script>
                                                 @endif
                                                 </td>
                                               <td>@if(Auth::user()->hasRole('Superman') && $deduction->approved==1) 
                                               <div id="deduction{{$deduction->dedXtra_id}}">
                                               <button class="btn btn-xs" id="deductionUnapprove{{$deduction->dedXtra_id}}" value="{{$deduction->dedXtra_id}}"> <i class="fa fa-refresh"></i></button>
                                               </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#deductionUnapprove{{$deduction->dedXtra_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();

                                                         $.get('/payrollContentUnapprove',{stuff:'deduction', id:id }, function(actionBlade){                      
                                                            $("#deduction{{$deduction->dedXtra_id}}").html(actionBlade);
                                                             
                                                        });
                                                    });
                                                </script>
                                               @endif</td>
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>
                                
    <!-------------------------------------------------------------------------------------------------------------------------------------------- -->  
                                    @endif <!-- if stuff=='xyz'  -->
         
                         
                    </div>
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 
        @endsection