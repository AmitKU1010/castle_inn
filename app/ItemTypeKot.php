<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTypeKot extends Model
{
    protected $table = "item_type_kots";
    protected $fillable = [
        '_token','id', 'created_at', 'updated_at','deleted_at','item_type_kot','item_type_desc','status'
    ];
}
  