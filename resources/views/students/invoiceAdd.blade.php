@extends('students.invMaster') 

@section('urlTitles')
<?php session(['title' => 'Students']);
session(['subtitle' => '']); ?>
@endsection


@section('content')
<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Students</a></li>
				<li class="active"><a href="javascript:;">Invoice</a></li>
				 
			</ol> 
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Add <small> new reciept</small></h1>
			<!-- end page-header -->
			<!-- begin row -->
			 <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                 
                            </div>
                            <h4 class="panel-title">{{$student->full_name}}</h4>
                        </div>
                        <div class="panel-body panel-form">
                            <form class="form-horizontal form-bordered" data-parsley-validate="true" name="demo-form">
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Date * :</label>
                                    <div class="col-md-3 col-sm-3">
                                        <input    class="input-medium " type="text" id="dated"  name="dated" value="{{ $today }}" readonly   />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="email">Medium * :</label>
                                    <div class="col-md-6 col-sm-6">
                                           <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="trans" value="1" id="radio-required" data-fv-notempty="true" /> Cash
                                                </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="trans" id="radio-required2" value="2" /> Cheque
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="trans" id="radio-required2" value="3" /> Card
                                            </label>
                                        </div>
                                         
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="website">Card/Cheque Details</label>
                                    <div class="col-md-6 col-sm-6">
                                        <table><tr>
                                        <td style="padding:5px"><input class="form-control" type="text" placeholder="Cheque#" disabled /></td>
                                        <td style="padding:5px"><input class="form-control" type="text" placeholder="Cheque Date" disabled /></td>
                                        <td style="padding:5px"><input class="form-control" type="text" placeholder="Cheque Bank" disabled /></td>
                                        <td style="padding:5px"><input class="form-control" type="text" placeholder="Card Notes" disabled /></td>
                                        </tr> </table>
                                    </div>
                                </div>
                                
                                
                                 
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="message">Add Event +</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="col-md-3 col-sm-3"   name="event_id" id="event_id" >
                                          <option value="">Select</option>
                                          </select>
                                          &nbsp;&nbsp;&nbsp;
                                          <input type="button" value="Add" />
                                    </div>
                                     
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="website">Add Item +</label>
                                    <div class="col-md-6 col-sm-6">
                                        <table><tr>
                                        <td style="padding:5px"><select class="form-control"   name="category" id="category" >
                                          <option value="-1">All Categories</option>
                                          </select></td>
                                        <td style="padding:5px"><select class="form-control"   name="item_id" id="item_id" >
                                          <option value="0">Select</option>
                                          </select></td>
                                        <td style="padding:5px"><input class="form-control" type="number"  min="1" value="1" name="qty" id="qty" /></td>
                                        <td style="padding:5px"> <input type="button" value="Add" /></td>
                                        </tr> </table>
                                    </div>
                                </div>
                                
                                 
                                  
<!-------------------------------------------------------------------------------------------------------------------------------------------------- -->                              
                         <div id="live6">
                        
                        </div>   
 <!------------------------------------------------------------------------------------------------------------------------------------------------  -->                              
                         <div id="live7">
                        
                        </div>   


<!------------------------------------------------------------------------------------------------------------------------------------------------- -->                     
                      <div class="form-group">
                                    
                               
                                 <div id="live8">
                                 <table class="table"> 
                                 <tr>                                        
                                    <td><strong>Subscriptions Balance</strong></td> <td><input type="text" name="subscription_pay" id="subscription_pay" readonly value="{{$totalPayable-$totalPaid}}"></td> 
                                    <td><strong>Actual Min Payable</strong></td> <td> <input type="text" name="minimum" id="minimum" readonly value="0"></td>
                                    <td><strong>Discount</strong> </td> <td><input type="text" name="discount" id="discount"  value="0" ></td>
                                    <td><strong>Min. Pay Reduced</strong> </td> <td><input type="text" name="minimum_reduced" id="minimum_reduced" readonly value="0"></td></tr>
                                    <tr>
                                    <td><strong>Grand Total</strong> </td> <td><input type="text" name="grand_total" id="grand_total" readonly value="{{$totalPayable-$totalPaid}}"></td>
                                    <td><strong>Current Payment</strong></td> <td> <input type="number"  min="0" value="0"  name="current_payment" id="current_payment" onKeyUp="service_blast()" ></td>
                                    <td ><strong>Notes</strong> </td> <td colspan="3"> <textarea name="notes" cols="50" id="notes" ></textarea></td>
                                  </tr>
                                  <tr><td colspan="8"><strong><span id="finalBlast"></span></strong></td></tr>
                                      </table> 
                                </div> 
                        
                        </div>
                           
                                
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4"></label>
                                    <div class="col-md-6 col-sm-6">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
			<!-- end row -->
		</div>
    </div>
        @endsection