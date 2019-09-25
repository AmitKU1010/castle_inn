<?php

namespace App\Http\Controllers;

use App\Supplier;
use App\central_stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Auth;
use Log;
use App\itemName;
use DB;
use App\User;
class CentralStockController extends Controller
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

            $data =  DB::table('central_stocks')
        ->join('item_names','item_names.id','central_stocks.product_name')
        ->get(array('central_stocks.product_catagory as pd_cat','item_names.item_cat_name as item_type_name','item_names.item_name as item_name_real','central_stocks.drink_type as real_drink_size','central_stocks.stock_in as real_stock_in','central_stocks.stock_out as real_stock_out','central_stocks.stock_avail as stock_avail_real'));


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->item_name_real.'" data-original-title="Edit" class="btn btn-sm btn-info editRecord"><i class="fas fa-edit"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->item_name_real.'" data-original-title="Delete" class="btn btn-sm btn-danger deleteRecord"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('stock.central_stocks.central_stock', compact('data'));
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
