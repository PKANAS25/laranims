<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceType extends Model
{
    public $timestamps = false;
     
     protected $guarded = ['type_id']; 
     protected $primaryKey = 'type_id';
     protected $table = 'attendance_types';
}
