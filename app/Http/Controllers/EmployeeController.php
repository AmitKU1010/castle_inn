<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\itemCatagory;
use App\Employee;
use DB;
use Auth;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
 return view('krishna.employee.employee');

    }
 public function create()
    {
        //
    }

    public function store(Request $request)
    {
       //    $data = [
       //   'employee_id' => $request['employee_id'],

       //   'employee_name' => $request['employee_name'],

       //  'employee_contact' => $request['employee_contact'],

       //   'date_of_joining' => $request['date_of_joining'],
       //  'dob' => $request['dob'],
       //   // 'employee_image' => $request['employee_image'],

       //   'employee_designation' => $request['employee_designation'],
       //   'employee_salary' => $request['employee_salary'],
       //   'employee_salary' => $request['employee_salary'],
       //   'employee_salary' => $request['employee_salary'],
       //   'employee_salary' => $request['employee_salary'],


       // ];

      $Employee=new Employee;
      $Employee->employee_id=$request->input('employee_id');
      $Employee->employee_name=$request->input('employee_name');
      $Employee->employee_contact=$request->input('employee_contact');
      $Employee->date_of_joining=$request->input('date_of_joining');
      $Employee->dob=$request->input('dob');



      $image = $request->file('employee_image');
        $filename = time().'.'.$image->getClientOriginalExtension();

      // Image::make($image)->resize(300, 300)->save( storage_path('/images' . $filename ) );

        $destinationPath = public_path('/images');
        $image->move($destinationPath, $filename);

      $Employee->employee_image=$filename;







      $Employee->employee_designation=$request->input('employee_designation');
      $Employee->employee_salary=$request->input('employee_salary');
      $Employee->employee_dept=$request->input('employee_dept');
      $Employee->employee_description=$request->input('employee_description');
      $Employee->save();

         // return Employee::create($data);
    }

    public function show($id)
    {
         $Employee=Employee::find($id);
        return $Employee;
    }
    

      public function edit($id)
    {
         $itemCatagory=itemCatagory::find($id);
       return $itemCatagory;
    }

    public function update(Request $request, $id)
    {
        $itemCatagory=itemCatagory::find($id);
       $itemCatagory->item_category_name=$request['item_category_name'];
       $itemCatagory->item_description=$request['item_description'];
     
       $itemCatagory->update();
       return $itemCatagory;
    }

       public function destroy($id)
    {
         Employee::destroy($id);
        
    }

    



    public function allEmployee()
    {
   $Employee=Employee::all();
        return Datatables::of($Employee)
          ->addColumn('action', function($Employee){
             return '<a onclick="showData('.$Employee->id.')" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>'.' '. 
                    '<a onclick="editForm('.$Employee->id.')" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>'.' '. 
                    '<a onclick="deleteData('.$Employee->id.')" class="btn btn-sm btn-danger"><i class="fa fa-remove"></i></a>';

          })->make(true);
      }
}

