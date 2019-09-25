<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitStock extends Model
{
    protected $table = "unit_stocks";
    protected $fillable = [
        '_token','id', 'created_at', 'updated_at','deleted_at'
    ];
}
