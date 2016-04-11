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
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
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
                                                <th>  </th>
                                            </tr>
                                         </thead>   
                                         <tbody>
                                         @foreach($bonuses as $bonus)
                                         <tr>
                                         <td>{{ $bonus->employee_id }}</td>
                                         <td>{{ $bonus->fullname }}</td>
                                         <td>{{ str_replace( "Al Dana - ", '',$bonus->branch_name) }}</td>
                                         <td>{{ $bonus->dated }}</td>
                                         <td>{{ $bonus->amount }}</td>
                                         <?php $text=""?>
                                         @if($bonus->absent_correction) <?php $text = " Absent correction =>".$bonus->start_date." to ".$bonus->end_date ?>   @endif
                                         <td class="breakWord"><a title="{{ $bonus->notes.$text }}">{{ $bonus->notes.$text }}</a></td> 
                                         <td>{{ $bonus->admn }}</td> 
                                         <td><div id="bonus{{$bonus->bonus_id}}">
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
                                     


                                    <div class="tab-pane fade" id="default-tab-2">
                                        
                                            deduct
                                         
                                    </div>


                                    <div class="tab-pane fade" id="default-tab-3">
                                         
                                           loans
                                         
                                    </div>

                                    <div class="tab-pane fade" id="default-tab-4">
                                         
                                           over time
                                    </div>

                                    <div class="tab-pane fade" id="default-tab-5">
                                         
                                           Benefits
                                         
                                    </div>

                                    <div class="tab-pane fade" id="default-tab-6">
                                        
                                            Payroll
                                       
                                    </div>

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
 
        @endsection