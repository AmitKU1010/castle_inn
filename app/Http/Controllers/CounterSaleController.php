<?php
namespace App\Http\Controllers;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\UnitUseProducts;
use App\UnitUseProductInvoice;
use App\Branch;
use DB; 
use App\UnitStock;
use App\ItemTypeKot;
use App\ItemKot;
use App\User;


class CounterSaleController extends Controller
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

      $user = new User;
       $auth_id= $request->user()->id;


     if ($request->ajax()) {
            $data =DB::table('unit_useproducts_invoices')
        ->where('real_branch_invoice_id',$auth_id)
        ->get();

 
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<a href="issue/'.$data->real_branch_invoice_id.'" data-toggle="tooltip"   target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->real_branch_invoice_id.'" data-original-title="Delete" class="btn btn-sm btn-danger deleteRecord"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true); 
        } 
         $branch=Branch::all();
      $itemCatagory=ItemTypeKot::all();
        return view('kot.countersale') 
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
        'real_branch_invoice_id' => $request['branch'],
        'customer_name' => $request['customer_name'],
        'customer_address' => $request['customer_address'],
        'customer_number' => $request['customer_number'],
        'customer_invoice_date' => $request['customer_invoice_date'],
        'customer_invoice_number' => $request['customer_invoice_number'],
        'total_quantity_use' => $request['total_quantity_use'],
         ];

         // dd($data);
 

        if($SaleInvoice_for_id=UnitUseProductInvoice::create($data))
          {
        $last_id = $SaleInvoice_for_id->id;
          }


    for($i=0;$i<count($request->item_catagory);$i++)
    {

      //issue
       $saveSale = new UnitUseProducts;
       $saveSale->unit_useproduct_invoice_id=$last_id;
       $saveSale->real_branch_id=$request->input('branch');

       $saveSale->pd_cat=$request->pd_cat[$i];

       $saveSale->use_item_catagory=$request->item_catagory[$i];
       $saveSale->use_item_name=$request->item_name[$i];

       $saveSale->drink_size=$request->drink_quantity[$i];

       $saveSale->use_quantity=$request->quantity[$i];
       $saveSale->use_hsn=$request->hsn[$i];
       $saveSale->use_unit=$request->unit[$i];

       $saveSale->price=$request->price[$i];
       $saveSale->total_amount=$request->total_amount[$i];
       $saveSale->save();
      //issue
      

     


       //both for unit stock and centarl stock
       $input_item_name=$request->item_name[$i];
       $input_quanntity=$request->quantity[$i];

       $input_branch_user_id=$request->input('branch');


       //both for unit stock and centarl stock


 

        $item_name_unit=DB::table('unit_stocks')
       ->where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->value('item_name_unit');

       // dd($item_name_unit);



       if($item_name_unit==$input_item_name)
       {
       $select_stock_out_table=DB::table('unit_stocks')
        ->where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->value('stock_out_unit');

       $updated_stock_out=$select_stock_out_table+$input_quanntity;

              $select_stock_avail_table=DB::table('unit_stocks')
        ->where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->value('stock_avail_unit');

       $updated_stock_avail_unit=$select_stock_avail_table-$input_quanntity;

       // dd($updated_stock_avail_unit);

  UnitStock::where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->update(['stock_out_unit' => $updated_stock_out,'stock_avail_unit' => $updated_stock_avail_unit]);
       }
       //centarl stock

    }
        $validator = Validator::make($data, [
            // 'total_sale_amount' => ['required', 'string', 'max:255'],
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
