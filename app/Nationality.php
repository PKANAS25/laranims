<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    protected $table='nationality';
    protected $guarded = ['nation_id'];
}
