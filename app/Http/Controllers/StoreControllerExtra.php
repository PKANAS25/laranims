<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Carbon\Carbon;
use App\Item;

use File;
use Image;
use Validator;

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
 public function itemAddCheck(Request $request)
    {
          
          $item_name = $request->get('item_name'); 

          $count = Item::whereRAW("item_name LIKE '%".$item_name."%'")
                            ->count();
            

        if($count)
        return response()->json(['valid' => 'false', 'message' => 'Name exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
         
    }
   

//----------------------------------------------------------------------------------------------------------------------------------------
 public function editItem($itemId)
    {
          
          $itemId = base64_decode($itemId); 

          $categories = DB::table('categories')->orderBy('category')->get(); 

          $item = Item::select('items.*','categories.category AS category_name')
                        ->leftjoin('categories','categories.category_id','=','items.category')
                        ->where('item_id',$itemId)
                        ->first();
        $pic="";
        if (File::exists(base_path().'/public/uploads/store_items/'.$itemId.'_s.jpg'))
           { $pic='/uploads/store_items/'.$itemId.'_s.jpg';}
            

        return view('store.editItem',compact('item','categories','pic'));
         
    }
//----------------------------------------------------------------------------------------------------------------------------------------
 public function itemEditCheck(Request $request)
    {
          
          $item_name = $request->get('item_name'); 
          $itemId = $request->get('itemId'); 

          $count = Item::whereRAW("item_name LIKE '%".$item_name."%'")->where('item_id','!=',$itemId)->count();
            

        if($count)
        return response()->json(['valid' => 'false', 'message' => 'Name exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
         
    }
//----------------------------------------------------------------------------------------------------------------------------------------
   
 public function editItemSave(Request $request,$itemId)
    {
       $itemId = base64_decode($itemId);

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
           

           $item = Item::where('item_id',$itemId)->first();
           
           $item->item_name = $request->item_name; 
           $item->category = $request->category;
           $item->product_code = $request->product_code;                              
           $item->description = $request->description;
           $item->price = $request->price; 

           $item->save(); 

       
           if($request->file('fileToUpload'))
            {
             
             $imageName = $itemId.'.jpg';
             $imageNameSmall = $itemId.'_s.jpg';
             
             if (File::exists(base_path().'/public/uploads/store_items/'.$imageName))
                 File::delete(base_path().'/public/uploads/store_items/'.$imageName);
             
             if (File::exists(base_path().'/public/uploads/store_items/'.$imageNameSmall))
                 File::delete(base_path().'/public/uploads/store_items/'.$imageNameSmall);


             Image::make($request->file('fileToUpload'))->save(base_path().'/public/uploads/store_items/'.$imageName);
             Image::make($request->file('fileToUpload'))->resize(175, 200)->save(base_path().'/public/uploads/store_items/'.$imageNameSmall);
            } 

            return redirect()->action('StoreController@itemView',[base64_encode($itemId)])->with('status', 'Item editted successfully!');
        }

                         
    }  
//----------------------------------------------------------------------------------------------------------------------------------------
 public function categories()
    { 
     
     $categories = DB::table('categories')->orderBy('category')->get();  

     return view('store.categories',compact('categories'));
         
    }  
//----------------------------------------------------------------------------------------------------------------------------------------
 public function categoryAddCheck(Request $request)
    {
          
          $category = $request->get('category'); 

          $count = DB::table('categories')->whereRAW("category LIKE '%".$category."%'")
                            ->count();
            

        if($count)
        return response()->json(['valid' => 'false', 'message' => 'Category exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
         
    }      
//----------------------------------------------------------------------------------------------------------------------------------------   

 public function addCategory(Request $request)
    { 
      $this->validate($request, [
        'category' => 'required',]); 

       DB::table('categories')->insert([
                  'category' => $request->category,
                  'description' => $request->description
                  ]);  

     return redirect()->action('StoreControllerExtra@categories')->with('status', 'Category successfully added!');
         
    }  
//--------------------------------------------------------------------------------------------------------------------------------------------
public function editCategory($categoryId)
    { 
     
     $categoryId = base64_decode($categoryId);
     
     $category = DB::table('categories')->where('category_id',$categoryId)->first();  

     return view('store.editCategory',compact('category'));
         
    }  
//----------------------------------------------------------------------------------------------------------------------------------------
 public function categoryEditCheck(Request $request)
    {
          
          $category = $request->get('category'); 
          $categoryId = $request->get('categoryId'); 

          $count = DB::table('categories')->whereRAW("category LIKE '%".$category."%'")->where('category_id','!=',$categoryId)->count();
            

        if($count)
        return response()->json(['valid' => 'false', 'message' => 'Category exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
         
    }
//--------------------------------------------------------------------------------------------------------------------------------------------
 public function editSaveCategory(Request $request,$categoryId)
    {
       $categoryId = base64_decode($categoryId);

        $validator = Validator::make($request->all(),[ 
            'category' => 'required'
         ]    
        );
        


        if($validator->fails()){
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {  
           
             DB::table('categories')->where('category_id',$categoryId)->update(['category' => $request->category,'description' => $request->description]);  
           
             return redirect()->action('StoreControllerExtra@categories')->with('status', 'Category successfully editted!');
        }

                         
    }  
//----------------------------------------------------------------------------------------------------------------------------------------
 public function suppliers()
    { 
     
     
     $suppliers = DB::table('suppliers')
                    ->select('suppliers.*',DB::raw("(SELECT SUM(item_count*cost)  FROM stocks WHERE deleted = '0'  AND stocks.supplier_id = suppliers.supplier_id) AS volume"))
                    ->orderBy('name')->get();  

     return view('store.suppliers',compact('suppliers'));
         
    }      

//----------------------------------------------------------------------------------------------------------------------------------------

 public function supplierAddCheck(Request $request)
    {
          
          $name = $request->get('name'); 

          $count = DB::table('suppliers')->whereRAW("name LIKE '%".$name."%'")
                            ->count();
            

        if($count)
        return response()->json(['valid' => 'false', 'message' => 'Supplier exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
         
    } 
//----------------------------------------------------------------------------------------------------------------------------------------

   public function addSupplier(Request $request)
    { 
      $this->validate($request, [
        'name' => 'required',
        'ofc1' => 'required',
        'contact_person' => 'required']); 

       DB::table('suppliers')->insert([
                  'name' => $request->name,
                  'mob1' => $request->mob1,
                  'ofc1' => $request->ofc1,
                  'mob2' => $request->mob2,
                  'ofc2' => $request->ofc2,
                  'email' => $request->email,
                  'contact_person' => $request->contact_person,
                  'address' => $request->address,
                  ]);  

       

     return redirect()->action('StoreControllerExtra@suppliers')->with('status', 'Supplier successfully added!');
         
    }  

//--------------------------------------------------------------------------------------------------------------------------------------------
public function editSupplier($supplierId)
    { 
     
     $supplierId = base64_decode($supplierId);
     
     $supplier = DB::table('suppliers')->where('supplier_id',$supplierId)->first();  

     return view('store.editSupplier',compact('supplier'));
         
    }  
//--------------------------------------------------------------------------------------------------------------------------------------------

public function supplierEditCheck(Request $request)
    {
          
          $name = $request->get('name'); 
          $supplierId = $request->get('supplierId'); 

          $count = DB::table('suppliers')->whereRAW("name LIKE '%".$name."%'")->where('supplier_id','!=',$supplierId)->count();
            

        if($count)
        return response()->json(['valid' => 'false', 'message' => 'Supplier exists in the database. Make sure you are not repeating','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => ' ','available'=>'true']);
         
    }
//--------------------------------------------------------------------------------------------------------------------------------------------   
public function editSaveSupplier(Request $request,$supplierId)
    {
       $supplierId = base64_decode($supplierId);

        $validator = Validator::make($request->all(),[ 
            'name' => 'required',
            'ofc1' => 'required',
            'contact_person' => 'required'
         ]    
        );
        


        if($validator->fails()){
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        else
        {  
           
             DB::table('suppliers')->where('supplier_id',$supplierId)
                                    ->update([
                                                  'name' => $request->name,
                                                  'mob1' => $request->mob1,
                                                  'ofc1' => $request->ofc1,
                                                  'mob2' => $request->mob2,
                                                  'ofc2' => $request->ofc2,
                                                  'email' => $request->email,
                                                  'contact_person' => $request->contact_person,
                                                  'address' => $request->address
                                            ]);  
           
             return redirect()->action('StoreControllerExtra@suppliers')->with('status', 'Supplier successfully editted!');
        }

                         
    }  
//----------------------------------------------------------------------------------------------------------------------------------------

}
