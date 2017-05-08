<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Carbon\Carbon;
use Auth;

use App\Invoice;
use App\InvoicesCustomItem;
use App\BranchItem;
use App\Branch;
use Session;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($studentId)
    {
        if(!$studentId)
            return redirect()->intended('home');

        $studentId = base64_decode($studentId);
        
        $today = Carbon::now()->toDateString();

        DB::statement('DELETE temp_invoices,temp_invoices_custom_items FROM temp_invoices INNER JOIN temp_invoices_custom_items ON 
            temp_invoices.temp_id=temp_invoices_custom_items.invoice_id WHERE staff_id='.Auth::id()) ;
            
        DB::table('temp_invoices')
            ->where('staff_id',Auth::id())
            ->delete();

       $branch = DB::table('branches')
            ->where('id',Auth::user()->branch)
            ->first();

       if($branch->card_service_charge) 
            $serviceChargeFlag = $branch->card_service_charge;
       else
         $serviceChargeFlag =0;

    
    $student = DB::table('students') 
            
            ->leftjoin('classrooms','students.current_grade','=','classrooms.class_id')          
            ->select('students.*','classrooms.standard')              
            ->where('students.student_id',$studentId ) 
            ->first();
            

     $payable1 = DB::table('subscriptions')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->where('refunded',0 )
            ->sum('amount');

         $payable2 = DB::table('subscriptions')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->where('refunded',1 )
            ->sum('non_refundable_amount');   

        $payable3 = DB::table('subscriptions_hour')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->sum('amount');

        $totalPaid  = DB::table('invoices')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->whereRaw("NOT(cheque='1' AND cheque_bounce='1')")
            ->sum('subscriptions_amount');  

            $totalPayable = $payable1+$payable2+$payable3; 
            
        $events = DB::table('events')
                    ->where('end_date','>=',$today) 
                    ->where('standard',$student->standard)
                    ->where('branch',Auth::user()->branch)
                    ->orderBy('start_date')
                    ->get();

        $items = DB::table('branch_items')             
            ->leftjoin('items','branch_items.item_id','=','items.item_id')          
            ->select('branch_items.*','items.item_name')              
            ->where('branch',Auth::user()->branch)
            ->get();            

        $categories = DB::table('categories')
            ->select('categories.*')   
            ->get();


    $tempInvoice = DB::table('temp_invoices')->insertGetId(
        ['student_id' => $studentId, 'branch' => Auth::user()->branch, 'staff_id' => Auth::id()]
    );
        
      return view('students.invoiceAdd',compact('student','totalPayable','totalPaid','tempInvoice','serviceChargeFlag','today','events','items','categories')); 
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Add events.
     *
     */
     public function eventAdd(Request $request)
    {
       
        $eventId = $request->eventId;
        $invoiceId = $request->tempInv; 

        $event = DB::table('events')
                    ->where('event_id',$eventId) 
                    ->first();

        $amount = $event->amount;
        $eventTitle = $event->title;            

       
            DB::table('temp_invoices_custom_items')->insert(
            ['invoice_id' => $invoiceId, 'event_id' => $eventId, 'item_name' => $eventTitle, 'total_amount' => $amount]
            );

            $events = DB::table('temp_invoices_custom_items')
                    ->where('event_id','>',0) 
                    ->where('invoice_id',$invoiceId)
                    ->orderBy('custom_id')
                    ->get();

                    $i=1; $eventTotal = 0;
        ?>
        <table class="table table-striped table-hover table-bordered">
            <thead><tr><th>#</th> <th>Event</th><th>Amount</th><th>&nbsp;</th></tr></thead>
            <?php foreach($events as $event) {?>
            <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $event->item_name; ?></td>
                                      
                                        <td><?php echo $event->total_amount; $eventTotal+=$event->total_amount;?></td>
                                        <td><button class="delEventButton" value="<?php echo $event->custom_id;?>"><i class="fa fa-trash"></i></button></td>
                                    </tr><?php $i++; } ?>
                                    <tr>
  <td colspan="2" style="text-align:right !important"><strong>Event Total</strong></td><td colspan="2"><?php echo $eventTotal;?>
  <input type="hidden" name="event_total" id="event_total" value="<?php echo $eventTotal;?>"></td></tr>                                  
                                    </tbody>
                                </table>
        </table>
        <?php
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Remove events.
     *
     */
     public function eventRemove(Request $request)
    {
       
        $customId = $request->customId;
        $invoiceId = $request->tempInv; 

        DB::table('temp_invoices_custom_items')
            ->where('custom_id',$customId)
            ->delete();

            $events = DB::table('temp_invoices_custom_items')
                    ->where('event_id','>',0) 
                    ->where('invoice_id',$invoiceId)
                    ->orderBy('custom_id')
                    ->get();

             

                    $i=1; $eventTotal = 0;

                    if($events) {
        ?>
        <table class="table table-striped table-hover table-bordered">
            <thead><tr><th>#</th> <th>Event</th><th>Amount</th><th>&nbsp;</th></tr></thead>
            <?php foreach($events as $event) {?>
            <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $event->item_name; ?></td>
                                      
                                        <td><?php echo $event->total_amount; $eventTotal+=$event->total_amount;?></td>
                                        <td><button class="delEventButton" value="<?php echo $event->custom_id;?>"><i class="fa fa-trash"></i></button></td>
                                    </tr><?php $i++; } ?>
                                    <tr>
  <td colspan="2" style="text-align:right !important"><strong>Event Total</strong></td><td colspan="2"><?php echo $eventTotal;?>
  <input type="hidden" name="event_total" id="event_total" value="<?php echo $eventTotal;?>"></td></tr>                                  
                                    </tbody>
                                </table>
        </table>
        <?php } else {?>
<input type="hidden" name="event_total" id="event_total" value="0"></td></tr> 
        <?php }
    }    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Loading events after adding or removing.
     *     
     */
    public function eventLoader(Request $request)
    {
        $invoiceId = $request->tempInv;
        $standard = $request->standard;
        $today = Carbon::now()->toDateString();

       

        $events = DB::table('events')
                    ->whereNotExists(function ($query) use($invoiceId) {
                        $query->select(DB::raw('*'))
                      ->from('temp_invoices_custom_items')
                      ->whereRaw('temp_invoices_custom_items.event_id = events.event_id AND temp_invoices_custom_items.invoice_id=?')->setBindings([$invoiceId]) ;
            })
                    ->where('end_date','>=',$today) 
                    ->where('standard',$standard)
                    ->where('branch',Auth::user()->branch)
                    ->orderBy('start_date')
            ->get();
            ?>
           <select class="col-md-3 col-sm-3" name="event_id" id="event_id" > <option value="0">Select Event</option> 
                                        <?php  
                                        foreach($events AS $event) { ?>
                                        <option value="<?php echo $event->event_id; ?>" ><?php echo $event->title; ?></option>
                                        <?php } ?> 
                                        </select> 
            <?php
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Loadinf store items.
     *
     */
    public function itemLoader(Request $request)
    {
        $invoiceId = $request->tempInv;
        $category = $request->category;
        
        if($category)
        $items = DB::table('branch_items')  
         ->whereNotExists(function ($query) use($invoiceId) {
                        $query->select(DB::raw('*'))
                      ->from('temp_invoices_custom_items')
                      ->whereRaw('temp_invoices_custom_items.item_id = branch_items.item_id  AND temp_invoices_custom_items.invoice_id=?')->setBindings([$invoiceId]) ;
            })           
            ->leftjoin('items','branch_items.item_id','=','items.item_id')          
            ->select('branch_items.*','items.item_name')              
            ->where('branch',Auth::user()->branch)
            ->where('items.category',$category)
            ->get();
         else    
            $items = DB::table('branch_items')  
         ->whereNotExists(function ($query) use($invoiceId) {
                        $query->select(DB::raw('*'))
                      ->from('temp_invoices_custom_items')
                      ->whereRaw('temp_invoices_custom_items.item_id = branch_items.item_id  AND temp_invoices_custom_items.invoice_id=?')->setBindings([$invoiceId]) ;
            })           
            ->leftjoin('items','branch_items.item_id','=','items.item_id')          
            ->select('branch_items.*','items.item_name')              
            ->where('branch',Auth::user()->branch)
            ->get();

            ?>
           <select class="form-control" name="item_id" id="item_id" > <option value="0">Select Item</option> 
                                        <?php  
                                        foreach($items AS $item) { ?>
                                        <option value="<?php echo $item->item_id; ?>" ><?php echo $item->item_name; ?></option>
                                        <?php } ?> 
                                        </select> 
            <?php
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function itemAdd(Request $request)
    {
        $invoiceId = $request->tempInv;
        $item_id = $request->item_id;
        $qty = $request->qty;

        
       
        $item = DB::table('branch_items')             
            ->leftjoin('items','branch_items.item_id','=','items.item_id')          
            ->select('branch_items.price AS branchPrice','branch_items.stock AS branchStock','items.item_name')              
            ->where('branch',Auth::user()->branch)
            ->where('branch_items.item_id',$item_id)
            ->first();


         
        $item_name = $item->item_name;        
                
        $unitPrice = $item->branchPrice;
        $stock = $item->branchStock;
        
        if($stock>=$qty)
        {
            $quantity_received = $qty;
            $quantity_not_received=0;
        }
        
        else
        {            
            $quantity_not_received=$qty-$stock;
            $quantity_received = $qty-$quantity_not_received;
        
        }
        
        $total_amount = $unitPrice*$qty;
        
         
         DB::table('temp_invoices_custom_items')->insert(
            ['invoice_id' => $invoiceId, 'item_id' => $item_id, 'item_name' => $item_name,'unit_price'=>$unitPrice,'qty'=>$qty,'quantity_received'=>$quantity_received,'quantity_not_received'=>$quantity_not_received, 'total_amount' => $total_amount]
            );
        

        $items = DB::table('temp_invoices_custom_items')
                    ->where('item_id','!=',0) 
                    ->where('invoice_id',$invoiceId)
                    ->orderBy('custom_id')
                    ->get();

                    $i=1; $itemTotal = 0;
        ?>
        <table class="table table-striped table-hover table-bordered">
            <thead><tr><th>#</th><th>Item</th><th>Quantity</th><th>Received</th><th>Not Received</th><th>Unit Price</th> <th>Total</th><th>&nbsp;</th></tr></thead>
            <?php foreach($items as $item) {?>
            <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $item->item_name; ?></td>
                                        <td><?php echo $item->qty; ?></td>
                                        <td><?php echo $item->quantity_received; ?></td>
                                        <td><?php echo $item->quantity_not_received; ?></td>
                                        <td><?php echo $item->unit_price; ?></td>
                                        <td><?php echo $item->total_amount; $itemTotal+=$item->total_amount;?></td>
                                        <td><button class="delItemButton" value="<?php echo $item->custom_id;?>"><i class="fa fa-trash"></i></button></td>
                                    </tr><?php $i++; } ?>
                                    <tr>
  <td colspan="6" style="text-align:right !important"><strong>Store Total</strong></td><td colspan="2" ><?php echo $itemTotal;?>
  <input type="hidden" name="store_total" id="store_total" value="<?php echo $itemTotal;?>"></td></tr>                                  
                                    </tbody>
                                </table>
        
        <?php
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function itemRemove(Request $request)
    {
       
        $customId = $request->customId;
        $invoiceId = $request->tempInv; 

        DB::table('temp_invoices_custom_items')
            ->where('custom_id',$customId)
            ->delete();

            $items = DB::table('temp_invoices_custom_items')
                    ->where('item_id','!=',0) 
                    ->where('invoice_id',$invoiceId)
                    ->orderBy('custom_id')
                    ->get();

             

                    $i=1; $itemTotal = 0;

                    if($items) {
        ?>
        <table class="table table-striped table-hover table-bordered">
            <thead><tr><th>#</th><th>Item</th><th>Quantity</th><th>Received</th><th>Not Received</th><th>Unit Price</th> <th>Total</th><th>&nbsp;</th></tr></thead>
            <?php foreach($items as $item) {?>
            <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $item->item_name; ?></td>
                                        <td><?php echo $item->qty; ?></td>
                                        <td><?php echo $item->quantity_received; ?></td>
                                        <td><?php echo $item->quantity_not_received; ?></td>
                                        <td><?php echo $item->unit_price; ?></td>
                                        <td><?php echo $item->total_amount; $itemTotal+=$item->total_amount;?></td>
                                        <td><button class="delItemButton" value="<?php echo $item->custom_id;?>"><i class="fa fa-trash"></i></button></td>
                                    </tr><?php $i++; } ?>
                                    <tr>
  <td colspan="6" style="text-align:right !important"><strong>Store Total</strong></td><td colspan="2" ><?php echo $itemTotal;?>
  <input type="hidden" name="store_total" id="store_total" value="<?php echo $itemTotal;?>"></td></tr>                                  
                                    </tbody>
                                </table>
                                <?php } else {?>
                        <input type="hidden" name="store_total" id="store_total" value="0"></td></tr> 
                                <?php }
    }    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Save temp invoice to invoice table.
     *
     */

     public function save(Request $request,$studentId)
    {
        $tempInvoice = $request->tempInvoice;
     
    
        $studentId = base64_decode($studentId);
        
         
        $current_payment = $request->current_payment;        
        $items_events_amount_reduced = $request->minimum_reduced;                
        $pay_mode = $request->pay_mode;         
        $service_chargeFlag = $request->serviceChargeFlag;
        
        
        $card = 0; $cheque = 0;
        
         
         //-----------------------------
         if($pay_mode!=3)
         $service_chargeFlag = 0;
         
         else
         $card = 1;
         //-----------------------------
         
         if($pay_mode==2)
         $cheque = 1; 
         
         
         $subscriptions_amount = 0;
         
         if($current_payment>$items_events_amount_reduced)
         $subscriptions_amount = $current_payment-$items_events_amount_reduced;
         
         
         $service_charge = $current_payment*$service_chargeFlag;
         
         if($current_payment>=10000)
         { $amount_paid = $current_payment; $service_charge=0; }
         else
         $amount_paid = $current_payment+$service_charge;


     $payable1 = DB::table('subscriptions')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->where('refunded',0 )
            ->sum('amount');

         $payable2 = DB::table('subscriptions')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->where('refunded',1 )
            ->sum('non_refundable_amount');   

        $payable3 = DB::table('subscriptions_hour')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->sum('amount');

        $totalPaid  = DB::table('invoices')                    
            ->where('student_id',$studentId ) 
            ->where('deleted',0 )
            ->whereRaw("NOT(cheque='1' AND cheque_bounce='1')")
            ->sum('subscriptions_amount');  

            $totalPayable = $payable1+$payable2+$payable3; 

            $totalPayable-=$totalPaid;

            $subscriptions_balance = $totalPayable - $subscriptions_amount;

            $invoice_no = DB::table('invoices')->where('branch',Auth::user()->branch)->max('invoice_no');
            $invoice_no+=1;

             
            $invoice = new Invoice(array(
            'invoice_no' => $invoice_no,
            'branch' => Auth::user()->branch,
            'student_id' => $studentId,
            'entry_date' => Carbon::now()->toDateString(),
            'dated' => $request->get('dated'),
            'discount' => $request->get('discount'),
            'subscriptions_amount' => $subscriptions_amount,
            'subscriptions_balance' => $subscriptions_balance,
            'service_charge' => $service_charge,
            'amount_paid' => $amount_paid,
            'admin' => Auth::id(),
            'cheque' => $cheque,
            'cheque_no' => $request->get('cheque_no'),
            'cheque_date' => $request->get('cheque_date'),
            'cheque_bank' => $request->get('cheque_bank'),
            'card' => $card,
            'card_notes' => $request->get('card_notes'),
            'notes' => $request->get('notes')
                        
            ));
        
            $invoice->save();
            $invoiceId = $invoice->invoice_id;//$invoice->id not working because of custom primary key set in Invoice model

            $customItems = DB::table('temp_invoices_custom_items')
                    ->where('invoice_id',$tempInvoice)
                    ->orderBy('custom_id')
                    ->get();
            $customData = array();        
            foreach ($customItems as $customItem) 
                    {
                        $event_id = $customItem->event_id;
                        $item_id = $customItem->item_id;
                        $item_name = $customItem->item_name;
                         
                        
                        $unit_price = $customItem->unit_price;
                        $qty = $customItem->qty;
                        $quantity_received = $customItem->quantity_received;
                        $quantity_not_received = $customItem->quantity_not_received;
                        $total_amount = $customItem->total_amount;
 

                        $customData[] = array('invoice_id' => $invoiceId, 'event_id' => $event_id, 'item_id' => $item_id, 
                            'item_name' => $item_name, 'unit_price' => $unit_price, 'qty' => $qty, 'quantity_received' => $quantity_received, 
                            'quantity_not_received' => $quantity_not_received, 'total_amount' => $total_amount);


                        if($item_id!=0)
                                    {
                                        
                                        $item = BranchItem::where('branch',Auth::user()->branch)
                                        ->where('item_id',$item_id)
                                        ->first();
                                        
                                        $currentStock = $item->stock;                 
                                        $updatedStock = $currentStock-$quantity_received;
                                        
                                        $currentSold = $item->sold;
                                        $updatedSold = $currentSold+$qty;

                                        
                                               $item->stock = $updatedStock;
                                               $item->sold = $updatedSold;
                                               $item->save();
                                        
                                    }//if($item_id!=0)
                    }   
            if($customData)
            InvoicesCustomItem::insert($customData);  

             DB::statement('DELETE temp_invoices,temp_invoices_custom_items FROM temp_invoices INNER JOIN temp_invoices_custom_items ON 
            temp_invoices.temp_id=temp_invoices_custom_items.invoice_id WHERE staff_id='.Auth::id()) ;
            
            DB::table('temp_invoices')
                ->where('staff_id',Auth::id())
                ->delete();


            return redirect()->intended('invoice/'.base64_encode($invoiceId));            
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 public function view($invoiceId)
 {
        
    $invoiceId = base64_decode($invoiceId); 
    $today = Carbon::now()->toDateString();

    $invoice = DB::table('invoices') 
            ->leftjoin('users', 'invoices.admin', '=', 'users.id')
            ->leftjoin('students','invoices.student_id','=','students.student_id')
            ->select('invoices.*', 'users.name AS issued','students.full_name', 'students.father_name','students.mother_name') 
            ->where('invoice_id',$invoiceId ) 
            ->where('invoices.deleted',0)
            ->first();
    
    $branchId   =$invoice->branch;
    $branch = Branch::where('id', $branchId)->first(); 

    $events =  InvoicesCustomItem::where('invoice_id', $invoiceId)->where('event_id','!=',0)->get(); 
    $items =   InvoicesCustomItem::where('invoice_id', $invoiceId)->where('item_id','!=',0)->get();                         
                                     

    return view('invoice',compact('invoiceId','invoice','branch','items','events','today'));

 }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function delete(Request $request,$studentId)
    {
         $this->validate($request, [
        'delete_reason' => 'required|min:4',
        
    ]);

        $invoiceId =  base64_decode($request->invoiceId);

        $invoice = Invoice::where('invoice_id',$invoiceId)
                                        ->where('locked',0)
                                        ->first();

        if($invoice)
        {
            $invoice->deleted=1;
            $invoice->deleted_by=Auth::id();
            $invoice->delete_reason=$request->delete_reason;
            $invoice->delete_date=Carbon::now()->toDateString();
            $invoice->save();

            $customItems = DB::table('invoices_custom_items')
                    ->where('invoice_id',$invoiceId)
                    ->where('item_id','!=',0)
                    ->orderBy('custom_id')
                    ->get();

                    
            foreach ($customItems as $customItem) 
                    {
                        
                        $item_id = $customItem->item_id;
                         
                        $qty = $customItem->qty;
                        $quantity_received = $customItem->quantity_received;
                        $quantity_not_received = $customItem->quantity_not_received;

                        $item = BranchItem::where('branch',$invoice->branch)
                                        ->where('item_id',$item_id)
                                        ->first(); 
                        
                        $currentStock = $item->stock;                 
                        $updatedStock = $currentStock+$quantity_received;
                                        
                        $currentSold = $item->sold;
                        $updatedSold = $currentSold-$qty;

                        $item->stock = $updatedStock;
                        $item->sold = $updatedSold;
                        $item->save();

                         
                        }   
          Session::flash('status', 'Receipt deleted!');              
          return redirect()->action('StudentsController@profile', [$studentId]) ;               
        }//if($invoice)
        else{
                return redirect()->intended('profile/student/'.$studentId)->withErrors(['Technical Error. Contact Administrator']);
            }
    }
//----------------------------------------------------------------------------------------------------------------------------
    public function exchangeForm($customId)
    {
        if(!Auth::user()->hasRole('InvoiceEditor'))
            return redirect()->intended('/home')->with('warning', 'Tried to enter restricted area!');

        $customId = base64_decode($customId);

        $customItem = InvoicesCustomItem::where('custom_id',$customId)->first(); 
        $currentItemName = $customItem->item_name;

        $items = BranchItem::select('branch_items.*','items.item_name')    
                            ->leftjoin('items','branch_items.item_id','=','items.item_id') 
                            ->where('branch_items.branch',Auth::user()->branch)
                            ->where('branch_items.item_id','!=',$customItem->item_id)
                            ->where('branch_items.stock','>=',$customItem->qty)
                            ->orderBy('items.item_name')
                            ->get();   

        return view('invoiceExchangeItem',compact('items','currentItemName'));                 

    }


//----------------------------------------------------------------------------------------------------------------------------
    public function exchangeSave($customId,Request $request)
    {
        if(!Auth::user()->hasRole('InvoiceEditor'))
            return redirect()->intended('/home')->with('warning', 'Tried to enter restricted area!');

         $this->validate($request, [
        'item_id' => 'required']); 

        $customId = base64_decode($customId);

        $customItem = InvoicesCustomItem::where('custom_id',$customId)->first(); 
        
        $oldItemId = $customItem->item_id;
        $chosenItemId = $request->item_id;

        $qty = $customItem->qty;
        $quantity_received = $customItem->quantity_received;
        $quantity_not_received = $customItem->quantity_not_received;

        $oldItem = BranchItem::where('branch',Auth::user()->branch)
                             ->where('item_id',$oldItemId)
                             ->first();

                             $updatedStockOld = $oldItem->stock + $quantity_received;
                             $updatedSoldOld = $oldItem->sold - $qty;
       
        //echo "Old Item ====Current Stock".$oldItem->stock."====Updated Stock====".$updatedStockOld."====Sold====".$oldItem->sold."====Updated Sold====".$updatedSoldOld;                     

        $newItem =BranchItem::select('branch_items.*','items.item_name')    
                            ->leftjoin('items','branch_items.item_id','=','items.item_id') 
                            ->where('branch_items.branch',Auth::user()->branch)
                            ->where('branch_items.item_id',$chosenItemId)
                            ->first();  
                            
                            $updatedStockNew = $newItem->stock - $qty;
                            $updatedSoldNew = $newItem->sold + $qty;
        //echo "sdsd<br>New Item ====".$newItem->item_name."====current stock:".$newItem->stock."====Updated Stock====".$updatedStockNew."====Sold====".$newItem->sold."====Updated Sold====".$updatedSoldNew;                    
       
        $customItem->item_id = $chosenItemId; 
        $customItem->item_name = $newItem->item_name;
        $customItem->quantity_received = $qty;
        $customItem->quantity_not_received = 0; 
        $customItem->save();   

        DB::table('invoice_edit_history')->insert([
                                                    'invoice_id' => $customItem->invoice_id, 
                                                    'custom_id' => $customId, 
                                                    'old_item' => $oldItemId,
                                                    'new_item'=>$chosenItemId,
                                                    'dated'=>Carbon::now()->toDateString(),
                                                    'admin'=>Auth::id(),
                                                    'branch'=>Auth::user()->branch
                                                ]);   
        $oldItem->stock = $updatedStockOld;
        $oldItem->sold = $updatedSoldOld;  
        $oldItem->save();

        $newItem->stock = $updatedStockNew;
        $newItem->sold = $updatedSoldNew;  
        $newItem->save();    
        
        return redirect()->action('InvoiceController@view', [base64_encode($customItem->invoice_id)])->with('status','Item Exchanged successfully') ;                  

    }    
//-----------------------------------------------------------------------------------------------

public function lockUnlock(Request $request)
    {
         
        
        $invoiceId =  $request->invoiceId; 
                
        if(Auth::user()->hasRole('PaymentsUnlock'))
        {    
            $receipt = Invoice::where('invoice_id',$invoiceId)->first();
            if($receipt && $request->action=="unlock")
            {
                $receipt->locked=0;            
                $receipt->save();
                ?>
                &nbsp;&nbsp; &nbsp;&nbsp; <button class="btn btn-sm" id="invLock<?php echo $invoiceId;?>" value="<?php echo $invoiceId;?>"> <i class="fa fa-unlock"></i></button>
                <?php
              
            }

            else if($receipt && $request->action=="lock")
            {
                $receipt->locked=1;            
                $receipt->save();
                ?>
                &nbsp;&nbsp; &nbsp;&nbsp; <button class="btn btn-sm" id="invUnlock<?php echo $invoiceId;?>" value="<?php echo $invoiceId;?>"> <i class="fa fa-lock"></i></button>
                <?php
              
            }
        }

    }
//====--------------------------------------------------------------------------------------------------------------------------

}
 
 