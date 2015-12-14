@extends('formsMaster') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
  
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right hidden-print">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add Sick Leaves</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Add <small> Sick Leaves</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$employee->fullname}}</h4>
                        </div>
                        
                        <div class="panel-body">
                              <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <a href="{{ action('EmployeesController@profile',base64_encode($employee->employee_id)) }}"><i class="fa fa-arrow-left"></i> Back to Employee Profile</a>   
                                 
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
                                     
                                </div>    

                            
                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="starter">Start Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="starter" name="starter" data-fv-notempty="true" value="{{ old('starter') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="ender">End Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="ender" name="ender" data-fv-notempty="true" value="{{ old('ender') }}"  />
                                    </div>
                                </div> 
                               
                               
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                               </form>
                             
                         <div align="center"  class="external-event bg-blue-lighter" data-bg="bg-blue-lighter" > 
                          <h5> Current Period: {{$currentYearStart}} to {{$currentYearEnd}}</h5>
                          <input type="button" class="btn btn-warning m-r-5 m-b-5" value="Full Sick Leave: {{$sickFull}}">   
                         <input type="button" class="btn btn-warning m-r-5 m-b-5" value="Half Sick Leave: {{$sickHalf}}"> 
                         </div>
                        </div> 
                         
                             
                             

                    
                    <!-- end panel --> 
                </div>
            <!-- end row -->
        </div>
    </div>


    <script>
        $(document).ready(function() {
            App.init();


            $('#starter').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'starter');
            $('#eForm').formValidation('revalidateField', 'ender');
            });

            $('#ender').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'ender');
            $('#eForm').formValidation('revalidateField', 'starter');
            });

            
            
       $('#eForm').formValidation(); 
     

  });             
    </script>


 
        @endsection