@extends('master') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
<style type="text/css">
    
    .expiryCol{
        max-width: 100px !important;
    }
    .filesCol{
        max-width: 45px !important;
    }
    .uploadCol{
        max-width: 155px !important;
    }
    
    div#frm *{display:inline}
      
</style>
 <link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script>    

<link rel="stylesheet" type="text/css" href="/js/msgbox/messagebox.css" />
<script type="text/javascript" src="/js/msgbox/messagebox.min.js"></script>    
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Employee</a></li>
				<li class="active"><a href="javascript:;">Profile</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Employee <small> Profile</small></h1>
			<!-- end page-header -->
			 
             <!-- begin profile-container -->
            <div class="profile-container">
                <!-- begin profile-section -->
                <div class="profile-section">
                    <!-- begin profile-left -->
                    <div class="profile-left">
                        <!-- begin profile-image -->
                        <div class="profile-image">
                            <img src="{{$profile_pic}}" />
                            <i class="fa fa-user hide"></i>
                        </div>
                        <!-- end profile-image -->
                         
                        <!-- begin profile-highlight -->
                        <div class="profile-highlight">
                            
                        <div class="m-b-10">
                            <a class="btn btn-primary btn-block btn-sm">ID: <strong>{{$employee->employee_id}}</strong></a>
                        </div>
                        </div> 

                        <div class="profile-highlight">
                        <div class="m-b-10">
                            <a class="btn btn-success btn-block btn-sm">Timings : <strong>{{$employee->start_time}} to {{$employee->end_time}}</strong></a>
                        </div>

                        <div class="m-b-10">
                            <a class="btn btn-danger btn-block btn-sm">Biometric @if($employee->biometric)<i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif </a>
                        </div>

                        <div class="m-b-10">
                            <a href="#modal-dialog-history"  data-toggle="modal"  class="btn btn-warning btn-block btn-sm">History</a>
                        </div>
                            
                        <div class="m-b-10">
                            <a @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $employee->deleted!=1) 
                            href="{{action('EmployeesController@specialDays',base64_encode($employee->employee_id))}}" @endif class="btn btn-info btn-block btn-sm">Assign Special Working Days</a>
                        </div>  
                        
                        <div class="m-b-10">
                            <a @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $employee->deleted!=1) 
                            href="{{action('EmployeesController@edit',base64_encode($employee->employee_id))}}" @endif  class="btn btn-inverse btn-block btn-sm"><i class="fa fa-edit"></i> Edit</a>
                        </div>   


                            
                        </div>

                        <div class="modal fade" id="modal-dialog-history">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Employee edit history</h4>
                                        </div> 
                                        <div class="modal-body" align="center">
                                            <table class="table table-striped">
                                            @foreach($history as $index => $action)
                                            <tr>
                                                <td>{{$index+1}}. {{$action->action.$action->name}} on {{$action->date_time->format('Y-M-d')}} at {{$action->date_time->format('h:i:s:a')}}</td>
                                            </tr>
                                            @endforeach    
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> 
                        <!-- end profile-highlight -->
                    </div>
                    <!-- end profile-left -->
                    <!-- begin profile-right -->
                    <div class="profile-right">
                        <!-- begin profile-info -->
                        <div class="profile-info">
                            <!-- begin table -->
                            <div class="table-responsive">
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
                                </div>    
                                <table class="table table-profile table-striped ">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <h4>
                                                {{$employee->fullname}} @if($employee->gender=='f') <i class="fa fa-female"></i> @else <i class="fa fa-male"></i> @endif
                                                <small>{{$employee->designation}} - 
                                                <span class=
                                                             @if($employee->deleted==0) "text-success" 
                                                             @elseif($employee->deleted==1) "text-inverse" 
                                                             @elseif($employee->deleted==2) "text-danger" 
                                                             @elseif($employee->deleted==3) "text-warning"  
                                                             @endif
                                                             ><strong>{{$employee->status}}</strong> 
                                                </span>
                                                </small>
                                                </h4>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="highlight">
                                            <td class="field">Position in MOL</td>
                                            <td><a href="#">{{$employee->designation_mol}}</a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="field">Bonus Category</td>
                                            <td>{{$employee->bonus_category}}</td>
                                        </tr>
                                        <tr>
                                            <td class="field">Qualification</td>
                                            <td>{{$employee->qualification}} in {{$employee->specialization}}</td>
                                        </tr>
                                        <tr>
                                            <td class="field">Joining Date</td>
                                            <td><a href="#">{{$employee->joining_date}}</a></td>
                                        </tr>
                                        
                                        <tr class="highlight">
                                            <td class="field">Working For</td>
                                            <td>{{$employee->working_for}}</td>
                                        </tr>

                                        <tr class="highlight">
                                            <td class="field">Nationality</td>
                                            <td><a href="#">{{$employee->nation}}</a></td>
                                        </tr>

                                        <tr>
                                            <td class="field">Passport Details</td>
                                            <td>{{$employee->passport_no}} - Expires on <a>{{$employee->passport_expiry}}</a></td>
                                        </tr>

                                        <tr>
                                            <td class="field">Visa Details</td>
                                            <td>{{$employee->visa_in}} - Issued on <a>{{$employee->visa_issue}}</a> Expires on <a>{{$employee->visa_expiry}}</a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="field">Labour Card Details</td>
                                            <td>{{$employee->labour_card_no}} -  Expires on <a>{{$employee->labour_card_expiry}}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Person Code</td>
                                            <td>{{$employee->person_code}}</td>
                                        </tr>
                                        <tr>
                                            <td class="field">Mobile</td>
                                            <td><i class="fa fa-mobile fa-lg m-r-5"></i>  <a href="tel:{{$employee->mobile}}">{{$employee->mobile}}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Email</td>
                                            <td>
                                                <a href="mailto:{{$employee->email}}" target="_top">{{$employee->email}}</a>  
                                                <a href="mailto:{{$employee->personal_email}}" target="_top">{{$employee->personal_email}}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="field">Date of Birth</td>
                                            <td>{{$employee->date_of_birth}} (<strong>{{$age}}</strong>)</td>
                                        </tr>
                                        <tr>
                                            <td class="field">Religion</td>
                                            <td>{{$employee->rel}}</td>
                                        </tr>
                                         
                                         
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table -->
                        </div>
                        <!-- end profile-info -->
                    </div>
                    <!-- end profile-right -->
                </div>
                <!-- end profile-section -->
                 
                  </div><br>
<div class="row">
                        <!-- begin col-4 -->
                        <div class="col-md">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#default-tab-1" data-toggle="tab"><i class="fa fa-flag"></i> Actions</a></li>
                        <li class=""><a href="#default-tab-2" data-toggle="tab"><i class="fa fa-money"></i> Salary</a></li>
                        <li class=""><a href="#default-tab-3" data-toggle="tab"><i class="fa fa-bars"></i> Attendance</a></li> 
                        <li class=""><a href="#default-tab-4" data-toggle="tab"><i class="fa fa-file"></i> Documents</a></li>
                        <li class=""><a href="#default-tab-5" data-toggle="tab"><i class="fa fa-file-text-o"></i> Forms</a></li>
                        
                    </ul>
                    <div class="tab-content">
                        
    <!----------------------------------------------------Actions------------------------------------------------------------------------------- -->       
                        <div class="tab-pane fade active in" id="default-tab-1">
                        <div class="panel-body">
                        
                        
                                <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                               
                                    <div class="panel-body">
                                        <table class="table">
                                             
                                            <tbody> 
                                                <tr>
                                                    <td>
                                                    <a @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $employee->deleted==0)
                                                     href="/employee/{{base64_encode($employee->employee_id)}}/add/bonus" @endif class="btn btn-info  btn-sm">Add Bonus</a> 
                                                    <a   href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'bonus'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a>
                                                    </td>
                                                    <td>
                                                    <a @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/add/deduction" @endif class="btn btn-danger   btn-sm">Add Deduction</a>
                                                    <a   href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'deduction'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a>
                                                    </td>
                                                    <td>
                                                    <a @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/add/loan" @endif  class="btn btn-inverse btn-sm">Add Loan</a>
                                                    <a   href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'loan'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a>
                                                    </td>
                                                    <td>
                                                    <a @if((Auth::user()->hasRole('AttendanceManager')) && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/add/vacation" @endif  class="btn btn-warning btn-sm">Vacation</a>
                                                    <a   href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'vacation'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('AttendanceManager') && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/add/sicks" @endif  class="btn bg-aqua-darker text-white btn-block btn-sm">Sick Leave</a> 
                                                    </td>
                                                </tr> 

                                                <tr>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('HROfficer') && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/add/maternity" @endif  class="btn bg-yellow-darker text-white btn-block btn-sm">Maternity Leave</a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('AttendanceManager') && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/absentCorrection" @endif class="btn bg-green-darker text-white  btn-sm">Absent Correction</a>
                                                    <a   href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'absentCorrection'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a>
                                                    </td>
                                                    <td>
                                                    <a  @if(Auth::user()->hasRole('ProPayments') && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/proPayment" @endif class="btn bg-red-darker text-white btn-sm">Pro Payments</a>
                                                    <a   href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'expenses'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a>
                                                    </td>
                                                    <td>
                                                    <a  @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/add/benefit" @endif class="btn bg-blue-lighter text-white btn-sm">Personal Benefits</a>
                                                    <a href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'personal benefits'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a>
                                                    </td>
                                                    <td>
                                                    <a @if((Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('HROfficer')) && $employee->deleted==0) 
                                                    href="/employee/{{base64_encode($employee->employee_id)}}/add/overtime" @endif class="btn bg-red-lighter text-white btn-sm">Overtime</a>
                                                    <a href="{{action('EmployeesControllerExtra@payContentHistory',[base64_encode($employee->employee_id),'overtime'])}}" class="btn bg-purple  btn-sm text-white"><i class="fa fa-history"></i></a> 
                                                    </td>
                                                </tr>



<!-- -------------------------------------------------------------Resignation,terrmination,deletion etc------------------------------------------------------------------- -->

 
                                                <tr>
                                                    <td>
                                                     @if(Auth::user()->hasRole('HROfficer') && $employee->deleted==0)  
                                                     <button id="resignButton" class="btn bg-orange-darker text-white btn-block btn-sm">Resignation</button>
                                                     <script type="text/javascript">
                                                        $('#resignButton').click(function(ev) {
                                                        
                                                          $.msgbox("<p>Are you sure about this resignation?</p>", {
                                                            type    : "prompt",
                                                             inputs  : [
                                                              {type: "hidden", name: "_token", value: "{{ csrf_token() }}"} 
                                                            ],
                                                             
                                                            buttons : [
                                                              {type: "submit", name: "resign", value: "Yes"},
                                                              {type: "cancel", value: "No"}
                                                            ],
                                                            form : {
                                                              active: true,
                                                              method: 'post',
                                                              action: '{!! action('EmployeesControllerHR@resignation',base64_encode($employee->employee_id)) !!}'
                                                            }
                                                          });
                                                          
                                                          ev.preventDefault();
                                                        
                                                        });
                                                     </script>
                                                     @elseif(Auth::user()->hasRole('HROfficer') && $employee->deleted!=0)  
                                                     <button id="restoreButton" class="btn bg-blue-darker text-white btn-block btn-sm">Restore</button>
                                                     <script type="text/javascript">
                                                        $('#restoreButton').click(function(ev) {
                                                        
                                                          $.msgbox("<p>Are you sure about this restore?</p>", {
                                                            type    : "prompt",
                                                             inputs  : [
                                                              {type: "hidden", name: "_token", value: "{{ csrf_token() }}"} 
                                                            ],
                                                             
                                                            buttons : [
                                                              {type: "submit", name: "restore", value: "Yes"},
                                                              {type: "cancel", value: "No"}
                                                            ],
                                                            form : {
                                                              active: true,
                                                              method: 'post',
                                                              action: '{!! action('EmployeesControllerHR@restore',base64_encode($employee->employee_id)) !!}'
                                                            }
                                                          });
                                                          
                                                          ev.preventDefault();
                                                        
                                                        });
                                                     </script>
                                                     @endif
                                                    </td>
                                                    <td>
                                                    @if(Auth::user()->hasRole('HROfficer') && $employee->deleted==0)  
                                                    <button id="terminateButton" class="btn bg-blue-darker text-white btn-block btn-sm">Termination</button>
                                                     <script type="text/javascript">
                                                        $('#terminateButton').click(function(ev) {
                                                        
                                                          $.msgbox("<p>Are you sure about this termination?</p>", {
                                                            type    : "prompt",
                                                             inputs  : [
                                                              {type: "hidden", name: "_token", value: "{{ csrf_token() }}"}  
                                                            ],
                                                             
                                                            buttons : [
                                                              {type: "submit", name: "terminate", value: "Yes"},
                                                              {type: "cancel", value: "No"}
                                                            ],
                                                            form : {
                                                              active: true,
                                                              method: 'post',
                                                              action: '{!! action('EmployeesControllerHR@terminate',base64_encode($employee->employee_id)) !!}'
                                                            }
                                                          });
                                                          
                                                          ev.preventDefault();
                                                        
                                                        });
                                                     </script>
                                                    @endif
                                                    </td>
                                                    <td>
                                                    @if(Auth::user()->hasRole('HROfficer') && $employee->deleted==0)  
                                                    <button id="transferButton" class="btn  bg-purple-lighter text-white btn-block btn-sm">Transfer</button>
                                                     <script type="text/javascript">
                                                        $('#transferButton').click(function(ev) {
                                                        
                                                          var select = $("<select>");
                                                          select.append("<option value='0'>Select</option>");
                                                          @foreach ($branches as $branch)
                                                            select.append("<option value='{{ $branch->id }}'>{{ $branch->name }}</option>");
                                                          @endforeach 

                                                            // Show a MessageBox with custom Input
                                                            $.MessageBox({
                                                                buttonDone  : "Confirm",
                                                                buttonFail  : "Cancel",
                                                                message : "Select target branch",
                                                                input   : select
                                                            }).done(function(data){ 
                                                                if(data==0) 
                                                                $.msgbox("You must select target branch.", {buttons : [{type: "cancel", value: "OK"}]});
                                                                else
                                                                 window.location.href = '/employees/hr/transfer/{{ base64_encode($employee->employee_id) }}/'+data;
                                                            });
                                                          
                                                          ev.preventDefault();
                                                        
                                                        });
                                                     </script>
                                                    @endif
                                                    </td>
                                                    <td>
                                                    @if(Auth::user()->hasRole('HROfficer') && $employee->deleted==0)  
                                                    <button id="deleteButton" class="btn bg-black-lighter text-white btn-block btn-sm">Delete</button>
                                                     <script type="text/javascript">
                                                        $('#deleteButton').click(function(ev) {
                                                        
                                                          $.msgbox("<p>Are you sure about this deletion?</p>", {
                                                            type    : "prompt",
                                                             inputs  : [
                                                              {type: "hidden", name: "_token", value: "{{ csrf_token() }}"} 
                                                            ],
                                                             
                                                            buttons : [
                                                              {type: "submit", name: "delete", value: "Yes"},
                                                              {type: "cancel", value: "No"}
                                                            ],
                                                            form : {
                                                              active: true,
                                                              method: 'post',
                                                              action: '{!! action('EmployeesControllerHR@remove',base64_encode($employee->employee_id)) !!}'
                                                            }
                                                          });
                                                          
                                                          ev.preventDefault();
                                                        
                                                        });
                                                     </script>
                                                    @endif
                                                    </td>
                                                    <td>
                                                    @if(Auth::user()->hasRole('HROfficer') && $employee->deleted==0) 
                                                    <a href="#" class="btn bg-yellow-lighter   btn-block btn-sm">Personal Payroll</a> 
                                                    @endif
                                                    </td>
                                                </tr>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                        
                           

                        </div>
                        </div>
                      
      <!----------------------------------------------------Salary------------------------------------------------------------------------------- -->                
                      
                        <div class="tab-pane fade" id="default-tab-2">
                        
                       @if($salary)
                       <table class="table table-profile table-striped ">
                           <tr>
                               <td class="field">Basic</td>
                               <td>{{$salary->basic}}</td>
                           </tr>
                           <tr>
                               <td class="field">Accomodation</td>
                               <td>{{$salary->accomodation}}</td>
                           </tr>
                           <tr>
                               <td class="field">Transportation</td>
                               <td>{{$salary->travel}}</td>
                           </tr>
                           <tr>
                               <td class="field">Other</td>
                               <td>{{$salary->other}}</td>
                           </tr>
                           <tr>
                               <td class="field">Total</td>
                               <td><strong>{{ $salary->basic + $salary->accomodation + $salary->travel + $salary->other }}</strong></td>
                           </tr>
                           <tr>
                               <td class="field">WPS</td>
                               <td>{{$salary->wps}}</td>
                           </tr>
                           <tr>
                               <td class="field">Iban</td>
                               <td>{{$salary->iban}}</td>
                           </tr>
                           
                           <tr>
                               <td class="field">Last edit remarks</td>
                               <td>{{$salary->edit_reason}}</td>
                           </tr>
 

                           <tr>
                               <td class="field">Documents</td>
                               <td>
                               <span id="file1Span_15">
                                @if($employee->salaryDoc1) 
                                        <a href="{{ action('DocumentsController@staffDocShow',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(1)]) }}" target="_blank">File 1</a>
                                        &nbsp;&nbsp;
                                        <a href="{{ action('DocumentsController@staffDocDownload',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(1)]) }}"><i class="fa fa-download text-success"></i></a>  
                                @endif 
                                </span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span id="file2Span_15"> 
                                @if($employee->salaryDoc2)
                                 
                                        <a href="{{ action('DocumentsController@staffDocShow',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(2)]) }}" target="_blank">File 2</a>
                                        &nbsp;&nbsp;
                                        <a href="{{ action('DocumentsController@staffDocDownload',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(2)]) }}"><i class="fa fa-download text-success"></i></a>  
                                @endif 
                                </span>
                               </td>
                           </tr>
                                       @if(Auth::user()->hasRole('HROfficer'))         
                                        <tr>
                                        <td>File 1</td>
                                        <td>
                                                    <div id="frm">

                                                    <form enctype="multipart/form-data" id="upload_form15" role="form" method="POST" action="" >
                                                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                                    <input type="file" id="fileToUpload" name="fileToUpload" />
                                                    <input type="hidden" name="docId" value="15">
                                                    <input type="hidden" name="employee" value="{{ $employee->employee_id }}">
                                                    <input type="hidden" name="number" value="1">
                                                    <button class="btn btn-xs" id="upload1_15" ><i class="fa fa-save"></i></button>
                                                    </form>
                                                    </div>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td>File 2</td>
                                        <td>
                                                    <div id="frm">

                                                    <form enctype="multipart/form-data" id="upload2_form15" role="form" method="POST" action="" >
                                                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                                    <input type="file" id="fileToUpload" name="fileToUpload" />
                                                    <input type="hidden" name="docId" value="15">
                                                    <input type="hidden" name="employee" value="{{ $employee->employee_id }}">
                                                    <input type="hidden" name="number" value="2">
                                                    <button class="btn btn-xs" id="upload2_15" ><i class="fa fa-save"></i></button>
                                                    </form>
                                                    </div>
                                        </td>
                                        </tr>
                                             
                                        @endif
                           <tr>
                               <td class="field"></td>
                               <td>
                               <a @if(Auth::user()->hasRole('SalaryEditor') && $employee->deleted==0) 
                               href="{{action('EmployeesController@editSalary',base64_encode($employee->employee_id))}}" @endif class="btn btn-inverse btn-sm">
                               <i class="fa fa-edit"></i> Edit</a>
                               </td>
                           </tr>
                           
                       </table>
                       @else
                       <a @if(Auth::user()->hasRole('SalaryEditor') && $employee->deleted==0) 
                       href="{{action('EmployeesController@addSalary',base64_encode($employee->employee_id))}}" @endif class="btn btn-inverse btn-sm">
                               <i class="fa fa-plus"></i> Add Salary Details</a>
                       @endif
                            
                        </div>
                        
              <!-----------------------------------------------------Attendance------------------------------------------------------------------------------ -->          
                        
                        <div class="tab-pane fade" id="default-tab-3">
                             
                             
                            <table id="data-table2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Student</th> 
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    </tbody>
                                    </table>
                             
                             
                        </div>
                        
    <!------------------------------------------------------------Documents----------------------------------------------------------------------- -->                    
                         
                      <div class="tab-pane fade" id="default-tab-4">
                        <div class="panel-body">
                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>Document</th>
                                    <th class="expiryCol">Expiry</th>
                                    <th class="filesCol">Files</th> 
                                    <th class="uploadCol">File Handling</th> 
                                    
                                    <th> </th> 
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($staffDocs as $staffDoc)
                                    <tr>
                                    <td>{{ $staffDoc->doc_name }}</td>
                                    <td class="expiryCol">@if($staffDoc->expires==0) 
                                            NO 
                                        @elseif($staffDoc->expires==1 && ($staffDoc->locked==1 && Auth::user()->hasRole('HRAdmin') && !Auth::user()->hasRole('HROfficer') ) || (!Auth::user()->hasRole('HRAdmin') && !Auth::user()->hasRole('HROfficer') )  )
                                                {{ $staffDoc->expiry_date }}
                                        @elseif( ($staffDoc->expires==1 && Auth::user()->hasRole('HROfficer') ) || ($staffDoc->expires==1 && $staffDoc->locked==0 && Auth::user()->hasRole('HRAdmin')  ) )
                                        <span id="expiry{{ $staffDoc->doc_id }}"></span>
                                        <input  type="text" id="dated{{ $staffDoc->doc_id }}" name="dated{{ $staffDoc->doc_id }}" value="{{ $staffDoc->expiry_date }}" />
                                        <button class="btn btn-xs" id="saveExpiry{{$staffDoc->doc_id}}" value="{{ $staffDoc->doc_id }}"><i class="fa fa-save"></i></button>
                                         
                                        <script type="text/javascript"> 

                                                    $(document.body).on('click', '#saveExpiry{{$staffDoc->doc_id}}', function(e){
                                                        e.preventDefault();
                                                        id = $(this).val();
                                                        dated = window.document.getElementById("dated{{ $staffDoc->doc_id }}").value;
                                                        if(dated)
                                                        {
                                                            $.get('/staffDocExpiry',{employee:{{ $employee->employee_id }}, id:id, dated:dated}, function(actionBlade){ 
                                                            $("#expiry{{$staffDoc->doc_id}}").html(actionBlade); 
                                                            });
                                                        }
                                                         
                                                    });
                                        </script>
                                        @endif </td>
                                    <td class="filesCol"> 
                                        @if($staffDoc->file1)
                                        <div id="file1Span_{{ $staffDoc->doc_id }}">
                                        <a href="{{ action('DocumentsController@staffDocShow',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(1)]) }}" target="_blank">File 1</a>&nbsp;&nbsp;<a href="{{ action('DocumentsController@staffDocDownload',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(1)]) }}"><i class="fa fa-download text-success"></i></a> 
                                        </div>
                                        @else <div id="file1Span_{{ $staffDoc->doc_id }}"><span class="text-danger">File 1</span> </div>
                                        @endif
                                        
                                        @if($staffDoc->no_of_files>1 && $staffDoc->file2)<br/> 
                                        <div id="file2Span_{{ $staffDoc->doc_id }}">
                                        <a href="{{ action('DocumentsController@staffDocShow',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(2)]) }}" target="_blank">File 2</a>&nbsp;&nbsp;<a href="{{ action('DocumentsController@staffDocDownload',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(2)]) }}"><i class="fa fa-download text-success"></i></a>
                                        </div>
                                        @elseif($staffDoc->no_of_files>1 && !$staffDoc->file2)<div id="file2Span_{{ $staffDoc->doc_id }}"><span class="text-danger">File 2</span></div>
                                        @endif
                                    </td>
                                   
                                    <td class="uploadCol">
                                    @if(($staffDoc->locked==0 && Auth::user()->hasRole('HRAdmin') ) || (Auth::user()->hasRole('HROfficer') ) )
                                        <div id="frm">

                                        <form enctype="multipart/form-data" id="upload_form{{ $staffDoc->doc_id }}" role="form" method="POST" action="" >
                                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                        <input type="file" id="fileToUpload" name="fileToUpload" />
                                        <input type="hidden" name="docId" value="{{ $staffDoc->doc_id }}">
                                        <input type="hidden" name="employee" value="{{ $employee->employee_id }}">
                                        <input type="hidden" name="number" value="1">
                                        <button class="btn btn-xs" id="upload1_{{$staffDoc->doc_id}}" ><i class="fa fa-save"></i></button>
                                        </form>
                                        </div>
                                        @if($staffDoc->no_of_files>1 ) <br> 
                                        <div id="frm">
                                        <form enctype="multipart/form-data" id="upload2_form{{ $staffDoc->doc_id }}" role="form" method="POST" action="" >
                                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                        <input type="file" id="fileToUpload" name="fileToUpload" />
                                        <input type="hidden" name="docId" value="{{ $staffDoc->doc_id }}">
                                        <input type="hidden" name="employee" value="{{ $employee->employee_id }}">
                                        <input type="hidden" name="number" value="2">
                                        <button class="btn btn-xs" id="upload2_{{$staffDoc->doc_id}}" ><i class="fa fa-save"></i></button>
                                        </form>
                                        </div>
                                        <script type="text/javascript"> 

                                                    $(document.body).on('click', '#upload2_{{$staffDoc->doc_id}}', function(e){
                                                        e.preventDefault();
                                                        Form=window.document.getElementById("upload2_form{{ $staffDoc->doc_id }}");
                                                        var ext = Form.fileToUpload.value.slice(Form.fileToUpload.value.lastIndexOf(".")).toLowerCase(); 
                                                        if(ext!=".jpg" && ext!=".jpeg" && ext!=".pdf")
                                                          {     
                                                                $.msgbox("Please upload a valid jpg/pdf file.");
                                                                return false;
                                                          }
                                                         var iSize = (Form.fileToUpload.files[0].size / 1024);  
                                                         if(iSize>800)
                                                          {
                                                             $.msgbox("Max file size 800 KB.");
                                                             return false;
                                                          } 

                                                        $.ajax({
                                                                  url:'/staffDocUpload',
                                                                  data:new FormData($("#upload2_form{{ $staffDoc->doc_id }}")[0]),
                                                                   
                                                                  async:true,
                                                                  type:'post',
                                                                  processData: false,
                                                                  contentType: false,
                                                                  success:function(response){
                                                                    if(response)
                                                                    {
                                                                       $("#file2Span_{{$staffDoc->doc_id}}").html('<a href="{{ action('DocumentsController@staffDocShow',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(2)]) }}" target="_blank">File 2</a>&nbsp;&nbsp;<a href="{{ action('DocumentsController@staffDocDownload',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(2)]) }}"><i class="fa fa-download text-success"></i></a>'); 
                                                                         $("#upload2_form{{$staffDoc->doc_id}}").html('<span class="text-success">File uploaded <i class="fa fa-check-square-o"></i></span>'); 
                                                                    }
                                                                    
                                                                  },
                                                                });
                                                         
                                                    });
                                       </script>  
                                        @endif  
                                       <script type="text/javascript"> 

                                                    $(document.body).on('click', '#upload1_{{$staffDoc->doc_id}}', function(e){
                                                        e.preventDefault();
                                                        Form=window.document.getElementById("upload_form{{ $staffDoc->doc_id }}");
                                                        var ext = Form.fileToUpload.value.slice(Form.fileToUpload.value.lastIndexOf(".")).toLowerCase(); 
                                                        if(ext!=".jpg" && ext!=".jpeg" && ext!=".pdf")
                                                          {     
                                                                $.msgbox("Please upload a valid jpg/pdf file.");
                                                                return false;
                                                          }
                                                         var iSize = (Form.fileToUpload.files[0].size / 1024);  
                                                         if(iSize>800)
                                                          {
                                                             $.msgbox("Max file size 800 KB.");
                                                             return false;
                                                          } 

                                                        $.ajax({
                                                                  url:'/staffDocUpload',
                                                                  data:new FormData($("#upload_form{{ $staffDoc->doc_id }}")[0]),
                                                                   
                                                                  async:true,
                                                                  type:'post',
                                                                  processData: false,
                                                                  contentType: false,
                                                                  success:function(response){
                                                                    if(response)
                                                                    {
                                                                       $("#file1Span_{{$staffDoc->doc_id}}").html('<a href="{{ action('DocumentsController@staffDocShow',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(1)]) }}" target="_blank">File 1</a>&nbsp;&nbsp;<a href="{{ action('DocumentsController@staffDocDownload',[base64_encode($staffDoc->doc_id),base64_encode($employee->employee_id),base64_encode(1)]) }}"><i class="fa fa-download text-success"></i></a>'); 
                                                                         $("#upload_form{{$staffDoc->doc_id}}").html('<span class="text-success">File uploaded <i class="fa fa-check-square-o"></i></span>'); 
                                                                    }
                                                                    
                                                                  },
                                                                });
                                                         
                                                    });
                                       </script>   
                                     @endif
                                    </td>
                                     
                                    <td>&nbsp;</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    </table>
                        </div>
                      </div>
    <!------------------------------------------------------------Forms----------------------------------------------------------------------- -->                    

                      <div class="tab-pane fade" id="default-tab-5"> 
                       CCCCC 
                      </div>
     

      <!---------------------------------------------------------------------------------------------------------------------------------------- -->                                   
                    </div>
                     
                     
                </div>
                         
                    </div>
            <!-- end profile-container --> 
			  
</div>

 <script>
        $(document).ready(function() {
           @foreach($staffDocs as $staffDoc)
            $('#dated{{ $staffDoc->doc_id }}').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }) 
            @endforeach
          });     



 
        $(document.body).on('click', '#upload1_15', function(e){
            e.preventDefault();
            Form=window.document.getElementById("upload_form15");
            var ext = Form.fileToUpload.value.slice(Form.fileToUpload.value.lastIndexOf(".")).toLowerCase(); 
            if(ext!=".jpg" && ext!=".jpeg" && ext!=".pdf")
              {     
                    $.msgbox("Please upload a valid jpg/pdf file.");
                    return false;
              }
             var iSize = (Form.fileToUpload.files[0].size / 1024);  
             if(iSize>800)
              {
                 $.msgbox("Max file size 800 KB.");
                 return false;
              } 

            $.ajax({
                      url:'/staffDocUpload',
                      data:new FormData($("#upload_form15")[0]),
                       
                      async:true,
                      type:'post',
                      processData: false,
                      contentType: false,
                      success:function(response){
                        if(response)
                        {   $("#file1Span_15").html('<a href="{{ action('DocumentsController@staffDocShow',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(1)]) }}" target="_blank">File 1</a>&nbsp;&nbsp;<a href="{{ action('DocumentsController@staffDocDownload',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(1)]) }}"><i class="fa fa-download text-success"></i></a>'); 
                             $("#upload_form15").html('<span class="text-success">File uploaded <i class="fa fa-check-square-o"></i></span>'); 
                        }
                        
                      },
                    });
             
        });

        $(document.body).on('click', '#upload2_15', function(e){
            e.preventDefault();
            Form=window.document.getElementById("upload2_form15");
            var ext = Form.fileToUpload.value.slice(Form.fileToUpload.value.lastIndexOf(".")).toLowerCase(); 
            if(ext!=".jpg" && ext!=".jpeg" && ext!=".pdf")
              {     
                    $.msgbox("Please upload a valid jpg/pdf file.");
                    return false;
              }
             var iSize = (Form.fileToUpload.files[0].size / 1024);  
             if(iSize>800)
              {
                 $.msgbox("Max file size 800 KB.");
                 return false;
              } 

            $.ajax({
                      url:'/staffDocUpload',
                      data:new FormData($("#upload2_form15")[0]),
                       
                      async:true,
                      type:'post',
                      processData: false,
                      contentType: false,
                      success:function(response){
                        if(response)
                        {   $("#file2Span_15").html('<a href="{{ action('DocumentsController@staffDocShow',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(2)]) }}" target="_blank">File 2</a>&nbsp;&nbsp;<a href="{{ action('DocumentsController@staffDocDownload',[base64_encode(15),base64_encode($employee->employee_id),base64_encode(2)]) }}"><i class="fa fa-download text-success"></i></a>'); 
                             $("#upload2_form15").html('<span class="text-success">File uploaded <i class="fa fa-check-square-o"></i></span>'); 
                        }
                        
                      },
                    });
             
        });

        

    </script>
 
        @endsection