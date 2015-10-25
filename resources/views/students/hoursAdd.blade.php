@extends('students.hoursMaster') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Subscription</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Add <small> extra hours</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Extra hours</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                  

                                <fieldset>
                                    
                                     

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="no_of_hours">Hours :</label>
                                    <div class="col-md-6 col-sm-6">
                                        
                                            <input class="form-control"   name="no_of_hours" data-fv-notempty="true" data-fv-digits="true" type="number" value="0" min="1">  
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="dated" name="dated"  data-fv-notempty="true"    value="{{ old('dated') }}" />
                                    </div>
                                </div>

                                  

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="discount">Discount :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" class="form-control" name="discount"  id="discount" value="0" data-fv-integer="true"
                data-fv-integer-message="The value is not an integer" data-fv-greaterthan="true" data-fv-greaterthan-value="0"  value="{{old('discount')}}" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="discount_reason">Dicount Reason</label>
                                    <div class="col-md-6 col-sm-6"><input type="hidden" name="conflict" value="15"> 
                                        <select class="form-control" name="discount_reason" id="discount_reason" >
                                          <option value="">Select</option>
                                          <option value="Owners Decision">Owners Decision</option>
                                          <option value="Faculty Discount">Faculty Discount</option>
                                          <option value="Siblings Discount">Siblings Discount</option>
                                          <option value="UFE Approval">UFE Approval</option> 
                                          <option value="AbuDhabi Municipality">AbuDhabi Municipality</option>
                                          <option value="Bara'em Pvt. School">Bara'em Pvt. School</option>
                                          <option value="Dar Al Uloom Pvt. School">Dar Al Uloom Pvt. School</option>
                                        </select>
                                    </div>
                                </div>
 
                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="notes">Notes :</label>
                                    <div class="col-md-6 col-sm-6"><textarea cols="75" name="notes">{{old('notes')}}</textarea> 
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="reset" class="btn btn-sm btn-error">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>



                                </fieldset>
                            </form>

                              <script>
                                

                                    
                                  


                                </script>
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
        @endsection