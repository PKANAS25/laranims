<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Branch; 
use Session;
use Auth;
class BranchingController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function branching()
    {
       if(Auth::user()->admin_type==1)
        return redirect()->intended('home')->with('warning', 'Tried to enter restricted area!');

       $branches = Branch::orderBy('non_nursery')->orderBy('name')->get();
       return view('branching',compact('branches'));
    }


    public function updateCurrentBranch(Request $request)
    {

       $branch = $request->get('branch');
       $branches = Branch::where('id',$branch)->first();
       $branchName =$branches->name;
       $branchType = $branches->non_nursery;
       
       $user=Auth::user();
       $user->branch = $branch;
       $user->branch_name = $branchName;
       $user->branch_type = $branchType;
       $user->save();

       //Session::put('currentBranchName',$branchName );

       return redirect()->intended('home');
    }

     
}
