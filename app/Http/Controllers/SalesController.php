<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\supplier;
use App\itemCatagory;
use App\Sale;
use App\Saleinvoice;
use App\central_stock;
use App\Customer;
use DB;
use Auth;
use Yajra\DataTables\DataTables;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
      $Customer=Customer::all();
      $itemCatagory=itemCatagory::all();
    return view('ab_pvt.sales.add')
   ->with('Customer',$Customer)
   ->with('itemCatagory',$itemCatagory); 
    }
 public function create()
    {
 
    }

    public function store(Request $request)
    {
            
        $data = [
        'customer_name' => $request['customer_name'],
        'customer_contact_no' => $request['customer_contact_no'],
        'customer_address' => $request['customer_address'],

          'customer_email' => $request['customer_email'],
          'gstin' => $request['gstin'],
        'payment_type' => $request['payment_type'],

          'sale_invoice_date' => $request['sale_invoice_date'],
          'sale_invoice_no' => $request['sale_invoice_no'],


          'sale_description' => $request['sale_description'],

        'total_sale_amount' => $request['total_sale_amount'],
        ];

              if($Saleinvoice_for_id=Saleinvoice::create($data))
              {
          $last_id = $Saleinvoice_for_id->id;
              }

    for($i=0;$i<count($request->item_catagory);$i++)
    {
       $saveSale = new Sale;
       $saveSale->sale_invoice_id=$last_id;
       $saveSale->customer_name=$request->input('customer_name');
       $saveSale->item_catagory=$request->item_catagory[$i];
       $saveSale->item_name=$request->item_name[$i];
       $saveSale->quantity=$request->quantity[$i];
       $saveSale->hsn=$request->hsn[$i];
       $saveSale->gst=$request->gst[$i];
       $saveSale->price=$request->price[$i];
       $saveSale->total_tax=$request->total_tax[$i];
       $saveSale->total_amount=$request->total_amount[$i];
       $saveSale->save();

       $input_item_name=$request->item_name[$i];
       $input_quanntity=$request->quantity[$i];

       $results=DB::table('central_stocks')
       ->where('item_name_from_stock',$input_item_name)
       ->value('item_name_from_stock');

       if($results==$input_item_name)
       {
       $select_stock_out_table=DB::table('central_stocks')
       ->where('item_name_from_stock',$input_item_name)
       ->first()
       ->stock_out;

       $updated_stock_out=$select_stock_out_table+$input_quanntity;

       central_stock::where('item_name_from_stock',$input_item_name)
       ->first()
       ->update(['stock_out' => $updated_stock_out]);
       }
    }

    }

    public function show($id)
    {

       // $Sale=Sale::where('sale_invoice_id',$id)->get();
      $Sale=DB::table('sales')
      ->join('customers','sales.customer_name','customers.id')
      ->join('item_names','item_names.id','sales.item_name')
      ->join('item_catagories','item_catagories.id','sales.item_catagory')
      ->where('sale_invoice_id',$id)
      ->get();

       return view('krishna.sales.saleInvoice')->with('Sale',$Sale);

       
        // return $Sale->json_encode();

        // $purchases=Purchase::where('purchase_invoice_id',$id);
        // return $purchases;

      

        //  $purchases=DB::table('users')->get()
        // return $purchases;

    // DB::table('sale_invoices')
    //     ->join('suppliers','purchase_invoices.supplier_name','suppliers.id')
    //     ->get();

     // $Sale=Sale::wheresale_invoice_id($id);
       // return view('modal.saleForm.add',$sale);
       // return $Sale;

      // $Sale=DB::table('sale')->where('sale_invoice_id',$id)->get();

      // return $Sale;


    }
    
      public function edit($id)
    {
        
         $supplier=supplier::find($id);
        return $supplier;
    }

    public function update(Request $request, $id)
    {


       $supplier=supplier::find($id);
       $supplier->supplier_name=$request['supplier_name'];
       $supplier->contact_no=$request['contact_no'];
       $supplier->email=$request['email'];
       $supplier->address=$request['address'];
       $supplier->gstin=$request['gstin'];
       $supplier->about=$request['about'];

       $supplier->update();
       return $supplier;
    
    }

       public function destroy($id)
    {
        var_dump(Saleinvoice::destroy($id));

    }


    public function allSaleList()
    {

   $Sale_invoice=DB::table('sale_invoices')
        ->join('customers','sale_invoices.customer_name','customers.id')
        ->get(array('sale_invoices.id as sale_invoice_table_id','sale_invoices.sale_invoice_no as real_sale_invoice_no','sale_invoices.total_sale_amount as real_total_sale_amount','sale_invoices.sale_invoice_date as real_sale_invoice_date','customers.customer_name as real_customer_name','customers.contact_no as real_customer_contact'));


        return Datatables::of($Sale_invoice)
          ->addColumn('action', function($Sale_invoice){
             return '<a href="add/'.$Sale_invoice->sale_invoice_table_id.'" class="btn btn-sm btn-success"  target="_blank"><i class="fa fa-eye"></i></a>'.' '. 
                    '<a onclick="deleteData('.$Sale_invoice->sale_invoice_table_id.')" class="btn btn-sm btn-danger"><i class="fa fa-remove"></i></a>';

          })->make(true);
      }
}
