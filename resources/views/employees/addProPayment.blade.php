@extends('formsMaster') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
  
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right hidden-print">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add Bonus</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Add <small> Bonus</small></h1>
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
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="panel-body">
                              
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
                                    <label class="control-label col-md-4 col-sm-4" for="being">Being :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input list="services" class="form-control" type="text" id="being"   name="being" data-fv-notempty="true"   value="{{ old('specialization') }}" />
                                        <datalist id="services">
                                         @foreach($services AS $service)
                                         <option value="{{$service->service}}" ></option>
                                         @endforeach
                                        </datalist>
                                    </div>
                                </div>

                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Company</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="company" data-fv-notempty="true"> 
                                        <option value="">Please choose</option>                                         
                                            @foreach($companies as $company)
                                            <option value="{!! $company->id !!}">{!! $company->company !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="amount">Amount :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="amount" name="amount"   data-fv-notempty="true"  min="1"  value="{{ old('amount') }}" />  
                                    </div>
                                </div>


                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dated"   name="dated" data-fv-notempty="true"   value="{{ old('dated') }}" />
                                    </div>
                                </div>

                             

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="notes">Notes :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea class="form-control" name="notes" data-fv-notempty="true">{{ old('notes') }}</textarea>  
                                    </div>
                                </div>

                               
                               
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                               
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


            $('#dated').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'dated');
            });

            
            
                                             
        $('#eForm').formValidation();  

          });             
    </script>


 
        @endsection