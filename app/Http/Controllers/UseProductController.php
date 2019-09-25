<?php
namespace App\Http\Controllers;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\itemCatagory;
use App\Sale;
use App\central_stock;
use App\Branch;
use DB;
use App\UnitStock;
use Auth; 
use App\User;
use App\UnitUseProducts;
use App\UnitUseProductInvoice;


 
class UseProductController extends Controller
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

       $user = new User;
       $auth_id= $request->user()->id;
        // $request->user()->authorizeRoles(['employee', 'manager']);
     if ($request->ajax()) {
            $data =DB::table('unit_useproducts_invoices')
        ->join('branchs','unit_useproducts_invoices.real_branch_invoice_id','branchs.user_id')
        ->where('unit_useproducts_invoices.real_branch_invoice_id',$auth_id)
        ->get(array('branchs.name as branch_name','unit_useproducts_invoices.use_invoice_date as pd_use_dt','unit_useproducts_invoices.use_invoice_number as invoiceno','unit_useproducts_invoices.total_quantity_use as use_quantity','unit_useproducts_invoices.use_invoice_remarks as use_remarks'));

            return Datatables::of($data) 
                ->addIndexColumn()
                 ->addColumn('action', function ($data) {
                    $btn = '<a href="issue/'.$data->use_remarks.'" data-toggle="tooltip"   target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->use_remarks.'" data-original-title="Delete" class="btn btn-sm btn-danger deleteRecord"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } 
         $branch=Branch::all();
      $itemCatagory=itemCatagory::all();
        return view('stock.unit_use_product.use_product') 
   ->with('branch',$branch)
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

     //for validation

       $validator = Validator::make($request->all(), [
           'use_invoice_number' => 'required',
           'use_invoice_date' => 'required',
       ]);

          if ($validator->fails ()) {
            return response()->json ([
                'success' => false,
                'errors' => $validator->getMessageBag ()->toArray ()
            ]);
        }

     //for validation

       $saveUnitUseProductinvoice = new UnitUseProductInvoice;
       $saveUnitUseProductinvoice->real_branch_invoice_id=$request->auth_id;
       $saveUnitUseProductinvoice->use_invoice_date=$request->use_invoice_date;
       $saveUnitUseProductinvoice->use_invoice_number=$request->use_invoice_number;
       $saveUnitUseProductinvoice->total_quantity_use=$request->total_quantity;
       $saveUnitUseProductinvoice->use_invoice_remarks=$request->use_invoice_remarks;

      if($saveUnitUseProductinvoice->save())
      {
        $last_id = $saveUnitUseProductinvoice->id;
      }
 
    for($i=0;$i<count($request->use_item_catagory);$i++)

    { 

      //issue
       $saveUnitUseProducts = new UnitUseProducts;
       $saveUnitUseProducts->unit_useproduct_invoice_id=$last_id;
       $saveUnitUseProducts->real_branch_id=$request->input('auth_id');

       $saveUnitUseProducts->use_item_catagory=$request->use_item_catagory[$i];
       $saveUnitUseProducts->use_item_name=$request->use_item_name[$i];
       $saveUnitUseProducts->use_quantity=$request->use_quantity[$i];
       $saveUnitUseProducts->use_hsn=$request->use_hsn[$i];
       $saveUnitUseProducts->use_unit=$request->use_unit[$i];
       $saveUnitUseProducts->remarks=$request->remarks[$i];

       $saveUnitUseProducts->save();
      //issue
      

     


       //both for unit stock and centarl stock
       $input_item_name=$request->use_item_name[$i];
       $input_quanntity=$request->use_quantity[$i];

       $branch_id_unit_input=$request->input('auth_id');

       //both for unit stock and centarl stock



 
      //unit stock details
       $results_unit=DB::table('unit_stocks')
       ->where([
    ['item_name_unit', '=', $input_item_name],
    ['branch_id_unit', '=', $branch_id_unit_input],
                    ])
       ->value('item_name_unit');

 
       $results_branch_id_unit=DB::table('unit_stocks')
        ->where([
    ['item_name_unit', '=', $input_item_name],
    ['branch_id_unit', '=', $branch_id_unit_input],
                    ])
       ->value('branch_id_unit');


      if($results_unit==$input_item_name && $results_branch_id_unit==$branch_id_unit_input)
       {
       $select_stock_out_table=DB::table('unit_stocks')
       ->where([
    ['item_name_unit', '=', $input_item_name],
    ['branch_id_unit', '=', $branch_id_unit_input],
                    ])
       ->first()
       ->stock_out_unit;
 
        $select_stock_availbale_table=DB::table('unit_stocks')
       ->where([
    ['item_name_unit', '=', $input_item_name],
    ['branch_id_unit', '=', $branch_id_unit_input],
                    ])
       ->first()
       ->stock_avail_unit;

       $updated_stock_out=$select_stock_out_table+$input_quanntity;

       $updated_stock_avail=$select_stock_availbale_table-$input_quanntity;


       UnitStock::where([
    ['item_name_unit', '=', $input_item_name],
    ['branch_id_unit', '=', $branch_id_unit_input],
                    ])
       ->update(['stock_out_unit' => $updated_stock_out,'stock_avail_unit' => $updated_stock_avail]);
       }

    }

      
        
           
            return response()->json(['success' => true, 'message'=>'Department saved successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        DB::table('sale_invoices')->where('id',$id)->delete();
        return response()->json(['success' => true, 'message'=>'Purchase deleted successfully.']);
    }

    public function all_fetch($id)
    {
     $issue=DB::table('sales')
       ->join('sale_invoices','sales.sale_invoice_id','sale_invoices.id')
       ->join('item_names','item_names.id','sales.item_name')
       ->join('item_catagories','item_catagories.id','sales.item_catagory')
      ->where('sale_invoice_id',$id)
      ->get();
      return view('stock.issue.issue_details')->with('issue',$issue);

    }
   
}
