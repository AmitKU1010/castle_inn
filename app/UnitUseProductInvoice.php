<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitUseProductInvoice extends Model
{
    protected $table = "unit_useproducts_invoices";
    protected $fillable = [
        '_token','id', 'created_at', 'updated_at','deleted_at','real_branch_invoice_id','customer_name','customer_address','customer_number','customer_invoice_date','customer_invoice_number','total_quantity_use','use_invoice_remarks'
    ];
}
  