<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = false;

    protected $guarded = ['invoice_id'];
    protected $primaryKey = 'invoice_id';
}
