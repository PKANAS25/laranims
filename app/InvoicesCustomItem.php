<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicesCustomItem extends Model
{
    public $timestamps = false;
    protected $table='invoices_custom_items';
    protected $guarded = ['custom_id'];
}
