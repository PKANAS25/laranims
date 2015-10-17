<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserEditFormRequest extends Request
{
     public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
             
            'role'=> 'required',
             
        ];
    }
}
