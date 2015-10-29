<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
 
<head>
	<meta charset="utf-8" />
	<title>NMS V3.0</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link rel="SHORTCUT ICON"   href="/img/favicon.ico">
	
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
	
	<!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
     <link href="/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />

	 
    <link href="/plugins/DataTables/css/data-table.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="/plugins/pace/pace.min.js"></script>

	<script src="/plugins/jquery/jquery-1.9.1.min.js"></script>
	<!-- ================== END BASE JS ================== -->

	<!-- ================Formavalidation.io========================= -->
	<link rel="stylesheet" href="/dist/css/formValidation.css"/>

    
   <!-- <script type="text/javascript" src="/dist/bootstrap/js/bootstrap.min.js"></script>-->
    <script type="text/javascript" src="/dist/js/formValidation.js"></script>
    <script type="text/javascript" src="/dist/js/framework/bootstrap.js"></script>
    <!-- =========================================================================== -->
  
<SCRIPT LANGUAGE="Javascript">
		<!---
		function decision(message, url){
		if(confirm(message)) location.href = url;
		}
		// --->
	</SCRIPT>
</head>
<body>
	<!-- begin #page-loader -->
	 
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		 
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
    <script src="/plugins/morris/raphael.min.js"></script>
    <script src="/plugins/morris/morris.js"></script>
    <script src="/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/plugins/jquery-jvectormap/jquery-jvectormap-world-merc-en.js"></script>
    <script src="/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
	<script src="/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="/js/dashboard-v2.min.js"></script>
	<script src="/js/apps.min.js"></script>

	 

	<script src="/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="/js/table-manage-default.demo.min.js"></script>

	<!--<script src="/js/form-plugins.demo.min.js"></script> -->
	<script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	 
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	 
	 
</body>
 </html>
