<?php
namespace App\Http\Controllers;
use App\itemName;
use App\itemCatagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
class itemNameController extends Controller
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
            $data = itemName::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-info editRecord"><i class="fas fa-edit"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-danger deleteRecord"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

        }
      $item_catagories=itemCatagory::all();
        return view('stock.item_name')->with('item_catagories',$item_catagories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

             $item_cat_name_and_id= $request->input('item_cat_name');
             // dd($item_cat_name_and_id);
             $extract=explode('$', $item_cat_name_and_id);
             $item_cat_id_extract=$extract[0];
             $item_cat_name_extract=$extract[1];

             // dd($item_cat_id_extract);
             // dd($item_cat_name_extract);


        $data = [
        'item_catagories_id' =>  $item_cat_id_extract,
        'item_cat_name' =>  $item_cat_name_extract,
        'pd_catagory'=>$request['pd_cat'],
        'item_name' => $request['item_name'],
         'hsn_code' => $request['hsn_code'],
         'specification' => $request['specification'],
         'unit' => $request['unit'],

         'price_for_nib' => $request['price_for_nib'],
         'price_for_half' => $request['price_for_half'],
         'price_for_quat' => $request['price_for_quat'],
         'price_for_other' => $request['price_for_other'],

        ];
        $validator = Validator::make($data, [
            'item_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails ()) {
            return response()->json ([
                'success' => false,
                'errors' => $validator->getMessageBag ()->toArray ()
            ]);
        } else  {
            itemName::updateOrCreate(
                ['id' => $request->_id],
                $data
            );
            return response()->json(['success' => true, 'message'=>'Department saved successfully.']);
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
        $itemName = itemName::find($id);
        return response()->json($itemName);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = itemName::find($id);
        $department->delete();
        return response()->json(['success' => true, 'message'=>'itemName deleted successfully.']);
    }
   
}
