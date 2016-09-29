@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Employees']);
session(['subtitle' => 'addEmp']); ?>
@endsection


@section('content')

<link rel="stylesheet" type="text/css" href="/js/timepicker/jquery.ui.timepicker.css" />
<script type="text/javascript" src="/js/timepicker/jquery.ui.timepicker.js?v=0.3.3"></script>  
 
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Add <small> new Employee</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Add</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"  data-fv-message="Required Field"  data-fv-icon-invalid="glyphicon glyphicon-remove"  data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                     
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Fullname :</label>

                                    <div class="form-inline">

                                     <div class="form-group m-r-10">
                                         &nbsp;&nbsp;
                                     <input class="form-control" type="text" id="fname" name="fname" data-fv-notempty="true" value="{{ old('fname') }}" placeholder="First name" />
                                     </div>
                                         
                                     <div class="form-group m-r-10">
                                         <input class="form-control" type="text" id="mname" name="mname"  value="{{ old('mname') }}" placeholder="Middle name" />
                                     </div>

                                     <div class="form-group m-r-10">
                                       <input class="form-control" type="text" id="lname" name="lname" data-fv-notempty="true" data-fv-remote="true" value="{{ old('lname') }}" placeholder="Last name" />
                                     </div> 
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Position :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="designation" data-fv-notempty="true">
                                            
                                            <option value="">Select</option>
                                            <optgroup label="Nursery">
                                            <option value="Teacher">Teacher</option>
                                            <option value="Asst. Teacher">Asst. Teacher</option>
                                            <option value="Principal">Principal</option>
                                            <option value="Vice Principal">Vice Principal</option>
                                            <option value="Nurse">Nurse</option>
                                            <option value="Cleaner">Cleaner</option>
                                            <option value="Trainee">Trainee</option>
                                            </optgroup>
                                            @if(Auth::user()->admin_type>1) 
                                            <optgroup label="Finance">
                                            <option value="Accountant">Accountant</option>
                                            <option value="Senior Accountant">Senior Accountant</option>
                                            </optgroup>

                                            <optgroup label="HR">
                                            <option value="Chairman">Chairman</option>
                                            <option value="Deputy Manager">Deputy Manager</option>
                                            <option value="Executive Manager">Executive Manager</option>
                                            <option value="HR Executive">HR Executive</option>
                                            <option value="HR Manager">HR Manager</option>
                                            <option value="Operations Manager">Operations Manager</option>
                                            <option value="Private Secretary">Private Secretary</option>
                                            <option value="PRO">PRO</option>
                                            </optgroup>

                                            <optgroup label="IT">
                                            <option value="Graphic Designer">Graphic Designer</option>
                                            <option value="IT Admin">IT Admin</option>
                                            <option value="Software Developer">Software Developer</option>
                                            </optgroup>


                                            <optgroup label="Maintenance">
                                            <option value="Foreman">Foreman</option>
                                            <option value="Maintenance Worker">Maintenance Worker</option>
                                            <option value="Supervisor">Supervisor</option>
                                            </optgroup>


                                            <optgroup label="Miscellaneous">
                                            <option value="Call Center Agent">Call Center Agent</option>
                                            <option value="Driver">Driver</option>
                                            <option value="Office Boy">Office Boy</option>
                                            </optgroup>
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                
                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="designation_mol">Position in MOL :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="designation_mol"   name="designation_mol" data-fv-notempty="true"   value="{{ old('designation_mol') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Bonus Category :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="bonus_category" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            <option value="Teacher">Teacher</option>
                                            <option value="Principal">Principal</option>
                                            <option value="Cleaner">Cleaner</option>
                                            <option value="Trainee">Trainee</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Visa Under :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="visa_under" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            <option value="-1">Under Process</option>
                                            <option value="-2">Spouse</option>
                                            <option value="-3">Guardian</option>
                                            @foreach($branches as $branch)
                                            <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Working for :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="working_under" data-fv-notempty="true">
                                            <option value="{{Auth::user()->branch}}">{{Auth::user()->branch_name}}</option> 
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="address">Qualification :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="qualification" data-fv-notempty="true">
                                            <option value="">Please Choose</option>
                                            <option value="None">None</option>
                                            <option value="High school or equivalent">High school or equivalent</option>
                                            <option value="Diploma">Diploma</option>
                                            <option value="Bachelors degree">Bachelors degree</option>
                                            <option value="Masters degree">Masters degree</option>
                                            <option value="Doctorate">Doctorate</option>
                                            <option value="Certification">Other Certification</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="specialization">Specialisation :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input list="specialisations" class="form-control" type="text" id="specialization"   name="specialization" data-fv-notempty="true"   value="{{ old('specialization') }}" />
                                        <datalist id="specialisations">
                                         @foreach($specialisations AS $specialisation)
                                         <option value="{{$specialisation->specialization}}" ></option>
                                         @endforeach
                                        </datalist>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="joining_date">Joining Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="joining_date"  name="joining_date" data-fv-notempty="true"   value="{{ old('joining_date') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Worktime :</label>

                                     <div class="form-inline">

                                         <div class="form-group m-r-10 ">
                                             &nbsp;&nbsp;<input class="form-control" type="text" id="start_time" name="start_time" data-fv-notempty="true"  value="7:30" />
                                         </div>
                                         
                                         <div class="form-group m-r-10">
                                                <input class="form-control" type="text" name="end_time" id="end_time" data-fv-notempty="true" value="14:30" />
                                         </div> 
                                    </div>

                                </div>


                                

                                <h4>Important Documents</h4> 
                                <hr>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="passport_no">Passport No. :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="passport_no" name="passport_no"  data-fv-notempty="true" value="{{ old('passport_no') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="passport_expiry">Passport Expiry :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="passport_expiry" name="passport_expiry"   data-fv-notempty="true" value="{{ old('passport_expiry') }}" />
                                    </div>
                                </div>

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="person_code">Person Code :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="person_code" name="person_code"     value="{{ old('person_code') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="labour_card_no">Labour card no. :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="labour_card_no" name="labour_card_no"      value="{{ old('labour_card_no') }}" />
                                    </div>
                                </div>

                                

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="labour_card_expiry">Labour Card Expiry :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="labour_card_expiry" name="labour_card_expiry"    value="{{ old('labour_card_expiry') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="visa_issue">Visa Issued On :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="visa_issue" name="visa_issue"     value="{{ old('visa_issue') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="visa_expiry">Visa Expires on :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="visa_expiry" name="visa_expiry"    value="{{ old('visa_expiry') }}" />
                                    </div>
                                </div>

                                
                                <h4>Personal Info</h4> 
                                <hr>
                                 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Gender :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="gender" value="m" id="radio-required" data-fv-notempty="true" /> Male
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="gender" id="radio-required2" value="f" /> Female
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dob">Date of birth :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dob"   name="dob" data-fv-notempty="true"   value="{{ old('dob') }}" />
                                    </div>
                                </div>

 


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Nationality :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="nationality" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            @foreach($nations as $nation)
                                            <option value="{!! $nation->nation_id !!}">{!! $nation->nationality !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Religion :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="religion" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            @foreach($religions as $religion)
                                            <option value="{!! $religion->religion_id !!}">{!! $religion->religion !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="mobile">Mobile :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="mobile"  name="mobile" data-fv-notempty="true"   value="{{ old('mobile') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="email">Email :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="email"   name="email" data-fv-notempty="true"  data-fv-emailaddress="true"
                data-fv-emailaddress-message="The value is not a valid email address"  value="{{ old('email') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="personal_email">Personal Email :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="personal_email"   name="personal_email"    data-fv-emailaddress="true"
                data-fv-emailaddress-message="The value is not a valid email address"  value="{{ old('personal_email') }}" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fileToUpload">Profile pic</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="file"  accept="image/*"    data-fv-file="true"  data-fv-file-extension="jpeg,jpg"  data-fv-file-type="image/jpeg,image/jpg"  data-fv-file-maxsize="629760" data-fv-file-message="The selected file is not valid" id="fileToUpload" name="fileToUpload" /> <span class="text-info">Max size 500 Kb, JPG only</span>
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
 
                        </div> 
                    <!-- end panel --> 
                </div>
            <!-- end row -->
        </div>
    </div>
    <script>
        $(document).ready(function() {
            App.init(); 

             @if(Auth::user()->hasRole('AttendanceManager'))
             $('#start_time').timepicker({
                            showPeriodLabels: false,
                             minutes: { interval: 1 }
                        }).on('change', function(e) { 
            $('#eForm').formValidation('revalidateField', 'start_time');
            $('#eForm').formValidation('revalidateField', 'end_time');
            });

            $('#end_time').timepicker({
                            showPeriodLabels: false,
                             minutes: { interval: 1 }
                        }).on('change', function(e) { 
            $('#eForm').formValidation('revalidateField', 'end_time');
            $('#eForm').formValidation('revalidateField', 'start_time');
            });
            @endif   

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