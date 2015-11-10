@extends('formsMaster') 
 
<?php session(['title' => 'CallCenter']);
session(['subtitle' => 'refundFeedback']); ?> 

@section('content') 
  <link href="/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" />
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                 <li><a href="javascript:;">Refund</a></li>
                <li class="active"><a href="javascript:;">Ticket</a></li>
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header hidden-print">Feedback <small> Form</small></h1>
            <!-- end page-header -->
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                      
                    </div>
                  <h4 class="panel-title">{{ucwords(strtolower($student->full_name))}}</h4>
                </div>
                <div class="panel-body p-0">
                    <div class="vertical-box">
                        <div class="vertical-box-column p-15 bg-silver width-sm">
                            <div id="external-events" class="calendar-event">
                                <h4 class=" m-b-20">Contacts</h4>
                                <div class="external-event bg-green" data-bg="bg-green" >
                                    <h5><i class="fa fa-phone-square fa-lg fa-fw"></i> Father's Info</h5>
                                    <p>{{ucwords(strtolower($student->father_name))}} </p>
                                    <p>{{$student->father_mob}}</p> 
                                </div>

                                <div class="external-event bg-purple" data-bg="bg-purple" >
                                    <h5><i class="fa fa-phone-square fa-lg fa-fw"></i> Mother's Info</h5>
                                    <p>{{ucwords(strtolower($student->mother_name))}} </p>
                                    <p>{{$student->mother_mob}}</p> 
                                </div>
                                <div class="external-event bg-orange" data-bg="bg-orange" >
                                    <h5><i class="fa fa-phone-square fa-lg fa-fw"></i> Emergency</h5>
                                    <p>{{$student->emergency_phone}} </p>
                                     
                                </div>
                            </div>
                        </div>
                            <div id="calendar" class="vertical-box-column p-15 calendar">
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
                                   
                                    

     
                                   <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4" for="review">Feedback :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <textarea class="form-control" name="review" data-fv-notempty="true">{{ $ticket->review }}</textarea> 
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="cheque_name">Name on Cheque :</label>
                                    <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text"   name="cheque_name"   data-fv-notempty="true"    value="{{ $ticket->cheque_name  }}" />
                                    </div>
                                    </div>

                                    <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="cheque_account">Account # :</label>
                                    <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text"   name="cheque_account"   data-fv-notempty="true"    value="{{ $ticket->cheque_account  }}" />
                                    </div>
                                    </div>

                                    <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="cheque_bank">Bank :</label>
                                    <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text"   name="cheque_bank"   data-fv-notempty="true"    value="{{ $ticket->cheque_bank  }}" />
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
                        </div>
                    </div>
                </div>
            </div>
            <!-- end panel -->
        </div>

        <script>
        $(document).ready(function() {
            App.init();
            $('#eForm').formValidation();
        });
        </script>


        @endsection