@extends('master') 
 
<?php session(['title' => 'Payroll']);
session(['subtitle' => 'approvals']); ?> 

@section('content') 
<style type="text/css">
    .breakWord{
            
                max-width: 90px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;  
    } 
    .actionsCol{
        min-width: 170px;
    }

      
</style>
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Payroll</a></li>
				<li class="active"><a href="javascript:;">Approvals</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Payroll <small> Approvals</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            
                            <h4 class="panel-title">Pending Approvals</h4>
                        </div>
                        
                        <div class="panel-body">

                        <form class="form-inline hidden-print" name="eForm" id="eForm"  method="get" autocomplete="OFF">
                              
                                <div class="form-group m-r-10">
                                  <select class="form-control" id="select-required" name="branch" data-fv-notempty="true">
                                            <option value="">All Branches</option>
                                            @foreach($branches as $branch)
                                            <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                            @endforeach
                                        </select>
                                </div>
                                
                                 
                                <button type="submit" class="btn btn-primary m-r-5">Filter</button> 
                            </form>  
                            </div>

                             </div>
            <!-- end row -->
        </div>
        <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">

                            <div class="panel-body">
                              
                            <div class="row">
                            <div class="col-md">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#default-tab-1" data-toggle="tab">Bonus</a></li>
                                    <li class=""><a href="#default-tab-2" data-toggle="tab">Deductions</a></li>
                                    <li class=""><a href="#default-tab-3" data-toggle="tab">Loans</a></li>
                                    <li class=""><a href="#default-tab-4" data-toggle="tab">Overtime</a></li>
                                    <li class=""><a href="#default-tab-5" data-toggle="tab">Benefits</a></li>
                                    <li class=""><a href="#default-tab-6" data-toggle="tab">Payroll</a></li>
                                    <li class=""><a href="#default-tab-7" data-toggle="tab">Personal Pay</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="default-tab-1">
                                       
                                         <table id="data-table" class="table table-striped table-bordered">
                                         <thead>
                                            <tr> 
                                                <th>ID</th> 
                                                <th>Employee</th>
                                                <th>Branch</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Notes</th>
                                                <th>Added by</th>
                                                <th>Doc</th>
                                                <th>  </th>
                                            </tr>
                                         </thead>   
                                         <tbody>
                                         
                                         @foreach($bonuses as $bonus)
                                         <tr>
                                         <td>{{ $bonus->employee_id }}</td>
                                         <td><a href="{{ action('EmployeesController@profile',base64_encode($bonus->employee_id)) }}" target="_blank">{{ $bonus->fullname }}</a></td>
                                         <td>{{ str_replace( "Al Dana - ", '',$bonus->branch_name) }}</td>
                                         <td>{{ $bonus->dated }}</td>
                                         <td>{{ $bonus->amount }}</td>
                                         <?php $text=""?>
                                         @if($bonus->absent_correction) <?php $text = " Absent correction =>".$bonus->start_date." to ".$bonus->end_date ?>   @endif
                                         <td class="breakWord"><a title="{{ $bonus->notes.$text }}">{!! $bonus->notes.$text !!}</a></td> 
                                         <td>{{ $bonus->admn }}</td> 
                                         <td>@if($bonus->file)
                                               <a href="#modal-dialogBonus{{$bonus->bonus_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogBonus{{$bonus->bonus_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Bonus Document</h4> 
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/bonus/{{$bonus->bonus_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else 
                                                <span class="text-danger">✘</span>
                                                @endif 
                                            </td> 

                                         <td class="actionsCol"><div id="bonus{{$bonus->bonus_id}}">
                                               <button class="btn btn-xs" id="approveBonus{{$bonus->bonus_id}}" value="{{ $bonus->bonus_id }}"> <i class="fa fa-thumbs-up text-success"></i></button> &nbsp;&nbsp;&nbsp;
                                               <input type="text" size="5" id="reasonBonus{{$bonus->bonus_id}}">  
                                               <button class="btn btn-xs" id="rejectBonus{{$bonus->bonus_id}}" value="{{ $bonus->bonus_id }}"> <i class="fa fa-thumbs-down text-danger"></i></button>
                                        </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#approveBonus{{$bonus->bonus_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val(); 
                                                         $.get('/approvePayrollContent',{item:'bonus', id:id, action:'1',reason:''}, function(actionBlade){ 
                                                            $("#bonus{{$bonus->bonus_id}}").html(actionBlade); 
                                                        });
                                                    });

                                                    $(document.body).on('click', '#rejectBonus{{$bonus->bonus_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();
                                                        reason = window.document.getElementById("reasonBonus{{$bonus->bonus_id}}").value;
                                                        if(reason)
                                                        {
                                                            $.get('/approvePayrollContent',{item:'bonus', id:id, action:'-1',reason:reason}, function(actionBlade){ 
                                                            $("#bonus{{$bonus->bonus_id}}").html(actionBlade); 
                                                            });
                                                        }
                                                         
                                                    });
                                                </script></td>
                                         </tr>
                                         @endforeach
                                         </tbody>
                                         </table>
                                    </div>
                                     
      <!----------------------------------------------------Deductions------------------------------------------------------------------------------- -->                


                                    <div class="tab-pane fade" id="default-tab-2">
                                        
                                            <table id="data-table2" class="table table-striped table-bordered">
                                         <thead>
                                            <tr> 
                                                <th>ID</th> 
                                                <th>Employee</th>
                                                <th>Branch</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Notes</th>
                                                <th>Added by</th>
                                                <th>Doc</th>
                                                <th>  </th>
                                            </tr>
                                         </thead>   
                                         <tbody>
                                         
                                         @foreach($deductions as $deduction)
                                         <tr>
                                         <td>{{ $deduction->employee_id }}</td>
                                         <td><a href="{{ action('EmployeesController@profile',base64_encode($deduction->employee_id)) }}" target="_blank">{{ $deduction->fullname }}</a></td>
                                         <td>{{ str_replace( "Al Dana - ", '',$deduction->branch_name) }}</td>
                                         <td>{{ $deduction->dated }}</td>
                                         <td>{{ $deduction->amount }}</td>
                                        
                                         <td><a title="{{ $deduction->notes }}">{!! $deduction->reason !!}</a></td> 
                                         <td>{{ $deduction->admn }}</td> 

                                         <td>@if($deduction->file)
                                               <a href="#modal-dialogDedudction{{$deduction->dedXtra_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogDedudction{{$deduction->dedXtra_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Deduction Document</h4>  
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/deduction/{{$deduction->dedXtra_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                 <span class="text-danger">✘</span>                                              
                                                @endif
                                               </td>


                                         <td class="actionsCol"><div id="deduction{{$deduction->dedXtra_id}}">
                                               <button class="btn btn-xs" id="approveDeduction{{$deduction->dedXtra_id}}" value="{{ $deduction->dedXtra_id }}"> <i class="fa fa-thumbs-up text-success"></i></button> &nbsp;&nbsp;&nbsp;
                                               <input type="text" size="5" id="reasonDeduction{{$deduction->dedXtra_id}}">  
                                               <button class="btn btn-xs" id="rejectDeduction{{$deduction->dedXtra_id}}" value="{{ $deduction->dedXtra_id }}"> <i class="fa fa-thumbs-down text-danger"></i></button>
                                        </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#approveDeduction{{$deduction->dedXtra_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val(); 
                                                         $.get('/approvePayrollContent',{item:'deduction', id:id, action:'1',reason:''}, function(actionBlade){ 
                                                            $("#deduction{{$deduction->dedXtra_id}}").html(actionBlade); 
                                                        });
                                                    });

                                                    $(document.body).on('click', '#rejectDeduction{{$deduction->dedXtra_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();
                                                        reason = window.document.getElementById("reasonDeduction{{$deduction->dedXtra_id}}").value;
                                                        if(reason)
                                                        {
                                                            $.get('/approvePayrollContent',{item:'deduction', id:id, action:'-1',reason:reason}, function(actionBlade){ 
                                                            $("#deduction{{$deduction->dedXtra_id}}").html(actionBlade); 
                                                            });
                                                        }
                                                         
                                                    });
                                                </script></td>
                                         </tr>
                                         @endforeach
                                         </tbody>
                                         </table>
                                         
                                    </div>

      <!----------------------------------------------------Loans------------------------------------------------------------------------------- -->                


                                    <div class="tab-pane fade" id="default-tab-3">
                                         
                                            <table id="data-table3" class="table table-striped table-bordered">
                                         <thead>
                                            <tr> 
                                                <th>ID</th> 
                                                <th>Employee</th>
                                                <th>Branch</th>
                                                <th>Payment Date</th>
                                                <th>Deduction Start</th>
                                                <th>Amount</th>
                                                <th>Per Round</th> 
                                                <th>Added by</th>
                                                <th>Doc</th>
                                                <th>  </th>
                                            </tr>
                                         </thead>   
                                         <tbody>
                                         
                                         @foreach($loans as $loan)
                                         <tr>
                                         <td>{{ $loan->employee_id }}</td>
                                         <td><a href="{{ action('EmployeesController@profile',base64_encode($loan->employee_id)) }}" target="_blank">{{ $loan->fullname }}</a></td>
                                         <td>{{ str_replace( "Al Dana - ", '',$loan->branch_name) }}</td>
                                         <td>{{ $loan->payment_date }}</td>
                                         <td>{{ $loan->deduction_start }}</td>
                                         <td>{{ $loan->loaned_amount }}</td>
                                         <td>{{ $loan->deduction_amount }}</td>
                                         <td>{{ $loan->admn }}</td> 

                                         <td>@if($loan->file)
                                               <a href="#modal-dialogloan{{$loan->loan_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogloan{{$loan->loan_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Loan Document</h4>  
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/loan/{{$loan->loan_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                 <span class="text-danger">✘</span>                                             
                                                @endif
                                               </td>


                                         <td class="actionsCol"><div id="loan{{$loan->loan_id}}">
                                               <button class="btn btn-xs" id="approveLoan{{$loan->loan_id}}" value="{{ $loan->loan_id }}"> <i class="fa fa-thumbs-up text-success"></i></button> &nbsp;&nbsp;&nbsp;
                                               <input type="text" size="5" id="reasonLoan{{$loan->loan_id}}">  
                                               <button class="btn btn-xs" id="rejectLoan{{$loan->loan_id}}" value="{{ $loan->loan_id }}"> <i class="fa fa-thumbs-down text-danger"></i></button>
                                        </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#approveLoan{{$loan->loan_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val(); 
                                                         $.get('/approvePayrollContent',{item:'loan', id:id, action:'1',reason:''}, function(actionBlade){ 
                                                            $("#loan{{$loan->loan_id}}").html(actionBlade); 
                                                        });
                                                    });

                                                    $(document.body).on('click', '#rejectLoan{{$loan->loan_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();
                                                        reason = window.document.getElementById("reasonLoan{{$loan->loan_id}}").value;
                                                        if(reason)
                                                        {
                                                            $.get('/approvePayrollContent',{item:'loan', id:id, action:'-1',reason:reason}, function(actionBlade){ 
                                                            $("#loan{{$loan->loan_id}}").html(actionBlade); 
                                                            });
                                                        }
                                                         
                                                    });
                                                </script></td>
                                         </tr>
                                         @endforeach
                                         </tbody>
                                         </table>
                                         
                                    </div>

      <!----------------------------------------------------Overtime------------------------------------------------------------------------------- -->                


                                    <div class="tab-pane fade" id="default-tab-4">
                                         
                                         <table id="data-table4" class="table table-striped table-bordered">
                                         <thead>
                                            <tr> 
                                                <th>ID</th> 
                                                <th>Employee</th>
                                                <th>Branch</th>
                                                <th>Date</th>
                                                <th>Hours</th>
                                                <th>Amount</th>
                                                <th>Notes</th>
                                                <th>Added by</th>
                                                <th>Doc</th>
                                                <th>  </th>
                                            </tr>
                                         </thead>   
                                         <tbody>
                                         
                                         @foreach($overtimes as $overtime)
                                         <tr>
                                         <td>{{ $overtime->employee_id }}</td>
                                         <td><a href="{{ action('EmployeesController@profile',base64_encode($overtime->employee_id)) }}" target="_blank">{{ $overtime->fullname }}</a></td>
                                         <td>{{ str_replace( "Al Dana - ", '',$overtime->branch_name) }}</td>
                                         <td>{{ $overtime->dated }}</td>
                                         <td>{{ $overtime->hours }}</td>
                                         <td>{{ $overtime->amount }}</td> 
                                         <td class="breakWord"><a title="{{ str_replace( "<br />", '&#013;',$overtime->notes) }}">{!! $overtime->notes !!}</a></td> 
                                         <td>{{ $overtime->admn }}</td> 

                                         <td>@if($overtime->file)
                                               <a href="#modal-dialogOvertime{{$overtime->over_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogOvertime{{$overtime->over_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Overtime Document</h4>  
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/overtime/{{$overtime->over_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                <span class="text-danger">✘</span>                                               
                                                @endif
                                               </td>


                                         <td class="actionsCol"><div id="overtime{{$overtime->over_id}}">
                                               <button class="btn btn-xs" id="approveOvertime{{$overtime->over_id}}" value="{{ $overtime->over_id }}"> <i class="fa fa-thumbs-up text-success"></i></button> &nbsp;&nbsp;&nbsp;
                                               <input type="text" size="5" id="reasonOvertime{{$overtime->over_id}}">  
                                               <button class="btn btn-xs" id="rejectOvertime{{$overtime->over_id}}" value="{{ $overtime->over_id }}"> <i class="fa fa-thumbs-down text-danger"></i></button>
                                        </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#approveOvertime{{$overtime->over_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val(); 
                                                         $.get('/approvePayrollContent',{item:'overtime', id:id, action:'1',reason:''}, function(actionBlade){ 
                                                            $("#overtime{{$overtime->over_id}}").html(actionBlade); 
                                                        });
                                                    });

                                                    $(document.body).on('click', '#rejectOvertime{{$overtime->over_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();
                                                        reason = window.document.getElementById("reasonOvertime{{$overtime->over_id}}").value;
                                                        if(reason)
                                                        {
                                                            $.get('/approvePayrollContent',{item:'overtime', id:id, action:'-1',reason:reason}, function(actionBlade){ 
                                                            $("#overtime{{$overtime->over_id}}").html(actionBlade); 
                                                            });
                                                        }
                                                         
                                                    });
                                                </script></td>
                                         </tr>
                                         @endforeach
                                         </tbody>
                                         </table>
                                    </div>

      <!----------------------------------------------------Benefits------------------------------------------------------------------------------- -->                

                                    <div class="tab-pane fade" id="default-tab-5">
                                         
                                           <table id="data-table5" class="table table-striped table-bordered">
                                         <thead>
                                            <tr> 
                                                <th>ID</th> 
                                                <th>Employee</th>
                                                <th>Branch</th>
                                                <th>Being</th>
                                                <th>Start Date</th>
                                                <th>Amount</th> 
                                                <th>Added by</th>
                                                <th>Doc</th>
                                                <th>  </th>
                                            </tr>
                                         </thead>   
                                         <tbody>
                                         
                                         @foreach($benefits as $benefit)
                                         <tr>
                                         <td>{{ $benefit->employee_id }}</td>
                                         <td><a href="{{ action('EmployeesController@profile',base64_encode($benefit->employee_id)) }}" target="_blank">{{ $benefit->fullname }}</a></td>
                                         <td>{{ str_replace( "Al Dana - ", '',$benefit->branch_name) }}</td>
                                         <td>{{ $benefit->benefit }}</td>
                                         <td>{{ $benefit->benefit_start }}</td>
                                         <td>{{ $benefit->amount }} X {{ $benefit->max_rounds }}</td> 
                                         
                                         <td>{{ $benefit->admn }}</td>
                                         
                                         <td>@if($benefit->file)
                                               <a href="#modal-dialogbenefit{{$benefit->benefit_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogbenefit{{$benefit->benefit_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Personal Benefit Document</h4>  
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/benefit/{{$benefit->benefit_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                <span class="text-danger">✘</span>     
                                                @endif
                                               </td>

                                         <td class="actionsCol"><div id="benefit{{$benefit->benefit_id}}">
                                               <button class="btn btn-xs" id="approveBenefit{{$benefit->benefit_id}}" value="{{ $benefit->benefit_id }}"> <i class="fa fa-thumbs-up text-success"></i></button> &nbsp;&nbsp;&nbsp;
                                               <input type="text" size="5" id="reasonBenefit{{$benefit->benefit_id}}">  
                                               <button class="btn btn-xs" id="rejectBenefit{{$benefit->benefit_id}}" value="{{ $benefit->benefit_id }}"> <i class="fa fa-thumbs-down text-danger"></i></button>
                                        </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#approveBenefit{{$benefit->benefit_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val(); 
                                                         $.get('/approvePayrollContent',{item:'benefit', id:id, action:'1',reason:''}, function(actionBlade){ 
                                                            $("#benefit{{$benefit->benefit_id}}").html(actionBlade); 
                                                        });
                                                    });

                                                    $(document.body).on('click', '#rejectBenefit{{$benefit->benefit_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();
                                                        reason = window.document.getElementById("reasonBenefit{{$benefit->benefit_id}}").value;
                                                        if(reason)
                                                        {
                                                            $.get('/approvePayrollContent',{item:'benefit', id:id, action:'-1',reason:reason}, function(actionBlade){ 
                                                            $("#benefit{{$benefit->benefit_id}}").html(actionBlade); 
                                                            });
                                                        }
                                                         
                                                    });
                                                </script></td>
                                         </tr>
                                         @endforeach
                                         </tbody>
                                         </table>
                                         
                                    </div>

        <!----------------------------------------------------Payroll------------------------------------------------------------------------------- -->                

                                    <div class="tab-pane fade" id="default-tab-6">
                                        
                                            Payroll
                                       
                                    </div>

        <!----------------------------------------------------Personal Payroll------------------------------------------------------------------------------- -->                


                                    <div class="tab-pane fade" id="default-tab-7">
                                       
                                            Personal
                                        
                                    </div>


                                </div>
                     
                     
                </div>
                            </div>   
                        </div> 
                         
                            
                         
                    
                    <!-- end panel --> 
               </div>
            <!-- end row -->
        </div>
    </div>
 <script>
        $(document).ready(function() {
            App.init(); 
                                            
            $('#data-table2').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,
                "aaSorting": [],
                "columnDefs": [ {
                      "targets": 'nosort',
                      "bSortable": false,
                      "searchable": false
                    } ]
            } );
            

            $('#data-table3').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,
                "aaSorting": [],
                "columnDefs": [ {
                      "targets": 'nosort',
                      "bSortable": false,
                      "searchable": false
                    } ]
            } );

             $('#data-table4').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,
                "aaSorting": [],
                "columnDefs": [ {
                      "targets": 'nosort',
                      "bSortable": false,
                      "searchable": false
                    } ]
            } );


             $('#data-table5').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,
                "aaSorting": [],
                "columnDefs": [ {
                      "targets": 'nosort',
                      "bSortable": false,
                      "searchable": false
                    } ]
            } );
 
        
            //fn.datepicker.defaults.format = "yyyy-mm-dd";
             //FormPlugins.init();  

            

        });


    </script>
        @endsection