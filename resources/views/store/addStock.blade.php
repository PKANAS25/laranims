@extends('formsMaster') 
 
<?php session(['title' => 'Store']);
session(['subtitle' => '']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Store</a></li>
				<li class="active"><a href="javascript:;">Add Stock</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Add <small> Stock</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$item->item_name}}</h4>
                        </div>
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data">
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
                                     
                                </div>    

                            
                            <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Supplier :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="supplier_id" data-fv-notempty="true">
                                            <option value="">Please choose</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{!! $supplier->supplier_id !!}">{!! $supplier->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 

                            <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="item_count">No of items to stock :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="item_count" name="item_count"   data-fv-notempty="true"  min="1"  value="{{ old('stock') }}" />  
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="cost">Cost (per single item) :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" min="1" id="cost" name="cost"   data-fv-notempty="true" value="{{ old('cost') }}" />  
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="stocked_date">Stocked Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="stocked_date"   name="stocked_date" data-fv-notempty="true"   value="{{ old('stocked_date') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fileToUpload">Invoice</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="file"  accept="image/*"    data-fv-file="true"  data-fv-file-extension="jpeg,jpg"  data-fv-file-type="image/jpeg,image/jpg"  data-fv-file-maxsize="629760" data-fv-file-message="The selected file is not valid" id="fileToUpload" name="fileToUpload" /> <span class="text-info">Max size 500 Kb, JPG only</span>
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
                         
                    
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>


    <script>
        $(document).ready(function() {
            App.init();


            $('#stocked_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'stocked_date');
            });

            
            
                                             
        $('#eForm').formValidation();  

          });             
    </script>


 
        @endsection