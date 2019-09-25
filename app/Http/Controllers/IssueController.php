<?php
namespace App\Http\Controllers;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\itemCatagory;
use App\Sale;
use App\SaleInvoice;
use App\central_stock;
use App\Branch;
use DB;
use  App\UnitStock;
 
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
            $data =DB::table('sale_invoices')
        ->join('branchs','sale_invoices.branch_id','branchs.user_id')
        ->get(array('branchs.name as branch_name','branchs.contact_number as branch_con_no','sale_invoices.issue_invoice_date as isue_invoice_dt','sale_invoices.total_sale_amount as tot_sale_amt','sale_invoices.id as sale_invo_id'));

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<a href="issue/'.$data->sale_invo_id.'" data-toggle="tooltip"   target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->sale_invo_id.'" data-original-title="Delete" class="btn btn-sm btn-danger deleteRecord"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } 
         $branch=Branch::all();
      $itemCatagory=itemCatagory::all();
        return view('stock.issue.issue') 
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
            
        $data = [
        'branch_id' => $request['branch'],
        'branch_contact_number' => $request['branch_contact_number'],
        'issue_invoice_date' => $request['issue_invoice_date'],
        'issue_invoice_number' => $request['issue_invoice_number'],
          'issue_description' => $request['issue_description'],
        'total_sale_amount' => $request['total_sale_amount'],
         ];


        if($SaleInvoice_for_id=SaleInvoice::create($data))
          {
        $last_id = $SaleInvoice_for_id->id;
          }


    for($i=0;$i<count($request->item_catagory);$i++)
    {

      //issue
       $saveSale = new Sale;
       $saveSale->sale_invoice_id=$last_id;
       $saveSale->branch_id=$request->input('branch');

       $saveSale->pd_cat=$request->pd_cat[$i];
       $saveSale->item_catagory=$request->item_catagory[$i];
       $saveSale->item_name=$request->item_name[$i];

       $saveSale->drink_size=$request->drink_quantity[$i];

       $saveSale->quantity=$request->quantity[$i];
       $saveSale->hsn=$request->hsn[$i];
       $saveSale->unit=$request->unit[$i];
       $saveSale->remarks=$request->remarks[$i];

       $saveSale->price=$request->price[$i];
       $saveSale->total_tax=$request->total_tax[$i];
       $saveSale->total_amount=$request->total_amount[$i];
       $saveSale->save();
      //issue
      

     


       //both for unit stock and centarl stock
       $input_item_name=$request->item_name[$i];
       $input_quanntity=$request->quantity[$i];

       $input_branch_user_id=$request->input('branch');


       //both for unit stock and centarl stock

        $item_name_real=DB::table('central_stocks')->where([
    ['product_catagory', '=', $request->pd_cat[$i]],
    ['product_type', '=', $request->item_catagory[$i]],['product_name', '=', $request->item_name[$i]],['drink_type', '=', $request->drink_quantity[$i]],])->value('product_name');

        // dd($item_name_real);
 
 


       //centarl stock
       // $results=DB::table('central_stocks')
       // ->where('item_name_from_stock',$input_item_name)
       // ->value('item_name_from_stock');


       if($item_name_real==$input_item_name)
       {

       $select_stock_out_table=DB::table('central_stocks')
      ->where([
    ['product_catagory', '=', $request->pd_cat[$i]],
    ['product_type', '=', $request->item_catagory[$i]],['product_name', '=', $request->item_name[$i]],['drink_type', '=', $request->drink_quantity[$i]],])
       ->value('stock_out');

       $updated_stock_out=$select_stock_out_table+$input_quanntity;


        $select_stock_avail_table=DB::table('central_stocks')
      ->where([
    ['product_catagory', '=', $request->pd_cat[$i]],
    ['product_type', '=', $request->item_catagory[$i]],['product_name', '=', $request->item_name[$i]],['drink_type', '=', $request->drink_quantity[$i]],])
       ->value('stock_avail');
       

       $updated_stock_avail_unit=$select_stock_avail_table-$input_quanntity;

       // dd($updated_stock_avail_unit);

       central_stock::where([
    ['product_catagory', '=', $request->pd_cat[$i]],
    ['product_type', '=', $request->item_catagory[$i]],['product_name', '=', $request->item_name[$i]],['drink_type', '=', $request->drink_quantity[$i]],])
       ->update(['stock_out' => $updated_stock_out,'stock_avail' => $updated_stock_avail_unit]);
       }
       //centarl stock

 
      //unit stock details
       $item_name_unit=DB::table('unit_stocks')
       ->where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->value('item_name_unit');

       // dd($item_name_unit);

       //  $results_unit_branch_user_id=DB::table('unit_stocks')
       // ->where('branch_id_unit',$input_branch_user_id)
       // ->value('branch_id_unit');



   if($item_name_unit==$input_item_name)
       {

       $stockin_unit_table=DB::table('unit_stocks')
       ->where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->value('stockin_unit');


       $stockavailable_unit_table=DB::table('unit_stocks')
       ->where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->value('stock_avail_unit');

       // dd($stockavailable_unit_table);


       $updatedstock_in_unit=$stockin_unit_table+$input_quanntity;
       // dd($updatedstock_in_unit);

       if($stockavailable_unit_table=='')
       {
       $updated_stock_avail_unit=0+$input_quanntity;
       // dd($updated_stock_avail_unit);
       }
       else
       {
       $updated_stock_avail_unit=$stockavailable_unit_table+$input_quanntity;
       // dd($updated_stock_avail_unit);


       }


       // dd($input_item_name);

       UnitStock::where([
    ['pd_cat_unit', '=', $request->pd_cat[$i]],
    ['item_catagory_unit', '=', $request->item_catagory[$i]],['item_name_unit', '=', $request->item_name[$i]],['drink_size', '=', $request->drink_quantity[$i]],['branch_id_unit', '=', $input_branch_user_id],])
       ->update(['stockin_unit' => $updatedstock_in_unit,'stock_avail_unit' => $updated_stock_avail_unit]);


       }

       else 
       {

         //unit stock details
       $UnitStock = new UnitStock;
       $UnitStock->sale_invoice_id_unit=$last_id;
       $UnitStock->branch_id_unit=$request->input('branch');

       $UnitStock->pd_cat_unit=$request->pd_cat[$i];
       $UnitStock->item_catagory_unit=$request->item_catagory[$i];
       
       $UnitStock->item_name_unit=$request->item_name[$i];

       $UnitStock->drink_size=$request->drink_quantity[$i];


       $UnitStock->stockin_unit=$request->quantity[$i];
       $UnitStock->stock_avail_unit=$request->quantity[$i];
       $UnitStock->hsn_unit=$request->hsn[$i];
       $UnitStock->unit_unit=$request->unit[$i];
       $UnitStock->save();
      //unit stock details

       }



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
