@extends('formsMaster') 
 
<?php session(['title' => 'Payroll']);
session(['subtitle' => '']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Payroll</a></li>
				<li class="active"><a href="javascript:;">View</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">{{ $payroll->branch_name }}  <small> {{ date('M-Y',strtotime($payroll->month_year."-01")) }}</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Saved Payroll</h4>
                        </div>

                        <div class="panel-body"> 
                     

                             <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr> 
                                        <th>ID</th> 
                                        <th>Name</th>
                                        <th>Days Absent</th>
                                        <th>Loan Deductions</th>
                                        <th>Personal Benefits</th>
                                        <th>Bonus</th>
                                        <th>Overtime</th>
                                        <th>Deductions Xtra</th>
                                        <th>Salary</th>
                                        <th>Absent Deduction</th>
                                        <th>Net Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($payrollExpandeds AS $payrollExpanded)
                                <tr>
                                
                                <td>{{ $payrollExpanded->emp_id }}</td>
                                
                                <td><a href="{{ action('EmployeesController@profile',base64_encode($payrollExpanded->emp_id)) }}" target="_blank">{{ $payrollExpanded->fullname }}</a></td>
                               
                                <td>{{ $payrollExpanded->days_absent }}</td>

                                <td><a href = "javascript: void(0)" onClick="window.open('{{action('PayrollController@individualContents',[base64_encode($payrollExpanded->emp_id),'loan',$payroll->start_date,$payroll->end_date])}}','flyout','resizable=no,scrollbars=yes,width=900,height=560,top=65,left=210')" >{{ $payrollExpanded->loan_deduction }}</a></td>
                                
                                <td><a href = "javascript: void(0)" onClick="window.open('{{action('PayrollController@individualContents',[base64_encode($payrollExpanded->emp_id),'personal benefits',$payroll->start_date,$payroll->end_date])}}','flyout','resizable=no,scrollbars=yes,width=900,height=560,top=65,left=210')" >{{ $payrollExpanded->benefits }}</a></td>
                                
                                <td><a href = "javascript: void(0)" onClick="window.open('{{action('PayrollController@individualContents',[base64_encode($payrollExpanded->emp_id),'bonus',$payroll->start_date,$payroll->end_date])}}','flyout','resizable=no,scrollbars=yes,width=900,height=560,top=65,left=210')" >{{ $payrollExpanded->bonus }}</a></td>
                                
                                <td><a href = "javascript: void(0)" onClick="window.open('{{action('PayrollController@individualContents',[base64_encode($payrollExpanded->emp_id),'overtime',$payroll->start_date,$payroll->end_date])}}','flyout','resizable=no,scrollbars=yes,width=900,height=560,top=65,left=210')" >{{ $payrollExpanded->overtime }}</a></td>
                                
                                <td><a href = "javascript: void(0)" onClick="window.open('{{action('PayrollController@individualContents',[base64_encode($payrollExpanded->emp_id),'deduction',$payroll->start_date,$payroll->end_date])}}','flyout','resizable=no,scrollbars=yes,width=900,height=560,top=65,left=210')" >{{ $payrollExpanded->deductions_xtra }}</a></td>
<?php 
$daySalary=0; $absentDeduction=0; $netAmount=0; 
$daySalary = ($payrollExpanded->salary/30);
$absentDeduction=$payrollExpanded->days_absent*$daySalary;
?>
                                <td>{{ $payrollExpanded->salary }}</td>
                                <td>{{ round($payrollExpanded->absentDeduction) }}</td>
                                <td>{{ round($payrollExpanded->net_amount) }}</td>
                                </tr>
                                @endforeach
                                 
                                </tbody>
                                </table>

                        

                        </div>
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
<script>
        $(document).ready(function() {
            App.init(); 
            });  
    </script> 
        @endsection