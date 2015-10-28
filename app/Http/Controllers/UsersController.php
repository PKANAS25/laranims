<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Role;

use DB;
use Auth;

use App\Http\Requests\UserEditFormRequest;
use App\Http\Requests\UserRegisterRequest;

class UsersController extends Controller
{
    
    public function index()
    {
        //$users = User::where('active',1)
        //->where('id','!=','1')
        //->get();

        $users = User::all();
        return view('users.index',compact('users'));
    }

  //------------------------------------------------------------------------------------------------------------------------------------------  
 
    public function edit($id)
    {
        $id = base64_decode($id);
        $user = User::whereId($id)->firstOrFail();
        $roles = Role::all();
        $selectedRoles = $user->roles->lists('id')->toArray();
        return view('users.edit', compact('user', 'roles', 'selectedRoles'));
    }
//------------------------------------------------------------------------------------------------------------------------------------------
    public function update($id, UserEditFormRequest $request)
    {
        $id = base64_decode($id);

        $user = User::whereId($id)->firstOrFail();
        $user->name = $request->get('name');
         
        $user->save();
        $user->saveRoles($request->get('role'));


        return redirect(action('UsersController@edit', base64_encode($user->id)))->with('status', 'The user has been updated!');
    }

//------------------------------------------------------------------------------------------------------------------------------------------

     public function duplicateCheck(Request $request)
    {
          

          if($request->get('email'))
            $count = User::where('email',$request->get('email'))->count();
            
        
        if($count)
        return response()->json(['valid' => 'false', 'message' => 'Username is taken','available'=>'false']);

        else
        return response()->json(['valid' => 'true', 'message' => '','available'=>'true']);
         
    }
//------------------------------------------------------------------------------------------------------------------------------------------

    public function add(UserRegisterRequest $request)
    {
       $branche = DB::table('branches')->where('id',$request->get('branch'))->first();

       $newUser = new User(array(
            'name' => ucwords(strtolower($request->get('name'))),
            'branch' => $request->get('branch'),
            'branch_name' => $branche->name,
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'added_by' => Auth::id()            
        ));
        
        $newUser->save(); 
         

        return redirect('users/register')->with('status', 'New Admin added!');
    }

//------------------------------------------------------------------------------------------------------------------------------------------

   public function branchLoader(Request $request)
    {
        $adminType = $request->adminType;
        
        if($adminType==1)
        {    
            $branches = DB::table('branches')
                        ->where('non_nursery',0) 
                        ->orderBy('name')
                        ->get();
                ?>
                    <select class="form-control" name="branch"  data-fv-notempty="true" >
                    <?php  
                        foreach($branches AS $branch) { ?>
                        <option value="<?php echo $branch->id; ?>" ><?php echo $branch->name; ?></option>
                    <?php } ?> 
                    </select> 
                <?php
        }

        elseif($adminType==2)
        {  ?>
            <select class="form-control" name="branch" data-fv-notempty="true" > 
            <option value="0">All Branches</option> 
            </select>
        <?php 
        }
        else
        {  ?>
            <select class="form-control" name="branch" data-fv-notempty="true" > 
            <option value="">Please choose</option>
            </select>
        <?php 
        }
    }
//------------------------------------------------------------------------------------------------------------------------------------------



}
