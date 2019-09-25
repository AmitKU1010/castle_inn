<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\supplier;
use App\itemCatagory;
use App\Purchase;
use App\PurchaseInvoice;
use App\central_stock;
use DB;
use Auth;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
     // $central_stocks=central_stock::all();

       $central_stocks=DB::table('central_stocks')
      ->join('item_names','central_stocks.item_name_from_stock','item_names.id')
      ->select('central_stocks.*','item_names.item_name')
      ->get();

   return view('krishna.central_stock.get_stock')->with('central_stocks',$central_stocks);
  
    }
 public function create()
    {

    }

    

    public function show($id)
    {
        

    }
    
    

       public function destroy($id)
    {
         PurchaseInvoice::destroy($id);
        
    }

    



    public function allPurchaseList()
    {
   $Purchase_invoice=DB::table('purchase_invoices')
        ->join('suppliers','purchase_invoices.supplier_name','suppliers.id')
        ->get(array('purchase_invoices.id as purchase_invoice_table_id','purchase_invoices.purchase_invoice_number as real_purchase_invoice_number','purchase_invoices.total_purchase_amount as real_total_purchase_amount','purchase_invoices.purchase_invoice_date as real_purchase_date','suppliers.supplier_name as real_supplier_name','suppliers.contact_no as real_supplier_contact'));

        return Datatables::of($Purchase_invoice)
          ->addColumn('action', function($Purchase_invoice){
             return '<a href="add/'.$Purchase_invoice->purchase_invoice_table_id.'" class="btn btn-sm btn-success" target="_blanck"><i class="fa fa-eye"></i></a>'.' '.
                    '<a onclick="deleteData('.$Purchase_invoice->purchase_invoice_table_id.')" class="btn btn-sm btn-danger"><i class="fa fa-remove"></i></a>';

          })->make(true);
      }
}

