<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Role;

use App\Http\Requests\UserEditFormRequest;

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

    
 
    public function edit($id)
    {
        $id = base64_decode($id);
        $user = User::whereId($id)->firstOrFail();
        $roles = Role::all();
        $selectedRoles = $user->roles->lists('id')->toArray();
        return view('users.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function update($id, UserEditFormRequest $request)
    {
        $id = base64_decode($id);

        $user = User::whereId($id)->firstOrFail();
        $user->name = $request->get('name');
         
        $user->save();
        $user->saveRoles($request->get('role'));


        return redirect(action('UsersController@edit', base64_encode($user->id)))->with('status', 'The user has been updated!');
    }

   
}
