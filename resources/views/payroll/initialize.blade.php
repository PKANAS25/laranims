@extends('formsMaster') 
 
<?php session(['title' => 'Payroll']);
session(['subtitle' => 'generate']); ?> 

@section('content') 
  
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right hidden-print">
				<li><a href="javascript:;">Payroll</a></li>
				<li class="active"><a href="javascript:;">Generate</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header hidden-print">Generate <small> Payroll</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading hidden-print">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Initialize</h4>
                        </div>
                        
                        <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" >
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
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}   
                                        </div>
                                    @endif
                                </div>     
                            
                        </div> 
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="payroll_month">Month :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="payroll_month"   name="payroll_month" data-fv-notempty="true"   value="{{ old('payroll_month') }}"   />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="payroll_month">Branch :</label>
                                    <div class="col-md-6 col-sm-6" id="branchDiv">
                                          <select class="form-control"  name="company" id="branchSel"  data-fv-notempty="true" >
                                            <option value="">Select</option>
                                         </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="start_date">Start Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="start_date"   name="start_date" data-fv-notempty="true"   value="{{ old('start_date') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="end_date">End Date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="end_date"   name="end_date" data-fv-notempty="true"   value="{{ old('end_date') }}" />
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6"> 
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            
            $('#payroll_month').datepicker({
                format: "yyyy-mm",
                autoclose: true
            }).on('changeDate', function(e) {  
             
 
             Form=document.eForm;
             var pMonth=window.document.getElementById("payroll_month").value;  
 
                  $.get('/payrollBranches',{pMonth:pMonth }, function(actionBlade){                      
                    $("#branchSel").html(actionBlade);
                                                             
                    });
              
             
            var m = (pMonth.substr(pMonth.length-2))
            var y = (pMonth.substr(0,4)) 
            var start = y+"-"+(m)+"-01";
            var end = y+"-"+(m)+"-"+daysInMonth(m,y); 
             
             Form.start_date.value=start;
             Form.end_date.value=end;   
             Form.company.value='';   

            $('#eForm').formValidation('revalidateField', 'payroll_month');
            $('#eForm').formValidation('revalidateField', 'end_date');
            $('#eForm').formValidation('revalidateField', 'start_date'); 
            $('#eForm').formValidation('revalidateField', 'company'); 


            });

             $('#start_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'start_date');
            });

             $('#end_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'end_date');
            });

            
            
                                             
        $('#eForm').formValidation(); 
}); 


          function daysInMonth(month, year) {
            return new Date(year, month, 0).getDate();
        }
        
          
    </script>
        @endsection