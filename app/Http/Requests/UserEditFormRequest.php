<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;



class UserEditFormRequest extends Request
{
     public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        $id = $this->route()->getParameter('id');
        $id=base64_decode($id);

        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'role'=> 'required',
             
        ];
    }
}
