@extends('master') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'main']); ?> 

@section('content') 
  
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
                        <i class="fa fa-info-circle fa-lg m-r-5 pull-left m-t-2"></i> There are <b class="text-inverse">{{$pending}}</b> branch transfers pending for this item.
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
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}   
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
                                    <li class=""><a href="#nav-tab-3" data-toggle="tab">History</a></li> 
                                     
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="nav-tab-1">
                                 
                        <a class="btn btn-primary btn-xs m-r-5" href = "" title="Click here to add new stock"><i class="fa fa-plus"></i>  Add stock</a>        
                    <p> </p>
                    <div class="panel-group" id="accordion">
                        
                <!--------------------------------------------------Added Stock------------------------------------------------------------------>

                        <div class="panel panel-info overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Added Stocks
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <table   class="table table-striped table-bordered">
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
                                        @if($stock->deleted==0)
                                        <tr>
                                            <td>{{$stock->stocked_date}}</td>
                                            <td>{{$stock->item_count}}</td>
                                            <td>{{$stock->cost}}</td>
                                            <td>{{$stock->cost*$stock->item_count}}</td>
                                            <td>{{$stock->supplier_name}}</td>
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


                        <!--------------------------------------------------Deleted Stock------------------------------------------------------------------>
                        <div class="panel panel-danger overflow-hidden">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-plus-circle pull-right"></i> 
                                        Deleted Stocks
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                         
                    </div>
                    <!----------------------------------------------------------------------------------------------------------------------------------- -->

                            </div>
                            <div class="tab-pane fade" id="nav-tab-2">
                                <h3 class="m-t-10">Nav Tab 2</h3>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                    Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
                                    est diam sagittis orci, a ornare nisi quam elementum tortor. 
                                    Proin interdum ante porta est convallis dapibus dictum in nibh. 
                                    Aenean quis massa congue metus mollis fermentum eget et tellus. 
                                    Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
                                    nec eleifend orci eros id lectus.
                                </p>
                                <p>
                                    Aenean eget odio eu justo mollis consectetur non quis enim. 
                                    Vivamus interdum quam tortor, et sollicitudin quam pulvinar sit amet. 
                                    Donec facilisis auctor lorem, quis mollis metus dapibus nec. Donec interdum tellus vel mauris vehicula, 
                                    at ultrices ex gravida. Maecenas at elit tincidunt, vulputate augue vitae, vulputate neque.
                                    Aenean vel quam ligula. Etiam faucibus aliquam odio eget condimentum. 
                                    Cras lobortis, orci nec eleifend ultrices, orci elit pellentesque ex, eu sodales felis urna nec erat. 
                                    Fusce lacus est, congue quis nisi quis, sodales volutpat lorem.
                                </p>
                            </div>
                            <div class="tab-pane fade" id="nav-tab-3">
                                <h3 class="m-t-10">Nav Tab 3</h3>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                    Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
                                    est diam sagittis orci, a ornare nisi quam elementum tortor. 
                                    Proin interdum ante porta est convallis dapibus dictum in nibh. 
                                    Aenean quis massa congue metus mollis fermentum eget et tellus. 
                                    Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
                                    nec eleifend orci eros id lectus.
                                </p>
                                <p>
                                    Aenean eget odio eu justo mollis consectetur non quis enim. 
                                    Vivamus interdum quam tortor, et sollicitudin quam pulvinar sit amet. 
                                    Donec facilisis auctor lorem, quis mollis metus dapibus nec. Donec interdum tellus vel mauris vehicula, 
                                    at ultrices ex gravida. Maecenas at elit tincidunt, vulputate augue vitae, vulputate neque.
                                    Aenean vel quam ligula. Etiam faucibus aliquam odio eget condimentum. 
                                    Cras lobortis, orci nec eleifend ultrices, orci elit pellentesque ex, eu sodales felis urna nec erat. 
                                    Fusce lacus est, congue quis nisi quis, sodales volutpat lorem.
                                </p>
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