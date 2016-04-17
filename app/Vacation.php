<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
     public $timestamps = false;
     
     protected $guarded = ['vacation_id']; 
     protected $primaryKey = 'vacation_id';
     protected $table = 'vacation'; 
}
