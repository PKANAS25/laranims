@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => 'grades']); ?>
@endsection


@section('content')
<link href="/css/invoice-print.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script> 


 <script type="text/javascript">
 
    $(function(){
 
    // add multiple select / deselect functionality
    $("#selectall").click(function () {
          $('.checkboxer').attr('checked', this.checked);
    });
 
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".checkboxer").click(function(){
 
        if($(".checkboxer").length == $(".checkboxer:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }
 
    });
});
</script>
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
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" >
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="panel-body">
                            
                                <div class="invoice-header onlyprintCenter">
                                    <div class="invoice-from" >
                                         
                                        <address class="m-t-5 m-b-5" align="center">
                                          <img src="/img/logo.jpg" width="65" height="65" alt=""> {{Auth::user()->branch_name}} - {{ucwords($filter)}} Students List
                                        </address>
                                    </div>
                                  
                                </div>
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
                                    @if (session('statusDel'))
                                        <div class="alert alert-danger">
                                            {{ session('statusDel') }}   
                                        </div>
                                    @endif
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
                                    @if($filter=='all' || $filter=='deleted')<th class="hidden-print">&nbsp;</th>@endif
                                    @if($filter!='deleted')<th class="hidden-print"><input  type="checkbox" id="selectall"/></th>@endif
                                    </tr>
                                </thead><?php $i=0;?>
                                 <tbody> 
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
                                @if($filter=='all')
                                    <td class="hidden-print">
                                        @if(($student->deleteFlag1+$student->deleteFlag2+$student->deleteFlag3)==0)
                                            <!--<a href="javascript:decision('Are you sure you want to delete this student?','{!! action('StudentsController@delete', base64_encode($student->student_id)) !!}')"><i class="fa fa-trash"></i></a>-->
                                             <button id="Xdel{{$i}}"><i  class="fa fa-trash text-danger"></i></button>
                                        <script type="text/javascript">
                                            $('#Xdel{{$i}}').click(function(ev) {
                                            
                                              $.msgbox("<p>Are you sure you want to delete this student?</p>", {
                                                type    : "prompt",
                                                 inputs  : [
                                                  {type: "hidden", name: "no", value: "no"} 
                                                ],
                                                 
                                                buttons : [
                                                  {type: "submit", name: "delete", value: "Delete"},
                                                  {type: "cancel", value: "Cancel"}
                                                ],
                                                form : {
                                                  active: true,
                                                  method: 'get',
                                                  action: '{!! action('StudentsController@delete', base64_encode($student->student_id)) !!}'
                                                }
                                              });
                                              
                                              ev.preventDefault();
                                            
                                            });
                                        </script>
                                        @else 
                                          <button onclick='$.msgbox("You cannot delete this student. There are subscriptions or invoices assigned.", {type: "error"});'><i class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                @elseif($filter=='deleted')
                                    <td class="hidden-print">                                         
                                           <!-- <a href="javascript:decision('Are you sure you want to restore this student?','{!! action('StudentsController@restore', base64_encode($student->student_id)) !!}')"><i class="fa fa-undo"></i></a> -->
                                            <button id="XRef{{$i}}"><i  class="fa fa-undo text-info"></i></button>
                                        <script type="text/javascript">
                                            $('#XRef{{$i}}').click(function(ev) {
                                            
                                              $.msgbox("<p>Are you sure you want to restore this student?</p>", {
                                                type    : "prompt",
                                                 inputs  : [
                                                  {type: "hidden", name: "no", value: "no"} 
                                                ],
                                                 
                                                buttons : [
                                                  {type: "submit", name: "delete", value: "Delete"},
                                                  {type: "cancel", value: "Cancel"}
                                                ],
                                                form : {
                                                  active: true,
                                                  method: 'get',
                                                  action: '{!! action('StudentsController@restore', base64_encode($student->student_id)) !!}'
                                                }
                                              });
                                              
                                              ev.preventDefault();
                                            
                                            });
                                        </script>                                      
                                    </td>
                                @endif
                                @if($filter!='deleted')<td class="hidden-print"><input class="checkboxer" type="checkbox" name="studentIds[]"   value="{{$student->student_id}}"  ></td>@endif
                                </tr>
                                @endforeach
                              
                               
                                </tbody>
                                
                            </table>

                              
                                <a class="btn btn-sm btn-info m-b-10 hidden-print" href="{!! action('GradesController@students', array($classId,'active')) !!}">Show Active</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-sm btn-warning m-b-10 hidden-print" href="{!! action('GradesController@students', array($classId,'all')) !!}">Show All</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-sm btn-inverse m-b-10 hidden-print" href="{!! action('GradesController@students', array($classId,'deleted')) !!}">Show Deleted</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-success m-b-10 hidden-print"><i class="fa fa-print m-r-5"></i> Print</a>
                        </div> 
                        <div class="panel-body hidden-print">
                             @if($filter!='deleted')
                             <div id="messages" align="center" class="alert-danger" ></div>
                            <fieldset>
                                <table class="table" ><tr><td>
                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="newGrade">Transfer to :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="newGrade" data-fv-notempty="true" data-fv-message="Please select target grade"   >
                                            <option value="">Please choose</option>
                                            @foreach($transferGrades as $transferGrade)
                                            <option value="{!! $transferGrade->class_id !!}">{!! $transferGrade->standard."-".$transferGrade->division !!}</option>
                                            @endforeach
                                        </select> </div></div></td>
                                        <td>
                                         <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        @if(Auth::user()->hasRole('nursery_admin'))<button type="submit" class="btn btn-primary">Transfer</button>@endif
                                    </div></div></td></tr></table>
                                    
                                    </fieldset>
                                    @endif
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

            $('#data-table').dataTable( {
                "paging":   false,
                "ordering": true,
                "info":     false,

            } );
                                             
            //$('#eForm').formValidation();

            $('#eForm').formValidation({
                framework: 'bootstrap',
                err: {
                    container: '#messages'
                },
                fields:{
                    

                'studentIds[]': {
                  validators: {
                    notEmpty: {
                        message: 'Please select at least one student'
                    }
                }
            } 

 


        }                
 
    }) 
      
        });

                            
    </script>
        @endsection