<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Mirrored from seantheme.com/color-admin-v1.7/admin/html/form_validation.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 24 Apr 2015 10:57:29 GMT -->
<head>
	<meta charset="utf-8" />
	<title>Color Admin | Form Validation</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="/css/animate.min.css" rel="stylesheet" />
	<link href="/css/style.min.css" rel="stylesheet" />
	<link href="/css/style-responsive.min.css" rel="stylesheet" />
	<link href="/css/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
 
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="/plugins/pace/pace.min.js"></script>
    <script src="/plugins/jquery/jquery-1.9.1.min.js"></script>
	<!-- ================== END BASE JS ================== -->
    
     <link rel="stylesheet" href="/dist/css/formValidation.css"/>

  
    <script type="text/javascript" src="/dist/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/dist/js/formValidation.js"></script>
    <script type="text/javascript" src="/dist/js/framework/bootstrap.js"></script>
    <!-- =========================================================================== -->
</head>
<body>
	<!-- begin #page-loader -->
	 
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		 @include('shared.header')
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		 
		@include('shared.navbar')
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		 @yield('content')
		<!-- end #content -->
		
        <!-- begin theme-panel -->
         
        <!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	
	<script src="/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="/crossbrowserjs/html5shiv.js"></script>
		<script src="/crossbrowserjs/respond.min.js"></script>
		<script src="/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="/plugins/parsley/dist/parsley.js"></script>
	<script src="/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	 

	
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
		$(document).ready(function() {
			App.init();

			  $(document).ready(function() {

                                           

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
                                             
                                            $('#eForm').formValidation();
                                             
                                     
                                    });

		    

		});


	</script>
	 
	 
</body>
 </html>
