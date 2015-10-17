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
			 FormPlugins.init();	

		    

		});

							
	</script>
	 
	 
</body>
 </html>
