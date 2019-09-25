<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\itemCatagory;
use App\Purchase;
use App\PurchaseInvoice;
use App\central_stock;
use DB;

class IssueController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $request->user()->authorizeRoles(['employee', 'manager']);
     if ($request->ajax()) {
            $data = DB::table('purchase_invoices')
            ->join('suppliers','purchase_invoices.supplier_name','suppliers.id')
            ->get(array('purchase_invoices.id as purchase_invoice_table_id','purchase_invoices.purchase_invoice_number as real_purchase_invoice_number','purchase_invoices.total_purchase_amount as real_total_purchase_amount','purchase_invoices.purchase_invoice_date as real_purchase_date','suppliers.supplier_name as real_supplier_name','suppliers.contact_no as real_supplier_contact','suppliers.gstin as supplier_gst'));

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<a href="purchase/'.$data->purchase_invoice_table_id.'" data-toggle="tooltip"   target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->purchase_invoice_table_id.'" data-original-title="Delete" class="btn btn-sm btn-danger deleteRecord"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

         $supplier=supplier::all();
      $itemCatagory=itemCatagory::all();
        return view('stock.purchase.purchase') 
   ->with('supplier',$supplier)
   ->with('itemCatagory',$itemCatagory);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
           
        $data = [
        'supplier_name' => $request['supplier_name'],
        'supplier_contact_no' => $request['supplier_contact_no'],
        'supplier_address' => $request['supplier_address'],

          'supplier_email' => $request['supplier_email'],
          'purchase_invoice_date' => $request['purchase_invoice_date'],
          'gstin' => $request['gstin'],

          'purchase_discription' => $request['purchase_discription'],

          'purchase_invoice_number' => $request['purchase_invoice_number'],
        'payment_type' => $request['payment_type'],
        'total_purchase_amount' => $request['total_purchase_amount'],
        ];


              if($PurchaseInvoice_for_id=PurchaseInvoice::create($data))
              {

          $last_id = $PurchaseInvoice_for_id->id;

              }

    for($i=0;$i<count($request->item_catagory);$i++)
    {
       $savePurchase = new Purchase;
       $savePurchase->purchase_invoice_id=$last_id;
       $savePurchase->item_catagory=$request->item_catagory[$i];
       $savePurchase->item_name=$request->item_name[$i];
       $savePurchase->quantity=$request->quantity[$i];
       $savePurchase->hsn=$request->hsn[$i];
       $savePurchase->unit=$request->unit[$i];
       $savePurchase->remarks=$request->remarks[$i];


      

       $savePurchase->price=$request->price[$i];
       $savePurchase->total_amount=$request->total_amount[$i];


       $item_name_input=$request->item_name[$i];
       $quantity_input=$request->quantity[$i];

      
       $savePurchase->save();
         // $results = array();


         $results = DB::table('central_stocks')->where('item_name_from_stock',$item_name_input)->value('item_name_from_stock');

          // var_dump($results);
       
 // $results = DB::select( DB::raw("SELECT item_name_from_stock FROM central_stocks WHERE item_name_from_stock = '$item_name_input'"));
       // $results=DB::table('central_stocks')
       // ->where('item_name_from_stock',$item_name_input)
       // ->first()
       // ->item_name_from_stock;

       // var_dump($results);
       // ->pluck('item_name_from_stock');

      
       // $item_name_input=$request->item_name[$i];
      
        // echo $item_name_input;
        // echo $results;
       // return $request->item_name[$i];
        // return $item_name_input;
        // return $results;


       if($item_name_input==$results)
       {
        // return "sumit";
        // $central_stock=new central_stock;
        // $central_stock = central_stock::find($request->item_name[$i]);
        // $central_stock->item_name_from_stock=$request->item_name[$i];
        // $central_stock->stock_in=$request->quantity[$i];
        // $central_stock->save();

        $stock_in_table=DB::table('central_stocks')
       ->where('item_name_from_stock',$item_name_input)
       ->first()
       ->stock_in;
       // var_dump($stock_in_table);
       $updated_stock_in=$stock_in_table+$quantity_input;
       // var_dump($updated_stock_in);


       central_stock::where('item_name_from_stock',$item_name_input)
       ->first()
       ->update(['stock_in' => $updated_stock_in]);
       }

       else 
       {
        // return "amit";
        $central_stock=new central_stock;
        $central_stock->item_name_from_stock=$request->item_name[$i];
        $central_stock->stock_in=$request->quantity[$i];
        $central_stock->save();


       }
      }
        $validator = Validator::make($data, [
            'supplier_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails ()) {
            return response()->json ([
                'success' => false,
                'errors' => $validator->getMessageBag ()->toArray ()
            ]);
        } else  {
           
            return response()->json(['success' => true, 'message'=>'Department saved successfully']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd($id);
        $Purchase = Purchase::find($id);
        return response()->json($Purchase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('purchase_invoices')->where('id',$id)->delete();
        return response()->json(['success' => true, 'message'=>'Purchase deleted successfully.']);
    }

    public function all_fetch($id)
    {
       
  $purchase=DB::table('purchases')
       ->join('purchase_invoices','purchases.purchase_invoice_id','purchase_invoices.id')
       ->join('suppliers','suppliers.id','purchase_invoices.supplier_name')
       ->join('item_names','item_names.id','purchases.item_name')
       ->join('item_catagories','item_catagories.id','purchases.item_catagory')
      ->where('purchase_invoice_id',$id)
      ->get();
      return view('stock.purchase.purchase_details')->with('purchase',$purchase);

    }
   
}
