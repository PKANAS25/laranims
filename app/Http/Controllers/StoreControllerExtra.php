<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Carbon\Carbon;

class StoreControllerExtra extends Controller
{
    //----------------------------------------------------------------------------------------------------------------------------------------

    public function addStoreRequest()
    {
          $items = DB::table('branch_items')             
            ->leftjoin('items','branch_items.item_id','=','items.item_id')          
            ->select('branch_items.*','items.item_name')              
            ->where('branch',Auth::user()->branch)
            ->get();            

        $categories = DB::table('categories')
            ->select('categories.*')   
            ->get();
 
         DB::statement('DELETE temp_requests,temp_request_items FROM temp_requests INNER JOIN temp_request_items ON 
                        temp_requests.temp_id=temp_request_items.request_id WHERE branch='.Auth::user()->branch);

         DB::table('temp_requests')->where('branch',Auth::user()->branch)->delete();

         $tempRequest = DB::table('temp_requests')->insertGetId(['branch' => Auth::user()->branch]); 

         return view('store.newStoreRequest',compact('items','categories','tempRequest'));
    }
//----------------------------------------------------------------------------------------------------------------------------------------    
    public function itemLoader(Request $request)
    {
        $requestId = $request->tempReq;
        $category = $request->category;
        
        if($category)
        $items = DB::table('branch_items')  
         ->whereNotExists(function ($query) use($requestId) {
                        $query->select(DB::raw('*'))
                      ->from('temp_request_items')
                      ->whereRaw('temp_request_items.item_id = branch_items.item_id  AND temp_request_items.request_id=?')->setBindings([$requestId]) ;
            })           
            ->leftjoin('items','branch_items.item_id','=','items.item_id')          
            ->select('branch_items.*','items.item_name')              
            ->where('branch',Auth::user()->branch)
            ->where('items.category',$category)
            ->get();
         else    
           $items = DB::table('branch_items')  
         ->whereNotExists(function ($query) use($requestId) {
                        $query->select(DB::raw('*'))
                      ->from('temp_request_items')
                      ->whereRaw('temp_request_items.item_id = branch_items.item_id  AND temp_request_items.request_id=?')->setBindings([$requestId]) ;
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

//------------------------------------------------------------------------------------------------------------------------------------------------------
 public function itemAdd(Request $request)
    {
        $requestId = $request->tempReq;
        $item_id = $request->item_id;
        $qty = $request->qty;
        $new_item = $request->new_item;
        if($item_id>0) $new_item="" ;
         
         DB::table('temp_request_items')->insert(['request_id' => $requestId, 'item_id' => $item_id, 'new_item' => $new_item,'qty'=>$qty]);
        

        $items = DB::table('temp_request_items')
                    ->select('temp_request_items.*','items.item_name') 
                    ->leftjoin('items','temp_request_items.item_id','=','items.item_id') 
                    ->where('request_id',$requestId)
                    ->orderBy('custom_id')
                    ->get();

                    $i=1; $itemTotal = 0;
        ?>
        <table class="table table-striped table-bordered">
            <thead><tr><th>#</th><th>Item</th><th>Quantity</th><th>&nbsp;</th></tr></thead>
            <?php foreach($items as $item) {?>
            <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $item->item_name.$item->new_item; ?></td>
                                        <td><?php echo $item->qty; ?></td>
                                        <td><button class="delItemButton" value="<?php echo $item->custom_id;?>"><i class="fa fa-trash"></i></button></td>
                                    </tr><?php $i++; } ?>
                       <tr><td colspan="3"><input type="text" size="75" placeholder="Notes" name="notes" id="notes" /></td>
                        <td><button type="submit" class="btn btn-primary">Save</button></td></tr>                                            
                                    </tbody>
                                </table>
        
        <?php
    }    

//------------------------------------------------------------------------------------------------------------------------------------------------------

public function itemRemove(Request $request)
    {
       
        $customId = $request->customId;
        $requestId = $request->tempReq;

        DB::table('temp_request_items')
            ->where('custom_id',$customId)
            ->delete();

           $items = DB::table('temp_request_items')
                    ->select('temp_request_items.*','items.item_name') 
                    ->leftjoin('items','temp_request_items.item_id','=','items.item_id') 
                    ->where('request_id',$requestId)
                    ->orderBy('custom_id')
                    ->get();

             

                    $i=1; $itemTotal = 0;

                    if($items) {
        ?>
        <table class="table table-striped table-bordered">
            <thead><tr><th>#</th><th>Item</th><th>Quantity</th><th>&nbsp;</th></tr></thead>
            <?php foreach($items as $item) {?>
            <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $item->item_name.$item->new_item; ?></td>
                                        <td><?php echo $item->qty; ?></td>
                                        <td><button class="delItemButton" value="<?php echo $item->custom_id;?>"><i class="fa fa-trash"></i></button></td>
                                    </tr><?php $i++; } ?>
                                     <tr><td colspan="3"><input type="text" size="75" placeholder="Notes" name="notes" id="notes" /></td>
                                        <td><button type="submit" class="btn btn-primary">Save</button></td></tr>                                
                                    </tbody>
                                </table> 
        <?php }
    }    
//----------------------------------------------------------------------------------------------------------------------------------------

    public function saveStoreRequest(Request $request)
    {
        $storeRequestId = DB::table('store_requests')->insertGetId([
                                                            'branch' => Auth::user()->branch,
                                                            'admin'=>Auth::id(),
                                                            'request_date'=>Carbon::now()->toDateString(),
                                                            'notes' => $request->notes]);  

        $items = DB::table('temp_request_items')  
                    ->where('request_id',$request->tempRequest)
                    ->orderBy('custom_id')
                    ->get();
        
        foreach($items as $item)
        {
            DB::table('store_request_items')->insert(['request_id' => $storeRequestId,
                                                    'item_id'=>$item->item_id,
                                                    'new_item'=>$item->new_item,
                                                    'qty' => $item->qty]);  
        }

        return redirect()->action('StoreController@storeRequestsBranch')->with('status', 'Request has been sent to store manager!');
    }            
//----------------------------------------------------------------------------------------------------------------------------------------
 
   
}
