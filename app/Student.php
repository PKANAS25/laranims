<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	public $timestamps = false;

    protected $guarded = ['student_id']; 
    protected $primaryKey = 'student_id';

   /* protected $fillable = ['full_name', 'full_name_arabic', 'current_grade','branch' ,'gender' ,'dob'  ,'joining_date' ,
    'nationality','address','map' ,'father_name','father_tel','father_mob','father_email','father_job' ,'father_workplace' ,
    'mother_name','mother_tel','mother_mob','mother_email','mother_job','mother_workplace','emergency_phone','authorities','enrolled_by','enrolled_on'];*/

    
}
?>