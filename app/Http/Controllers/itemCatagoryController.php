<?php
namespace App\Http\Controllers;
use App\itemCatagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class itemCatagoryController extends Controller
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
            $data = itemCatagory::latest()->get();
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
        return view('stock.item_catagory', compact('item_catagory'));
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
        'pd_catagory' => $request->pd_catagory,
        'item_category_name' => $request->item_category_name,
        'item_description' => $request->item_description,

        ]; 
        $validator = Validator::make($data, [
            'item_category_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails ()) {
            return response()->json ([
                'success' => false,
                'errors' => $validator->getMessageBag ()->toArray ()
            ]);
        } else  {
            itemCatagory::updateOrCreate(
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
