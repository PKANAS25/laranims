@extends('formsMaster') 

@section('urlTitles')
<?php session(['title' => 'Employees']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
 
<div id="content" class="content">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="javascript:;">Employee</a></li>
                <li class="active"><a href="javascript:;">Add</a></li>
                 
            </ol> 
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Add <small> Salary</small></h1>
            <!-- end page-header -->
            <!-- begin row -->
             <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">{{$employee->fullname}}</h4>
                        </div>
                        <div class="panel-body">

                           <a href="{{ action('EmployeesController@profile',base64_encode($employee->employee_id)) }}"><i class="fa fa-arrow-left"></i> Back to Employee Profile</a>  
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"  data-fv-message="Required Field"  data-fv-icon-invalid="glyphicon glyphicon-remove"  data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset> 
                                     
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Labour Card Under :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control"  name="labour_card_under" data-fv-notempty="true">
                                                <option value="{{$salary->labour_card_under}}">{{$salary->wps}}</option>
                                                <option value="0">No WPS</option>
                                                @foreach($branches as $branch)
                                                <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div> 
                                
                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="basic">Basic :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="basic" name="basic" data-fv-notempty="true" value="{{$salary->basic}}" onKeyUp="totalRes()" />
                                    </div>
                                </div>

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="accomodation">Accomodation :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="accomodation" name="accomodation" data-fv-notempty="true" value="{{$salary->accomodation}}" onKeyUp="totalRes()" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="travel">Transportation :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="travel" name="travel" data-fv-notempty="true" value="{{$salary->travel}}" onKeyUp="totalRes()" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="other">Other :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="other" name="other" data-fv-notempty="true" value="{{$salary->other}}" onKeyUp="totalRes()"  />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="totalT">Total :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="number" id="totalT" name="totalT" data-fv-notempty="true" value="{{$salary->totalT}}" onKeyUp="partSalary()" onBlur="partSalary()" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="iban">IBAN # :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="iban" name="iban" data-fv-notempty="true" value="{{$salary->iban}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="edit_reason">Edit Reason:</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="edit_reason" name="edit_reason" data-fv-notempty="true" /> (Last Reason: {{$salary->edit_reason}})
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
     var colCount = 4;
     function totalRes()
            {
                var field = new Array(colCount); 

                field[0] = "basic";
                field[1] = "accomodation";
                field[2] = "travel";
                field[3] = "other";
                
             
                 var totalJ=0;
                 var temp=0;
                 for(i=0;i<colCount;i++)
                 {
                     temp=window.document.getElementById(field[i]).value;
                     if(temp=="")
                       temp="0";
                  var totalJ = parseInt(totalJ)+parseInt(temp);
                 }
                 $('#eForm').formValidation('revalidateField', 'totalT');
                 document.eForm.totalT.value=totalJ;
            }
//================================================================================
        function partSalary()
                {
                    
                    var totalSalary=document.eForm.totalT.value;
                    
                     if(totalSalary!="" || totalSalary!="0")
                    {
                      var basicJ = parseInt(totalSalary)*(50/100);
                      document.eForm.basic.value=basicJ;
                      
                      var accomodationJ = parseInt(totalSalary)*(30/100);
                      document.eForm.accomodation.value=accomodationJ;
                      
                      var transportationJ = parseInt(totalSalary)*(20/100);
                      document.eForm.travel.value=transportationJ;
                     
                     $('#eForm').formValidation('revalidateField', 'basic');
                     $('#eForm').formValidation('revalidateField', 'accomodation');
                     $('#eForm').formValidation('revalidateField', 'travel');
                     $('#eForm').formValidation('revalidateField', 'other');
                    }
                }
//================================================================================

        $(document).ready(function() {
            App.init();  
 

            $('#dob').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('changeDate', function(e) { 
            $('#eForm').formValidation('revalidateField', 'dob');
            });

            
            $('#joining_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'joining_date');
                });

             $('#passport_expiry').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'passport_expiry');
                });
             
             $('#labour_card_expiry').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'labour_card_expiry');
                });
            
             $('#visa_issue').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'visa_issue');
                });  

              $('#visa_expiry').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
                }).on('changeDate', function(e) { 
                    $('#eForm').formValidation('revalidateField', 'visa_expiry');
                });         
            
            $('#eForm').formValidation();

  });

                            
    </script>
        @endsection