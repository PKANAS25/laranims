<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EnrollFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullname'=>'required',
            'full_name_arabic'=>'required',
            'current_grade'=>'required',
            'gender'=>'required',
            'dob'=>'required|date_format:Y-m-d',
            'joining_date'=>'required|date_format:Y-m-d',
            'nationality'=>'required',
            'address'=>'required', 
            'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',
            'father_name'=>'required',
            'father_tel'=>'required',
            'father_mob'=>'required',
            'father_email'=>'required|email',
            'father_job'=>'required',
            'father_workplace'=>'required',
            'mother_name'=>'required',
            'mother_tel'=>'required',
            'mother_mob'=>'required',
            'mother_email'=>'required|email',
            'mother_job'=>'required',
            'mother_workplace'=>'required',
            'emergency_phone'=>'required',
        ];
    }
}
