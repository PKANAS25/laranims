@extends('master') 
@section('urlTitles')
<?php session(['title' => 'Administrator']);
session(['subtitle' => 'user_edit']); ?>
@endsection
@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Administrator</a></li>
				<li class="active"><a href="javascript:;">Add Role</a></li>
				 
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
                            <h4 class="panel-title">Add New</h4>
                        </div>
                        <div class="panel-body">
                            <form  method="POST" autocomplete="OFF" class="form-horizontal">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                    <div class="form-group">
                                    <label for="name" class="col-lg-2 control-label">Name</label>
                                   <div class="col-md-9">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-2 control-label">User name</label>
                                   <div class="col-md-9">
                                        <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="role" class="col-lg-2 control-label">Role</label>
                                   <div class="col-md-9">
                                       <select class="form-control" id="role" name="role[]" multiple>
                                            @foreach($roles as $role)
                                                <option value="{!! $role->id !!}"  @if(in_array($role->id, $selectedRoles))
                                                        selected="selected" @endif >{!! $role->display_name !!}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                
                                    
                                <div class="form-group">
                                    
                                    <div class="col-md-9">
                                         <button type="reset" class="btn btn-sm btn-error">Cancel</button>
                                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                                    </div>
                                </div>

                                



                                </fieldset>
                            </form>
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
        @endsection