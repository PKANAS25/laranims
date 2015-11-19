<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

use App\Item;
use App\Stock;
use App\BranchItem;
use App\BranchStock;

use App\InvoicesCustomItem;

use App\Branch;
use DB;

use File;

use Validator;
use Carbon\Carbon;

use Image;

class StoreController extends Controller
{
     
    public function mainStore()
    {
        $Items = Item::select('items.*','categories.category AS category_name')
                     ->leftjoin('categories','items.category','=','categories.category_id')
                     ->orderBy('item_name')
                     ->get();

                     foreach($Items as  $item)
                     {
                         

                        $pending = DB::table('item_transfers')
                                     ->where('item_id',$item->item_id)
                                     ->where('approval',0)
                                     ->sum('count');
 
                        $item->pending = $pending;
                     }
        return view('store.mainStore',compact('Items'));
    }

//----------------------------------------------------------------------------------------------------------------------------------------

     
    public function itemView($itemId)
    {
        $itemId = base64_decode($itemId);
        $pic=0;
        
        $item = Item::select('items.*','categories.category AS category_name')
                     ->leftjoin('categories','items.category','=','categories.category_id')
                     ->where('item_id',$itemId)
                     ->first();

        $pending = DB::table('item_transfers')
                                     ->where('item_id',$itemId)
                                     ->where('approval',0)
                                     ->sum('count');

        $transfered = DB::table('item_transfers')
                                     ->where('item_id',$itemId)
                                     ->where('approval',1)
                                     ->sum('count');

        
        $stocks = Stock::select('stocks.*','users.name AS added_by','suppliers.name AS supplier_name','branches.name AS branch_name')
                     ->leftjoin('users','stocks.admin_id','=','users.id')
                     ->leftjoin('suppliers','stocks.supplier_id','=','suppliers.supplier_id')
                     ->leftjoin('branches','stocks.branch_id','=','branches.id')
                     ->where('item_id',$itemId) 
                     ->orderBy('stocked_date','DESC')
                     ->get();   

                     foreach ($stocks as $stock) 
                     {
                          if (File::exists(base_path().'/public/uploads/stock_invoices/'.$stock->stock_id.'.jpg'))
                            $stock->file=1;
                         
                         else
                            $stock->file=0;
                     }
 

    $transfers = DB::table('item_transfers')
                   ->select('item_transfers.*', 'transferer.name AS transfered_by','approver.name AS approved_by','branches.name AS branch_name')
                   ->leftjoin('users AS transferer','item_transfers.accountant','=','transferer.id')
                   ->leftjoin('users AS approver','item_transfers.approved_admin','=','approver.id')
                   ->leftjoin('branches','item_transfers.branch','=','branches.id')
                   ->where('item_id',$itemId)
                   ->orderBy('transfer_id','DESC')
                   ->get(); 
                                                                                               
        
        if (File::exists(base_path().'/public/uploads/store_items/'.$itemId.'_s.jpg'))
        {
            $pic='/uploads/store_items/'.$itemId.'_s.jpg';
            $picBig='/uploads/store_items/'.$itemId.'.jpg';
        }
        

        return view('store.itemDetails',compact('item','pending','transfered','pic','picBig','stocks','transfers'));
    }
//----------------------------------------------------------------------------------------------------------------------------------------

    public function addNewItem()
    {
        $categories = DB::table('categories')->orderBy('category')->get(); 

        return view('store.addItem',compact('categories'));              
    } 
//----------------------------------------------------------------------------------------------------------------------------------------

    public function saveNewItem(Request $request)
    {
         
        $validator = Validator::make($request->all(),[
            'item_name' => 'required',
            'category' => 'required',
            'product_code' => 'required',
            'price' => 'required|numeric|min:1',
            'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',
         ]    
        );

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {  
            $item = new Item(array(
                               'item_name' => $request->item_name, 
                               'category' => $request->category,
                               'product_code' => $request->product_code,                               
                               'description' => $request->description,
                               'price' => $request->price,
                               'accountant' => Auth::id() 
                               ));
            $item->save();
            $itemId = $item->item_id; 

       
           if($request->file('fileToUpload') && $itemId)
            {
             $imageName = $itemId.'.jpg';
             $imageNameSmall = $itemId.'_s.jpg';

             Image::make($request->file('fileToUpload'))->save(base_path().'/public/uploads/store_items/'.$imageName);
             Image::make($request->file('fileToUpload'))->resize(175, 200)->save(base_path().'/public/uploads/store_items/'.$imageNameSmall);
            } 

            return redirect()->action('StoreController@mainStore')->with('status', 'New Item added!');
        }

                         
    }    
//----------------------------------------------------------------------------------------------------------------------------------------

    public function addStock($itemId)
    {
        $itemId = base64_decode($itemId);
         
        
        $item = Item::where('item_id',$itemId)
                     ->first(); 

        $suppliers = DB::table('suppliers')
                         ->where('deleted',0)
                         ->orderBy('name')
                         ->get();

        return view('store.addStock',compact('suppliers','item'));                 
    }


//----------------------------------------------------------------------------------------------------------------------------------------


    public function saveStock($itemId,Request $request)
    {
        $validator = Validator::make($request->all(),[
            'supplier_id' => 'required',
            'item_count' => 'required|numeric|min:1',
            'cost' => 'required|numeric|min:1',
            'stocked_date' => 'required|date_format:Y-m-d',
            'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',
         ]    
        );

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {
            $itemId = base64_decode($itemId); 
        
             
 
            $stocker = new Stock(array(
                               'item_id' => $itemId, 
                               'dated' => Carbon::now()->toDateString(),
                               'supplier_id' => $request->supplier_id,                               
                               'item_count' => $request->item_count,
                               'cost' => $request->cost,
                               'stocked_date' => $request->stocked_date,
                               'admin_id' => Auth::id() 
                               ));

            $stocker->save();
            $stockId = $stocker->stock_id; 

            $item = Item::where('item_id',$itemId)->first();
            
            $currentStock = $item->stock;        
            $updatedStock = $currentStock+$request->item_count;                
             
            Item::where('item_id',$itemId)
                  ->update(['stock' => $updatedStock]);   

          if($request->file('fileToUpload') && $stockId)
          {
             $imageName = $stockId.'.jpg';
             $request->file('fileToUpload')->move(base_path().'/public/uploads/stock_invoices/', $imageName);
          } 

           return redirect()->action('StoreController@itemView', [base64_encode($itemId)])->with('status', 'Stock added!');                                  
        }



    }

//----------------------------------------------------------------------------------------------------------------------------------------

    public function deleteStock(Request $request,$stockId)
    {
        $stockId = base64_decode($stockId);
        
         $this->validate($request, [
        'delete_reason' => 'required|min:4',]); 
        
        $stock = Stock::select('stocks.*','items.stock')
                      ->leftjoin('items','stocks.item_id','=','items.item_id')
                      ->where('stock_id',$stockId)
                      ->first(); 
        
        $updatedStock = $stock->stock-$stock->item_count;  

        Stock::where('stock_id',$stockId)->update(['deleted' => 1,
                                                   'delete_date' => Carbon::now()->toDateString(),
                                                   'deleted_by' => Auth::id(),
                                                   'delete_reason' => $request->delete_reason]); 

        Item::where('item_id',$stock->item_id)->update(['stock' => $updatedStock]); 
 

       return redirect()->action('StoreController@itemView', [base64_encode($stock->item_id)])->with('status', 'Stock removed!');                
    }

  //----------------------------------------------------------------------------------------------------------------------------------------


    public function uploadInvoice($stockId, Request $request)
    {
        $stockId = base64_decode($stockId); 

        $validator = Validator::make($request->all(),[
            'fileToUpload'=>'required|image|max:615|mimes:jpeg,jpg',
         ]    
        );

        $stock = Stock::where('stock_id',$stockId)->first();
        $itemId = $stock->item_id;

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {
            $imageName = $stockId.'.jpg';

            if (File::exists(base_path().'/public/uploads/stock_invoices/'.$imageName))
                 File::delete(base_path().'/public/uploads/stock_invoices/'.$imageName);

            
            $request->file('fileToUpload')->move(base_path().'/public/uploads/stock_invoices/', $imageName);
        }

        return redirect()->action('StoreController@itemView', [base64_encode($itemId)])->with('status', 'Stock invoice uploaded!');
    }

    //----------------------------------------------------------------------------------------------------------------------------------------

    public function itemTransfer($itemId)
    {
        $itemId = base64_decode($itemId);

         $item = Item::where('item_id',$itemId)->first();  

         $branches = Branch::where('non_nursery',0)->orderBy('name')->get();            
         
         return view('store.itemTransfer',compact('item','branches'));   
    }

    //----------------------------------------------------------------------------------------------------------------------------------------
    public function itemTransferSave($itemId,Request $request)
    {
        
        $itemId = base64_decode($itemId);
        
        $item = Item::where('item_id',$itemId)->first();  

        $validator = Validator::make($request->all(),[
            'branch' => 'required',
            'count' => 'required|numeric|min:1|max:'.$item->stock,
         ]    
        );

         if($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {
            $currentStock = $item->stock;                 
            $updatedStock = $currentStock-$request->count;


            DB::table('item_transfers')->insert([
                  'item_id' => $itemId,
                  'branch' => $request->branch,
                  'count' => $request->count,
                  'dated' => Carbon::now()->toDateString(),
                  'message' => $request->message,
                  'accountant' => Auth::id()
                  ]);

             Item::where('item_id',$itemId)->update(['stock' => $updatedStock]);  

            return redirect()->action('StoreController@itemView', [base64_encode($itemId)])->with('status', 'Item transfer initiated!');   
        }
    }

//----------------------------------------------------------------------------------------------------------------------------------------

public function itemTransferCallback($transferId)
    {
        $transferId = base64_decode($transferId);

        $transfer = DB::table('item_transfers')
                        ->select('item_transfers.*','items.stock')
                        ->leftjoin('items','item_transfers.item_id','=','items.item_id')
                        ->where('transfer_id',$transferId)
                        ->first();

        $updatedStock = $transfer->stock+$transfer->count;  

         if($transfer->approval==0) 
             {
                DB::table('item_transfers')->where('transfer_id',$transferId)->delete();
                Item::where('item_id',$transfer->item_id)->update(['stock' => $updatedStock]);    

                return redirect()->action('StoreController@itemView', [base64_encode($transfer->item_id)])->with('status', 'Item transfer reverted!'); 
             }  
         
         else   
         return redirect()->action('StoreController@itemView', [base64_encode($transfer->item_id)])->withErrors('Can\'t revert an approved transfer!');           

    }

    //----------------------------------------------------------------------------------------------------------------------------------------

public function pendingTransfers()
    {
        $transfers = DB::table('item_transfers')
                        ->select('item_transfers.*','users.name AS transfered_by','items.item_name')
                        ->leftjoin('users','item_transfers.accountant','=','users.id')
                        ->leftjoin('items','item_transfers.item_id','=','items.item_id')
                        ->where('approval',0)
                        ->where('item_transfers.branch',Auth::user()->branch)
                        ->get();
 
        return view('store.transfersPending',compact('transfers'));   
        
    }
//----------------------------------------------------------------------------------------------------------------------------------------

public function approveTransfer($transferId)
    {
        $transferId = base64_decode($transferId);

        $transfer = DB::table('item_transfers')
                        ->select('item_transfers.*','items.price')
                        ->leftjoin('items','item_transfers.item_id','=','items.item_id')
                        ->where('transfer_id',$transferId)
                        ->first();

        $count = $transfer->count;
        $item_id = $transfer->item_id;

        if($transfer->approval==0)
        {    
            $branchItemExists = BranchItem::where('item_id',$item_id)->where('branch',Auth::user()->branch)->first();

                if($branchItemExists==null) 
                {
                    $branchItemInsert = new BranchItem(array(
                                       'item_id' => $item_id, 
                                       'branch' => Auth::user()->branch,
                                       'price' => $transfer->price 
                                       ));
                    $branchItemInsert->save();
                }     
            
            $branchItem = BranchItem::where('item_id',$item_id)->where('branch',Auth::user()->branch)->first();  
            $updatedBranchStock = $branchItem->stock+$count;
            $branchItem->stock=$updatedBranchStock;
            $branchItem->save();

            $branchStock = new BranchStock(array(
                                       'item_id' => $item_id, 
                                       'branch' => Auth::user()->branch,
                                       'transfered_date' => $transfer->dated,
                                       'item_count' => $count,
                                       'accountant' => $transfer->accountant,
                                       'accepted_admin' => Auth::id() 
                                       ));
            $branchStock->save();

            DB::table('item_transfers')->where('transfer_id',$transferId)->update(['approval' => 1,'approved_admin' => Auth::id()]);

            return redirect()->back()->with('status', 'Stock added to the store!');

        }//if($transfer->approval==0)
        return redirect()->back()->withErrors('Something went wrong!');
    }
//----------------------------------------------------------------------------------------------------------------------------------------

public function rejectTransfer(Request $request,$transferId)
    {
        $transferId = base64_decode($transferId);

        $this->validate($request, [
        'reject_reason' => 'required|min:4',]); 

        $transfer = DB::table('item_transfers')
                        ->select('item_transfers.*','items.price')
                        ->leftjoin('items','item_transfers.item_id','=','items.item_id')
                        ->where('transfer_id',$transferId)
                        ->first();

        $count = $transfer->count;
        $item_id = $transfer->item_id;

        if($transfer->approval==0)
        {
                
             $item = Item::where('item_id',$item_id)->first(); 
             $revertedStock = $item->stock+$count;   
             $item->stock=$revertedStock;
             $item->save(); 

             DB::table('item_transfers')->where('transfer_id',$transferId)->update(['approval' => -1,'approved_admin' => Auth::id(),'rejection_reason' => $request->reject_reason]);

             return redirect()->back()->with('status', 'Item backed to main store!');
        }
        return redirect()->back()->withErrors('Something went wrong!');
    }

    
                         
//----------------------------------------------------------------------------------------------------------------------------------------

public function branchStore()
    {
         $Items = BranchItem::select('branch_items.*','items.item_name', 'items.product_code', 'items.category','categories.category AS category_name')
                     ->leftjoin('items','branch_items.item_id','=','items.item_id')
                     ->leftjoin('categories','items.category','=','categories.category_id')
                     ->where('branch',Auth::user()->branch)
                     ->orderBy('item_name')
                     ->get();

                     foreach($Items as  $item)
                     { 
                        $qnr = InvoicesCustomItem::select('invoices.*', 'invoices_custom_items.*')
                                     ->leftjoin('invoices','invoices_custom_items.invoice_id','=','invoices.invoice_id')
                                     ->where('item_id',$item->item_id)
                                     ->where('invoices.branch',Auth::user()->branch)
                                     ->where('invoices.deleted',0)
                                     ->sum('quantity_not_received');
 
                        $item->qnr = $qnr;
                     }
        return view('store.branchStore',compact('Items'));
    }
//----------------------------------------------------------------------------------------------------------------------------------------    
public function itemViewBranch($itemId)
    {
        $itemId = base64_decode($itemId);
        $pic=0;
        
        $item = BranchItem::select('branch_items.*','items.item_name', 'items.product_code', 'items.category','categories.category AS category_name')
                     ->leftjoin('items','branch_items.item_id','=','items.item_id')
                     ->leftjoin('categories','items.category','=','categories.category_id')
                     ->where('branch_items.item_id',$itemId)
                     ->where('branch',Auth::user()->branch)
                     ->first();

        $qnr = InvoicesCustomItem::select('invoices.*', 'invoices_custom_items.*')
                                     ->leftjoin('invoices','invoices_custom_items.invoice_id','=','invoices.invoice_id')
                                     ->where('item_id',$itemId)
                                     ->where('invoices.branch',Auth::user()->branch)
                                     ->where('invoices.deleted',0)
                                     ->sum('quantity_not_received');
        
         
        $pending = DB::table('item_returns')
                     ->where('item_id',$itemId)
                     ->where('approval',0)
                     ->where('branch',Auth::user()->branch)
                     ->sum('count');                                 

        $stocks = BranchStock::select('branch_stocks.*','transferer.name AS transfered_by','approver.name AS approved_by')
                         ->leftjoin('users AS transferer','branch_stocks.accountant','=','transferer.id')
                         ->leftjoin('users AS approver','branch_stocks.accepted_admin','=','approver.id')  
                         ->where('item_id',$itemId)                      
                         ->where('branch_stocks.branch',Auth::user()->branch)
                         ->orderBy('transfered_date','DESC')
                         ->get();   
     
        
        $returns = DB::table('item_returns')
                       ->select('item_returns.*', 'transferer.name AS transfered_by','approver.name AS approved_by')
                       ->leftjoin('users AS approver','item_returns.approved_accountant','=','approver.id')
                       ->leftjoin('users AS transferer','item_returns.admin','=','transferer.id') 
                       ->where('item_returns.item_id',$itemId)
                       ->where('item_returns.branch',Auth::user()->branch)
                       ->orderBy('return_id','DESC')
                       ->get(); 

        
                                                                                               
        
        if (File::exists(base_path().'/public/uploads/store_items/'.$itemId.'_s.jpg'))
        {
            $pic='/uploads/store_items/'.$itemId.'_s.jpg';
            $picBig='/uploads/store_items/'.$itemId.'.jpg';
        }
        

        return view('store.itemDetailsBranch',compact('item','pic','picBig','stocks','returns','qnr','pending'));
    }
//----------------------------------------------------------------------------------------------------------------------------------------


public function itemReturn($itemId)
    {
         $itemId = base64_decode($itemId);

         $item = BranchItem::where('item_id',$itemId)->where('branch',Auth::user()->branch)->first();                   
         
         return view('store.itemReturn',compact('item'));   
    }
//----------------------------------------------------------------------------------------------------------------------------------------  

public function itemReturnSave($itemId,Request $request)
    {
        
        $itemId = base64_decode($itemId);
        
        $item = BranchItem::where('item_id',$itemId)->where('branch',Auth::user()->branch)->first();

        $validator = Validator::make($request->all(),[ 
            'count' => 'required|numeric|min:1|max:'.$item->stock,
         ]    
        );

         if($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {
            $currentStock = $item->stock;                 
            $updatedStock = $currentStock-$request->count;  

            $currentPendingReturns = $item->pending_returns;
            $updatedPendingReturns = $currentPendingReturns+$request->count;
        
            DB::table('item_returns')->insert([
                  'item_id' => $itemId,
                  'branch' => Auth::user()->branch,
                  'count' => $request->count,
                  'dated' => Carbon::now()->toDateString(),
                  'message' => $request->message,
                  'admin' => Auth::id()
                  ]);

             BranchItem::where('item_id',$itemId)->where('branch',Auth::user()->branch)->update(['stock' => $updatedStock,'pending_returns' => $updatedPendingReturns]);  

            return redirect()->action('StoreController@itemViewBranch', [base64_encode($itemId)])->with('status', 'Item transfer initiated!');   
        }
    }

//----------------------------------------------------------------------------------------------------------------------------------------  

public function itemReturnCallback($returnId)
    {
        $returnId = base64_decode($returnId);

        $return = DB::table('item_returns')->where('return_id',$returnId)->first();
        
        $item = BranchItem::where('item_id',$return->item_id)->where('branch',Auth::user()->branch)->first();    
                     

        $updatedStock = $item->stock+$return->count;   
        $updatedPendingReturns = $item->pending_returns-$return->count;

         if($return->approval==0) 
             {
                DB::table('item_returns')->where('return_id',$returnId)->delete();
                BranchItem::where('item_id',$return->item_id)->where('branch',Auth::user()->branch)->update(['stock' => $updatedStock,'pending_returns' => $updatedPendingReturns]);    

                return redirect()->action('StoreController@itemViewBranch', [base64_encode($return->item_id)])->with('status', 'Item return reverted!'); 
             }  
         
         else   
         return redirect()->action('StoreController@itemViewBranch', [base64_encode($return->item_id)])->withErrors('Can\'t revert an approved transfer!');           

    }

//----------------------------------------------------------------------------------------------------------------------------------------

    public function transferRejections($viewer)
    {
        ($viewer=="unread")?$read=0:$read=1;

        $transfers = DB::table('item_transfers')
                   ->select('item_transfers.*', 'transferer.name AS transfered_by','approver.name AS rejected_by','branches.name AS branch_name','items.item_name')
                   ->leftjoin('users AS transferer','item_transfers.accountant','=','transferer.id')
                   ->leftjoin('users AS approver','item_transfers.approved_admin','=','approver.id')
                   ->leftjoin('branches','item_transfers.branch','=','branches.id')
                   ->leftjoin('items','item_transfers.item_id','=','items.item_id')
                   ->where('reject_read',$read)
                   ->where('approval',-1)
                   ->orderBy('transfer_id','DESC')
                   ->get(); 

        return view('store.transferRejections',compact('transfers','viewer'));          
    }
//----------------------------------------------------------------------------------------------------------------------------------------
    
    public function transferRejectRead(Request $request)
    {
                 
        $transferId =  $request->transferId;         

        $transfer = DB::table('item_transfers')->where('transfer_id',$transferId)->first();
        
        DB::table('item_transfers')->where('transfer_id',$transferId)->update(['reject_read' => 1]);

        echo $transfer->rejection_reason;    
    }
//----------------------------------------------------------------------------------------------------------------------------------------
    
    public function storeReturns($viewer)
    {
        if($viewer=="approved") $approval=1;
        elseif($viewer=="rejected") $approval=-1;
        else  $approval=0;    

        $returns = DB::table('item_returns')
                       ->select('item_returns.*', 'transferer.name AS transfered_by','approver.name AS approved_by','branches.name AS branch_name','items.item_name')
                       ->leftjoin('users AS approver','item_returns.approved_accountant','=','approver.id')
                       ->leftjoin('users AS transferer','item_returns.admin','=','transferer.id')  
                       ->leftjoin('branches','item_returns.branch','=','branches.id')
                       ->leftjoin('items','item_returns.item_id','=','items.item_id')
                       ->where('approval',$approval)
                       ->orderBy('return_id','DESC')
                       ->get();

        return view('store.storeReturns',compact('returns','viewer'));                  
    }

//----------------------------------------------------------------------------------------------------------------------------------------

    public function itemReturnApprove($returnId)
    {
            $returnId = base64_decode($returnId); 
            $return = DB::table('item_returns')->where('return_id',$returnId)->first();;
            
            if($return->approval==0)
            {
                $count = $return->count;
                $item_id = $return->item_id;
                $branch =  $return->branch; 
                 
                $item = Item::where('item_id',$item_id)->first(); 
                        
                $updatedMainReturned = $item->returned+$count;            
                $updatedMainStock = $item->stock+$count; 
                
                $branchItem = BranchItem::where('item_id',$item_id)->where('branch',$branch)->first();
            
                $updatedBranchReturn = $branchItem->returned+$count;
                $updatedBranchReturnPending = $branchItem->pending_returns-$count;

                DB::table('item_returns')->where('return_id',$returnId)->update(['approval' => 1,'approved_accountant' => Auth::id()]);

                $stocker = new Stock(array(
                               'item_id' => $item_id, 
                               'dated' => Carbon::now()->toDateString(),
                               'branch_id' => $branch,                               
                               'item_count' => $count,
                               'cost' => 0,
                               'stocked_date' => Carbon::now()->toDateString(),
                               'admin_id' => Auth::id() 
                               ));

                $stocker->save();

                $item->stock=$updatedMainStock;
                $item->returned=$updatedMainReturned;
                $item->save();

                $branchItem->pending_returns=$updatedBranchReturnPending;
                $branchItem->returned=$updatedBranchReturn;
                $branchItem->save();   
                
                return redirect()->back()->with('status', 'Items stored in main store!');
          
            }//if($return->approval==0)
            return redirect()->back()->withErrors('Something went wrong!');
    }
//----------------------------------------------------------------------------------------------------------------------------------------

    public function itemReturnReject($returnId,Request $request)
    {
        $returnId = base64_decode($returnId);

        $this->validate($request, [
        'rejection_reason' => 'required|min:4',]); 

         
        $return = DB::table('item_returns')->where('return_id',$returnId)->first();
         
        if($return->approval==0)
            {
                $count = $return->count;
                $item_id = $return->item_id;
                $branch =  $return->branch; 

                $branchItem = BranchItem::where('item_id',$item_id)->where('branch',$branch)->first(); 

                $changedStock = $branchItem->stock+$count;          
                $changedPending = $branchItem->pending_returns-$count;

                DB::table('item_returns')->where('return_id',$returnId)->update(['approval' => -1,'approved_accountant' => Auth::id(),'rejection_reason' => $request->rejection_reason]);

                $branchItem->stock=$changedStock;
                $branchItem->pending_returns=$changedPending;
                $branchItem->save();

                return redirect()->back()->with('status', 'Items returned to branch store!');
            } 
         return redirect()->back()->withErrors('Something went wrong!');              
    }
//----------------------------------------------------------------------------------------------------------------------------------------

    public function returnRejections($viewer)
    {
        ($viewer=="unread")?$read=0:$read=1;

        $returns = DB::table('item_returns')
                   ->select('item_returns.*', 'transferer.name AS transfered_by','approver.name AS rejected_by','items.item_name')
                   ->leftjoin('users AS transferer','item_returns.admin','=','transferer.id')
                   ->leftjoin('users AS approver','item_returns.approved_accountant','=','approver.id') 
                   ->leftjoin('items','item_returns.item_id','=','items.item_id')
                   ->where('reject_read',$read)
                   ->where('approval',-1) 
                   ->orderBy('return_id','DESC')
                   ->get(); 

        return view('store.returnRejections',compact('returns','viewer'));          
    }
//----------------------------------------------------------------------------------------------------------------------------------------
    
    public function returnRejectRead(Request $request)
    {
                 
        $returnId =  $request->returnId;         

        $return = DB::table('item_returns')->where('return_id',$returnId)->first();
        
        DB::table('item_returns')->where('return_id',$returnId)->update(['reject_read' => 1]);

        echo $return->rejection_reason;    
    }    
//----------------------------------------------------------------------------------------------------------------------------------------

    public function storeRequestsBranch()
    {
 
        $requests = DB::table('store_requests')
                     ->select('store_requests.*', 'users.name')
                     ->leftjoin('users','store_requests.admin','=','users.id')
                     ->where('store_requests.branch',Auth::user()->branch)
                     ->orderBy('store_requests.request_id','DESC')
                     ->get();

        
       
       foreach($requests AS $index => $request)  
       {
          
            $requestItems[$index] = DB::table('store_request_items')
                             ->select('store_request_items.*', 'items.item_name')
                             ->leftjoin('items','store_request_items.item_id','=','items.item_id') 
                             ->where('request_id',$request->request_id)
                             ->orderBy('custom_id')
                             ->get();

            
       }         // echo "<pre>"; print_r($requestItems); echo "</pre>";

        return view('store.storeRequestsBranch',compact('requests','requestItems')); 
    }

//----------------------------------------------------------------------------------------------------------------------------------------

    public function storeRequestsMain($viewer)
    {
        ($viewer=="unread")?$read=0:$read=1;

        $requests = DB::table('store_requests')
                     ->select('store_requests.*', 'users.name','branches.name AS branch_name')
                     ->leftjoin('users','store_requests.admin','=','users.id')
                     ->leftjoin('branches','store_requests.branch','=','branches.id')
                     ->where('store_requests.read_status',$read)
                     ->orderBy('store_requests.request_date','DESC')
                     ->get();

        
       
       foreach($requests AS $index => $request)  
       {
          
            $requestItems[$index] = DB::table('store_request_items')
                             ->select('store_request_items.*', 'items.item_name')
                             ->leftjoin('items','store_request_items.item_id','=','items.item_id') 
                             ->where('request_id',$request->request_id)
                             ->orderBy('custom_id')
                             ->get();

            
       }         // echo "<pre>"; print_r($requestItems); echo "</pre>";

        return view('store.storeRequestsMain',compact('requests','requestItems','viewer')); 
    }

//----------------------------------------------------------------------------------------------------------------------------------------

    public function storeRequestRead(Request $request)
    {
         
        $requestItems = DB::table('store_request_items')
                         ->select('store_request_items.*', 'items.item_name')
                         ->leftjoin('items','store_request_items.item_id','=','items.item_id') 
                         ->where('request_id',$request->requestId)
                         ->orderBy('custom_id')
                         ->get();

        DB::table('store_requests')->where('request_id',$request->requestId)->update(['read_status' => 1]);
                         
        echo '<table  class="table"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>';
        foreach ($requestItems as $requestItem) 
        {
            echo '<tr><td>'.$requestItem->item_name.$requestItem->new_item.'</td><td>'.$requestItem->qty.'</td></tr>';
        }  
        echo '</tbody></table>';
         
    }

//----------------------------------------------------------------------------------------------------------------------------------------

    public function storeRequestsTransfers()
    {
         $submit =0;
         $branches = Branch::where('non_nursery',0)->orderBy('name')->get();
         return view('store.RequestsAndTransfers',compact('submit','branches'));
    }

//----------------------------------------------------------------------------------------------------------------------------------------

    public function RequestsTransfersReport(Request $request)
    {
         $submit =1;
         $branches = Branch::where('non_nursery',0)->orderBy('name')->get();

         $requestItems = DB::table('store_request_items')
                           ->select('store_request_items.*', 'items.item_name','store_requests.branch','store_requests.request_date')
                           ->selectRaw('SUM(qty) AS total_qty')
                           ->leftjoin('items','store_request_items.item_id','=','items.item_id')
                           ->leftjoin('store_requests','store_request_items.request_id','=','store_requests.request_id')
                           ->whereBetween('store_requests.request_date', [$request->start_date, $request->end_date])
                           ->where('store_requests.branch',$request->branch)
                           ->groupBy('store_request_items.item_id')
                           ->orderBy('items.item_name')
                           ->get();

        foreach($requestItems as $requestItem)
        {
            if($requestItem->item_id!=0)
            {
              $requestItem->transferApproved = DB::table('item_transfers')->where('item_id',$requestItem->item_id)->where('branch',$request->branch)
                                                ->where('approval',1)->whereBetween('dated', [$request->start_date, $request->end_date])->sum('count');
              $requestItem->transferPending = DB::table('item_transfers')->where('item_id',$requestItem->item_id)->where('branch',$request->branch)
                                                ->where('approval',0)->whereBetween('dated', [$request->start_date, $request->end_date])->sum('count');
              $requestItem->transferRejected = DB::table('item_transfers')->where('item_id',$requestItem->item_id)->where('branch',$request->branch)
                                                ->where('approval',-1)->whereBetween('dated', [$request->start_date, $request->end_date])->sum('count');
            }
            else
            {
                $requestItem->transferApproved = "Unknown Item";
                $requestItem->transferPending = " - ";
                $requestItem->transferRejected = " - ";
            }
        }                   



         return view('store.RequestsAndTransfers',compact('submit','branches','requestItems'));
    }

   
}

