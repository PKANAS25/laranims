@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Administrator']);
session(['subtitle' => 'Roles']); ?>
@endsection

@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Administrator</a></li>
				<li class="active"><a href="javascript:;">Roles</a></li>
				 
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Roles <small></small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                 
                            </div>
                            <h4 class="panel-title">Roles</h4>
                        </div>
                        <div class="panel-body">
                             <div class="table-responsive">
                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                       <th>Name</th>
                                        <th>Display Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                     
                                     @foreach($roles as $role)
                                    <tr>
                                        <td>{!! $role->name !!}</td>
                                        <td>{!! $role->display_name !!}</td>
                                        <td>{!! $role->description !!}</td>
                                    </tr>
                                     
                                @endforeach
                                     
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
        @endsection