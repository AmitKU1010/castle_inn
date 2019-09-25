<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
      // use SoftDeletes;
	protected $guarded = ['_token','id', 'created_at', 'updated_at','deleted_at'];
	protected $dates = ['deleted_at'];
}
