@extends('master') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
  
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
                            <a class="btn btn-primary btn-block btn-sm">  ID: <strong>{{$employee->employee_id}}</strong></a>
                        </div>
                        </div> 

                        <div class="profile-highlight"  >
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
                            <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted!=1) href="{{action('EmployeesController@specialDays',base64_encode($employee->employee_id))}}" @endif class="btn btn-info btn-block btn-sm">Assign Special Working Days</a>
                        </div> 
                            
                        

                        
                        <div class="m-b-10">
                            <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted!=1) href="{{action('EmployeesController@edit',base64_encode($employee->employee_id))}}" @endif  class="btn btn-inverse btn-block btn-sm"><i class="fa fa-edit"></i> Edit</a>
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
                                                <h4>{{$employee->fullname}} @if($employee->gender=='f') <i class="fa fa-female"></i> @else <i class="fa fa-male"></i> @endif
                                                <small>{{$employee->designation}} - <span class="@if($employee->deleted==0) text-success @elseif($employee->deleted==1) text-inverse @elseif($employee->deleted==2) text-danger @elseif($employee->deleted==3) text-warning  @endif"><strong>{{$employee->status}}</strong> 
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
                                            <td class="field">Visa Details</td>
                                            <td>{{$employee->visa_in}} - Issued on <a>{{$employee->visa_issue}}</a> Expires on <a>{{$employee->visa_expiry}}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Passport Details</td>
                                            <td>{{$employee->passport_no}} - Expires on <a>{{$employee->passport_expiry}}</a></td>
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
                        <li class=""><a href="#default-tab-6" data-toggle="tab"><i class="fa fa-history"></i> Actions History</a></li>
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
                                                    <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="/employee/{{base64_encode($employee->employee_id)}}/add/bonus" @endif class="btn btn-info btn-block btn-sm">Add Bonus</a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="/employee/{{base64_encode($employee->employee_id)}}/add/deduction" @endif class="btn btn-danger btn-block btn-sm">Add Deduction</a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="/employee/{{base64_encode($employee->employee_id)}}/add/loan" @endif  class="btn btn-inverse btn-block btn-sm">Personal Loans</a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="#" @endif  class="btn btn-warning btn-block btn-sm">Vacation</a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="#" @endif  class="btn bg-aqua-darker text-white btn-block btn-sm">Sick Leave</a> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="#" @endif  class="btn bg-yellow-darker text-white btn-block btn-sm">Maternity Leave</a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('AttendanceManager') && $employee->deleted==0) href="#" @endif class="btn bg-green-darker text-white btn-block btn-sm">Absent Correction</a>
                                                    </td>
                                                    <td>
                                                    <a  @if(Auth::user()->hasRole('ProPayments') && $employee->deleted==0) href="#" @endif class="btn bg-purple text-white btn-block btn-sm">Pro payments</a>
                                                    </td>
                                                    <td>
                                                    <a  @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="#" @endif class="btn bg-blue-lighter text-white btn-block btn-sm">Personal Benefits</a>
                                                    </td>
                                                    <td>
                                                    <a @if(Auth::user()->hasRole('HRAdmin') && $employee->deleted==0) href="#" @endif class="btn bg-red-lighter text-white btn-block btn-sm">Overtime</a> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <a href="#" class="btn bg-orange-darker text-white btn-block btn-sm">Resignation</a>
                                                    </td>
                                                    <td>
                                                    <a href="#" class="btn bg-blue-darker text-white btn-block btn-sm">Termination</a>
                                                    </td>
                                                    <td>
                                                    <a href="#" class="btn  bg-red-darker text-white btn-block btn-sm">Transfer</a>
                                                    </td>
                                                    <td>
                                                    <a href="#" class="btn bg-black-lighter text-white btn-block btn-sm">Delete</a>
                                                    </td>
                                                    <td>
                                                    <a href="#" class="btn bg-yellow-lighter   btn-block btn-sm">View Payroll</a> 
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
                               <td class="field">Documents</td>
                               <td></td>
                           </tr>
                           <tr>
                               <td class="field">Last edit remarks</td>
                               <td>{{$salary->edit_reason}}</td>
                           </tr>
                           <tr>
                               <td class="field"></td>
                               <td>
                               <a @if(Auth::user()->hasRole('SalaryEditor') && $employee->deleted==0) href="{{action('EmployeesController@editSalary',base64_encode($employee->employee_id))}}" @endif class="btn btn-inverse btn-sm">
                               <i class="fa fa-edit"></i> Edit</a>
                               </td>
                           </tr>
                       </table>
                       @else
                       <a @if(Auth::user()->hasRole('SalaryEditor') && $employee->deleted==0) href="{{action('EmployeesController@addSalary',base64_encode($employee->employee_id))}}" @endif class="btn btn-inverse btn-sm">
                               <i class="fa fa-plus"></i> Add Salary Details</a>
                       @endif
                            
                        </div>
                        
              <!-----------------------------------------------------Attendance------------------------------------------------------------------------------ -->          
                        
                        <div class="tab-pane fade" id="default-tab-3">
                             
                             
                            <table id="data-table6" class="table table-striped table-bordered">
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
                           bbbbbb7777777
                      </div>
    <!------------------------------------------------------------Forms----------------------------------------------------------------------- -->                    

                      <div class="tab-pane fade" id="default-tab-5"> 
                       CCCCC 
                      </div>
     <!------------------------------------------------------------Actions History----------------------------------------------------------------------- -->                    

                      <div class="tab-pane fade" id="default-tab-6"> 
                       <div class="panel-group" id="accordion">
                        
                        <div class="panel panel-info overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Bonus
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                   <table class="table">
                                       <thead>
                                           <tr>
                                               <th>Date</th>
                                               <th>Amount</th>
                                               <th>Notes</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th></th>
                                               <th></th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach($bonuses AS $bonus)
                                           <tr class="@if($bonus->approved==0) text-warning @elseif($bonus->approved==-1) text-danger @elseif($bonus->approved==1) text-success @endif">
                                               <td>{{$bonus->dated}}</td>
                                               <td>{{$bonus->amount}}</td>
                                               <td>{{$bonus->notes}}</td>
                                               <td>{{$bonus->admn}}</td> 
                                               <td>{{$bonus->hrm}}</td>
                                               <td>{{$bonus->reject_reason}}</td>
                                               <td>@if($bonus->file)
                                               <a href="#modal-dialogBonus{{$bonus->bonus_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialogBonus{{$bonus->bonus_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Bonus Document</h4> 
                                                                @if(Auth::user()->hasRole('HRAdmin') && $bonus->approved!=-1)
                                                                <a href="/employee/upload/bonus/{{base64_encode($bonus->bonus_id)}}/{{base64_encode($employee->employee_id)}}" title="Click here to upload document">
                                                                @endif
                                                <i class="fa fa-upload text-inverse"></i> Change File</a>
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/hrx/bonus/{{$bonus->bonus_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                @if(Auth::user()->hasRole('HRAdmin') && $bonus->approved!=-1)
                                                <a href="/employee/upload/bonus/{{base64_encode($bonus->bonus_id)}}/{{base64_encode($employee->employee_id)}}" title="Click here to upload document">
                                                <i class="fa fa-upload text-inverse"></i></a>
                                                @endif                                               
                                                @endif
                                               </td>
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>
                                </div>
                            </div>
                        </div>

    <!-------------------------------------------------------------------------------------------------------------------------------------------- -->  

                        <div class="panel overflow-hidden">
                            <div class="panel-heading bg-red-lighter">
                                <h3 class="panel-title text-white">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Deductions
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>


    <!-------------------------------------------------------------------------------------------------------------------------------------------- -->  

                        <div class="panel  overflow-hidden">
                            <div class="panel-heading bg-black-lighter text-white">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Loans
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>

        <!-------------------------------------------------------------------------------------------------------------------------------------------- -->  

                        <div class="panel  overflow-hidden">
                            <div class="panel-heading bg-yellow-darker text-white">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Vacations
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>

        <!-------------------------------------------------------------------------------------------------------------------------------------------- -->  

                        <div class="panel  overflow-hidden">
                            <div class="panel-heading bg-green-darker">
                                <h3 class="panel-title text-white">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Absent Correction
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>

        <!-------------------------------------------------------------------------------------------------------------------------------------------- -->  

                        <div class="panel  overflow-hidden">
                            <div class="panel-heading  bg-purple">
                                <h3 class="panel-title text-white">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                                        <i class="fa fa-plus-circle pull-right text-white"></i> 
                                        PRO Payments
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseSix" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>

        <!-------------------------------------------------------------------------------------------------------------------------------------------- -->  

                        <div class="panel panel-primary overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Personal Benefits
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseSeven" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
        <!-------------------------------------------------------------------------------------------------------------------------------------------- --> 

        <div class="panel overflow-hidden">
                            <div class="panel-heading bg-orange">
                                <h3 class="panel-title  text-white">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEight">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Overtimes
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseEight" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div> 

                    </div> 
                      </div>

      <!---------------------------------------------------------------------------------------------------------------------------------------- -->                                   
                    </div>
                     
                     
                </div>
                         
                    </div>
            <!-- end profile-container --> 
			  
</div>
 
        @endsection