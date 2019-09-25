<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use DB;
use Auth;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
 return view('krishna.customer');

    }
 public function create()
    {
        //
    }

    public function store(Request $request)
    {

            
         $data = [
        'customer_name' => $request['customer_name'],
        'contact_no' => $request['contact_no'],

          'email' => $request['email'],
        'address' => $request['address'],

          'gstin' => $request['gstin'],
        'about' => $request['about'],
       ];
        return Customer::create($data); 
        
    }

    public function show($id)
    {
         $Customer=Customer::find($id);
        return $Customer;
    }
    

      public function edit($id)
    {
         $Customer=Customer::find($id);
        return $Customer;
    }

    public function update(Request $request, $id)
    {
       
       $customer=customer::find($id);
       $customer->customer_name=$request['customer_name'];
       $customer->contact_no=$request['contact_no'];
       $customer->email=$request['email'];
       $customer->address=$request['address'];
       $customer->gstin=$request['gstin'];
       $customer->about=$request['about'];

       $customer->update();
       return $customer;
    }

       public function destroy($id)
    {
         Customer::destroy($id);
        
    }

    



    public function customerList()
    {
   $Customer=Customer::all();
        return Datatables::of($Customer)
          ->addColumn('action', function($Customer){
             return '<a onclick="showData('.$Customer->id.')" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>'.' '. 
                    '<a onclick="editForm('.$Customer->id.')" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>'.' '. 
                    '<a onclick="deleteData('.$Customer->id.')" class="btn btn-sm btn-danger"><i class="fa fa-remove"></i></a>';

          })->make(true);
      }
}

