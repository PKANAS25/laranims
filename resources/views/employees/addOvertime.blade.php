@extends('formsMaster') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
<link rel="stylesheet" type="text/css" href="/js/timepicker/jquery.ui.timepicker.css" />
<script type="text/javascript" src="/js/timepicker/jquery.msgbox.min.js"></script>    
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right hidden-print">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add Overtime</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Add <small> Overtime</small></h1>
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
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="panel-body">
                              
                             <a href="{{ action('EmployeesController@profile',base64_encode($employee->employee_id)) }}"><i class="fa fa-arrow-left"></i> Back to Employee Profile</a>  
                                 
                                <div class="hidden-print">
                                     @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                     
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="payment_date">Loan Payment Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="payment_date" name="payment_date" data-fv-notempty="true"   value="{{ old('payment_date') }}" />
                                    </div>
                                </div>   

                            
                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="time_1">Start time :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="time_1"   name="time_1" data-fv-notempty="true"   value="{{ old('time_1') }}" />
                                    </div>
                                </div>

                             <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="time_2">End time :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="time_2"   name="time_2" data-fv-notempty="true"   value="{{ old('time_2') }}" />
                                    </div>
                                </div>   

                            <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="amount">Amount :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="amount" name="amount"   data-fv-notempty="true"  min="1"  value="{{ old('amount') }}" onKeyUp="totalRes()" onChange="totalRes()" />  
                                    </div>
                                </div> 

                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="max_rounds">Max Rounds :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="max_rounds" name="max_rounds"   data-fv-notempty="true"  min="1"  value="1"  onKeyUp="totalRes()" onChange="totalRes()" />  
                                    </div>
                                </div>   

                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="per_round">Deduction amount per round :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="per_round" name="per_round"  value="{{ old('per_round') }}" readonly="readonly" min="1" />  
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fileToUpload">Document</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="file"  accept="image/*"   data-fv-file="true"  data-fv-file-extension="jpeg,jpg"  data-fv-file-type="image/jpeg,image/jpg"  data-fv-file-maxsize="629760" data-fv-file-message="The selected file is not valid" id="fileToUpload" name="fileToUpload" /> <span class="text-info">Max size 500 Kb, JPG only</span>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                               
                        </div> 
                         
                             
                             
                             </form>
                         
                    
                    <!-- end panel --> 
                </div>
            <!-- end row -->
        </div>
    </div>


    <script>

    function totalRes()
    {
        
        var amount = window.document.getElementById("amount").value;
        var max_rounds = window.document.getElementById("max_rounds").value;
        
        amount = parseInt(amount);
        max_rounds = parseInt(max_rounds);
        
        var per_rounder = amount/max_rounds;
        per_rounder = parseInt(per_rounder);
        
     document.eForm.per_round.value=per_rounder;
     
    }


        $(document).ready(function() {
            App.init();


            $('#payment_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'payment_date');
            });

            $('#time_1').timepicker({
                            showPeriodLabels: false,
                             minutes: { interval: 1 }
                        });
           $('#time_2').timepicker({
                            showPeriodLabels: false,
                             minutes: { interval: 1 }
                        }); 
             
                                             
        $('#eForm').formValidation();  

          });             
    </script> 

 
        @endsection