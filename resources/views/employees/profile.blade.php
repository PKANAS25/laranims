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
                            <a href="#" class="btn btn-primary btn-block btn-sm">  ID: <strong>{{$employee->employee_id}}</strong></a>
                        </div>
                        </div> 

                        <div class="profile-highlight"  >
                        <div class="m-b-10">
                            <a href="#" class="btn btn-success btn-block btn-sm">Timings : <strong>{{$employee->start_time}} to {{$employee->end_time}}</strong></a>
                        </div>
                            
                        <div class="m-b-10">
                            <a href="#" class="btn btn-info btn-block btn-sm">Assign Special Working Days</a>
                        </div>

                        <div class="m-b-10">
                            <a href="#" class="btn btn-danger btn-block btn-sm">Biometric @if($employee->biometric)<i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif </a>
                        </div>
                            
                        <div class="m-b-10">
                            <a href="#" class="btn btn-warning btn-block btn-sm">History</a>
                        </div>
                        
                        <div class="m-b-10">
                            <a href="#" class="btn btn-inverse btn-block btn-sm"><i class="fa fa-edit"></i> Edit</a>
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
                                <table class="table table-profile table-striped ">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <h4>{{$employee->fullname}}
                                                <small>{{$employee->designation}} - <span class="@if($employee->deleted==0) text-success @elseif($employee->deleted==1) text-inverse @elseif($employee->deleted==2) text-danger @elseif($employee->deleted==3) text-warning  @endif"><strong>{{$employee->status}}</strong> 
                                                @if($employee->gender=='f') <i class="fa fa-female"></i> @else <i class="fa fa-female"></i> @endif</span>
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
                        <li class=""><a href="#default-tab-3" data-toggle="tab"><i class="fa fa-child"></i> Attendance</a></li> 
                        <li class=""><a href="#default-tab-4" data-toggle="tab"><i class="fa fa-phone"></i> Documents</a></li>
                        <li class=""><a href="#default-tab-5" data-toggle="tab"><i class="fa fa-phone"></i> Forms</a></li>
                    </ul>
                    <div class="tab-content">
                        
    <!----------------------------------------------------Actions------------------------------------------------------------------------------- -->       
                        <div class="tab-pane fade active in" id="default-tab-1">
                        <table class="table">
                            <tr>
                                <td width="50%"><a href="#" class="btn btn-warning btn-sm">Resignation</a></td>
                                <td><a href="#" class="btn btn-warning btn-sm">Add Bonus</a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                            
                        </div>
                      
      <!----------------------------------------------------Salary------------------------------------------------------------------------------- -->                
                      
                        <div class="tab-pane fade" id="default-tab-2">
                        
                       BBBBB
                            
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
                      
                    </div>
                     
                     
                </div>
                         
                    </div>
            <!-- end profile-container --> 
			  
</div>
 
        @endsection