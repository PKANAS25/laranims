<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
     public $timestamps = false;
     
     protected $guarded = ['id']; 
     protected $table = 'branches';
}
