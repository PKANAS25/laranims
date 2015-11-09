@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
 <div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Students</a></li>
                <li><a href="javascript:;">Profile</a></li>
                 
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Student Profile <small> </small></h1>
            <!-- end page-header -->
            <!-- begin profile-container -->
            <div class="profile-container">
                <!-- begin profile-section -->
                <div class="profile-section">
                    <!-- begin profile-left -->
                    <div class="profile-left">
                        <!-- begin profile-image -->
                        <div class="profile-image">

                            <img src="{{$profile_pic}}" />
                            <i class="fa fa-user hide"></i>
                        </div>
                        <!-- end profile-image -->
                         
                        <!-- begin profile-highlight -->
                        <div class="profile-highlight">
                            <div class="alert alert-info fade in m-b-15">
                                Amount Payable: <strong>{{$totalPayable}}</strong> <br>
                                Total Paid: <strong>{{round($totalPaid)}}</strong> <br>
                                Balance: <strong>{{round($totalPayable-$totalPaid)}}</strong>
                            </div>
                             
                        </div><br>

                        <div class="profile-highlight"  >
                            <div class="checkbox m-b-5 m-t-0">
                               @if(Auth::user()->hasRole('nursery_admin') && Auth::user()->branch==$student->branch) 
                               <a  href="{!! action('SubscriptionController@add', array(base64_encode($student->student_id),base64_encode($student->standard)) ) !!}"  class="btn btn-primary btn-xs m-r-5">New Subscription</a> 
                               @else <a href="javascript:;" class="btn btn-default btn-xs m-r-5">New Subscription</a> @endif 
                            </div>
                            
                            <div class="checkbox m-b-5 m-t-0">
                                @if(Auth::user()->hasRole('nursery_admin') && Auth::user()->branch==$student->branch)
                                <a href="{!! action('SubscriptionController@addHours', array(base64_encode($student->student_id),base64_encode($student->standard)) ) !!}" class="btn btn-primary btn-xs m-r-5">Extra Hours</a>  
                                @else <a href="javascript:;" class="btn btn-default btn-xs m-r-5">Extra Hours</a>  @endif 
                            </div>
                            
                            <div class="checkbox m-b-5 m-t-0">
                                @if(Auth::user()->hasRole('nursery_admin') && Auth::user()->branch==$student->branch)
                                <a  href="{!! action('InvoiceController@add', base64_encode($student->student_id) ) !!}" class="btn btn-success btn-xs m-r-5">New Payment</a> 
                                @else <a href="javascript:;" class="btn btn-default btn-xs m-r-5">New Payment</a> @endif 
                            </div> 
                            
                            <div class="checkbox m-b-5 m-t-0">
                                @if(Auth::user()->hasRole('nursery_admin') && Auth::user()->branch==$student->branch)
                                <a  href="{!! action('SubscriptionController@refund', array(base64_encode($student->student_id),base64_encode($student->standard)) ) !!}" class="btn btn-danger btn-xs m-r-5">Refund</a>
                                @else <a href="javascript:;" class="btn btn-default btn-xs m-r-5">Refund</a> @endif 

                            </div>

                            @if(Auth::user()->hasRole('nursery_admin') && Auth::user()->branch==$student->branch && (round($totalPayable-$totalPaid))<0)
                             <div class="checkbox m-b-5 m-t-0">
                                
                                <a  href="{!! action('SubscriptionController@refundExcess', array(base64_encode($student->student_id),base64_encode(abs(round($totalPayable-$totalPaid)))) ) !!}" class="btn btn-danger btn-xs m-r-5">Excess Refund</a>
                               
                            </div>
                            @endif
                            
                            <div class="checkbox m-b-5 m-t-0">
                                @if(Auth::user()->hasRole('nursery_admin') && Auth::user()->branch==$student->branch)
                                <a href="javascript:;" class="btn btn-info btn-xs m-r-5">Transfer</a> 
                                @else <a href="javascript:;" class="btn btn-default btn-xs m-r-5">Transfer</a>  @endif 
                            </div>
                             
                            
                            
                        </div>
                        <!-- end profile-highlight -->
                    </div>
                    <!-- end profile-left -->
                    <!-- begin profile-right -->
                    <div class="profile-right">
                        <!-- begin profile-info -->
                        <div class="profile-info">
                            <!-- begin table -->
                            <div class="table-responsive">
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
                                <table class="table table-profile table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <h4>{{ $student->full_name }} <small>{{ $student->full_name_arabic }} </small></h4>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="highlight">
                                            <td class="field">ID</td>
                                            <td><a  >{{ $student->student_id }} </a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="field">Grade</td>
                                            <td>{{ $student->standard." - ".$student->division }} </td>
                                        </tr>
                                        <tr>
                                            <td class="field">Gender</td>
                                            <td><a >{{ ($student->gender=='f')?"Girl" : "Boy" }} </a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Date of Birth</td>
                                            <td><a >{{date('d-M-Y',strtotime($student->dob))}}</a></td>
                                        </tr>
                                        
                                        <tr class="highlight">
                                            <td class="field">Age</td>
                                            <td><a >{{round($student->age,1)}}</a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="field">Joined on</td>
                                            <td>
                                                {{date('d-M-Y',strtotime($student->joining_date))}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="field">Nationality</td>
                                            <td>{{$student->nation}}</td>
                                        </tr>
                                        <tr>
                                            <td class="field">Address</td>
                                            <td><a >{{$student->address}}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Location</td>
                                            <td>@if($student->map)<a style="text-decoration:none" href="https://maps.google.com/?q={{$student->map}}" title="View location" target="_blank" ><i class="fa fa-map-marker"></i> Location</a> @else  <i class="fa fa-map-marker"></i> Location @endif</td>
                                        </tr>
                                        <tr>
                                            <td class="field">Bus</td>
                                            <td>
                                                {{$student->bus_name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="field"> </td>
                                            <td >
                                                Edit
                                            </td>
                                        </tr>
                                         
                                         
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table -->
                        </div>
                        <!-- end profile-info -->
                    </div>
                    <!-- end profile-right -->
                </div>
                <!-- end profile-section -->
                 
                  </div><br>

            <!-- end profile-container --> 
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-4 -->
                        <div class="col-md">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#default-tab-1" data-toggle="tab"><i class="fa fa-calendar"></i> Subscriptions</a></li>
                        <li class=""><a href="#default-tab-2" data-toggle="tab"><i class="fa fa-money"></i> Payments</a></li>
                        <li class=""><a href="#default-tab-3" data-toggle="tab"><i class="fa fa-child"></i> Events</a></li> 
                        <li class=""><a href="#default-tab-4" data-toggle="tab"><i class="fa fa-phone"></i> Guardian Info</a></li>
                    </ul>
                    <div class="tab-content">
                        
                        
                        <div class="tab-pane fade active in" id="default-tab-1">
                       <div class="panel-group" id="accordion">
                        <div class="panel panel-inverse overflow-hidden">

<!----------------------------------------------------Valid Subscriptions ------------------------------------------------------------------------------- -->  

                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Valid Subscriptions 
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <table id="data-table1" class="table table-striped  ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subscription</th>
                                        <th>Grade</th>
                                        <th>Added by</th>
                                        <th>Validity</th>                                        
                                        <th>Transportation</th>
                                        <th>Discount</th>
                                        <th>Amount</th>                                        
                                        <th colspan="2">&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                   @foreach($subscriptions as $subscription )
                                   @if($subscription->deleted==0 && $subscription->refunded==0)
                                        <tr><td>{{$i}}</td>
                                        <td>{{$subscription->group_name}}</td>
                                        <td>{{$subscription->current_standard}}</td>
                                        <td>{{$subscription->subscriber}}</td>
                                        <td>{!!  $subscription->subscription_type==5 ? date("d-M-y",strtotime($subscription->start_date)) : date("d-M-y",strtotime($subscription->start_date))." To ".date("d-M-y",strtotime($subscription->end_date)) !!}</td>
                                        <td>@if($subscription->added)
                                                Added
                                                @elseif($subscription->trans==3)
                                                NO
                                                @elseif($subscription->trans==2)
                                                Oneway
                                                @elseif($subscription->trans==1)
                                                RoundTrip
                                                @endif
                                         </td>
                                        <td><a title="{{$subscription->discount_reason}}">{{$subscription->discount}}</a></td>
                                        <?php $detailing = "Registration Fee: ".$subscription->registration_fee."\n"."Activity: ".$subscription->other_fee."\n".
                                                            "Transportation: ".$subscription->transportation_fee."\n";
                                         ?>

                                        <td><a title="{{$detailing}}">{{ $subscription->amount}}</a></td>
                                        <td>@if($subscription->locked==0)&nbsp;&nbsp; &nbsp;&nbsp; <a title="Delete suscription" href="#modal-dialogs{{$subscription->subscription_id}}" class="btn btn-sm btn-danger" data-toggle="modal"><i class="fa fa-trash"></i></a>@endif</td>
                                            <td>
                                                <div id="divUnlock{{ $subscription->subscription_id}}">
                                                    @if(Auth::user()->hasRole('subscriptionUnlock') && $subscription->locked==1)&nbsp;&nbsp; &nbsp;&nbsp;
                                                        <button class="btn btn-sm" id="subsUnlock{{ $subscription->subscription_id}}" value="{{ $subscription->subscription_id}}"> <i class="fa fa-lock"></i></button>
                                                    @elseif(Auth::user()->hasRole('subscriptionUnlock') && $subscription->locked==0)&nbsp;&nbsp; &nbsp;&nbsp;
                                                        <button class="btn btn-sm" id="subsLock{{ $subscription->subscription_id}}" value="{{ $subscription->subscription_id}}"> <i class="fa fa-unlock"></i></button>
                                                    @endif
                                                </div>
                                            <script type="text/javascript">
                                            $(document.body).on('click', '#subsUnlock{{ $subscription->subscription_id}}', function(e){
                                                e.preventDefault();
                                                subscriptionId = $(this).val();

                                                 $.get('/subsLockUnlock',{action:'unlock', subscriptionId:subscriptionId }, function(actionBlade){                      
                                                    $("#divUnlock{{ $subscription->subscription_id}}").html(actionBlade);
                                                     
                                                });
                                            });

                                            $(document.body).on('click', '#subsLock{{ $subscription->subscription_id}}', function(e){
                                                e.preventDefault();
                                                subscriptionId = $(this).val();

                                                 $.get('/subsLockUnlock',{action:'lock', subscriptionId:subscriptionId }, function(actionBlade){                      
                                                    $("#divUnlock{{ $subscription->subscription_id}}").html(actionBlade);
                                                     
                                                });
                                            });
                                            </script>
                                            
                                        </td>
                                        </tr>
                                        <?php $i++;?>
                                        @endif                                   
                                    @endforeach

                                    @foreach($subscriptions_hour as $subscription )
                                   @if($subscription->deleted==0)
                                        <tr><td>{{$i}}</td>
                                        <td>{{$subscription->no_of_hours}} Hours</td>
                                        <td>{{$subscription->current_standard}}</td>
                                        <td>{{$subscription->subscriber}}</td>
                                        <td>{{ $subscription->dated }}</td>
                                        <td>-</td>
                                        <td><a title="{{$subscription->discount_reason}}">{{$subscription->discount}}</a></td>
                                        <td>{{ $subscription->amount}}</td>
                                        <td></td>
                                        <td></td>
                                        </tr>
                                        <?php $i++;?>
                                        @endif                                   
                                    @endforeach
                                     
                                    </tbody>
                                    </table>
                                    @foreach($subscriptions as $subscription  )
                                    @if($subscription->deleted==0   && $subscription->refunded==0 && $subscription->locked==0)
                                    <!-- #modal-dialog -->
                                        <div class="modal fade" id="modal-dialogs{{$subscription->subscription_id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">{{$subscription->group_name}}</h4>
                                                    </div>

                                                     
                                                    <form autocomplete="OFF" method="post" action="/subscriptionDelete/{{base64_encode($student->student_id)}}" >
                                                        <div class="modal-body">
                                                             
                                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">   
                                                                <input type="hidden" name="subscriptionId" value="{!! base64_encode($subscription->subscription_id) !!}">                                                     
                                                                <input  class="form-control" type="text" id="delete_reasons{{$subscription->subscription_id}}" name="delete_reason" placeholder="Delete Reason"   />
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Close</a>
                                                            <button disabled type="submit" id="submits{{$subscription->subscription_id}}" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                     

                                                </div>
                                            </div>
                                        </div>

                                        <script type="text/javascript">
       
                                    $('#delete_reasons{{$subscription->subscription_id}}').keyup(function(e){                                    
                             
                                        e.preventDefault();
                                        var value =($(this).val());
                                        
                                       if(value!="" && value.length>3)
                                       $('#submits{{$subscription->subscription_id}}').prop('disabled', false);
                                       else
                                       $('#submits{{$subscription->subscription_id}}').prop('disabled', true);
                                       
                                    }); 
                                   </script>  
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
<!---------------------------------------------------- Refunded Subscriptions ------------------------------------------------------------------------------- --> 
                        <div class="panel panel-inverse overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Refunded Subscriptions
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table id="data-table2" class="table table-striped  ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subscription</th>
                                        <th>Grade</th>
                                        <th>Validity</th>
                                         
                                        <th>Amount</th>
                                        <th>Refunded By</th> 
                                        <th>Date of refund</th> 
                                        <th>Non Refundable Amount</th>                                        
                                       
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                   @foreach($subscriptions as $subscription )
                                   @if($subscription->deleted==0 && $subscription->refunded==1)
                                        <tr><td>{{$i}}</td>
                                        <td>{{$subscription->group_name}}</td>
                                        <td>{{$subscription->current_standard}}</td>
                                        <td>{!!  $subscription->subscription_type==5 ? date("d-M-y",strtotime($subscription->start_date)) : date("d-M-y",strtotime($subscription->start_date))." To ".date("d-M-y",strtotime($subscription->end_date)) !!}</td>
                                        <td>{{$subscription->amount}}</td>
                                        <td>{{$subscription->refunder}}</td>
                                        <td>{{$subscription->refunded_date}}</td>                                         
                                        <td>{{$subscription->non_refundable_amount}}</td>
                                        
                                        </tr>
                                        <?php $i++;?>
                                        @endif                                   
                                    @endforeach
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
<!----------------------------------------------------Deleted Subscriptions  ------------------------------------------------------------------------------- --> 
                        
                        <div class="panel panel-inverse overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Deleted Subscriptions  
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table id="data-table3" class="table table-striped  ">
                                <thead>
                                   <tr>
                                        <th>#</th>
                                        <th>Subscription</th>
                                        <th>Grade</th>
                                        <th>Validity</th>
                                        <th>Amount</th>    
                                        <th>Deleted By</th>
                                        <th>Delete Reason</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                         <?php $i=1;?>
                                   @foreach($subscriptions as $subscription )
                                   @if($subscription->deleted==1)
                                     <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$subscription->group_name}}</td>
                                        <td>{{$subscription->current_standard}}</td>
                                        <td>{!!  $subscription->subscription_type==5 ? date("d-M-y",strtotime($subscription->start_date)) : date("d-M-y",strtotime($subscription->start_date))." To ".date("d-M-y",strtotime($subscription->end_date)) !!}</td>  
                                        <td>{{$subscription->amount}}</td>
                                        <td>{{$subscription->deleter. " on ".$subscription->delete_date}}</td>
                                        <td>{{$subscription->delete_reason}}</td>
                                     </tr>
                                    <?php $i++;?>
                                    @endif                                   
                                    @endforeach

                                    @foreach($subscriptions_hour as $subscription )
                                   @if($subscription->deleted==1)
                                        <tr><td>{{$i}}</td>
                                        <td>{{$subscription->no_of_hours}} Hours</td>
                                        <td>{{$subscription->current_standard}}</td>
                                        <td>{{ $subscription->dated }}</td>
                                        <td>{{ $subscription->amount }}</td>
                                        <td>{{$subscription->deleter. " on ".$subscription->delete_date}}</td>
                                        <td>{{$subscription->delete_reason}}</td>
                                         
                                        </tr>
                                        <?php $i++;?>
                                        @endif                                   
                                    @endforeach
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                         
                        
                         
                    </div> 
                            
                            </div>
                      
      <!----------------------------------------------------Payments------------------------------------------------------------------------------- -->                
                      
                        <div class="tab-pane fade" id="default-tab-2">
                        
                       <div class="panel-group" id="accordion">
                        <div class="panel panel-inverse overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                          Receipts 
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <table id="data-table" class="table table-striped  ">
                                <thead>
                                        <th >Receipt #</th>
                                        <th >Date</th>
                                        <th >Received by</th>
                                        <th >Medium of payment</th>
                                        <th >Subscriptions Pay</th>
                                        <th >Item/Events Pay</th>                                        
                                        <th >Discount</th>
                                        <th >Service Charge</th>
                                        <th >Amount</th>                                        
                                        <th >&nbsp;</th>
                                    </thead>
                                    <tbody> 
                                               
                                               @foreach($invoices as $invoice )
                                               @if($invoice->deleted==0 &&  $invoice->amount_paid>=0)
                                                 <tr>
                                                     
                                                    <td>{{$invoice->invoice_no}}</td>
                                                    <td>{{date("d-M-Y",strtotime($invoice->dated))}}</td>
                                                    <td>{{$invoice->issuer}}</td>
                                                    <td>
                                                        @if($invoice->cheque==1)
                                                        Cheque{{$invoice->cheque_no}}
                                                        @elseif($invoice->card==1)
                                                        Card
                                                        @else
                                                        Cash
                                                        @endif
                                                    </td>
                                                    <td>{{$invoice->subscriptions_amount}}</td>
                                                    <td>{{$invoice->amount_paid - $invoice->service_charge - $invoice->subscriptions_amount}}</td>
                                                    <td>{{$invoice->discount}}</td>
                                                    <td>{{$invoice->service_charge}}</td>
                                                    <td>{{$invoice->amount_paid}}</td>
                                                    <td><a title="Print receipt"  href="{!! action('InvoiceController@view', base64_encode($invoice->invoice_id) ) !!}" class="btn btn-sm btn-primary"><i class="fa fa-print"></i></a>
                                                     @if($invoice->locked==0)&nbsp;&nbsp; &nbsp;&nbsp; <a title="Delete receipt" href="#modal-dialog{{$invoice->invoice_id}}" class="btn btn-sm btn-danger" data-toggle="modal"><i class="fa fa-trash"></i></a>@endif
                                                    </td>

                                                 </tr>
                                                 
                                                @endif                                   
                                                @endforeach   
                                    </tbody>
                                    </table>
                                    @foreach($invoices as $invoice )
                                    @if($invoice->deleted==0 &&  $invoice->amount_paid>=0 && $invoice->locked==0)
                                    <!-- #modal-dialog -->
                                        <div class="modal fade" id="modal-dialog{{$invoice->invoice_id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Receipt# {{$invoice->invoice_no}}</h4>
                                                    </div>

                                                     
                                                    <form autocomplete="OFF" method="post" action="{!! action('InvoiceController@delete', base64_encode($student->student_id) ) !!}" >
                                                        <div class="modal-body">
                                                             
                                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">   
                                                                <input type="hidden" name="invoiceId" value="{!! base64_encode($invoice->invoice_id) !!}">                                                     
                                                                <input  class="form-control" type="text" id="delete_reason{{$invoice->invoice_id}}" name="delete_reason" placeholder="Delete Reason"   />
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Close</a>
                                                            <button disabled type="submit" id="submit{{$invoice->invoice_id}}" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                     

                                                </div>
                                            </div>
                                        </div>

                                        <script type="text/javascript">
       
                                    $('#delete_reason{{$invoice->invoice_id}}').keyup(function(e){                                    
                             
                                        e.preventDefault();
                                        var value =($(this).val());
                                        
                                       if(value!="" && value.length>3)
                                       $('#submit{{$invoice->invoice_id}}').prop('disabled', false);
                                       else
                                       $('#submit{{$invoice->invoice_id}}').prop('disabled', true);
                                       
                                    }); 
                                   </script>  
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
<!----------------------------------------------------Refunds Issued  ------------------------------------------------------------------------------- --> 
                        
                        <div class="panel panel-inverse overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Refunds Issued 
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table id="data-table4" class="table table-striped ">
                                <thead>
                                    <tr>
                                    <th>Refund #</th>
                                    <th>Date</th>
                                    <th>Issued by</th>
                                    <th>Amount</th>                                        
                                    <th>&nbsp;</th>
                                    </tr>
                                  </thead>
                                  <tbody>  
                                     @foreach($invoices as $invoice )
                                               @if($invoice->deleted==0 &&  $invoice->amount_paid<0)
                                                 <tr>
                                                     
                                                    <td>{{$invoice->invoice_no}}</td>
                                                    <td>{{date("d-M-Y",strtotime($invoice->dated))}}</td>
                                                    <td>{{$invoice->issuer}}</td> 
                                                    <td>{{round(abs($invoice->amount_paid))}}</td>
                                                    <td>Actions</td>

                                                 </tr>
                                                 
                                                @endif                                   
                                                @endforeach   
                                  </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
<!----------------------------------------------------  Deleted Receipts  ------------------------------------------------------------------------------- --> 
                        
                        <div class="panel panel-inverse overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Deleted Receipts  
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseSix" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table id="data-table5" class="table table-striped ">
                                <thead>
                                    <tr>
                                    <th>Receipt #</th>
                                    <th>Date</th>
                                    <th>Deleted by</th>
                                    <th>Deleted on</th>
                                    <th>Delete reason</th>                                        
                                    <th>Amount</th>      
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($invoices as $invoice )
                                               @if($invoice->deleted==1)
                                                 <tr>
                                                     
                                                    <td>{{$invoice->invoice_no}}</td>
                                                    <td>{{date("d-M-Y",strtotime($invoice->dated))}}</td>
                                                    <td>{{$invoice->deleter}}</td> 
                                                    <td>{{$invoice->delete_date}}</td> 
                                                    <td>{{$invoice->delete_reason}}</td>
                                                    <td>{{$invoice->amount_paid}}</td>

                                                 </tr>
                                                 
                                                @endif                                   
                                                @endforeach  
                                </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                         
                        
                         
                    </div> 
                            
                        </div>
                        
    <!-----------------------------------------------------Events------------------------------------------------------------------------------ -->          
                        
                        <div class="tab-pane fade" id="default-tab-3">
                             
                             
                            <table id="data-table6" class="table table-striped ">
                                <thead>
                                    <tr>
                                    
                                        <th>Event Title</th>                                        
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($events as $event)
                                        <tr>
                                        <td>{{$event->title}}</td>
                                        <td>{{$event->start_date}}</td>
                                        <td>{{$event->end_date}}</td>
                                        <td>{{$event->description}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                             
                             
                        </div>
                        
    <!------------------------------------------------------------Guardian info----------------------------------------------------------------------- -->                    
                         
                      <div class="tab-pane fade" id="default-tab-4">
                            <p>
                            <div class="table-responsive">
                            <table class="table table-profile table-striped">
                                     
                                    <tbody>
                                        <tr class="highlight">
                                            <td class="field">Father's Name</td>
                                            <td><a  >{{$student->father_name}}</a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="field">Tel</td>
                                            <td><a>{{$student->father_tel}}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Mobile</td>
                                            <td><i class="fa fa-mobile fa-lg m-r-5"></i>{{$student->father_mob}} </td>
                                        </tr>
                                        <tr>
                                            <td class="field">Email</td>
                                            <td> {{$student->father_email}}</td>
                                        </tr>
                                        
                                        <tr class="highlight">
                                            <td class="field">Works at</td>
                                            <td><a  >{{$student->father_workplace}} as {{$student->father_job}}</a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="field"> </td>
                                            <td>
                                                <hr>
                                            </td>
                                        </tr>
                                         <tr class="highlight">
                                            <td class="field">Mother's Name</td>
                                            <td><a  >{{$student->mother_name}}</a></td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="field">Tel</td>
                                            <td><a>{{$student->mother_tel}}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Mobile</td>
                                            <td><i class="fa fa-mobile fa-lg m-r-5"></i>{{$student->mother_mob}} </td>
                                        </tr>
                                        <tr>
                                            <td class="field">Email</td>
                                            <td> {{$student->mother_email}}</td>
                                        </tr>
                                        
                                        <tr class="highlight">
                                            <td class="field">Works at</td>
                                            <td><a  >{{$student->mother_workplace}} as {{$student->mother_job}}</a></td>
                                        </tr>
                                         
                                         
                                    </tbody>
                                </table>
                                </div>
                            </p>
                        </div>
                      
                      
                    </div>
                     
                     
                </div>
                         
                    </div>
                    <!-- end row -->
                 
               
          
        </div>
            
@endsection