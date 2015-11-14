@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'main']); ?> 

@section('content') 

<link rel="stylesheet" type="text/css" href="/dist/msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="/dist/msgbox/jquery.msgbox.min.js"></script> 

<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Store</a></li>
                <li><a href="javascript:;">Item</a></li>
                
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Item <small>Details</small></h1>
            <!-- end page-header -->
            
            <!-- begin row -->
            <div class="row">
                <!-- begin col-3 -->
                <div class="col-md-3">
                    <div class="m-b-10 text-inverse f-s-12"><b>{{$item->item_name}}</b></div>
                    <table class="m-b-20 width-full">
                        
                        <tr>
                            <td class="p-t-10" width="50%">
                                <i class="fa fa-barcode fa-2x pull-left fa-fw"></i>  
                                <div class="m-t-4">Product Code: </div>                                
                            </td>
                            <td class="p-t-10"><span class="label label-default f-s-11"><strong>{{$item->product_code}}</strong></span></td>
                        </tr>

                        <tr>
                            <td class="p-t-10">    
                                <i class="fa fa-list-alt fa-2x pull-left fa-fw"></i>                             
                                <div class="m-t-4  ">Current Stock:</div>
                            </td>
                            <td class="p-t-10"> <span class="label label-warning f-s-11"><strong>{{$item->stock}}</strong></span> </td>
                        </tr>
                        

                        <tr>
                            <td class="p-t-10"> 
                            <i class="fa fa-money fa-2x pull-left fa-fw"></i>                                
                                <div class="m-t-4 ">Current Price: </div>
                                </td>
                                <td class="p-t-10"><span class="label label-info f-s-11"><strong>{{$item->price}}</strong></span></td>
                            
                        </tr>
                        <tr>
                            <td class="p-t-10">
                                 <i class="fa fa-folder-open-o fa-2x pull-left fa-fw"></i>   
                                <div class="m-t-4">Category: </div>
                            </td>
                            <td class="p-t-10"><span class="label label-success f-s-11"><strong>{{$item->category_name}}</strong></span></td>
                        </tr>
                        
                        <tr>
                            <td class="p-t-10">
                                <i class="fa fa-random fa-2x pull-left fa-fw"></i>  
                                <div class="m-t-4">Transfered: </div>                                
                            </td>
                            <td class="p-t-10"><span class="label label-primary f-s-11"><strong>{{$transfered}}</strong></span></td>
                        </tr>

                        


                        @if($pic)
                        <tr>
                            <td class="p-t-10" colspan="2" >
                                    <a href="#modal-dialog"  data-toggle="modal"><img src="{{$pic}}" style="border:#06F solid 1px" /></a>
                             </td>
                        </tr>
                        @endif

                        @if($item->description)
                        <tr>
                            <td class="p-t-10" colspan="2">
                                <i class="fa fa-comment-o fa-2x pull-left fa-fw"></i>  
                                  {{$item->description}} 
                             </td>
                        </tr>
                        @endif
                    </table>
                   
                     @if($pic)
                     <div class="modal fade" id="modal-dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">{{$item->item_name}}</h4>
                                        </div>
                                        <div class="modal-body" align="center">
                                            <img src="{{$picBig}}" style="border:#06F solid 1px" />
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> 
                    @endif

                    @if($pending)
                    <div class="alert alert-warning">
                        <i class="fa fa-info-circle fa-lg m-r-5 pull-left m-t-2"></i> Total pending branch transfer count for this item is <b class="text-inverse">{{$pending}}</b>.
                    </div>
                    @endif
  
                    <div class="hidden-print">
                                    
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}   
                                            <span class="close" data-dismiss="alert">×</span>
                                        </div>
                                    @endif
                                </div>  
                </div>
                <!-- end col-3 -->
                <!-- begin col-9 -->
                <div class="col-md-9">

                    <!-- begin panel -->
                    <div class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
                        <div class="panel-heading p-0">
                            <div class="panel-heading-btn m-r-10 m-t-10">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            </div>
                            <!-- begin nav-tabs -->
                            <div class="tab-overflow">
                                <ul class="nav nav-tabs nav-tabs-inverse">
                                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                                    <li class="active"><a href="#nav-tab-1" data-toggle="tab">Stock Management</a></li>
                                    <li class=""><a href="#nav-tab-2" data-toggle="tab">Transfers</a></li>
                                   
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="nav-tab-1">
                        
                        @if(Auth::user()->hasRole('StoreManager'))         
                        <div align="right"><a class="btn btn-primary btn-xs m-r-5" href = "{{action('StoreController@addStock',base64_encode($item->item_id))}}" title="Click here to add new stock"><i class="fa fa-plus"></i>  Add stock</a> </div>    
                        @endif   

                    <p> </p>
                    <div class="panel-group" id="accordion">
                        
                <!--------------------------------------------------Added Stock------------------------------------------------------------------>

                        <div class="panel panel-success overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Added Stocks
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table   class="table  ">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Quantity</th>
                                            <th>Cost</th>
                                            <th>Total Cost</th>
                                            <th>Supplier</th>
                                            <th>Added By</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stocks as $stock)
                                        @if($stock->deleted==0 && $stock->branch_id==0)
                                        <tr>
                                            <td>{{$stock->stocked_date}}</td>
                                            <td>{{$stock->item_count}}</td>
                                            <td>{{$stock->cost}}</td>
                                            <td>{{$stock->cost*$stock->item_count}}</td>
                                            <td>{{$stock->supplier_name}}</td>
                                            <td>{{$stock->added_by}}</td>
                                            <td>@if($stock->file)
                                                <a href="#modal-dialog{{$stock->stock_id}}"  data-toggle="modal"><i class="fa fa-download text-info"></i></a>
                                                <div class="modal fade" id="modal-dialog{{$stock->stock_id}}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title">Stock Invoice</h4> <a href="/store/upload/invoice/{{base64_encode($stock->stock_id)}}" title="Click here to upload an invoice">
                                                <i class="fa fa-upload text-inverse"></i> Change File</a>
                                                            </div>
                                                            <div class="modal-body" >
                                                                <img height="100%" width="100%" src="/uploads/stock_invoices/{{$stock->stock_id}}.jpg"  />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div> 
                                                @else
                                                @if(Auth::user()->hasRole('StoreManager'))
                                                <a href="/store/upload/invoice/{{base64_encode($stock->stock_id)}}" title="Click here to upload an invoice">
                                                <i class="fa fa-upload text-inverse"></i></a>
                                                @endif                                               
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            <!-------------------------------------------------- Branch Returns ------------------------------------------------------------------>
                        <div class="panel panel-info overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Branch Returns
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                   <div class="panel-body">
                                    <table   class="table  ">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Quantity</th> 
                                            <th>Branch</th>
                                            <th>Returned By</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stocks as $stock)
                                        @if($stock->deleted==0 && $stock->branch_id>0)                                        
                                        <tr>
                                            <td>{{$stock->stocked_date}}</td>
                                            <td>{{$stock->item_count}}</td> 
                                            <td>{{$stock->branch_name}}</td>
                                            <td>{{$stock->added_by}}</td>
                                            <td></td>
                                        </tr>
                                        @endif                                         
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-------------------------------------------------- Deleted Stock ------------------------------------------------------------------>
                        <div class="panel panel-danger overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Deleted Stocks
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                   <div class="panel-body">
                                    <table   class="table  ">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Quantity</th>
                                            <th>Cost</th>
                                            <th>Total Cost</th>
                                            <th>Supplier</th>
                                            <th>Reason</th>                                             
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stocks as $stock)
                                        @if($stock->deleted==1)
                                        <tr>
                                            <td>{{$stock->stocked_date}}</td>
                                            <td>{{$stock->item_count}}</td>
                                            <td>{{$stock->cost}}</td>
                                            <td>{{$stock->cost*$stock->item_count}}</td>
                                            <td>{{$stock->supplier_name.$stock->branch_name}}</td>
                                            <td>{{$stock->delete_reason}}</td> 
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

                    </div>
                    <!----------------------------------------------------------------------------------------------------------------------------------- -->

                            
                            <div class="tab-pane fade" id="nav-tab-2">
                                @if(Auth::user()->hasRole('StoreManager'))         
                        <div align="right"><a class="btn btn-primary btn-xs m-r-5" href = "{{action('StoreController@itemTransfer',base64_encode($item->item_id))}}" title="Click here to initiate a transfer"><i class="fa fa-plus"></i>  New Transfer</a> </div><br> 
                        @endif 
                                <table   class="table  ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Branch</th>
                                            <th>No. of items</th>
                                            <th>Transfered on</th>
                                            <th>Transfered By</th>
                                            <th>Approval</th>
                                            <th> </th>                                             
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transfers as $index => $transfer)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td>{{$transfer->branch_name}}</td>
                                            <td>{{$transfer->count}}</td>
                                            <td>{{$transfer->dated}}</td>
                                            <td>{{$transfer->transfered_by}}</td>
                                            <td>@if($transfer->approval==0)<span class="text-warning">Pending <i class="fa fa-exclamation-circle"></i></span>
                                                @elseif($transfer->approval==1)<span class="text-success">{{$transfer->approved_by}} <i class="fa fa-check-circle-o"></i></span>
                                                @elseif($transfer->approval<0)<span class="text-danger"><a class="text-danger" title="{{$transfer->rejection_reason}}">{{$transfer->approved_by}}</a> <i class="fa fa-times-circle"></i></span>
                                                @endif
                                            </td>
                                            <td>@if($transfer->approval==0)
                                                <button id="XRes{{$index+1}}"><i  class="fa fa-undo text-info"></i></button>
                                                <script type="text/javascript">
                                                    $('#XRes{{$index+1}}').click(function(ev) {
                                                    
                                                      $.msgbox("<p>Are you sure you want to callback this transfer?</p>", {
                                                        type    : "prompt",
                                                         inputs  : [
                                                          {type: "hidden", name: "no", value: "no"} 
                                                        ],
                                                         
                                                        buttons : [
                                                          {type: "submit", name: "Callback", value: "Callback"},
                                                          {type: "cancel", value: "Cancel"}
                                                        ],
                                                        form : {
                                                          active: true,
                                                          method: 'get',
                                                          action: '{!! action('StoreController@itemTransferCallback', base64_encode($transfer->transfer_id)) !!}'
                                                        }
                                                      });
                                                      
                                                      ev.preventDefault();
                                                    
                                                    });
                                                </script>
                                                @endif
                                            </td> 
                                        </tr>
                                        @endforeach
                                    </tbody>
                            </div> 

                             
                             
                        </div>
                    </div>
                    <!-- end panel -->
                     
                </div>
                <!-- end col-9 -->
            </div>
            <!-- end row -->
        </div>
 
        @endsection