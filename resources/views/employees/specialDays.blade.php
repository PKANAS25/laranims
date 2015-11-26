@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
 
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Special Days</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Employee <small>  Special Working Days</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Assign Days</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"  data-fv-message="Required Field"  data-fv-icon-invalid="glyphicon glyphicon-remove"  data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach
 
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>  

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="day1">Day 1:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="day1" name="day1"   data-fv-notempty="true" value="{{old('day1')}}" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="day2">Day 2:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="day2" name="day2"  value="{{old('day2')}}" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="day3">Day 3:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="day3" name="day3"  value="{{old('day3')}}" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="day4">Day 4:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="day4" name="day4"  value="{{old('day4')}}" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="day5">Day 5:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="day5" name="day5"  value="{{old('day5')}}" />
                                    </div>
                                </div> 
 
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div> 

                                </fieldset>
                            </form> 
                              
                        </div> 
                        <div class="panel-body">
                        <hr><h4>History</h4><hr>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>Date</td>
                                    <td>Added by</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($assignedDays AS $day)
                                <tr>
                                    <td>{{$day->dated}}</td>
                                    <td>{{$day->adder}} on {{$day->added_on}}</td>
                                    <td class="text-danger">@if($day->deleted==1) {{$day->deleteman}} on {{$day->deleted_on}} @endif</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    <!-- end panel --> 
                </div>
            <!-- end row -->
        </div>
    </div>
    <script>
        $(document).ready(function() {
            App.init(); 

            $('#day1').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
               daysOfWeekDisabled: [0,1,2,3,4,5]
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'day1');
            });

            
            $('#day2').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
               daysOfWeekDisabled: [0,1,2,3,4,5]
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'day2');
                });

             $('#day3').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
               daysOfWeekDisabled: [0,1,2,3,4,5]
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'day3');
                });
             
             $('#day4').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
               daysOfWeekDisabled: [0,1,2,3,4,5]
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'day4');
                });
            
             $('#day5').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
               daysOfWeekDisabled: [0,1,2,3,4,5]
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'day5');
                });  

                 
                                             
            $('#eForm').formValidation();

            

  });

                            
    </script>
        @endsection