<?php

namespace App\Http\Controllers;

use App\Supplier;
use App\UnitStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Auth;
use Log;
use App\itemName;
use DB;
use App\User;
class UnitStockController extends Controller
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

       // dd($auth_id);

        if ($request->ajax()) {
            $data =  DB::table('unit_stocks')
        ->join('item_names','item_names.id','unit_stocks.item_name_unit')
        ->where('unit_stocks.branch_id_unit',$auth_id)
        ->get(array('unit_stocks.pd_cat_unit as pd_cat_real','item_names.item_cat_name as real_item_catagory','item_names.item_name as real_item_name','item_names.hsn_code as real_hsncode','unit_stocks.stockin_unit as real_stock_in','unit_stocks.stock_out_unit as real_stock_out','unit_stocks.stock_avail_unit as real_stock_avail'));

         //    $data=DB::table('unit_stocks')
         // ->where('unit_stocks.branch_id_unit',$auth_id)
         // ->get(array('unit_stocks.stockin_unit as real_stock_in','unit_stocks.stock_out_unit as real_stock_out','unit_stocks.stock_avail_unit as real_stock_avail'));


        // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->real_item_name.'" data-original-title="Edit" class="btn btn-sm btn-info editRecord"><i class="fas fa-edit"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->real_item_name.'" data-original-title="Delete" class="btn btn-sm btn-danger deleteRecord"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('stock.unit_stocks.unit_stock', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return response()->json($supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Supplier::find($id);
        $department->delete();
        return response()->json(['success' => true, 'message'=>'Supplier deleted successfully.']);
    }
   
}
