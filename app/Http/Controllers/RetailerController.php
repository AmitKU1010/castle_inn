<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Retailer;
use DB;
use Auth;

class RetailerController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
    public function save(Request $request, $id = null)
    {
        $viewData['pageTitle'] = 'Retailer Dretail'; 
       
        $getFormAutoFillup=array();
       	if(isset($id) && $id != null ){
    		$getFormAutoFillup = Retailer::whereId($id)->first()->toArray();
    	}
    	else 
    	{
            if(((!isset($id) && $id == null) && $request->isMethod('post') ))
            {
                $allInputValue=$request->all();
                $allInputValueManage = new Retailer($allInputValue);
                $allInputValueManage['user_id']=Auth::user()->id;
                if($allInputValueManage->save()){
                    $request->session()->flash('message.level', 'success');
                    $request->session()->flash('message.content', 'Retailer Dretail Saved Successfully!');
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
         $viewData['retailer'] = Retailer::orderBy('id','desc')->get();   	
		return view('krishna.retailer', $viewData)->with($getFormAutoFillup);
    }

    public function update(Request $request, $id = null)
    {
        $viewData['pageTitle'] = 'Retailer Dretail'; 
        // $viewData['customer'] = Customer::orderBy('id','desc')->get();

        $getFormAutoFillup=array();
       
            if(((!isset($id) && $id == null) && $request->isMethod('post') ))
            {
                $allInputValueForUpdate=request()->except(['_token']);
                $allInputValueForUpdate['user_id']=Auth::user()->id;

                if(Retailer::where([['id', '=', $request->id]])->update($allInputValueForUpdate)){
                    $request->session()->flash('message.level', 'info');
                    $request->session()->flash('message.content', 'Retailer Dretails Updated Successfully!');
                    $request->session()->flash('message.icon', 'check');
                } 
                else
                {
                     $request->session()->flash('message.level', 'danger');
                     $request->session()->flash('message.content', 'Somthing went Worng! !');
                    $request->session()->flash('message.icon', 'times');
                }
              
            }
            
             $viewData['retailer'] = Retailer::orderBy('id','desc')->get();  
        return view('krishna.retailer', $viewData)->with($getFormAutoFillup);
    }


     public function view(Request $request)
    {
    	if($request->isMethod('post'))
    	{
    		// echo $request->created_at_from;echo "<br/>";
    		// echo $request->created_at_to;
    		// exit;
    		//DB::enableQueryLog();
    		$viewData['pageTitle'] = 'retailer';       	
			$retailer= DB::table('retailers');
			if($request->has('id') && $request->id !=''){
				$retailer->where('id', '=', $request->id);
			}
			if($request->has('retailer_name') && $request->retailer_name !=''){
				$retailer->where('retailer_name', 'like', '%'.$request->retailer_name.'%');
			}
			if($request->has('created_at_from') && $request->created_at_from !=''){
				$created_at_from = date("Y-m-d", strtotime($request->created_at_from) );
				$retailer->whereDate('created_at', '<=', $created_at_from);
			}
			if($request->has('created_at_to') && $request->created_at_to !=''){
				$created_at_to = date("Y-m-d", strtotime($request->created_at_to) );
				$retailer->whereDate('created_at', '>=', $created_at_to);
			}
			if($request->has('retailer_contact_no') && $request->contact_no !=''){
				$retailer->where('retailer_contact_no', '=', $request->contact_no);
			}
			if($request->has('retailer_email') && $request->email !=''){
				$retailer->where('retailer_email', '=', $request->email);
			}
			$retailer->orderBy('id','desc');
       		$retailer= $retailer->get();
//        		$queries = DB::getQueryLog();
// print_r($queries);
// exit;
			$viewData['retailer']=json_decode(json_encode($retailer), true);
		    return view('krishna.retailer', $viewData);

    	}else
    	{
    		$viewData['pageTitle'] = 'Retailer';       	
			$viewData['retailer'] = Retailer::orderBy('id','desc')->get();
		    return view('krishna.retailer', $viewData);
    	}
      
    }
    public function trash(Request $request,$id)
    {
    	if(($id!=null) && (Retailer::where('id',$id)->delete())){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Retailer was Trashed!');
            $viewData['pageTitle'] = 'Retailer';       	
			$viewData['retailer'] = Retailer::get();
			return view('krishna.retailer', $viewData);
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
			$viewData['pageTitle'] = 'Retailer';       	
			$viewData['retailer'] = Retailer::get();;
			return view('krishna.retailer', $viewData);
       }
    
    }
    public function trashedList()
    {
         $TrashedParty = Retailer::orderBy('deleted_at', 'desc')->onlyTrashed()->simplePaginate(10);
         return view('krishna.retailer.delete', compact('TrashedParty', 'TrashedParty')); 
    }
    public function permanemetDelete(Request $request,$id)
    {
    	if(($id!=null) && (Retailer::where('id',$id)->forceDelete())){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', "Retailer was deleted Permanently and Can't rollback in Future!"); 
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
       }
    	 $TrashedParty = Retailer::orderBy('deleted_at', 'desc')->onlyTrashed()->simplePaginate(10);
         return view('krishna.retailer', compact('TrashedParty', 'TrashedParty'));
    }
}

