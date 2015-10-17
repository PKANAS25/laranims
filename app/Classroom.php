<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    public $timestamps = false;

    protected $guarded = ['class_id'];
}
