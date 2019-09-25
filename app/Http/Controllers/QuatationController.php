<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\MeasurementData;
use App\MeasurementQuatation;
use App\Customer;
use App\quotation;
use DB;
use Auth;
use Yajra\DataTables\DataTables;
 
class QuatationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
      $Customer=Customer::all();
    return view('krishna.quatation.createQuatation')
    ->with('Customer',$Customer);
    }
 public function create()
    {

    }

    public function store(Request $request)
    {
        $data = [
        'customer_name' => $request['customer_name'],
        'contact_no' => $request['contact_no'],
        'email' => $request['email'],

          'address' => $request['address'],
          'about' => $request['about'],
        'measurement_onfirmed_by' => $request['measurement_onfirmed_by'],

          'measurement_taken_by' => $request['measurement_taken_by'],
          'measurement_received_for_production' => $request['measurement_received_for_production'],

          'measurement_amount' => $request['measurement_amount'],
 
        'name_of_qua_for_measu' => $request['name_of_qua_for_measu'],
        ];

              if($MeasurementQuatation_for_id=MeasurementQuatation::create($data))
              {
          $MeasurementQuatation_for_id = $MeasurementQuatation_for_id->id;
              }

    for($i=0;$i<count($request->window_des);$i++)
    {
       $MeasurementData = new MeasurementData;

       $MeasurementData->measurement_id=$MeasurementQuatation_for_id;


      $real = $request->file('drawing');

      $image=$real[$i];

      $filename = time().'.'.$image->getClientOriginalExtension();

      // Image::make($image)->resize(300, 300)->save( storage_path('/images' . $filename ) );

       $destinationPath = public_path('/images');

       $image->move($destinationPath, $filename);

       $MeasurementData->drawing=$filename;

       $MeasurementData->window_des=$request->window_des[$i];

       $MeasurementData->width=$request->width[$i];

       $MeasurementData->height=$request->height[$i];

       $MeasurementData->area=$request->area[$i];

       $MeasurementData->quantity=$request->quantity[$i];

       $MeasurementData->glass=$request->glass[$i];

       $MeasurementData->drawing_description=$request->drawing_description[$i];
       $MeasurementData->save();
    }

    }

    public function show($id)
    {
      $all_view=DB::table('measurement_datas')
        ->join('measurement_quatations','measurement_quatations.id','measurement_datas.measurement_id')
        ->join('customers','customers.id','measurement_quatations.customer_name')
        ->where('measurement_quatations.id','=',$id);

        $all_view=$all_view->select('measurement_quatations.*','measurement_datas.*','customers.customer_name as customerName','customers.contact_no as contactNo','customers.email')->get();

          
        $viewData['MeasurementQuatationView']= json_decode(json_encode($all_view), true);

      return view('krishna.quatation.qutation_invoice_view',$viewData);
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

   public function create_Quotation(Request $request)
   {
    for($i=0;$i<count($request->window_design);$i++)
    {

      $quotation=new quotation;

// for image starts
      $real = $request->file('window_design');

      $image=$real[$i];

      $filename = time().'.'.$image->getClientOriginalExtension();

      // Image::make($image)->resize(300, 300)->save( storage_path('/images' . $filename ) );

       $destinationPath = public_path('/images');

       $image->move($destinationPath, $filename);

      $quotation->window_design=$filename;

// for image ends

      $quotation->meaurement_id_from_md_table=$request->id_repeat;
      
      $quotation->windows_type=$request->windows_type[$i];

      $quotation->width=$request->width[$i];

      $quotation->height=$request->height[$i];

      $quotation->area=$request->area[$i];

      $quotation->unit=$request->unit[$i];

      $quotation->total_area=$request->total_area[$i];

      $quotation->glass=$request->glass[$i];

      $quotation->unit_rate=$request->unit_rate[$i];

      $quotation->total_amount=$request->total_amount[$i];

      $quotation->hardware=$request->hardware[$i];

      $quotation->remarks=$request->remarks[$i];


      $quotation->save();


    }
    }


    public function view_quotation($id)
    {
      
       $qutation_all_view=DB::table('measurement_datas')
        ->join('measurement_quatations','measurement_quatations.id','measurement_datas.measurement_id')
        ->join('customers','customers.id','measurement_quatations.customer_name')
        ->join('quotations','quotations.meaurement_id_from_md_table','measurement_datas.measurement_id')
        ->where('quotations.meaurement_id_from_md_table','=',$id);

        // dd($qutation_all_view);

        $qutation_all_view=$qutation_all_view->select('measurement_quatations.*','measurement_datas.*','customers.customer_name as customerName','customers.contact_no as contactNo','customers.email as customer_email','customers.address as customer_address','quotations.*')
        ->groupby('quotations.window_design')
        ->get();



        $viewData['QuatationView']= json_decode(json_encode($qutation_all_view), true);



      return view('krishna.quatation.view_quotation_to_customer',$viewData);
    }

    public function view_quotation_factory($id)
    {

        $qutation_all_view=DB::table('measurement_datas')
        ->join('measurement_quatations','measurement_quatations.id','measurement_datas.measurement_id')
        ->join('customers','customers.id','measurement_quatations.customer_name')
        ->join('quotations','quotations.meaurement_id_from_md_table','measurement_datas.measurement_id')
        ->where('quotations.meaurement_id_from_md_table','=',$id);

        // dd($qutation_all_view);

        $qutation_all_view=$qutation_all_view->select('measurement_quatations.*','measurement_datas.*','customers.customer_name as customerName','customers.contact_no as contactNo','customers.email as customer_email','customers.address as customer_address','quotations.*')
        ->groupby('quotations.window_design')
        ->get();



        $viewData['QuatationView']= json_decode(json_encode($qutation_all_view), true);

      return view('krishna.quatation.view_quotation_to_factory',$viewData);
    }




       public function destroy($id)
    {
         DB::table('measurement_quatations')->where('id',$id)->delete();
    }
 
    public function qutationAlldata()
    {
   $qutationAlldata=DB::table('measurement_datas')
        ->join('measurement_quatations','measurement_quatations.id','measurement_datas.measurement_id')
        ->join('customers','customers.id','measurement_quatations.customer_name')
        ->groupBy('measurement_datas.measurement_id')
        ->get();

        return Datatables::of($qutationAlldata)
          ->addColumn('action', function($qutationAlldata){

             return '<a onclick="createQuote('.$qutationAlldata->measurement_id.')" class="btn btn-sm btn-success" title="create Quotation"><i class="fa fa-plus"></i></a>'.' '.'<a href="add/'.$qutationAlldata->measurement_id.'" class="btn btn-sm btn-success" title="view Measurement Data" target="_blank"><i class="fa fa-eye"></i></a>'.' '.'<a href="view_quotation/'.$qutationAlldata->measurement_id.'" class="btn btn-sm btn-success" title="view Quotation Data" target="_blank"><i class="fa fa-fa fa-quora" title="Quotation View"></i></a>'.' '.'<a href="view_quotation_factory/'.$qutationAlldata->measurement_id.'" class="btn btn-sm btn-success" title="view Quotation Data" target="_blank"><i class="fa fa-fa fa-" title="Factory Quotation View"></i></a>'.' '. 
                    '<a onclick="deleteData('.$qutationAlldata->measurement_id.')" class="btn btn-sm btn-danger" target="_blank"><i class="fa fa-remove" title="Delete"></i></a>';

          })->make(true);
      }
}
