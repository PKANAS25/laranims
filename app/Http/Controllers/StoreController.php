<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

use App\Item;
use App\Stock;
use App\Branch;
use DB;

use File;

use Validator;
use Carbon\Carbon;

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

    }
//----------------------------------------------------------------------------------------------------------------------------------------


}
