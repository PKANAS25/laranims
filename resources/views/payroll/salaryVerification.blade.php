@extends('master') 
 
<?php session(['title' => 'Payroll']);
session(['subtitle' => 'verification']); ?> 

@section('content') 
<style type="text/css">
    .breakWord{
            
            max-width: 80px;
            word-wrap: break-word;  

    }
</style>  
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right hidden-print">
                <li><a href="javascript:;">Payroll</a></li>
                <li class="active"><a href="javascript:;">Salary Verification</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Salary <small> Verification</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Salary Verification</h4>
                        </div>
                      
                        <div class="panel-body"> 
                     

                             <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr> 
                                        <th>ID</th> 
                                        <th>Employee</th>
                                        <th>Branch</th>
                                        <th>Joining Date</th>
                                        <th>IBAN</th>
                                        <th>Labour Card</th>
                                        <th>WPS Under</th>
                                        <th>Person Code</th>
                                        <th>Basic</th>
                                        <th>Accom</th>
                                        <th>Trans</th>
                                        <th>Other</th>
                                        <th>Total</th>
                                        @foreach($teamUsers As $index => $teamUser)
                                        <th>{{ $teamUser->name }}</th>
                                        @endforeach  
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees As $index => $employee)
                                    <tr> 
                                        <td>{{$employee->employee_id}}</td>
                                        <td><a href="{{ action('EmployeesController@profile',base64_encode($employee->employee_id)) }}" target="_blank">{{$employee->fullname}}</a></td>
                                        <td>{{ str_replace( "Al Dana - ", '',$employee->working_for) }}</td>
                                        <td>{{$employee->joining_date}}</td>
                                        <td class="breakWord">{{$employee->iban}}</td>
                                        <td>{{$employee->labour_card_no}}</td>
                                        <td>{{ str_replace( "Al Dana - ", '',$employee->lc_in) }}</td>
                                        <td class="breakWord">{{$employee->person_code}}</td>
                                        <td>{{$employee->basic}}</td>
                                        <td>{{$employee->accomodation}}</td>
                                        <td>{{$employee->travel}}</td>
                                        <td>{{$employee->other}}</td>
                                        <td>{{$totalSalary = $employee->basic + $employee->other + $employee->accomodation + $employee->travel + $employee->other}}</td>
                                        
                                        @foreach($teamUsers As $index => $teamUser) 
                                        <?php $i=$index+1;?>   
                                        <td> 
                                        @if($employee->{'verification'.$i}==1)
                                        <i class="fa fa-check-circle text-success"></i> 

                                        @elseif($employee->{'verification'.$i}==-1)
                                        <a title="{{ $employee->{'reason'.$i} }}"><i class="fa fa-minus-circle text-danger"></i></a><br>&nbsp;
                                         

                                        @elseif($employee->{'verification'.$i}==0 && Auth::id()!=$team->{'verifier'.$i})
                                        <i class="fa fa-exclamation-triangle text-warning"></i>
                                        @endif


                                        @if($employee->{'verification'.$i}!=1 && Auth::id()==$team->{'verifier'.$i} && $totalSalary)
                                        <div id="action{{ $i }}{{$employee->employee_id}}">
                                               <button class="btn btn-xs" id="approve{{ $i }}{{$employee->employee_id}}" value="{{ $i }}"> <i class="fa fa-thumbs-up text-success"></i></button>
                                               <br>&nbsp;
                                               <input type="text" size="5" id="reason{{ $i }}{{$employee->employee_id}}">  
                                               <button class="btn btn-xs" id="reject{{ $i }}{{$employee->employee_id}}" value="{{ $i }}"> <i class="fa fa-thumbs-down text-danger"></i></button>
                                        </div>
                                                <script type="text/javascript">
                                                    $(document.body).on('click', '#approve{{ $i }}{{$employee->employee_id}}', function(e){
                                                        e.preventDefault();
                                                        verifier = $(this).val(); 
                                                         $.get('/verifySalary',{employee:'{{$employee->employee_id}}', verifier:verifier, action:'1',reason:''}, function(actionBlade){ 
                                                            $("#action{{ $i }}{{$employee->employee_id}}").html(actionBlade); 
                                                        });
                                                    });

                                                    $(document.body).on('click', '#reject{{ $i }}{{$employee->employee_id}}', function(e){
                                                        e.preventDefault();
                                                        verifier = $(this).val();
                                                        reason = window.document.getElementById("reason{{ $i }}{{$employee->employee_id}}").value;
                                                        if(reason)
                                                        {
                                                            $.get('/verifySalary',{employee:'{{$employee->employee_id}}', verifier:verifier, action:'-1',reason:reason}, function(actionBlade){ 
                                                            $("#action{{ $i }}{{$employee->employee_id}}").html(actionBlade); 
                                                            });
                                                        }
                                                         
                                                    });
                                                </script>
                                        
                                        @endif
                                        </td>
                                        @endforeach

                                       


                                    </tr>
                                    @endforeach
                                </tbody>
                              
                            </table>
                                <br>&nbsp;&nbsp;&nbsp;
                              <span class="text-warning"><i class="fa fa fa-exclamation-triangle"></i> Pending</span>&nbsp;&nbsp;&nbsp;&nbsp;  
                              <span class="text-success"><i class="fa fa-check-circle"></i> Approved</span>&nbsp;&nbsp;&nbsp;&nbsp; 
                              <span class="text-danger"><i class="fa fa-minus-circle"></i> Rejected</span>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    <!-- end panel --> 
                </div>
            <!-- end row -->
        </div>
    </div>
 
 
        @endsection