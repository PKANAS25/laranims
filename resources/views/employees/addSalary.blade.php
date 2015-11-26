@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
 
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Add <small> Salary</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$employee->fullname}}</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"  data-fv-message="Required Field"  data-fv-icon-invalid="glyphicon glyphicon-remove"  data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                     
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Labour Card Under :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="labour_card_under" data-fv-notempty="true">
                                                <option value="0">No WPS</option>
                                                @foreach($branches as $branch)
                                                <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>

                               
                                
                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="XXXXX">Basic :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="XXXXX" name="XXXX" data-fv-notempty="true" value="{{ old('XXXXX') }}" />
                                    </div>
                                </div>

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="XXXXX">Accomodation :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="XXXXX" name="XXXX" data-fv-notempty="true" value="{{ old('XXXXX') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="XXXXX">Transportation :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="XXXXX" name="XXXX" data-fv-notempty="true" value="{{ old('XXXXX') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="XXXXX">Other :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="XXXXX" name="XXXX" data-fv-notempty="true" value="{{ old('XXXXX') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="XXXXX">Total :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="XXXXX" name="XXXX" data-fv-notempty="true" value="{{ old('XXXXX') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="XXXXX">IBAN # :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="XXXXX" name="XXXX" data-fv-notempty="true" value="{{ old('XXXXX') }}" />
                                    </div>
                                </div>

                               
 
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>



                                </fieldset>
                            </form>

                              <script>
                                

                                    
                                  


                                </script>
                        </div> 
                    <!-- end panel --> 
                </div>
            <!-- end row -->
        </div>
    </div>
    <script>
        $(document).ready(function() {
            App.init(); 

            $('#dob').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'dob');
            });

            
            $('#joining_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'joining_date');
                });

             $('#passport_expiry').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'passport_expiry');
                });
             
             $('#labour_card_expiry').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'labour_card_expiry');
                });
            
             $('#visa_issue').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'visa_issue');
                });  

              $('#visa_expiry').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'visa_expiry');
                });         
                                             
            //$('#eForm').formValidation();

            $('#eForm').formValidation({
                message: 'This value is not valid',
                

                fields: {
                      
                
                    mobile: {
                        validators: {
                            notEmpty: {},
                            digits: {},
                            phone: {
                                country: 'AE'
                            }
                        }
                    },
                 
            lname: {
                     
                     verbose: false,
                     
                     validators: {
                     
                     notEmpty: {},
                     remote: {
                        url: '/employeeAddCheck' ,
                        data: function(validator, $field, value) {
                            return {                                 
                                fname: validator.getFieldElements('fname').val(),
                                mname: validator.getFieldElements('mname').val()
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

         

        if (data.field === 'lname'
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
                .find('small[data-fv-validator="remote"][data-fv-for="lname"]')
                    .show();
        }


        if (data.field === 'lname'
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
                .find('small[data-fv-validator="remote"][data-fv-for="lname"]')
                    .show();
        }

    })
    // This event will be triggered when the field doesn't pass given validator
    .on('err.validator.fv', function(e, data) { 
         
        // We need to remove has-warning class
        // when the field doesn't pass any validator
         

        if (data.field === 'lname') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }
    });

  });

                            
    </script>
        @endsection