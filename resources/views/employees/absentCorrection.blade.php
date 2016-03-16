@extends('formsMaster') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
  
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right hidden-print">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Absent Correction</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Absent <small> Correction</small></h1>
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
                                    <label class="control-label col-md-4 col-sm-4" for="starter">Start Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="starter" name="starter" data-fv-notempty="true" value="{{ old('starter') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="ender">End Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="ender" name="ender" data-fv-notempty="true" value="{{ old('ender') }}"  />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Payment Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dated" name="dated" data-fv-notempty="true" value="{{ old('dated') }}"  />
                                    </div>
                                </div>  

                            <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="amount">Amount :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="amount" name="amount"   data-fv-notempty="true"  min="1"  value="{{ old('amount') }}" />  
                                    </div>
                                </div> 

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="notes">Notes :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea class="form-control" name="notes" >{{ old('notes') }}</textarea>  
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
        $(document).ready(function() {
            App.init();


            $('#starter').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'starter');
            $('#eForm').formValidation('revalidateField', 'ender');
            });

            $('#ender').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'ender');
            $('#eForm').formValidation('revalidateField', 'starter');
            });

            $('#dated').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'dated');
            });

            
            
       $('#eForm').formValidation({
                message: 'This value is not valid',
                

                fields: {
                      
                
                    
                 
            starter: {
                     
                     threshold: 5,
                     verbose: false,
                     
                     validators: {
                     
                     notEmpty: {},
                      callback: {
                                message: 'End date must be greater than start date',
                                callback: function(value, validator, $field) {
                                    var end = $('#eForm').find('[name="ender"]').val();
                                   if(value>end)
                                    return false;
                                    else return true;
                                }
                            },
                     remote: {
                        url: '/absentCorrectionCheck' ,
                        data: function(validator, $field, value) {
                            return {        
                                ender: validator.getFieldElements('ender').val(),                         
                                employeeId: {{$employee->employee_id}} 
                            };
                        }

                    }
                }
            },

            ender: {
                     
                     threshold: 5,
                     verbose: false,
                     
                     validators: {
                     
                     notEmpty: {},                    
                     remote: {
                        url: '/absentCorrectionCheck' ,
                        data: function(validator, $field, value) {
                            return {   
                                starter: validator.getFieldElements('starter').val(),                               
                                employeeId: {{$employee->employee_id}} 
                            };
                        }

                    }
                }
            }
        }
    })
    // This event will be triggered when the field passes given validator
    .on('success.validator.fv', function(e, data) {
        // data.field     --> The field name
        // data.element   --> The field element
        // data.result    --> The result returned by the validator
        // data.validator --> The validator name

         

        if (data.field === 'starter'
            && data.validator === 'remote'
            && (data.result.available === false || data.result.available === 'false'))
        {

            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-success')
                .addClass('has-warning')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="starter"]')
                    .show();
        }


        if (data.field === 'starter'
            && data.validator === 'remote'
            && (data.result.available === true || data.result.available === 'true'))
        {
             
            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-warning')
                .addClass('has-success')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="starter"]')
                    .show();
        }

  //--------------------------------------------------------------------------------------------------------   
     if (data.field === 'ender'
            && data.validator === 'remote'
            && (data.result.available === false || data.result.available === 'false'))
        {

            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-success')
                .addClass('has-warning')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="ender"]')
                    .show();
        }


        if (data.field === 'ender'
            && data.validator === 'remote'
            && (data.result.available === true || data.result.available === 'true'))
        {
             
            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-warning')
                .addClass('has-success')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="ender"]')
                    .show();
        }

    })
    // This event will be triggered when the field doesn't pass given validator
    .on('err.validator.fv', function(e, data) { 
         
        // We need to remove has-warning class
        // when the field doesn't pass any validator
         

        if (data.field === 'starter') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }

        if (data.field === 'ender') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }
    });
     

  });             
    </script>            
    </script>


 
        @endsection