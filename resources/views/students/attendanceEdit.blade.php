@extends('formsMaster') 

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
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" >
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="panel-body">
                               
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
                                </div>    


                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th >Id</th>
                                    <th>Name</th>
                                    <th class="hidden-print">Name in Arabic</th>  
                                    <th>&nbsp;</th>
                                   
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
                                <td><select class="input-medium" name="attend{{$student->student_id}}" id="attend{{$student->student_id}}">
                                   <option value="0">Present</option>
                                   <option value="1" @if($student->saved) selected @endif>Absent</option>
                                   </select>&nbsp;&nbsp;&nbsp;
                                   <input type="text" class="input-medium" name="reason{{$student->student_id}}" value=""></td>
                               </tr>
                              @endforeach
                                                             
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4"></th>
                                    <th><div class="form-group"> <button type="submit" class="btn btn-primary">Save</button></div></th>
                                </tr>
                             </tfoot>
                            </table>

                              
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