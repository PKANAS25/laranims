@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => 'grades']); ?>
@endsection


@section('content')
<link href="/css/invoice-print.min.css" rel="stylesheet" />

<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Grades</a></li>
				<li class="active"><a href="javascript:;">List</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Students <small> List</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$grade->standard." - ".ucwords($grade->division)}}</h4>
                        </div>
                        <div class="panel-body">
                             <div class="hidden-print">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{!! action('GradesController@students', array($classId,'active')) !!}">Show Active</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{!! action('GradesController@students', array($classId,'all')) !!}">Show All</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{!! action('GradesController@students', array($classId,'deleted')) !!}">Show Deleted</a><hr><br/></div>
                               
                                <div class="invoice-header onlyprint">
                                    <div class="invoice-from" >
                                         
                                        <address class="m-t-5 m-b-5" align="center">
                                          <img src="/img/logo.jpg" width="65" height="65" alt=""> {{Auth::user()->branch_name}} - {{ucwords($filter)}} Students List
                                        </address>
                                    </div>
                                  
                                </div>


                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th >Id</th>
                                    <th>Name</th>
                                    <th class="hidden-print">Name in Arabic</th>  
                                    <th>Age</th>
                                    <th class="onlyprint">Gender</th>                                   
                                    <th>Nationality</th>                              
                                    <th class="hidden-print">Father's Mobile</th>                                     
                                    <th class="hidden-print">Mother's Mobile</th>
                                    @if($filter=='active')<th width="11%" class="hidden-print">EndDate</th><th class="hidden-print">Remaining Days</th>@endif
                                    <th class="onlyprint">Balance</th>
                                    <th class="hidden-print">&nbsp;</th>
                                    @if($filter!='del')<th class="hidden-print">&nbsp;</th>@endif
                                    <th class="hidden-print"><input type="checkbox" id="selectall"/></th>
                                    </tr>
                                </thead><?php $i=0;?>
                                @foreach($students as $student)
                                <?php $i++;?>
                                <tr>
                                <td>{{$i}}</td>    
                                <td>{{$student->student_id}}</td>
                                <td><a href="{!! action('StudentsController@profile', base64_encode($student->student_id)) !!}">{{$student->full_name}}</a></td>
                                <td class="hidden-print">{{$student->full_name_arabic}}</td>
                                <td>{{round($student->age)}}</td>
                                <td class="onlyprint">{{ ($student->gender=='f')?"Girl" : "Boy" }} </td>
                                <td>{{$student->nation}}</td>
                                <td class="hidden-print">{{$student->father_mob}}</td>
                                <td class="hidden-print">{{$student->mother_mob}}</td>
                                @if($filter=='active')<td class="hidden-print">{{$student->end_date}}</td><td class="hidden-print">{{$student->remainingDays}}</td>@endif
                                <td class="onlyprint">{{ ($student->totalSubs+$student->totalRefunded+$student->totalHours)-$student->totalPaid }}</td>
                                <td class="hidden-print"><i class="fa fa-print"></td>
                                @if($filter!='del')<td class="hidden-print"><i class="fa fa-trash"></td>@endif
                                <td class="hidden-print"></td>
                                </tr>
                                @endforeach
                                <tbody> 
                                </tbody>
                            </table>
                             

                              <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10 hidden-print"><i class="fa fa-print m-r-5"></i> Print</a>
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
        @endsection