<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

use App\Item;
use App\Stock;
use DB;

use File;

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
                     ->orderBy('item_name')
                     ->first();

        $pending = DB::table('item_transfers')
                                     ->where('item_id',$itemId)
                                     ->where('approval',0)
                                     ->sum('count');

        $transfered = DB::table('item_transfers')
                                     ->where('item_id',$itemId)
                                     ->where('approval',1)
                                     ->sum('count');

        // $sqlStock = mysql_query("SELECT stocks.*, admin_users.name , suppliers.name AS supplier_name,branches.name AS branch_name 
        //     FROM stocks LEFT JOIN admin_users ON(stocks.admin_id = admin_users.admin_id)   LEFT JOIN suppliers ON(stocks.supplier_id = suppliers.supplier_id)  
        //      LEFT JOIN branches ON(stocks.branch_id = branches.id) WHERE stocks.item_id = '$item_id' AND stocks.deleted='0' ORDER BY stocked_date DESC"); 
        
        $stocks = Stock::select('stocks.*','users.name AS added_by','suppliers.name AS supplier_name')
                     ->leftjoin('users','stocks.admin_id','=','users.id')
                     ->leftjoin('suppliers','stocks.supplier_id','=','suppliers.supplier_id')
                     ->where('item_id',$itemId) 
                     ->where('branch_id',0)
                     ->orderBy('stocked_date')
                     ->get();                                    
        
        if (File::exists(base_path().'/public/uploads/store_items/'.$itemId.'_s.jpg'))
        {
            $pic='/uploads/store_items/'.$itemId.'_s.jpg';
            $picBig='/uploads/store_items/'.$itemId.'.jpg';
        }
        

        return view('store.itemDetails',compact('item','pending','transfered','pic','picBig','stocks'));
    }
//----------------------------------------------------------------------------------------------------------------------------------------

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
