@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Refund</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Request <small> Refund</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Refund</h4>
                        </div>
                        <div class="panel-body">

                     @if($posted)
                     <table  class="table table-striped table-bordered">
                     <tr>
                        <td width="22%">Amount Payable</td>
                        <td width="78%">{{$totalPayable}}</td>
                      </tr>
                      <tr>
                        <td>Total Paid</td>
                        <td>{{$totalPaid}}</td>
                      </tr>
                      <tr>
                        <td>Balance</td>
                        <td>{{$balance}}</td>
                      </tr>
                      
                       <tr>
                        <td>Last Date</td>
                        <td>{{$last_date}}</td>
                      </tr>
                      </table>

                      <table class="table">
                        <thead>
                        <tr>
                            <th>Subscription</th>
                            <th>Validity</th>
                            <th>Days Attended</th>
                            <th>Total Amount</th>
                            <th>Non-Refundable</th>
                            <th>Refundable</th>
                        </tr>
                    </thead>
                    <tr>
                    <td>{{$subscription->group_name}}</td>
                    <td>{!!  $subscription->subscription_type==5 ? date("d-M-y",strtotime($subscription->start_date)) : date("d-M-y",strtotime($subscription->start_date))." To ".date("d-M-y",strtotime($subscription->end_date)) !!}</td>
                    <td>{{$stayed_days}}</td>
                    <td> {{$subscription->amount}} </td>
                    <td> {{$non_refundable_grand}}</td>
                    <td> {{$refundable_grand }} </td>                     
                    </tr>
                    <tr>
                     <td colspan="6">
                     <form enctype="multipart/form-data" name="frmTkt" method="post" action="SubscriptionController@refundTicket" autocomplete="OFF">
                     
                     <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                     
                     <input type="hidden" name="subscription_chosen" id="subscription_chosen" value="{{$subscription->subscription_id}}">
                     <input type="hidden" name="refundable_amount" id="refundable_amount" value="{{$refundable_final}}">
                     <input type="hidden" name="non_refundable" id="non_refundable" value="{{ $non_refundable_grand }}">
                     <input type="hidden" name="refund_date" id="refund_date" value="{{$last_date}}">
                     
                     <input type="hidden" name="full_refundable_amount" id="full_refundable_amount" value="{{$fullRefundFinal}}">
                     <input type="hidden" name="full_non_refundable" id="full_non_refundable" value="{{$fullNonRefund}}">
                     
                     @if($refundable_final>0)
                     <div class="external-event bg-green" data-bg="bg-green" >
                                    <h5><i class="fa fa-check fa-lg fa-fw"></i> Refundable</h5>
                                    <p>Refundable amount including other balance = {{$refundable_final}} </p>
                                    <input class="btn btn-warning m-r-5 m-b-5"  type="submit" name="ticket" id="ticket"  value="Issue refund ticket">
                                </div>
                     
                     
                     @else
                     <div class="external-event bg-red" data-bg="bg-red" >
                                    <h5><i class="fa fa-ban fa-lg fa-fw"></i> No Refunds</h5>
                                    <p>Refundable amount including previous balance = {{$refundable_final}}. No Refunds Possible</p>
                                </div>
                     
                     @endif
                    <br>
                    <br>

                    @if($fullRefundFinal>0 && $today<$subscription->start_date)
                    <div class="external-event bg-purple" data-bg="bg-purple" >
                                    <h5><i class="fa fa-check-square-o fa-lg fa-fw"></i> Full Refund</h5>
                                    <p>Refundable amount excluding registration & special fees = {{$fullRefundFinal}}  </p>
                                    <input class="btn btn-success m-r-5 m-b-5"  type="Submit" name="ticket" id="ticket"  value="Issue Full Refund" >
                                </div> 
                    @endif
                    </form>
                     
                     
                      </td>
                                        </tr>
                    </table>


<!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                             @else
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                 
                                <input type="hidden" name="student" value="{!! $studentId !!}"> 
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                     

                                <fieldset>
                                
                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="last_date">Last Date Attended:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="last_date" name="last_date"  data-fv-notempty="true"   data-fv-remote="true" value="{{ old('start_date') }}" />
                                    </div>
                                </div>
 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        @if($notOk>0)<p class="alert alert-danger">No refunds Possible. There is uncashed cheques.</p>
                                        @else
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Check</button>
                                        @endif
                                    </div>
                                </div>

                                </fieldset>


                            </form>
<!-- -------------------------------------------------------------------------------------------------------------------------------------------------- -->
                            @endif

                              <script>
                                

                                    
                                  


                                </script>
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
    <script>
        $(document).ready(function() {
            App.init();


            $('#last_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'last_date'); 
             
            });

            
            
            
             
                                             
            //$('#eForm').formValidation();

            $('#eForm').formValidation({

                message: 'This value is not valid',
                fields:{ 

                    last_date: { 
                     validators: {
                     
                     notEmpty: {},
                     remote: {
                        url: '/subsCheckRefund' ,
                        data: function(validator, $field, value) {
                            return {
                                last_date: validator.getFieldElements('last_date').val(),
                                studentId: {{$studentId}}
                            };
                        }

                    }
                }
            } 

            


        }                
 
    });

        
    

        });

                            
    </script>
        @endsection