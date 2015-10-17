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

	<script src="/js/form-plugins.demo.min.js"></script>
	<script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	 
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
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
                                             
			//$('#eForm').formValidation();

			$('#eForm').formValidation({
		        message: 'This value is not valid',
		        

		        fields: {
		        	 father_email: {
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: 'The email address is required and can\'t be empty'
                        },
                        emailAddress: {
                            message: 'The input is not a valid email address'
                        },
                        stringLength: {
                            max: 512,
                            message: 'Cannot exceed 512 characters'
                        },
                        remote: {
                            type: 'GET',
                            url: 'https://api.mailgun.net/v2/address/validate?callback=?',
                            crossDomain: true,
                            name: 'address',
                            data: {
                                api_key: 'pubkey-83a6-sl6j2m3daneyobi87b3-ksx3q29'
                            },
                            dataType: 'jsonp',
                            validKey: 'is_valid',
                            message: 'The email is not valid'
                        }
                    }
                },
                
                mother_email: {
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: 'The email address is required and can\'t be empty'
                        },
                        emailAddress: {
                            message: 'The input is not a valid email address'
                        },
                        stringLength: {
                            max: 512,
                            message: 'Cannot exceed 512 characters'
                        },
                        remote: {
                            type: 'GET',
                            url: 'https://api.mailgun.net/v2/address/validate?callback=?',
                            crossDomain: true,
                            name: 'address',
                            data: {
                                api_key: 'pubkey-83a6-sl6j2m3daneyobi87b3-ksx3q29'
                            },
                            dataType: 'jsonp',
                            validKey: 'is_valid',
                            message: 'The email is not valid'
                        }
                    }
                },
                
		            father_tel: {
		                validators: {
		                    notEmpty: {},
		                    digits: {},
		                    phone: {
		                        country: 'AE'
		                    }
		                }
		            },
		            father_mob: {
		                validators: {
		                    notEmpty: {},
		                    digits: {},
		                    phone: {
		                        country: 'AE'
		                    }
		                }
		            },
		            mother_tel: {
		                validators: {
		                    notEmpty: {},
		                    digits: {},
		                    phone: {
		                        country: 'AE'
		                    }
		                }
		            },
		            mother_mob: {
		                validators: {
		                    notEmpty: {},
		                    digits: {},
		                    phone: {
		                        country: 'AE'
		                    }
		                }
		            },

		            emergency_phone: {
		                validators: {
		                    notEmpty: {},
		                    digits: {},
		                    phone: {
		                        country: 'AE'
		                    }
		                }
		            } ,
		        
		        fullname: {
		        	 threshold: 5,
		        	 verbose: false,
                	 
                	 validators: {
                	 notEmpty: {},
                     remote: {
                        url: '/enrollCheck'
                    }
                }
            },
            	full_name_arabic: {
		        	 threshold: 5,
		        	 verbose: false,
                	 
                	 validators: {
                	 notEmpty: {},
                     remote: {
                        url: '/enrollCheck'
                    }
                }
            }
        }
    })
    // This event will be triggered when the field passes given validator
    .on('success.validator.fv', function(e, data) {
        // data.field     --> The field name
        // data.element   --> The field element
        // data.result    --> The result returned by the validator
        // data.validator --> The validator name

        if (data.field === 'fullname'
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
                .find('small[data-fv-validator="remote"][data-fv-for="fullname"]')
                    .show();
        }


        if (data.field === 'fullname'
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
                .find('small[data-fv-validator="remote"][data-fv-for="fullname"]')
                    .show();
        }

        //------------------------------------------------------------------------------------

        if (data.field === 'full_name_arabic'
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
                .find('small[data-fv-validator="remote"][data-fv-for="full_name_arabic"]')
                    .show();
        }


        if (data.field === 'full_name_arabic'
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
                .find('small[data-fv-validator="remote"][data-fv-for="full_name_arabic"]')
                    .show();
        }


    })
    // This event will be triggered when the field doesn't pass given validator
    .on('err.validator.fv', function(e, data) { 
    	 
        // We need to remove has-warning class
        // when the field doesn't pass any validator
        if (data.field === 'fullname') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }

        if (data.field === 'full_name_arabic') {
            data.element
                .closest('.form-group')
                .removeClass('has-warning')
                  

        }
    });


			 
			 
			//fn.datepicker.defaults.format = "yyyy-mm-dd";
			 FormPlugins.init();	

		    

		});

							
	</script>
	 
	 
</body>
 </html>
