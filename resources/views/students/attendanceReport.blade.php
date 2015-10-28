@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => 'attendance']); ?>
@endsection


@section('content')
<link href="/css/invoice-print.min.css" rel="stylesheet" />
 
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Attendance</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Attendance <small> Report</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Attendance Report</h4>
                        </div>
                        
                        <div class="panel-body">
                              
                                    
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF"  class="form-inline hidden-print" >
                            
                                 <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                 
                                 <div class="form-group m-r-10">
                                        <input class="form-control" type="text" id="startDate" name="startDate" data-fv-notempty="true"   value="{{ old('startDate') }}" placeholder="Start Date" />
                                 </div>
                                 
                                 <div class="form-group m-r-10">
                                       <input class="form-control" type="text" id="endDate" name="endDate" data-fv-notempty="true"   value="{{ old('endDate') }}" placeholder="End Date" />
                                 </div>
                                    
                                <button type="submit" class="btn btn-sm btn-primary m-r-5">Generate</button> <br> <br>
                            </form>

                                 

                              @if($submit)
                              <div class="invoice-header onlyprintCenter">
                                    <div class="invoice-from" >
                                         
                                        <address class="m-t-5 m-b-5" align="center">
                                          <img src="/img/logo.jpg" width="65" height="65" alt=""> {{Auth::user()->branch_name}}  Attendance Report ({{$startDate." - ".$endDate}})
                                        </address>
                                    </div>
                                  
                                </div>

                                <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    </tr/>
                                </thead>
                                <tbody><?php $i=1;?>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$student->student_id}}</td>
                                        <td>{{$student->full_name}}</td>
                                        <td>{{$student->standard." - ".$student->division}}</td>
                                        <td>{{$student->dated}}</td>
                                        <td>{{$student->reason}}</td>

                                    </tr><?php $i++;?>    
                                    @endforeach
                                </tbody>    
                                </table>
                                 <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10 hidden-print"><i class="fa fa-print m-r-5"></i> Print</a>
                              @endif
                              
                              
                           
<br>
                        </div> 
                         
                             
                             
                            
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>

    <script>
        $(document).ready(function() {
            App.init();
            

             $('#startDate').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'startDate');
            });

             $('#endDate').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'endDate');
             $('#eForm').formValidation('revalidateField', 'startDate');
            });


            $('#data-table').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,

            } );
                                             
             
            $('#eForm').formValidation({
                
                fields:{
                    

                    startDate: {
                      
                        validators: {
                           
                                 callback: {
                                message: 'End date must be greater than start date',
                                callback: function(value, validator, $field) {
                                    var end = $('#eForm').find('[name="endDate"]').val();
                                   if(value>end)
                                    return false;
                                    else return true;
                                }
                            }
                        }
                    }

 


                }                
 
    }) ;
      
        });

                            
    </script>
        @endsection