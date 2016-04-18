@extends('formsMaster') 
 
<?php session(['title' => 'Payroll']);
session(['subtitle' => 'generate']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Payroll</a></li>
				<li class="active"><a href="javascript:;">Generate</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Generate <small> Payroll</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Step II</h4>
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
                                @foreach($employees AS $employee)
                                <tr @if($employee->paidPersonal) class="bg-yellow-lighter" @elseif($employee->salaryNotOk) class="text-danger"  @endif>
                                <td>{{ $employee->employee_id }}</td>
                                <td><a href="{{ action('EmployeesController@profile',base64_encode($employee->employee_id)) }}" target="_blank">{{ $employee->fullname }}</a></td>
                                <td>{{ $employee->deduction_days }}</td>
                                <td>{{ $employee->loanDeduction }}</td>
                                <td>{{ $employee->totalBenefits }}</td>
                                <td>{{ $employee->totalBonus }}</td>
                                <td>{{ $employee->totalOverTimePay }}</td>
                                <td>{{ $employee->totalDeduction }}</td>
                                <td>{{ $employee->totalSalary }}</td>
                                <td>{{ round($employee->absentDeduction) }}</td>
                                <td>{{ round($employee->netAmount) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                <td colspan="11" align="right">
                                       <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" >
                                            <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                                            <input type="hidden" name="company" value="{{ $company }}" />
                                            <input type="hidden" name="payroll_month" value="{{ $payroll_month }}" />
                                            <input type="hidden" name="start_date" value="{{ $start_date }}" />
                                            <input type="hidden" name="end_date" value="{{ $end_date }}" />

                                            @if($noSave==1)
                                            <p class="text-danger">
                                            <strong>
                                                @if($salriesNotVerified) Salary verification pending. @endif
                                                @if($pendingApprovals) Payroll contents approval pending. @endif
                                                @if($bankRejections) Open payroll bank rejections. @endif
                                            </strong>
                                            </p> 
                                            @else 
                                            <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4"></label>
                                                    <div class="col-md-6 col-sm-6"> 
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                            </div> 
                                            @endif

                                        </form> 
                                </td>
                                </tr>
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