<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitUseProducts extends Model
{
    protected $table = "unit_useproducts";
    protected $fillable = [
        '_token','id', 'created_at', 'updated_at','deleted_at'
    ];
}
