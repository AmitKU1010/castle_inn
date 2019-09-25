<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Distributor;
use DB;
use Auth;

class DistributorController extends Controller 
{
   public function __construct()
    {
        $this->middleware('auth');
    }
    public function save(Request $request, $id = null)
    {
        $viewData['pageTitle'] = 'Distributor Dretail'; 
       
        $getFormAutoFillup=array();
       	if(isset($id) && $id != null ){
    		$getFormAutoFillup = Distributor::whereId($id)->first()->toArray();
    	}
    	else 
    	{
            if(((!isset($id) && $id == null) && $request->isMethod('post') ))
            {
                $allInputValue=$request->all();
                $allInputValueManage = new Distributor($allInputValue);
                $allInputValueManage['user_id']=Auth::user()->id;
                if($allInputValueManage->save()){
                    $request->session()->flash('message.level', 'success');
                    $request->session()->flash('message.content', 'Distributor Dretail Saved Successfully!');
                    $request->session()->flash('message.icon', 'check');
                } 
                else
                {
                     $request->session()->flash('message.level', 'danger');
                     $request->session()->flash('message.content', 'Somthing went Worng! !');
                    $request->session()->flash('message.icon', 'times');
                }
              
            }
    		
    	} 
         $viewData['distributor'] = Distributor::orderBy('id','desc')->get();   	
		return view('krishna.distributor', $viewData)->with($getFormAutoFillup);
    }

    public function update(Request $request, $id = null)
    {
        $viewData['pageTitle'] = 'Distributor Dretail'; 
        // $viewData['customer'] = Customer::orderBy('id','desc')->get();

        $getFormAutoFillup=array();
       
            if(((!isset($id) && $id == null) && $request->isMethod('post') ))
            {
                $allInputValueForUpdate=request()->except(['_token']);
                $allInputValueForUpdate['user_id']=Auth::user()->id;

                if(Distributor::where([['id', '=', $request->id]])->update($allInputValueForUpdate)){
                    $request->session()->flash('message.level', 'info');
                    $request->session()->flash('message.content', 'Distributor Dretails Updated Successfully!');
                    $request->session()->flash('message.icon', 'check');
                } 
                else
                {
                     $request->session()->flash('message.level', 'danger');
                     $request->session()->flash('message.content', 'Somthing went Worng! !');
                    $request->session()->flash('message.icon', 'times');
                }
              
            }
            
             $viewData['distributor'] = Distributor::orderBy('id','desc')->get();  
        return view('krishna.distributor', $viewData)->with($getFormAutoFillup);
    }


     public function view(Request $request)
    {
    	if($request->isMethod('post'))
    	{
    		// echo $request->created_at_from;echo "<br/>";
    		// echo $request->created_at_to;
    		// exit;
    		//DB::enableQueryLog();
    		$viewData['pageTitle'] = 'distributor';       	
			$distributor= DB::table('distributors');
			if($request->has('id') && $request->id !=''){
				$distributor->where('id', '=', $request->id);
			}
			if($request->has('distributor_name') && $request->distributor_name !=''){
				$distributor->where('distributor_name', 'like', '%'.$request->customer_name.'%');
			}
			if($request->has('created_at_from') && $request->created_at_from !=''){
				$created_at_from = date("Y-m-d", strtotime($request->created_at_from) );
				$distributor->whereDate('created_at', '<=', $created_at_from);
			}
			if($request->has('created_at_to') && $request->created_at_to !=''){
				$created_at_to = date("Y-m-d", strtotime($request->created_at_to) );
				$distributor->whereDate('created_at', '>=', $created_at_to);
			}
			if($request->has('contact_no') && $request->contact_no !=''){
				$distributor->where('contact_no', '=', $request->contact_no);
			}
			if($request->has('email') && $request->email !=''){
				$distributor->where('email', '=', $request->email);
			}
			$distributor->orderBy('id','desc');
       		$distributor= $distributor->get();
//        		$queries = DB::getQueryLog();
// print_r($queries);
// exit;
			$viewData['distributor']=json_decode(json_encode($distributor), true);
		    return view('krishna.distributor', $viewData);

    	}else
    	{
    		$viewData['pageTitle'] = 'distributor';       	
			$viewData['distributor'] = Distributor::orderBy('id','desc')->get();
		    return view('krishna.distributor', $viewData);
    	}
      
    }
    public function trash(Request $request,$id)
    {
    	if(($id!=null) && (Distributor::where('id',$id)->delete())){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Distributor was Trashed!');
            $viewData['pageTitle'] = 'distributor';       	
			$viewData['distributor'] = Distributor::get();
			return view('krishna.distributor', $viewData);
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
			$viewData['pageTitle'] = 'distributor';       	
			$viewData['distributor'] = Distributor::get();;
			return view('krishna.distributor', $viewData);
       }
    
    }
    public function trashedList()
    {
         $TrashedParty = Distributor::orderBy('deleted_at', 'desc')->onlyTrashed()->simplePaginate(10);
         return view('krishna.distributor.delete', compact('TrashedParty', 'TrashedParty')); 
    }
    public function permanemetDelete(Request $request,$id)
    {
    	if(($id!=null) && (Distributor::where('id',$id)->forceDelete())){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', "Distributor was deleted Permanently and Can't rollback in Future!"); 
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
       }
    	 $TrashedParty = Distributor::orderBy('deleted_at', 'desc')->onlyTrashed()->simplePaginate(10);
         return view('krishna.distributor', compact('TrashedParty', 'TrashedParty'));
    }
}
