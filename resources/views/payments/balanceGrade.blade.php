@extends('master') 
 
<?php session(['title' => 'Payments']);
session(['subtitle' => 'balance']); ?> 

@section('content') 

<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">List</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">{{ $grade->standard }} - {{ $grade->division }}<small> Balance</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Students</h4>
                        </div>
                        <div class="panel-body">
 
                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th class="nosort">#</th>
                                    <th>ID</th>
                                    <th>Name</th> 
                                    <th>Name in Arabic</th> 
                                    <th>Father's Mobile</th>
                                    <th>Mother's Mobile</th>
                                    <th>Total Amount</th>
                                    <th>Balance</th>
                                    </tr>
                                </thead>
                                @foreach($students as $student)
                                @if($student->studentBalance)
                                <tr>
                                <td>{{++$i}}</td>    
                                <td>{{$student->student_id}}</td>
                                <td><a href="{!! action('StudentsController@profile', base64_encode($student->student_id)) !!}">{{$student->full_name}}</a></td>
                                <td class="hidden-print">{{$student->full_name_arabic}}</td> 
                                <td class="hidden-print">{{$student->father_mob}}</td>
                                <td class="hidden-print">{{$student->mother_mob}}</td>
                                <td>{{$student->totalPayable}}</td>
                                <td>{{$student->studentBalance}}</td>
                                </tr>
                                @endif
                                @endforeach
                                
                                <tbody>  
                                <tfoot><tr><td colspan="7">&nbsp;</td><td>{{ $gradeBalance }}</td></tr></tfoot>
                            </table>
                             
<a class="btn btn-sm btn-info m-b-10 hidden-print" href="{!! action('PaymentsController@balanceGrades', array(base64_encode($grade->class_id),'active')) !!}">Show Active</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="btn btn-sm btn-warning m-b-10 hidden-print" href="{!! action('PaymentsController@balanceGrades', array(base64_encode($grade->class_id),'all')) !!}">Show All</a>
                              
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
 <script> 
            $('#data-table').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,
                "aaSorting": [],
                "columnDefs": [ {
                      "targets": 'nosort',
                      "bSortable": false,
                      "searchable": false 
                    } ]
            } );
            </script>
@endsection