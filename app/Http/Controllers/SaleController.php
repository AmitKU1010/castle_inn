<?php

namespace App\Http\Controllers;

use App\sale;
use Illuminate\Http\Request;
use DB;
use App\Customer;
use App\itemCatagory;
use App\itemName;
use Illuminate\Support\Facades\Auth;
use App\SaleInvoice;

class SaleController extends Controller
{
 public function __construct()
    {
        $this->middleware('auth');
    }
    public function save(Request $request, $id = null)
    {
         $getFormAutoFillup = array();
         $viewData['customerSelect'] = Customer::pluck('customer_name', 'id');
         $viewData['itemCatagorySelect'] = itemCatagory::pluck('item_category_name', 'id');
         $viewData['itemNameSelect'] = itemName::pluck('item_name', 'id');

      if(isset($id) && $id != null ){
      $getFormAutoFillup = Sale::whereId($id)->first();           
      if ($getFormAutoFillup) {
          $getFormAutoFillup = $getFormAutoFillup->toArray();
        }
        else
        {
            $request->session()->flash('message.level', 'Error');
            $request->session()->flash('message.content', 'Somthing Went Wrong!');
        }
       return view('krishna.sales.add', $viewData)->with($getFormAutoFillup);
      }
      else
        {
          if ($request->isMethod('post'))
          {

              $saveSaleInvoice = new SaleInvoice;
              $saveSaleInvoice->customer_id = $request->customer_id;
              $saveSaleInvoice->customer_name = $request->customer_name;
              $saveSaleInvoice->customer_contact_no = $request->customer_contact_no;
              $saveSaleInvoice->customer_address = $request->customer_address;
              $saveSaleInvoice->customer_email = $request->customer_email;
              $saveSaleInvoice->sale_invoice_no =  $request->sale_invoice_no;
              $saveSaleInvoice->sale_invoice_date =  date('Y-m-d H:i:s', strtotime($request->input('sale_invoice_date')));
              $saveSaleInvoice->sale_invoice_amount =  $request->sale_invoice_amount;
              $saveSaleInvoice->sale_description =  $request->sale_description;
              $saveSaleInvoice->payment_type =  $request->payment_type;
              $saveSaleInvoice->total_sale_amount =  $request->total_sale_amount;
              // $savePurchaseInvoice->purchase_due_amount =  $request->purchase_due_amount;
              $saveSaleInvoice->gstin =  $request->gstin;
              
              if($saveSaleInvoice->save())
              {
               $saleInvoiceId= $saveSaleInvoice->id;
              }
             

          for($i=0; $i < count($request->item_catagory); $i++)
          {
            $saveSale = new Sale;
                          $saveSale->sale_invoice_id = $saleInvoiceId;
                          $saveSale->customer_id = $request->customer_id;
                          $saveSale->customer_name = $request->customer_name; 
                          $saveSale->customer_email =$request->input('customer_email');                      
                          $saveSale->customer_contact_no =  $request->customer_contact_no;
                          $saveSale->customer_address =$request->input('customer_address'); 
                          $saveSale->sale_invoice_no =  $request->sale_invoice_no;
                          $saveSale->purchase_invoice_date = date('Y-m-d H:i:s', strtotime($request->input('purchase_invoice_date')));
                          $saveSale->purchase_invoice_amount =  $request->purchase_invoice_amount;
                          $saveSale->purchase_discription =  $request->purchase_discription;
                          $saveSale->payment_type =  $request->payment_type;
                          // $savePurchase->gstin =  $request->gstin;
                          $saveSale->item_name =  $request->item_name[$i];
                          $saveSale->item_catagory =  $request->item_catagory[$i];
                          $saveSale->gst =  $request->gst[$i];
                          $saveSale->hsn =  $request->hsn[$i];
                          $saveSale->quantity =  $request->quantity[$i];
                          $saveSale->price =  $request->price[$i];
                          $saveSale->total_tax =  $request->total_tax[$i];
                          $saveSale->total_amount =  $request->total_amount[$i];
                          // $savePurchase->user_id = Auth::user()->id;
                          if($saveSale->save())
                          {
                            $ItemDetail=itemName::whereId($request->item_name[$i])->first()->toArray();

                            $productStockIn=$ItemDetail['stock_in'];
                            $productStockAvailable=$ItemDetail['available_stock'];
                            $productManame['stock_in']=$productStockIn+$request->quantity[$i];
                            $productManame['available_stock']=$productStockAvailable+$request->quantity[$i];
                            itemName::where([['id', '=',$request->item_name[$i]]])->update($productManame);
                              $request->session()->flash('message.level', 'success');
                              $request->session()->flash('message.content', ' Saved Successfully!');
                          }

                          
          }
          
            }

            //  exit;
        }    
         // exit;
      return view('krishna.sales.add',$viewData);
        }
    public function update(Request $request, $id = null)
    {
          $getFormAutoFillup = array();
        $viewData['pageTitle'] = 'Update Sale Detail'; 
        $viewData['customer'] = Customer::pluck('customer_name', 'id');
        $viewData['item_catagory'] = item_catagory::pluck('item_catagory', 'id');
        $viewData['item_name'] = item_name::pluck('item_name', 'id');
            if ($request->isMethod('post')){    
                    $customer_name = $request->input('customer_name');
                    $customer_contact_no = $request->input('customer_contact_no');
                    $customer_email = $request->input('customer_email');
                    $customer_address = $request->input('customer_address');
                    $sale_invoice_no = $request->input('sale_invoice_no');
                    $sale_invoice_date = $request->input('sale_invoice_date');
          $gst = $request->input('gst');
          $item_name = $request->input('item_name');
                    $item_catagory = $request->input('item_catagory');
                    $cgst = $request->input('cgst');
                    $quantity = $request->input('quantity');
                    $sgst = $request->input('sgst');
                    $hsn = $request->input('hsn');
          $price = $request->input('price');
                    $total_amount = $request->input('total_amount');    
          $total_tax_amount = $request->input('total_tax_amount');   

                    for($i=0; $i < count($product_id); $i++){
                            $updateSaleDetail = Sale::where('id', '=', $request->id);;
                            $saveSale->id = $requst->id;
                            $saveSale['customer_name']= $customer_name;
                            $saveSale['customer_contact_no']= $customer_contact_no;
                            $saveSale['customer_email']= $customer_email;
                            $saveSale['customer_address'] = $customer_address[$i];
                            $saveSale['sale_invoice_no'] = $sale_invoice_no[$i];
                            $saveSale['sale_invoice_date'] = $sale_invoice_date[$i];
                            $saveSale['gstin'] = $gstin[$i];
                            $saveSale['item_name'] = $item_name[$i];
                            $saveSale['item_catagory']= $item_catagory[$i];
                            $saveSale['cgst']= $cgst[$i];
                            $saveSale['sgst'] = $sgst[$i];
                            $saveSale['quantity'] = $quantity[$i];
                            $saveSale['hsn']= $hsn[$i];
                            $saveSale['total_tax_amount']= $total_tax_amount[$i];
                            $saveSale['total_amount'] = $total_amount[$i];
                              
                            Sale::where([['id', '=',$product_id[$i]]])->update($productManame);
 
                            if(!$updatePurchaseDetail->update($savePurchase))
                            {
                                $request->session()->flash('message.level', 'Error');
                                $request->session()->flash('message.content', 'Somthing Went Wrong!');
                            }
                        }
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', ' Updated Successfully!');
        }
         return redirect('/krishna/sales/add/'.$request->id);
    }
   // this is for search
    public function view(Request $request)
    {
        $getFormAutoFillup = array();
        if($request->isMethod('post'))
        {
            $viewData['pageTitle'] = 'Sale Details';            
            $sale= DB::table('sales');
             $sale->leftJoin('customer','customers.id','=','sales.customer_name');
             $sale->leftJoin('item_catagories','item_catagories.id','=','purchases.item_catagory');
             $sale->leftJoin('item_names','item_names.id','=','purchases.item_name');
            if($request->has('customer_name') && $request->customer_name !=''){
                $getFormAutoFillup['customer_name']=$request->customer_name;
                $sale->where('sales.customer_name', 'like', '%'.$request->customer_name.'%');
            }
            if($request->has('created_at_from') && $request->created_at_from !=''){
                $getFormAutoFillup['created_at_from']=$request->created_at_from;
                $sale->whereDate('sales.created_at', '<=', $request->created_at_from);
            }
            if($request->has('created_at_to') && $request->created_at_to !=''){
                $getFormAutoFillup['created_at_to']=$request->created_at_to;
                $sale->whereDate('sales.created_at', '>=', $request->created_at_to);
            }
            
            $sale->orderBy('sales.id','desc');
            $sale= $sale->get();
            $viewData['sale']=json_decode(json_encode($sale), true);
            return view('krishna.sales.search', $viewData)->with($getFormAutoFillup);

        }else
        {
        $viewData['pageTitle'] = 'Sale Details';        
        $sale= DB::table('sale_invoices');
        $sale->join('sales','sale_invoices.id','=','sales.sale_invoice_id');
        $sale->join('item_names','item_names.id','=','sales.item_name');
        $sale->select('sale_invoices.*','sale_invoices.id as purchaseInvoiceId','sales.*','sales.id as saleId','item_names.item_name as Itemname');
        $sale->where('sale_invoices.deleted_at','=',null);
        $sale->orderBy('sales.id','desc');
        $sale= $sale->get();
        $viewData['sale']=json_decode(json_encode($sale), true);
            return view('krishna.sales.search', $viewData);
        }
      
    }

    public function viewPurchaseInvoice(Request $request)
    {
      $getFormAutoFillup = array();
      if($request->isMethod('post'))
      {
        $viewData['pageTitle'] = 'Sale Details';       
        $sale= DB::table('sale_invoices');
        $sale->leftJoin('customers','customers.id','=','sale_invoices.customer_id');
        $sale->where('customer_invoices.deleted_at','=',null);
      if($request->has('id') && $request->id !=''){
        $getFormAutoFillup['id']=$request->id;
        $sale->where('customer_invoices.id', '=', $request->id);
      }
      if($request->has('customer_name') && $request->customer_name !=''){
        $getFormAutoFillup['customer_name']=$request->customer_name;
        $sale->where('sales.customer_name', 'like', '%'.$request->customer_name.'%');
      }
      if($request->has('created_at_from') && $request->created_at_from !=''){
        $getFormAutoFillup['created_at_from']=$request->created_at_from;
        $sale->whereDate('sales.created_at', '<=', $request->created_at_from);
      }
      if($request->has('created_at_to') && $request->created_at_to !=''){
        $getFormAutoFillup['created_at_to']=$request->created_at_to;
        $sale->whereDate('sales.created_at', '>=', $request->created_at_to);
      }
      $sale->select('purchase_invoices.id as purchase_invoice_id','sasle_invoices.*','customers.*');
      $sale->orderBy('sale_invoices.id','desc');
          $sale= $sale->get();
      $viewData['sale']=json_decode(json_encode($sale), true);
      // print_r($viewData['purchase']);
      // exit;

        return view('krishna.sale.purchaseInvoiceSearch', $viewData)->with($getFormAutoFillup);;

      }else
      {
        $viewData['pageTitle'] = 'Sale Details';        
        $sale= DB::table('sale_invoices');
        $sale->leftJoin('customers','customers.id','=','sale_invoices.customer_id');
        $sale->where('sale_invoices.deleted_at','=',null);
        $sale->select('sale_invoices.id as sale_invoice_id','sale_invoices.*','customers.*');
        $sale->orderBy('sale_invoices.id','desc');
        $sale= $sale->get();
        $viewData['sale']=json_decode(json_encode($sale), true);
        return view('krishna.sale.saleInvoiceSearch', $viewData);
      }
      
    }
    public function saleInvoiceView($id)
    {

    }

    public function trash(Request $request,$id)
    {
        if(($id!=null) && (Sale::where('id',$id)->delete())){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Sale was Trashed!');
            $viewData['pageTitle'] = 'Sale';        
            $sale= DB::table('sales');
            $sale->where('sales.deleted_at', '=', null);
             $sale->leftJoin('customers','customers.id','=','sales.customer_name');
             $sale->leftJoin('item_catagories','item_catagories.id','=','sales.item_catagory');
             $sale->leftJoin('item_names','item_names.id','=','sales.item_name');
             $sale->select('sales.*','customers.customer_name as customer_name_from_customer','item_catagories.item_catagory as item_catagory','item_names.item_name as item_name');
             $sale= $sale->get();
            $viewData['sale']=json_decode(json_encode($sale), true);
            return view('krishna.sale.search', $viewData);
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
            $viewData['pageTitle'] = 'Sale';        
            $sale= DB::table('sales');
            $sale->where('sales.deleted_at', '=', null);
             $sale->leftJoin('customers','customers.id','=','sales.customer_name');
            $sale->leftJoin('item_catagories','item_catagories.id','=','sales.item_catagory');
             $sale->leftJoin('item_names','item_names.id','=','sales.item_name');
             $sale->select('sales.*','sales.customer_name as customer_name_from_customer','item_catagories.item_catagory as item_catagory','item_names.item_name as item_name');
             $sale= $sale->get();
            $viewData['sale']=json_decode(json_encode($sale), true);
            return view('krishna.sale.search', $viewData);
       }
    
    }
    public function trashedList()
    {

         $TrashedSale = Sale::orderBy('deleted_at', 'desc')->onlyTrashed()->simplePaginate(10);
         return view('krishna.sale.delete', compact('TrashedSale', 'TrashedSale'));
      
    }
    public function permanemetDelete(Request $request,$id)
    {
        if(($id!=null) && (Sale::where('id',$id)->forceDelete())){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', "Sale was deleted Permanently and Can't rollback in Future!"); 
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
       }

         $TrashedSale = Sale::orderBy('deleted_at', 'desc')->onlyTrashed()->simplePaginate(50);
         return view('krishna.sale.delete', compact('TrashedSale', 'TrashedSale'));
    }
}
