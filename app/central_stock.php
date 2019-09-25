<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class central_stock extends Model
{
    protected $table = "central_stocks";
    protected $fillable = [
        '_token','id', 'created_at', 'updated_at','deleted_at','item_name_from_stock','product_catagory','product_type','product_name','drink_type','stock_in','stock_out','stock_avail'
    ];
}
  