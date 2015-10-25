@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => 'grades']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Grades</a></li>
				<li class="active"><a href="javascript:;">List</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Grades <small> List</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Grades</h4>
                        </div>
                        <div class="panel-body">

                              <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>Grade</th>
                                    <th>Strength</th>
                                    <th>Advisor</th> 
                                    </tr>
                                </thead>
                                @foreach($grades as $grade)
                                <tr>
                                <td><a href="{!! action('GradesController@students', array(base64_encode($grade->class_id),'active')) !!}">{{$grade->standard."-".ucwords($grade->division)}}</a></td>
                                <td>{{$grade->strength}}</td>
                                <td>{{$grade->fullname}}</td>
                                </tr>
                                @endforeach
                                <tbody> 
                                </tbody>
                            </table>
                             

                              
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
        @endsection