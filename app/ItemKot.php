<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ItemKot extends Model
{
    protected $table = "items_kots";
    protected $fillable = [
        '_token','id', 'created_at', 'updated_at','deleted_at','item_type_id_kots','item_type_kots','item_name_kot','hsn_code_kot','item_price_nib','item_price_half','item_price_quater','item_price_other'
    ];
}
  