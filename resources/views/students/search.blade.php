@extends('master') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => 'search']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Search</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Search <small> students</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Search</h4>
                        </div>
                        <div class="panel-body">

                             
                            <form name="eForm" id="eForm"  method="GET" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">

                                

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                     

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="keyword">Name / Id / Guardians Name / Tel / Mob</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="keyword" name="keyword"     />
                                        
                                    </div>
                                </div>



                                 <div id="searchResults"> </div>

                                    <script type="text/javascript">

 
                                    $('#keyword').keyup(function(e){  
                             
                                        e.preventDefault();
                                        var value =($(this).val());

                                        var data = {thisConfirm : $(this).val()};
                                        clearTimeout($(this).data('timer'));

                                         $(this).data('timer', setTimeout(function() {
                                            $.get('/searchBind',{keyword:value }, function(searchBlade){                      
                                            $("#searchResults").html(searchBlade);
                                            });
                                        },400));
                        
                                     }); 
                                   </script> 

                                </fieldset>
                            </form>

                              
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
    </div>
        @endsection