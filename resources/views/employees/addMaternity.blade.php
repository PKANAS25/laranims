@extends('formsMaster') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
  
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right hidden-print">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add Maternity Bonus</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Add <small> Maternity Bonus</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$employee->fullname}}</h4>
                        </div>
                         <div class="panel-body">
                              
                            <a href="{{ action('EmployeesController@profile',base64_encode($employee->employee_id)) }}"><i class="fa fa-arrow-left"></i> Back to Employee Profile</a> 
                        <br>&nbsp;
                        <table class="table table-profile table-bordered ">
                        <tbody>
                                        <tr class="highlight">
                                            <td class="field">Joining Date</td>
                                            <td>{{$employee->joining_date}}</td>
                                        </tr>
                                        @if($checked)
                                         <tr class="highlight">
                                            <td class="field">Months Worked until {{$dated}}</td>
                                            <td>{{$months}}</td>
                                        </tr>
                                        @else
                                        <tr class="highlight">
                                            <td class="field">Months Worked</td>
                                            <td>{{$months}}</td>
                                        </tr>
                                        @endif
                        </tbody>
                        </table>
                        @if($checked)
                         <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data" action="/employee/{{base64_encode($employee->employee_id)}}/add/maternity">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                         
                             
                              @if($months>6 && $months<12 && $monthdDiffPaid>=12)
                              Benefit Available = 22.5 Days<br />
                              Amount : {{$daySalary*22.5}}
                                <input class="form-control" type="hidden" id="amount"   name="amount" data-fv-notempty="true"   value="{{$daySalary*22.5}}" />
                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Bonus Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dated"   name="dated" data-fv-notempty="true"   value="{{ old('dated') }}" />
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6"> 
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                              @elseif($months>=12 && $monthdDiffPaid>=12)
                              Benefit Available = 45 Days<br />
                              Amount : {{$daySalary*45}}
                                <input class="form-control" type="hidden" id="amount"   name="amount" data-fv-notempty="true"   value="{{$daySalary*45}}" />
                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Bonus Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dated"   name="dated" data-fv-notempty="true"   value="{{ old('dated') }}" />
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6"> 
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>        
                               
                               @elseif($months<=6)
                                 <div class="alert alert-danger" align="center">
                                  <h5>Currently not qualified for maternity bonus</h5> 
                                 </div>
                               
                               @elseif($monthdDiffPaid<12)
                                 <div class="alert alert-danger" align="center">
                                  <h5>Maternity bonus for this period is paid</h5> 
                                 </div>
                               @endif
                               

                             </form>
                        @else
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data" action="/employee/{{base64_encode($employee->employee_id)}}/check/maternity">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                         
                             
                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated"><strong>Maternity Planner</strong> -> Delivery Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dated"   name="dated" data-fv-notempty="true"   value="{{ old('dated') }}" />
                                    </div>
                                </div>

                           

                               
                               
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6"> 
                                        <button type="submit" class="btn btn-primary">Check</button>
                                    </div>
                                </div>
                               
                        </div> 
                         
                             
                             
                             </form>
                        @endif 
                    
                    <!-- end panel --> 
                </div>
            <!-- end row -->
        </div>
    </div>


    <script>
        $(document).ready(function() {
            App.init();


            $('#dated').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'dated');
            });

            
            
                                             
        $('#eForm').formValidation();  

          });             
    </script>


 
        @endsection