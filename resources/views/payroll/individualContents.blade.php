<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
 
<head>
    
    <meta charset="UTF-8" />
    <title>NMS V3.0</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="SHORTCUT ICON"   href="/img/favicon.ico">
    
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/css/animate.min.css" rel="stylesheet" />
    <link href="/css/style.min.css" rel="stylesheet" />
    <link href="/css/style-responsive.min.css" rel="stylesheet" />
    <link href="/css/theme/default.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
     <link href="/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
    <link href="/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />

     
    <link href="/plugins/DataTables/css/data-table.css" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL CSS STYLE ================== -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/plugins/pace/pace.min.js"></script>

    <script src="/plugins/jquery/jquery-1.9.1.min.js"></script>
    <!-- ================== END BASE JS ================== -->
     
 
</head>
<body> 
    <!-- begin #page-loader -->
     
    <!-- end #page-loader -->
    
    
<div id="content" class="content2">
			<!-- begin breadcrumb -->
			 
			<!-- end page-header -->
			<!-- begin row -->
			 <div class=" ">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                             
                            <h4 class="panel-title">{{$employee->fullname}} {{ucwords($stuff)}} History</h4>
                        </div>
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" >
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="panel-body">
                              
                               <span class="text-warning"><i class="fa fa-circle"></i> Pending</span>&nbsp;&nbsp;&nbsp;&nbsp;  
                                <span class="text-success"><i class="fa fa-circle"></i> Approved</span>&nbsp;&nbsp;&nbsp;&nbsp; 
                                <span class="text-danger"><i class="fa fa-circle"></i> Rejected</span>&nbsp;&nbsp;&nbsp;&nbsp;
                               
@if($stuff=='bonus')
                                   <table  id="data-table" class="table table-striped table-bordered">
                                       <thead>
                                           <tr>
                                                <th>#</th>
                                               <th>Date</th>
                                               <th>Amount</th>
                                               <th>Notes</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th>Reject Reason</th> 
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach($bonuses AS $index => $bonus)
                                           <tr class=@if($bonus->approved==0) "text-warning" @elseif($bonus->approved==-1) "text-danger" @elseif($bonus->approved==1) "text-success" @endif>
                                                <td>{{$index+1}}</td>
                                               <td>{{$bonus->dated}}</td>
                                               <td>{{$bonus->amount}}</td>
                                               <td>{{$bonus->notes}}</td>
                                               <td>{{$bonus->admn}}</td> 
                                               <td>{{$bonus->hrm}}</td>
                                               <td>{{$bonus->reject_reason}}</td>
                                                
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>

 

    <!--------------------------------------------------------------Deductions----------------------------------------------------------------------- -->  

                                    @elseif($stuff=='deduction')
                                    <table  id="data-table" class="table table-striped table-bordered">
                                       <thead>
                                           <tr>
                                                <th>#</th>
                                               <th>Date</th>
                                               <th>Reason</th>
                                               <th>Amount</th>
                                               <th>Notes</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th>Reject Reason</th> 
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach($deductions AS $index => $deduction)
                                           <tr class="@if($deduction->approved==0) text-warning @elseif($deduction->approved==-1) text-danger @elseif($deduction->approved==1) text-success @endif">
                                               <td>{{$index+1}}</td>
                                               <td>{{$deduction->dated}}</td>
                                               <td>{{$deduction->reason}}</td>
                                               <td>{{$deduction->amount}}</td>
                                               <td>{{$deduction->notes}}</td>
                                               <td>{{$deduction->admn}}</td> 
                                               <td>{{$deduction->hrm}}</td>
                                               <td>{{$deduction->reject_reason}}</td>
                                                
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>
<!--------------------------------------------------------------Loans----------------------------------------------------------------------- -->  

                                    @elseif($stuff=='loan')
                                    <table  id="data-table" class="table table-striped table-bordered">
                                       <thead>
                                           <tr>
                                                <th>#</th>
                                               <th>Payment Date</th>
                                               <th>Deduction Starts On</th>
                                               <th>Amount</th>
                                               <th>Per Round</th>
                                               <th>Returned</th>
                                               <th>Balance</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th>Reject Reason</th>
                                               
                                           </tr>
                                       </thead>
                                        <tbody>
                                        @foreach($loans AS $index => $loan)
                                           <tr class="@if($loan->approved==0) text-warning @elseif($loan->approved==-1) text-danger @elseif($loan->approved==1) text-success @endif">
                                               <td>{{$index+1}}</td>
                                               <td>{{$loan->payment_date}}</td>
                                               <td>{{$loan->deduction_start}}</td>
                                               <td>{{$loan->loaned_amount}}</td>
                                               <td>{{$loan->deduction_amount}}</td>
                                               <td>{{$loan->total_deducted}}</td>
                                               <td>{{$loan->loaned_amount - $loan->total_deducted}}</td>
                                               <td>{{$loan->admn}}</td> 
                                               <td>{{$loan->hrm}}</td>
                                               <td>{{$loan->reject_reason}}</td>
                                               
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>  
 
<!--------------------------------------------------------------Personal Benefits----------------------------------------------------------------------- -->                                                                      
                                    @elseif($stuff=='personal benefits')
                                   <table  id="data-table" class="table table-striped table-bordered">
                                       <thead>
                                           <tr>
                                                <th>#</th>
                                               <th>Being</th> 
                                               <th>Start Date</th>
                                               <th>Amount</th>
                                               <th>Max Rounds</th> 
                                               <th>Rounds Given</th>
                                               <th>Status</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th>Reject Reason</th>
                                             
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach($benefits AS $index => $benefit)
                                           <tr class=@if($benefit->approved==0) "text-warning" @elseif($benefit->approved==-1 || $benefit->cancelled==1) "text-danger" @elseif($benefit->approved==1) "text-success" @endif>
                                               <td>{{$index+1}}</td>
                                               <td>{{$benefit->benefit}}</td> 
                                               <td>{{$benefit->benefit_start}}</td>
                                               <td>{{$benefit->amount}}</td>
                                               <td>{{$benefit->max_rounds}}</td>
                                               <td>{{$benefit->rounds_given}}</td>
                                               <td> @if($benefit->max_rounds==$benefit->rounds_given) Completed @elseif($benefit->cancelled==1) Cancelled @else Active @endif</td>
                                               <td>{{$benefit->admn}}</td> 
                                               <td>{{$benefit->hrm}}</td>
                                               <td>{{$benefit->reject_reason}}</td>
                                               
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>
 

 <!--------------------------------------------------------------Overtimes----------------------------------------------------------------------- -->  

                                    @elseif($stuff=='overtime')
                                    <table  id="data-table" class="table table-striped table-bordered">
                                       <thead>
                                           <tr>
                                                <th>#</th>
                                               <th>Date</th>
                                               <th>Hours</th>
                                               <th>Amount</th>
                                               <th>Notes</th>
                                               <th>Entered By</th>
                                               <th>Approved By</th> 
                                               <th>Reject Reason</th>
                                               s
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach($overtimes AS $index => $overtime)
                                           <tr class=@if($overtime->approved==0) "text-warning" @elseif($overtime->approved==-1)" text-danger" @elseif($overtime->approved==1) "text-success" @endif>
                                               <td>{{$index+1}}</td>
                                               <td>{{$overtime->dated}}</td>
                                               <td>{{$overtime->hours}}</td>
                                               <td>{{$overtime->amount}}</td>
                                               <td>{!! $overtime->notes !!}</td>
                                               <td>{{$overtime->admn}}</td> 
                                               <td>{{$overtime->hrm}}</td>
                                               <td>{{$overtime->reject_reason}}</td>
                                                
                                           </tr>
                                        @endforeach   
                                       </tbody>
                                   </table>

                         @endif  
                               
                                
                        </div> 
                         
                             
                             
                             </form>
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div> 
    
    
    <!-- ================== BEGIN BASE JS ================== -->
    
    <script src="/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="/crossbrowserjs/html5shiv.js"></script>
        <script src="/crossbrowserjs/respond.min.js"></script>
        <script src="/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/plugins/morris/raphael.min.js"></script>
    <script src="/plugins/morris/morris.js"></script>
    <script src="/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/plugins/jquery-jvectormap/jquery-jvectormap-world-merc-en.js"></script>
    <script src="/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
    <script src="/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="/js/dashboard-v2.min.js"></script>
    <script src="/js/apps.min.js"></script>

     

    <script src="/plugins/DataTables/js/jquery.dataTables.js"></script>
    <script src="/js/table-manage-default.demo.min.js"></script>

    <!-- <script src="/js/form-plugins.demo.min.js"></script> -->
    <script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
     
    <!-- ================== END PAGE LEVEL JS ================== -->
    <script>
        $(document).ready(function() {
            App.init(); 
                                            
            $('#data-table').dataTable( {
                "paging":   false,
                "ordering": true,
                "searching": false,
                "info":     false,
                "aaSorting": [],
                "columnDefs": [ {
                      "targets": 'nosort',
                      "bSortable": false
                    } ]
            } );
 
        
            //fn.datepicker.defaults.format = "yyyy-mm-dd";
             //FormPlugins.init();  

            

        });


    </script>
     
     
</body>
 </html>
