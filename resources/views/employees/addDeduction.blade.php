@extends('formsMaster') 
 
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?> 

@section('content') 
  
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right hidden-print">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add Deduction</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Add <small> Deduction</small></h1>
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
                                    <label class="control-label col-md-4 col-sm-4" for="reason">Reason :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="reason" name="reason" data-fv-notempty="true"   value="{{ old('reason') }}" />
                                    </div>
                                </div>   

                            
                              <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dated"   name="dated" data-fv-notempty="true"   value="{{ old('dated') }}" />
                                    </div>
                                </div>

                            <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="amount">Amount :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="amount" name="amount"   data-fv-notempty="true"  min="1"  value="{{ old('amount') }}" />  
                                    </div>
                                </div> 

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="notes">Notes :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea class="form-control" name="notes" >{{ old('notes') }}</textarea>  
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