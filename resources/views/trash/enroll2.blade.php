@extends('master') 
@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Enrole</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Enroll <small> new student</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                
                                
                            </div>
                            <h4 class="panel-title">Enroll</h4>
                        </div>
                        <div class="panel-body">
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered" data-parsley-validate="true">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                     

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fullname">Full Name in English :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="fullname" name="fullname"  data-parsley-required="true" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="full_name_arabic">Full Name in Arabic :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="full_name_arabic" name="full_name_arabic"  data-parsley-required="true" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Grade :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="current_grade" data-parsley-required="true">
                                            <option value="">Please choose</option>
                                            @foreach($grades as $grade)
                                            <option value="{!! $grade->class_id !!}">{!! $grade->standard." - ".$grade->division !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Gender :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="gender" value="m" id="radio-required" data-parsley-required="true" /> Male
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="gender" id="radio-required2" value="f" /> Female
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dob">Date of birth :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="datepicker-autoClose" name="dob"  data-parsley-required="true"  placeholder="YYYY-MM-DD" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="joining_date">Joining date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="datepicker-autoClose" name="joining_date"  data-parsley-required="true"  placeholder="YYYY-MM-DD" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Nationality :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="nationality" data-parsley-required="true">
                                            <option value="">Please choose</option>
                                            @foreach($nations as $nation)
                                            <option value="{!! $nation->nation_id !!}">{!! $nation->nationality !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="address">Address :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea class="form-control" id="address" name="address" rows="3" data-parsley-required="true" data-parsley-error-message="Min 10 characters. Max 200" data-parsley-range="[10,200]" ></textarea>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="map">Address in Google Maps</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="map" name="map"   placeholder="" />
                                        <span class="text-info">Open google maps. Right click on exact location. Choose What's here?. In the search box, you'll get Longitude & Latitude seperated by a comma. Copy & paste it here.</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fullname">Profile pic</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="file" id="fileToUpload" name="fileToUpload"    />
                                    </div>
                                </div>

                                


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="sdsd">sdsdsdsdsdsd</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="sdsd" name="sdsd" data-parsley-custom="true"   />
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

                            <script type="text/javascript">
                            $( '#eForm' ).parsley( {
                                validators: {
                                  sdsd: function ( val ) {
                                   if(val!='abc');
                                   
                                  }
                                }
                              , messages: {
                                  sdsd: "Please enter a valid value"
                                }
                            } );
                                </script>
                        </div> 
                    <!-- end panel --> 
                </div>
			<!-- end row -->
		</div>
        @endsection