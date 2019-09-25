<?php
use App\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 
*/ 
 
Auth::routes();
// Auth::routes(['register' => false]);
 // $user = new User;
 // $auth_id= $user->id; 
 // dd($auth_id);
Route::get('/', 'Auth\LoginController@login');
Route::get('/home', 'HomeController@index')->name('home');


// //department starts

// Route::get('/hr/departments', 'DepartmentController@index')->name('hr_department');
// Route::resource('ajaxdepartments', 'DepartmentController');

// //department ends

Route::get('/dashboard', 'dashboardController@index')->name('dashboard');


//supplier starts

Route::get('/stock/supplier', 'SupplierController@index')->name('stock_supplier');
Route::resource('ajaxsuppliers', 'SupplierController');

//supplier ends

//Product Type starts

Route::get('/stock/item_catagories', 'itemCatagoryController@index')->name('item_catagories');
Route::resource('ajaxitem_catagories', 'itemCatagoryController');

//Product Type ends

// //Product Catagories starts

// Route::get('/stock/item_catagories', 'itemCatagoryController@index')->name('item_catagories');
// Route::resource('ajaxitem_catagories', 'itemCatagoryController');

// //Product Catagories ends


//Product Details starts

Route::get('/stock/item_name', 'itemNameController@index')->name('item_name');
Route::resource('ajaxitem_name', 'itemNameController');

//Product Details ends

 
//Product Details starts

Route::get('/stock/purchase', 'PurchaseController@index')->name('purchase');
Route::get('/stock/purchase/{id}', 'PurchaseController@all_fetch');
Route::resource('ajaxpurchase', 'PurchaseController');

//Product Details ends


 
//Product Details starts

Route::get('/stock/issue', 'IssueController@index')->name('issue');
Route::get('/stock/issue/{id}', 'IssueController@all_fetch');
Route::resource('ajaxissue', 'IssueController');

//Product Details ends



//central stock starts 

Route::get('/stock/central_stock', 'CentralStockController@index')->name('central_stock');
// Route::get('/stock/central_stock/{id}', 'CentralStockController@all_fetch');
Route::resource('ajaxcentralstock', 'CentralStockController');

//central stock ends

 
//Available Unit stock

Route::get('/stock/available_unit_stock', 'UnitStockController@index')->name('available_unit_stock');
// Route::get('/stock/central_stock/{id}', 'CentralStockController@all_fetch');
Route::resource('ajax_available_unit_stock', 'UnitStockController');

//Available Unit stock



//Use Product

Route::get('/stock/use_product', 'UseProductController@index')->name('use_product');
// Route::get('/stock/central_stock/{id}', 'CentralStockController@all_fetch');
Route::resource('ajax_use_unit_stock', 'UseProductController');

//Use Product



//KOT MANAGE ITEM TYPES

Route::get('/kot/item_types', 'ItemTypesKotController@index')->name('item_types_kot');
// Route::get('/stock/central_stock/{id}', 'CentralStockController@all_fetch');
Route::resource('ajax_item_type_kot', 'ItemTypesKotController');

//KOT MANAGE ITEM TYPES


 
//KOT MANAGE ITEMS

Route::get('/kot/items', 'ItemController@index')->name('items_kot');
// Route::get('/stock/central_stock/{id}', 'CentralStockController@all_fetch');
Route::resource('ajax_items_kot', 'ItemController');

//KOT MANAGE ITEMS


//Counter Sale

Route::get('/kot/counter_sale', 'CounterSaleController@index')->name('counter_sale');
// Route::get('/stock/central_stock/{id}', 'CentralStockController@all_fetch');
Route::resource('ajax_counter_sale', 'CounterSaleController');

//Counter Sale




 
 


// RIGHT SIDE MENU
    Menu::create('navbar', function ($menu) {
    $menu->setView('menus::default');
    $menu->route('home', 'Dashboard', [], ['icon' => 'fas fa-tachometer-alt']);
    // $menu->route('hr_department', 'Departments', [], ['icon' => 'fa fa-sitemap']);
                 if('dsf')
                 {
    $menu->route('stock_supplier', 'Add Supplier', [], ['icon' => 'fa fa-sitemap']);
    $menu->route('item_catagories', 'Product Type', [], ['icon' => 'fa fa-sitemap']);
    $menu->route('item_name', 'Product Details', [], ['icon' => 'fa fa-sitemap']);
    $menu->route('purchase', 'Purchase Product', [], ['icon' => 'fa fa-university']);
    $menu->route('issue', 'Issue Product', [], ['icon' => 'fa fa-university']);
    $menu->route('central_stock', 'Centra Stock', [], ['icon' => 'fa fa-university']);
                 }

    $menu->route('available_unit_stock', 'Available Stock', [], ['icon' => 'fa fa-university']);
    $menu->route('use_product', 'Use Product', [], ['icon' => 'fa fa-university']);

    $menu->route('use_product', 'KOT', [], ['icon' => 'fa fa-university']);

    $menu->route('item_types_kot', 'Manage Drink Types', [], ['icon' => 'fa fa-university']);

    $menu->route('items_kot', 'Manage Drinks', [], ['icon' => 'fa fa-university']);

    $menu->route('counter_sale', 'Counter Sale', [], ['icon' => 'fa fa-university']);






 // Start: Ajax Related
Route::post('/ajax/getPurchase','AjaxController@getPurchase');
Route::post('/ajax/getModal','AjaxController@getProductModel');
Route::post('/ajax/getSupplierDetailById','AjaxController@getSupplierDetailById');
Route::post('/ajax/getCustomerDetailById','AjaxController@getCustomerDetailById');
Route::post('/ajax/getBrandByProductType','AjaxController@getBrandByProductType');
Route::post('/ajax/getColorByBrand','AjaxController@getColorByBrand');
Route::post('/ajax/getModelByBrand','AjaxController@getModelByBrand');
Route::post('/ajax/getColorByBrandModelType','AjaxController@getColorByBrandModelType');
Route::post('/ajax/getNameByBrandModelTypeColor','AjaxController@getNameByBrandModelTypeColor');
Route::post('/ajax/getProduct','AjaxController@getProduct');
Route::post('/ajax/saleReturn','AjaxController@saleReturn');
Route::post('/ajax/purchaseReturn','AjaxController@purchaseReturn');
Route::post('/ajax/getItemCatagory','AjaxController@getItemCatagory');

 
Route::post('/ajax/getItemNameByCategory','AjaxController@getItemNameByCategory');
Route::post('/ajax/getproductDetailById','AjaxController@getproductDetailById');

Route::post('/ajax/getBranchByID','AjaxController@getBranchByID');


Route::post('/ajax/getPriceOfNib','AjaxController@getPriceOfNib');

Route::post('/ajax/get_pdcat','AjaxController@get_pdcat');

Route::post('/ajax/getitemNameById','AjaxController@getitemNameById');

Route::post('/ajax/getitemdetailById','AjaxController@getitemdetailById');

Route::post('/ajax/getDrinkDetailsById','AjaxController@getDrinkDetailsById');











// End: Ajax Related
    
    // $menu->dropdown('Designation', function ($sub) {
    //     $sub->route('home', 'view All', [], ['icon' => 'far fa-circle nav-icon']);
    //     $sub->route('home', 'Add New', [], ['icon' => 'far fa-circle nav-icon']);
    // }, ['icon' => 'fa fa-user-plus']);
});
