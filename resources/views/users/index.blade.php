@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Administrator']);
session(['subtitle' => 'users']); ?>
@endsection

@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Administrator</a></li>
				<li class="active"><a href="javascript:;">Users</a></li>
				 
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Users <small></small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                
                            </div>
                            <h4 class="panel-title">Admins</h4>
                        </div>
                        <div class="panel-body">
                             <div class="table-responsive">
                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <?php $i=1; ?>
                                     @foreach($users as $user)
                                    <tr>
                                        <td>{!! $i; !!}</td>
                                        <td><a href="{!! action('UsersController@edit', base64_encode($user->id)) !!}">{!! $user->name !!} </a></td>
                                        <td>{!! $user->email !!}</td>
                                    </tr>
                                    <?php $i++; ?>
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