<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmployeeAddRequest extends Request
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
            'fname'=>'required',
            'lname'=>'required',
            'designation'=>'required',
            'designation_mol'=>'required',
            'bonus_category'=>'required',
            'visa_under'=>'required',
            'working_under'=>'required',
            'qualification'=>'required',
            'specialization'=>'required',
            'joining_date'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'passport_no'=>'required',
            'passport_expiry'=>'required|date_format:Y-m-d',
            'person_code'=>'required',
            'labour_card_no'=>'required',
            'labour_card_expiry'=>'required|date_format:Y-m-d',
            'visa_issue'=>'required|date_format:Y-m-d',
            'visa_expiry'=>'required|date_format:Y-m-d',
            'gender'=>'required',
            'dob'=>'required',
            'nationality'=>'required',
            'religion'=>'required',
            'mobile'=>'required',
            'email'=>'required|email',
            'personal_email'=>'required|email',
            'fileToUpload'=>'image|max:615|mimes:jpeg,jpg',
        ];
    }
}
