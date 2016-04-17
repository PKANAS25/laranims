<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
   	 public $timestamps = false;
     
     protected $guarded = ['attendance_id']; 
     protected $primaryKey = 'attendance_id';
     protected $table = 'staff_attendance';
}
