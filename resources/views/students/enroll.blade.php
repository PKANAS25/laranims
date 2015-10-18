@extends('students.enrollMaster') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => 'enroll']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Enroll</a></li>
				 
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

                             
                            <form name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status')." - Click" }} <a href="{!! action('StudentsController@profile', base64_encode(session('student'))) !!}">here</a> to view student details 
                                    </div>
                                @endif

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <fieldset>
                                    
                                     

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fullname">Full Name in English :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="fullname" name="fullname"   data-fv-notempty="true"   data-fv-remote="true"  value="{{ old('fullname') }}" />
                                        <div id="live" class=></div>  
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="full_name_arabic">Full Name in Arabic :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="full_name_arabic" name="full_name_arabic"  data-fv-notempty="true"   data-fv-remote="true" value="{{ old('full_name_arabic') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Grade :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="current_grade" data-fv-notempty="true">
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
                                                <input type="radio" name="gender" value="m" id="radio-required" data-fv-notempty="true" /> Male
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
                                        <input class="form-control" type="text" id="dob"   name="dob" data-fv-notempty="true"   value="{{ old('dob') }}" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="joining_date">Joining date :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="joining_date"   name="joining_date"  data-fv-notempty="true"  value="{{ old('joining_date') }}" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">Nationality :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" id="select-required" name="nationality" data-fv-notempty="true">
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
                                        <textarea class="form-control" id="address" name="address" rows="3" data-fv-notempty="true"   >{{ old('address') }}</textarea>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="map">Address in Google Maps</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="map" name="map"   value="{{ old('map') }}" />
                                        <span class="text-info">Open google maps. Right click on exact location. Choose What's here?. In the search box, you'll get Longitude & Latitude seperated by a comma. Copy & paste it here.</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="fileToUpload">Profile pic</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="file"  accept="image/*"    data-fv-file="true"  data-fv-file-extension="jpeg,jpg"  data-fv-file-type="image/jpeg,image/jpg"  data-fv-file-maxsize="629760" data-fv-file-message="The selected file is not valid" id="fileToUpload" name="fileToUpload" /> <span class="text-info">Max size 500 Kb, JPG only</span>
                                    </div>
                                </div>

                                  <h4>Guardian Info</h4> 
                                <hr>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="father_name">Father's Name in English :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="father_name" name="father_name"  data-fv-notempty="true" value="{{ old('father_name') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="father_tel">Father's Tel :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="father_tel" name="father_tel"   data-fv-notempty="true" value="{{ old('father_tel') }}" />
                                    </div>
                                </div>

                               <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="father_mob">Father's Mobile :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="father_mob" name="father_mob"   data-fv-notempty="true" value="{{ old('father_mob') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="father_email">Father's Email :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="father_email" name="father_email"   data-fv-notempty="true"  data-fv-emailaddress="true"
                data-fv-emailaddress-message="The value is not a valid email address" value="{{ old('father_email') }}" />
                                    </div>
                                </div>

                                

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="father_job">Father's Job :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="father_job" name="father_job"   data-fv-notempty="true" value="{{ old('father_job') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="father_workplace">Father's Workplace :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="father_workplace" name="father_workplace"   data-fv-notempty="true" value="{{ old('father_workplace') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="mother_name">Mother's Name in English :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="mother_name" name="mother_name"   data-fv-notempty="true" value="{{ old('mother_name') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="mother_tel">Mother's Tel :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="mother_tel" name="mother_tel"   data-fv-notempty="true" value="{{ old('mother_tel') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="mother_mob">Mother's Mob :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="mother_mob" name="mother_mob"   data-fv-notempty="true" value="{{ old('mother_mob') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="mother_email">Mother's Email :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="mother_email" name="mother_email"   data-fv-notempty="true"  data-fv-emailaddress="true"
                data-fv-emailaddress-message="The value is not a valid email address"  value="{{ old('mother_email') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="mother_job">Mother's Job :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="mother_job" name="mother_job"   data-fv-notempty="true" value="{{ old('mother_job') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="mother_workplace">Mother's Work Place :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="mother_workplace" name="mother_workplace"   data-fv-notempty="true" value="{{ old('mother_workplace') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="emergency_phone">Emergency Phone :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" id="emergency_phone" name="emergency_phone"   data-fv-notempty="true" value="{{ old('emergency_phone') }}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="ABCD">Authorized & non authorized persons to take the child :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea class="form-control" id="authorities" name="authorities" rows="3"   >{{ old('authorities') }}</textarea>
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