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
				<li class="active"><a href="javascript:;">Subscription</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Add <small> new subscription</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Subscription</h4>
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
                                 <input type="hidden" name="student" value="{!! $studentId !!}"> 

                                <fieldset>
                                    
                                     

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="subscription_type">Subscription :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="subscription_type" data-fv-notempty="true" data-fv-remote="true" >
                                            <option value="">Please choose</option>
                                            @foreach($groups as $group)
                                            <option value="{!! $group->group_id !!}">{!! $group->group_name !!}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="start_date">Starting Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="start_date" name="start_date"  data-fv-notempty="true"   data-fv-remote="true" value="{{ old('start_date') }}" />
                                    </div>
                                </div>

                                 

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Transportation :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="trans" value="1" id="radio-required" data-fv-notempty="true" /> Round Trip
                                            </label>    
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="trans" id="radio-required2" value="2" /> Oneway
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="trans" id="radio-required2" value="3" /> No
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="Registration">Add Registration Fee:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" name="registration" />
                                                 
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="other">Add activity/Other Fee :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" name="other" />
                                                 
                                            </label>
                                        </div>
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
                                    <label class="control-label col-md-4 col-sm-4" for="offer">Select</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="offer" id="offer" class="form-control"  >
                                            <option value="">Select an offer</option>
                                            @foreach($offers as $offer)
                                            <option value="{!! $offer->offer_id !!}">{!! $offer->title !!}</option>
                                            @endforeach
                                        </select>
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
    <script>
        $(document).ready(function() {
            App.init();


            $('#start_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'start_date');
            $('#eForm').formValidation('revalidateField', 'subscription_type');
             
            });

            
            
            
             
                                             
            //$('#eForm').formValidation();

            $('#eForm').formValidation({

                message: 'This value is not valid',
                fields:{
                    

                    subscription_type: {
                     
                     
                     validators: {
                     
                     notEmpty: {},
                     remote: {
                        url: '/subsCheck' ,
                        data: function(validator, $field, value) {
                            return {
                                start_date: validator.getFieldElements('start_date').val(),
                                studentId: {{$studentId}}
                            };
                        }

                    }
                }
            },

                    start_date: {
                     threshold: 5,
                     verbose: false,
                     
                     validators: {
                     
                     notEmpty: {},
                     remote: {
                        url: '/subsCheck' ,
                        data: function(validator, $field, value) {
                            return {
                                subscription_type: validator.getFieldElements('subscription_type').val(),
                                studentId: {{$studentId}}
                            };
                        }

                    }
                }
            },


            discount_reason: {
                  
                    validators: {
                       
                             callback: {
                            message: 'You must provide a reason for discount',
                            callback: function(value, validator, $field) {
                                var discount = $('#eForm').find('[name="discount"]').val();
                               if(discount>0 && value==0)
                                return false;
                            else return true;
                            }
                        }
                    }
                }

            


        }                
 
    }).on('change', '[name="subscription_type"]', function(e) {
       $('#eForm').formValidation('revalidateField', 'start_date');
    })
    .on('keyup', '[name="discount"]', function(e) {
       $('#eForm').formValidation('revalidateField', 'discount_reason');
    })
    .on('change', '[name="offer"]', function(e) { 
          if($('#eForm').find('[name="offer"]').val()==0)
          { 
              document.getElementById('discount').disabled = false;
              document.getElementById('discount_reason').disabled = false;  
         }
         else{
            document.getElementById('discount').value = "0";
            document.getElementById('discount').disabled = true;
            document.getElementById('discount_reason').disabled = true;
            document.getElementById('discount_reason').selectedIndex = 0;
         }
    })
     
    // This event will be triggered when the field passes given validator
    .on('success.validator.fv', function(e, data) {
        // data.field     --> The field name
        // data.element   --> The field element
        // data.result    --> The result returned by the validator
        // data.validator --> The validator name
        

        if (data.field === 'start_date'
            && data.validator === 'remote'
            && (data.result.available === false || data.result.available === 'false'))
        {

            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-success')
                .addClass('has-warning')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="start_date"]')
                    .show();



        }


        if (data.field === 'start_date'
            && data.validator === 'remote'
            && (data.result.available === true || data.result.available === 'true'))
        {
             
            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-warning')
                .addClass('has-success')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="start_date"]')

                    .show();
        }

        //--------------------------------------------------------------------------------------------------------
        if (data.field === 'subscription_type'
            && data.validator === 'remote'
            && (data.result.available === false || data.result.available === 'false'))
        {

            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-success')
                .addClass('has-warning')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="subscription_type"]')
                    .show();
        }


        if (data.field === 'subscription_type'
            && data.validator === 'remote'
            && (data.result.available === true || data.result.available === 'true'))
        {
             
            // The userName field passes the remote validator
            data.element                    // Get the field element
                .closest('.form-group')     // Get the field parent

                // Add has-warning class
                .removeClass('has-warning')
                .addClass('has-success')

                // Show message
                .find('small[data-fv-validator="remote"][data-fv-for="subscription_type"]')
                    .show();
        }

         


    })
    // This event will be triggered when the field doesn't pass given validator
    .on('err.validator.fv', function(e, data) { 
         
        // We need to remove has-warning class
        // when the field doesn't pass any validator
        if (data.field === 'start_date') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }

        if (data.field === 'subscription_type') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }

        
    });


             
             
            //fn.datepicker.defaults.format = "yyyy-mm-dd";
            // FormPlugins.init();  

            

        });

                            
    </script>
        @endsection