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

	 function payFinalizer()
		{
  			var subscription_pay = $("#subscription_pay").val(); 
			var event_total = $("#event_total").val(); 
			var store_total = $("#store_total").val();
			var discount = $("input#discount").val();
			  
			 
			 
			if(discount == "")
			discount=0;
			
			if(typeof event_total === "undefined")
			event_total=0;
			
			if(typeof store_total === "undefined")
			store_total=0;
			
			var grand_total = (parseFloat(subscription_pay)+parseFloat(event_total)+parseFloat(store_total))-parseFloat(discount);
			
			var min_payable = (parseFloat(event_total)+parseFloat(store_total));
			
			var min_payable_reduced = (parseFloat(event_total)+parseFloat(store_total))-parseFloat(discount);
			
			  
			
			$('input[name=grand_total]').val(grand_total);
			$('input[name=minimum_reduced]').val(min_payable_reduced);
			$('input[name=minimum]').val(min_payable);
			
			
		
		}//payFinalizer

<!---------------------------------------------------==================--------------------------------------------- -->


	function service_blast()
		{
			
			var current_payment  = 	$("input#current_payment").val();
			var valueChecked = $('#eForm').find('[name="pay_mode"]:checked').val();  
			 
			if(valueChecked==3 && parseFloat(current_payment)<10000)
			{
				 var ser = {{ $serviceChargeFlag }} ;
				 
				 var finalBlaster = parseFloat(current_payment)+parseFloat(current_payment*ser);
                  
				 $("#finalBlast").text("Final amount inclusive service charge : "+finalBlaster+"/- AED");
				  
             }
			 else
			 {
				 $("#finalBlast").text("");
			 }
				
										  
			 
		}	
<!---------------------------------------------------==================--------------------------------------------- -->

	function eventLoader()
		{
			 $.get('/invEventLoader',{tempInv:{{$tempInvoice}},standard:"{{$student->standard}}" }, function(eventsBlade){ 
			        
			        $("#eventsLoader").html(eventsBlade);
			        $('#eForm').formValidation('revalidateField', 'discount');
			        $('#eForm').formValidation('revalidateField', 'current_payment');
			    });
		}		
<!---------------------------------------------------==================--------------------------------------------- -->
	$("#eventAdd").click( function(e) {
		
		e.preventDefault();
		var event_id = $("#event_id").val(); 		
		 
		 if(event_id==0)
		 {
			 
			 return false;
		 }
		 
		 else
		 {
			  

			 $.get('/invEventAdd',{eventId:event_id, tempInv:{{$tempInvoice}} }, function(eventsBlade){ 
			        //console.log(itemBlade); 
			        $("#addedEvents").html(eventsBlade);
			        eventLoader();
			        payFinalizer();
			    });

		 }//else
		
		 
		
		
    });
 
<!---------------------------------------------------==================--------------------------------------------- -->	

$(document.body).on('click', '.delEventButton', function(e){
		e.preventDefault();
		custom = $(this).val();

		$.get('/invEventRemove',{customId:custom, tempInv:{{$tempInvoice}} }, function(eventsBlade){ 
			        //console.log(itemBlade); 
			        $("#addedEvents").html(eventsBlade);
			        eventLoader();
			        payFinalizer();
			    });
	});

<!---------------------------------------------------==================--------------------------------------------- -->

	function itemLoader()
		{
			 
			var category = $("#category").val();

			 $.get('/invItemLoader',{tempInv:{{$tempInvoice}},category:category }, function(itemsBlade){ 
			        
			        $("#itemsLoader").html(itemsBlade);
			        $('input[name=qty]').val('1');
			        $('#eForm').formValidation('revalidateField', 'discount');
			        $('#eForm').formValidation('revalidateField', 'current_payment');
			    });
		}   

<!---------------------------------------------------==================--------------------------------------------- -->
	$("#itemAdd").click( function(e) {
		
		e.preventDefault();
		var item_id = $("#item_id").val(); 	
		var qty = $("#qty").val(); 	
		 
		 if(event_id==0)
		 {
			 
			 return false;
		 }
		 
		 else
		 {
			  

			 $.get('/invItemAdd',{item_id:item_id,qty:qty, tempInv:{{$tempInvoice}} }, function(itemsBlade){ 
			        //console.log(itemBlade); 
			        $("#addedItems").html(itemsBlade);
			        itemLoader();
			        payFinalizer();
			    });

		 }//else
		
		 
		
		
    });		

<!---------------------------------------------------==================--------------------------------------------- -->	

$(document.body).on('click', '.delItemButton', function(e){
		e.preventDefault();
		custom = $(this).val();

		$.get('/invItemRemove',{customId:custom, tempInv:{{$tempInvoice}} }, function(eventsBlade){ 
			        //console.log(itemBlade); 
			        $("#addedItems").html(eventsBlade);
			        itemLoader();
			        payFinalizer();
			    });
	});

 <!---------------------------------------------------==================--------------------------------------------- -->	
      $('#cheque_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
      }).on('changeDate', function(e) { 
      $('#eForm').formValidation('revalidateField', 'pay_mode');  
      });
		                                
			//$('#eForm').formValidation();

			$('#eForm').formValidation({

		        message: 'This value is not valid',
		        fields:{
                    
 
                    


            pay_mode: {
                  
                    validators: {
                       
                             callback: {
                            message: 'You must provide cheque details',
                            callback: function(value, validator, $field) {
                                var cheque_no = $('#eForm').find('[name="cheque_no"]').val();
                                var cheque_date = $('#eForm').find('[name="cheque_date"]').val();
                                var cheque_bank = $('#eForm').find('[name="cheque_bank"]').val();
                                var valueChecked = $('#eForm').find('[name="pay_mode"]:checked').val(); 

                               if(valueChecked==2 && (cheque_no =="" || cheque_bank=="" || cheque_date==""))
                                return false;

                            else return true;
                            }
                        }
                    }
                },

                discount: {
                  
                    validators: {
                       
                             callback: {
                            message: 'Given discount is not permissible',
                            callback: function(value, validator, $field) {
                            	var minimum_reduced = $('#eForm').find('[name="minimum_reduced"]').val();
                                if(parseFloat(minimum_reduced)<0) 
                                 return false;
								

                            else return true;
                            }
                        }
                    }
                },

                current_payment: {
                  
                    validators: {
                       notEmpty:{},
                             callback: { 
	                           
	                            callback: function(value, validator, $field) {
	                            	var minimum_reduced = $('#eForm').find('[name="minimum_reduced"]').val();
	                            	var grand_total  = 	$("input#grand_total").val();
	                            	var minimum = 	$("input#minimum").val();
	                                if(parseFloat(value)<parseFloat(minimum_reduced)) 
	                                {
	                                	return {
	                                    valid: false,
	                                    message: 'Minimum payable amount is '+minimum_reduced
	                                	}
									}
									else if(parseFloat(value)>parseFloat(grand_total))
									 {
										 return {
		                                    valid: false,
		                                    message: 'Maximum payable amount is '+grand_total
	                                		}
									 }
									 else if(parseFloat(minimum)==0 && parseFloat(value)==0)
									 {
										 return {
		                                    valid: false,
		                                    message: 'Invalid invoice'
	                                		}
									 }

                            		else return true;
                            }
                        }
                    }
                }

                
                           



            


        }                
 
    })
	.on('keyup', '[name="discount"]', function(e) {
	       payFinalizer();
	       $('#eForm').formValidation('revalidateField', 'discount');
	       $('#eForm').formValidation('revalidateField', 'current_payment');
	    })
	.on('change', '[name="discount"]', function(e) {
	       payFinalizer();
	       $('#eForm').formValidation('revalidateField', 'discount');
	       $('#eForm').formValidation('revalidateField', 'current_payment');
	    })

	.on('keyup', '[name="current_payment"]', function(e) {
	       service_blast();
	       $('#eForm').formValidation('revalidateField', 'current_payment');	       
	    })
	.on('change', '[name="current_payment"]', function(e) {
	       service_blast();
	       $('#eForm').formValidation('revalidateField', 'current_payment');
	    })

	.on('change', '[name="category"]', function(e) {
	       itemLoader();
	    })
	.on('keyup', '[name="cheque_no"]', function(e) {
	       $('#eForm').formValidation('revalidateField', 'pay_mode');
	    })
	.on('keyup', '[name="cheque_date"]', function(e) {
	       $('#eForm').formValidation('revalidateField', 'pay_mode');
	    })
	.on('keyup', '[name="cheque_bank"]', function(e) {
	       $('#eForm').formValidation('revalidateField', 'pay_mode');
	    })

    .on('click', '[name="pay_mode"]', function(e) { 
		 
           
          if($('#eForm').find('[name="pay_mode"]:checked').val()==1)
          { 
              document.getElementById('cheque_no').value = "";
              document.getElementById('cheque_no').disabled = true;

              document.getElementById('cheque_date').value = "";
              document.getElementById('cheque_date').disabled = true; 

              document.getElementById('cheque_bank').value = "";
              document.getElementById('cheque_bank').disabled = true;  

              document.getElementById('card_notes').value = "";
              document.getElementById('card_notes').disabled = true;
              
              $("#finalBlast").text("");
         }
         else  if($('#eForm').find('[name="pay_mode"]:checked').val()==2){
             
              document.getElementById('cheque_no').disabled = false;               
              document.getElementById('cheque_date').disabled = false; 
              document.getElementById('cheque_bank').disabled = false;  

              document.getElementById('card_notes').value = "";
              document.getElementById('card_notes').disabled = true;

              $("#finalBlast").text("");
         }
         else  if($('#eForm').find('[name="pay_mode"]:checked').val()==3){
             
              document.getElementById('cheque_no').value = "";
              document.getElementById('cheque_no').disabled = true;

              document.getElementById('cheque_date').value = "";
              document.getElementById('cheque_date').disabled = true; 

              document.getElementById('cheque_bank').value = "";
              document.getElementById('cheque_bank').disabled = true;   

               
              document.getElementById('card_notes').disabled = false;

              service_blast();
         }
    })
     

			 
			 
			//fn.datepicker.defaults.format = "yyyy-mm-dd";
			 FormPlugins.init();	


	

		    

		});

							
	</script>
	 
	 
</body>
 </html>
