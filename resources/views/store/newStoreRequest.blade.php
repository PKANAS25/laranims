@extends('formsMaster') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => 'storeRequests']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Request</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Store <small> New Request</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">New Request </h4>
                        </div>
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
                                <div class="panel-body">
                                <form class="form-inline" name="eForm" id="eForm"  method="POST" autocomplete="OFF">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="tempRequest" value="{!! $tempRequest !!}">
                                 
                                    <div class="form-group m-r-10">
                                        <select class="form-control"   name="category" id="category" >
                                          <option value="0">All Categories</option>
                                           @foreach($categories as $category)
                                          <option value="{{$category->category_id}}">{{$category->category}}</option>
                                          @endforeach
                                          </select>
                                    </div> 
                                    <div class="form-group m-r-10">
                                            <div id="itemsLoader">
                                                <select class="form-control"   name="item_id" id="item_id" >
                                                  <option value="0">Select</option>
                                                   @foreach($items as $item)
                                                  <option value="{{$item->item_id}}">{{$item->item_name}}</option>
                                                  @endforeach
                                                </select>
                                            </div>
                                       </div>   
                                 <div class="form-group m-r-10"><input class="form-control" type="text" placeholder="Items Not listed" name="new_item" id="new_item" /></div>
                                   <div class="form-group m-r-10"><input class="form-control" type="number"  min="1" value="1" name="qty" id="qty" /></div>

                                 
                                <input type="button" name="itemAdd" id="itemAdd" value="Add Item" /><br><br>&nbsp;
                                 <div id="addedItems"> </div>   
                            </form> 
                            </div>
                        
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
    <script>
 $(document).ready(function() {
      App.init();
      <!---------------------------------------------------==================--------------------------------------------- -->

  function itemLoader()
    {
        
      var category = $("#category").val();

       $.get('/storeItemLoader',{tempReq:{{$tempRequest}},category:category }, function(itemsBlade){ 
              
              $("#itemsLoader").html(itemsBlade);
              $('input[name=qty]').val('1'); 
              $('input[name=new_item]').val(''); 
          });
    }   

<!---------------------------------------------------==================--------------------------------------------- -->
  $("#itemAdd").click( function(e) {
    
    e.preventDefault();
    var item_id = $("#item_id").val();
    var new_item = $("#new_item").val();  
    var qty = $("#qty").val();  
     
     if(item_id==0 && new_item=="")
     {
       
       return false;
     }
     
     else
     {
        

       $.get('/storeItemAdd',{item_id:item_id,new_item:new_item,qty:qty, tempReq:{{$tempRequest}} }, function(itemsBlade){ 
              //console.log(itemBlade); 
              $("#addedItems").html(itemsBlade);
              itemLoader(); 
          });

     }//else
    
     
    
    
    });   
<!---------------------------------------------------==================--------------------------------------------- -->  

$(document.body).on('click', '.delItemButton', function(e){
    e.preventDefault();
    custom = $(this).val();

    $.get('/storeItemRemove',{customId:custom, tempReq:{{$tempRequest}} }, function(eventsBlade){ 
              //console.log(itemBlade); 
              $("#addedItems").html(eventsBlade);
              itemLoader();
              payFinalizer();
          });
  });
<!---------------------------------------------------==================--------------------------------------------- -->  

$(document.body).on('change', '[name="category"]', function(e) {
         itemLoader();
      });
<!---------------------------------------------------==================--------------------------------------------- -->  
  });
    </script>
        @endsection