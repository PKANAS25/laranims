@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'branchStore']); ?> 

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
                                <td class="p-t-10"><span class="label label-info f-s-11"><strong>{{$item->price}}</strong></span> 
                                @if(Auth::user()->hasRole('StoreManager')) <a class="btn btn-inverse btn-icon  btn-sm"><i class="ion-android-create"></i></a> @endif </td>
                            
                        </tr>
                        <tr>
                            <td class="p-t-10">
                                 <i class="fa fa-folder-open-o fa-2x pull-left fa-fw"></i>   
                                <div class="m-t-4">Category: </div>
                            </td>
                            <td class="p-t-10"><span class="label label-success f-s-11"><strong>{{$item->category_name}}</strong></span> </td>
                        </tr>
                        <tr>
                            <td class="p-t-10">
                                <i class="fa fa-mail-reply fa-2x pull-left fa-fw"></i>  
                                <div class="m-t-4">Returned: </div>                                
                            </td>
                            <td class="p-t-10"><span class="label label-danger f-s-11"><strong>{{$item->returned}}</strong></span></td>
                        </tr>
                        
                        <tr>
                            <td class="p-t-10">
                                <i class="fa fa-shopping-cart fa-2x pull-left fa-fw"></i>  
                                <div class="m-t-4">Sold: </div>                                
                            </td>
                            <td class="p-t-10"><span class="label label-primary f-s-11"><strong>{{$item->sold}}</strong></span></td>
                        </tr>

                        <tr>
                            <td class="p-t-10">
                                <i class="fa fa-exclamation fa-2x pull-left fa-fw"></i>  
                                <div class="m-t-4">Not Recieved: </div>                                
                            </td>
                            <td class="p-t-10"><span class="label label-danger f-s-11"><strong>{{$qnr}}</strong></span></td>
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
                                            <img height="100%" width="100%" src="{{$picBig}}" style="border:#06F solid 1px" />
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> 
                    @endif

                   @if($pending)
                    <div class="alert alert-warning">
                       <i class="fa fa-info-circle fa-lg m-r-5 pull-left m-t-2"></i> Total count of pending return is <b class="text-inverse">{{$pending}}</b>.
                    </div>
                    @endif
  
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
                                    @if(session('status'))
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
                    <div class="row">
                        <!-- begin col-4 -->
                    <div class="panel panel-inverse" >
                        
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a> 
                            </div> 
                        
                    <ul class="nav nav-tabs nav-tabs-inverse">
                        <li class="active"><a href="#default-tab-1" data-toggle="tab"><i class="fa fa-list-alt"></i> Stock Details</a></li>
                        <li><a href="#default-tab-2" data-toggle="tab"><i class="fa fa-reply"></i> Returned</a></li>
                         
                    </ul>
                        <div class="tab-content">
                        <div class="tab-pane fade active in" id="default-tab-1"> 
                            <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Quantity</th> 
                                            <th>Transfered By</th>
                                            <th>Approved By</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php $total=0;?>
                                        @foreach($stocks as $index => $stock) 
                                        <tr>
                                            <td>{{$stock->transfered_date}}</td>
                                            <td>{{$stock->item_count}}</td><?php $total+=$stock->item_count?> 
                                            <td>{{$stock->transfered_by}}</td>
                                            <td>{{$stock->approved_by}}</td>
                                            
                                        </tr> 
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td>{{$total}}</td>
                                            <td></td>
                                            <td></td> 
                                        </tr>
                                    </tbody>
                                    </table>
                        </div>
                        <div class="tab-pane fade" id="default-tab-2">
                            @if(Auth::user()->hasRole('BranchStore') && $item->stock>0)         
                        <div align="right"><a class="btn btn-primary btn-xs m-r-5" href = "{{action('StoreController@itemReturn',base64_encode($item->item_id))}}" title="Click here to initiate item return"><i class="fa fa-minus"></i>  Return Stock</a> </div><br> 
                        @endif 
                                <table class="table">
                                    <thead>
                                        <tr>
                                            
                                            <th>No. of items</th>
                                            <th>Returned on</th>
                                            <th>Returned By</th>
                                            <th>Message</th>
                                            <th>Approval</th>
                                            <th> </th>                                             
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($returns as $index => $return)
                                        <tr> 
                                            <td>{{$return->count}}</td>
                                            <td>{{$return->dated}}</td>
                                            <td>{{$return->transfered_by}}</td>
                                            <td>{{$return->message}}</td>
                                            <td>@if($return->approval==0)<span class="text-warning">Pending <i class="fa fa-exclamation-circle"></i></span>
                                                @elseif($return->approval==1)<span class="text-success">{{$return->approved_by}} <i class="fa fa-check-circle-o"></i></span>
                                                @elseif($return->approval<0)<span class="text-danger"><a class="text-danger" title="{{$return->rejection_reason}}">{{$return->approved_by}}</a> <i class="fa fa-times-circle"></i></span>
                                                @endif
                                            </td>
                                            <td>@if(Auth::user()->hasRole('BranchStore') && $return->approval==0)
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
                                                          action: '{!! action('StoreController@itemReturnCallback', base64_encode($return->return_id)) !!}'
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
                                    </table>
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