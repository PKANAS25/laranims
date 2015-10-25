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
                            <form class="form-horizontal form-bordered" name="eForm" id="eForm"  method="POST" autocomplete="OFF" class="form-horizontal form-bordered"  enctype="multipart/form-data"  data-fv-framework="bootstrap"
    data-fv-message="Required Field"
   
    data-fv-icon-invalid="glyphicon glyphicon-remove"
    data-fv-icon-validating="glyphicon glyphicon-refresh">
      @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach

                                

                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="tempInvoice" value="{{$tempInvoice}}">
                                <input type="hidden" name="serviceChargeFlag" value="{{$serviceChargeFlag}}">

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="dated">Date * :</label>
                                    <div class="col-md-3 col-sm-3">
                                        <input    class="input-medium " type="text" id="dated"  name="dated" value="{{ $today }}" readonly data-fv-notempty="true"   />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="email">Medium * :</label>
                                    <div class="col-md-6 col-sm-6">
                                           <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="pay_mode" value="1" id="radio-required" data-fv-notempty="true" /> Cash
                                                </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="pay_mode" id="radio-required" value="2" /> Cheque
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="pay_mode" id="radio-required" value="3" /> Card
                                            </label>
                                        </div>
                                         
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="website">Card/Cheque Details</label>
                                    <div class="col-md-6 col-sm-6">
                                        <table><tr>
                                        <td style="padding:5px"><input class="form-control" type="text" name="cheque_no" id="cheque_no" placeholder="Cheque#" disabled /></td>
                                        <td style="padding:5px"><input class="form-control" type="text" name="cheque_date" id="cheque_date" placeholder="Cheque Date" disabled /></td>
                                        <td style="padding:5px"><input class="form-control" type="text" name="cheque_bank" id="cheque_bank" placeholder="Cheque Bank" disabled /></td>
                                        <td style="padding:5px"><input class="form-control" type="text" name="card_notes" id="card_notes" placeholder="Card Notes" disabled /></td>
                                        </tr> </table>
                                    </div>
                                </div>
                                
                                
                                 
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="message">Add Event +</label>
                                    <div class="col-md-6 col-sm-6">
                                        <div id="eventsLoader">
                                            <select class="col-md-3 col-sm-3" name="event_id" id="event_id" >
                                              <option value="">Select</option>
                                              @foreach($events as $event)
                                              <option value="{{$event->event_id}}">{{$event->title}}</option>
                                              @endforeach
                                              </select>
                                        </div>
                                          &nbsp;&nbsp;&nbsp;
                                          <input type="button" name="eventAdd" id="eventAdd" value="Add" />
                                    </div>
                                     
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="website">Add Item +</label>
                                    <div class="col-md-6 col-sm-6">
                                        <table><tr>
                                        <td style="padding:5px"><select class="form-control"   name="category" id="category" >
                                          <option value="0">All Categories</option>
                                           @foreach($categories as $category)
                                          <option value="{{$category->category_id}}">{{$category->category}}</option>
                                          @endforeach
                                          </select></td>
                                        <td style="padding:5px">
                                            <div id="itemsLoader">
                                                <select class="form-control"   name="item_id" id="item_id" >
                                                  <option value="0">Select</option>
                                                   @foreach($items as $item)
                                                  <option value="{{$item->item_id}}">{{$item->item_name}}</option>
                                                  @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td style="padding:5px"><input class="form-control" type="number"  min="1" value="1" name="qty" id="qty" /></td>
                                        <td style="padding:5px"> <input type="button" name="itemAdd" id="itemAdd" value="Add" /></td>
                                        </tr> </table>
                                    </div>
                                </div>
                                
                                 
                                  
<!-------------------------------------------------------------------------------------------------------------------------------------------------- -->                              
                         <div id="addedEvents">
                        
                        </div>   
 <!------------------------------------------------------------------------------------------------------------------------------------------------  -->                              
                         <div id="addedItems">
                        
                        </div>   


<!------------------------------------------------------------------------------------------------------------------------------------------------- -->                     
                      <div class="form-group">
                                    
                               
                                 <div id="live8">
                                 <table class="table"> 
                                 <tr>                                        
                                    <td><strong>Subscriptions Balance</strong></td> <td><input type="text" name="subscription_pay" id="subscription_pay" readonly value="{{$totalPayable-$totalPaid}}"></td> 
                                    <td><strong>Actual Min Payable</strong></td> <td> <input type="text" name="minimum" id="minimum" readonly value="0"></td>
                                    <td><strong>Discount</strong> </td> <td><div class="form-group"> <input type="number"  min="0" value="0"  name="discount" id="discount" data-fv-digits="true"  ></div></td>
                                    <td><strong>Min. Pay Reduced</strong> </td> <td><input type="text" name="minimum_reduced" id="minimum_reduced" readonly value="0"></td></tr>
                                    <tr>
                                    <td><strong>Grand Total</strong> </td> <td><input type="text" name="grand_total" id="grand_total" readonly value="{{$totalPayable-$totalPaid}}"></td>
                                    <td><strong>Current Payment</strong></td> <td><div class="form-group"> <input type="number"  min="0" value="0"  name="current_payment" id="current_payment" data-fv-digits="true"  ></div></td>
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