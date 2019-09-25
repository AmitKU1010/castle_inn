<?php
namespace App\Http\Controllers;
use App\ItemTypeKot;
use App\ItemKot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
class ItemController extends Controller
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
            $data = ItemKot::latest()->get();
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
            $item_type = ItemTypeKot::all();

        return view('kot.items_kot')->with('item_type',$item_type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
 
    public function store(Request $request)
    {


             $item_cat_name_and_id= $request->input('item_type_kots');
             // dd($item_cat_name_and_id);
             $extract=explode('$', $item_cat_name_and_id);
             $item_cat_id_extract=$extract[0];
             $item_cat_name_extract=$extract[1];

              // var_dump($item_cat_id_extract);
              // dd($item_cat_name_extract);

  


        $data = [
        'item_type_id_kots' => $item_cat_id_extract,
        'item_type_kots' => $item_cat_name_extract,
        'item_name_kot' => $request->item_name_kot,
        'hsn_code_kot' => $request->hsn_code_kot,
        'item_price_nib' => $request->item_price_nib,
        'item_price_half' => $request->item_price_half,
        'item_price_quater' => $request->item_price_quater,
        'item_price_other' => $request->item_price_other,
        
        ];
        $validator = Validator::make($data, [
            'item_name_kot' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails ()) {
            return response()->json ([
                'success' => false,
                'errors' => $validator->getMessageBag ()->toArray ()
            ]);
        } else  {
            ItemKot::updateOrCreate(
                ['id' => $request->_id],
                $data
            );
            return response()->json(['success' => true, 'message'=>'Product saved successfully.']);
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
        $itemCatagory = itemCatagory::find($id);
        return response()->json($itemCatagory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itemCatagory = itemCatagory::find($id);
        $itemCatagory->delete();
        return response()->json(['success' => true, 'message'=>'Product Type deleted successfully.']);
    }
   
}
