@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Home']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Dashboard <small>header small text goes here...</small></h1>
            <!-- end page-header -->
            
            <!-- begin row -->
            <div class="row">
                 @if (session('warning'))
                                    <div class="alert alert-danger">
                                        {{ session('warning') }}   
                                    </div>
                                @endif
                <!-- begin col-3 -->
                <div class="col-md-3 col-sm-6">
                    <div class="widget widget-stats bg-green">
                        <div class="stats-icon"><i class="fa fa-money"></i></div>
                        <div class="stats-info">
                            <h4>NEW PAYMENTS</h4>
                            <p>71,922 AED</p>    
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;">  <i class="fa fa-arrow-circle-o-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- end col-3 -->
                <!-- begin col-3 -->
                <div class="col-md-3 col-sm-6">
                    <div class="widget widget-stats bg-blue">
                        <div class="stats-icon"><i class="fa fa-link"></i></div>
                        <div class="stats-info">
                            <h4>NEW SUBSCRIPTIONS</h4>
                            <p>9 yearly, 36 others</p>   
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"> <i class="fa fa-arrow-circle-o-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- end col-3 -->
                <!-- begin col-3 -->
                <div class="col-md-3 col-sm-6">
                    <div class="widget widget-stats bg-purple">
                        <div class="stats-icon"><i class="fa fa-child"></i></div>
                        <div class="stats-info">
                            <h4>NEW ENROLMENTS</h4>
                            <p>7</p>    
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"> <i class="fa fa-arrow-circle-o-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- end col-3 -->
                <!-- begin col-3 -->
                <div class="col-md-3 col-sm-6">
                    <div class="widget widget-stats bg-red">
                        <div class="stats-icon"><i class="fa fa-clock-o"></i></div>
                        <div class="stats-info">
                            <h4>EXPIRING STUDENTS</h4>
                            <p>12</p> 
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"> <i class="fa fa-arrow-circle-o-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- end col-3 -->
            </div>
            <!-- end row -->

            <div class="row"> <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                 
                            </div>
                            <h4 class="panel-title">Active students - Today</h4>
                        </div>
                        <div class="panel-body">
                             <div class="table-responsive"> 

                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Student</th> 
                                    <th>Grade</th>                                    
                                    <th>Subscription</th>
                                    <th>Validity</th>
                                    <th>Trans</th>
                                    <th>Discount</th>
                                    <th>Amount </th>
                                    <th> </th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <?php $i=1; ?>
                                     @foreach($subscriptions as $subs)

                                    <tr @if($subs->remaining_days<8) class="danger" @endif>
                                        <td>{!! $i !!}</td>
                                        <td>{!! $subs->student_id !!}</td>
                                        <td><a href="{!! action('StudentsController@profile', base64_encode($subs->student_id)) !!}">{!!  $subs->full_name !!} </a></td>
                                        <td>{!! $subs->standard !!}</td>
                                        <td>{!! $subs->group_name !!}</td>
                                        <td>{!!  $subs->subscription_type==5 ? date("d-M-y",strtotime($subs->start_date)) : date("d-M-y",strtotime($subs->start_date))." To ".date("d-M-y",strtotime($subs->end_date)) !!}</td>
                                        <td>@if($subs->added)
                                                Added
                                                @elseif($subs->trans==3)
                                                NO
                                                @elseif($subs->trans==2)
                                                Oneway
                                                @elseif($subs->trans==1)
                                                RoundTrip
                                                @endif
                                        </td>
                                        <td><a href="#" style="text-decoration:none" title="{!! $subs->discount_reason  !!}">{!! $subs->discount  !!} </a></td>
                                        <td>{!! $subs->amount !!}</td>
                                        <td>Transfer</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                     
                                </tbody>
                            </table> 
                            <a class="btn btn-sm text-white bg-yellow-darker"  href="/excelHome"><i class="fa fa-file-excel-o"></i> Excel</a>
                        </div>
                        </div>
                    </div> 
                    <!-- end panel --> 
                </div>
                 
            <!-- end row --></div>
        </div>
               
        @endsection