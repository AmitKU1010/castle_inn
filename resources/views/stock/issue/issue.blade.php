@extends('layouts.admin')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Issue Product</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
               <a href="{{route('home')}}"><i class="fa fa-home"></i> Home</a>
            </li>
            @for($i = 2; $i <= count(Request::segments()); $i++)
                <li class="breadcrumb-item active">
                  <a href="{{ URL::to( implode( '/', array_slice(Request::segments(), 0 ,$i, true)))}}">
                     {{ucwords(Request::segment($i))}}
                  </a>
               </li>
            @endfor
          </ol>
        </div>
      </div>
    </div>
  </div> 

  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title pb-2">Issue Product</h3>

        <div class="card-tools">
          <a class="btn btn-success" href="javascript:void(0)" id="createNew"><i class="fa fa-plus"></i> Add</a>
        </div>
      </div>
      <div class="card-body">

        <table class="table table-sm table-striped table-bordered table-hover data-table">
          <thead>
            <tr>
              <th width="100px">#</th>
               <th>UNIT NAME</th>
                <th>UNIT CONTACT NO</th>
                <th>ISSUE DATE</th>
                <th>ISSUE Amount</th>
              <th width="100px">ACTION</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>
    </div>

  </section>

  <div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog"  >
      <div class="modal-content" style="width: 911px;right:72px;">
        <div class="modal-header bg-secondary" >
          <h4 class="modal-title" id="modelHeading"></h4>
        </div>

        <form id="ajaxForm" class="form-horizontal">
          <div class="modal-body">
            <input type="hidden" name="_id" id="_id">

        <div class="row">
         <div class="col-sm-3">
         <div class="form-group">
           <label>Select Unit</label>
           <select class="form-control" name="branch" id="branch" style="color: black;">
            <option>Choose Unit</option>
             @if(count($branch)>0)
            @foreach($branch as $branches)
             <option value="{{$branches->user_id}}">
               {{$branches->name}}
             </option>
              @endforeach
             @endif
           </select>
         </div>
         </div>

        <!--  <div class="col-sm-3">
         <div class="form-group">
           <label>Branch Address.</label>
           <input type="text" class="form-control" name="branch_address" id="branch_address" placeholder="address"  autofocus="">
         </div>
         </div> -->


         <div class="col-sm-3">
         <div class="form-group">
           <label>Unit Con. No.</label>
           <input type="text" class="form-control" name="branch_contact_number" id="branch_contact_number" placeholder="contact_number"  autofocus="">
         </div>
         </div>

             <div class="col-sm-3">
         <div class="form-group">
           <label>Issue Invoice Date</label>
           <input type="date" class="form-control" name="issue_invoice_date" id="issue_invoice_date" placeholder="issue_invoice_date"  autofocus="">
         </div>
         </div>

             <div class="col-sm-3">
         <div class="form-group">
           <label>Issue Invoice Number</label>
           <input type="text" class="form-control" name="issue_invoice_number" id="issue_invoice_number" placeholder="issue_invoice_number"  autofocus="">
         </div>
         </div>

             

         <div class="col-sm-3">
         <div class="form-group">
           <label>Issue Description/Notes</label>
           
          <input type="text" class="form-control" name="issue_description" id="issue_description" placeholder="issue_description"  autofocus="">

         </div>
         </div>
      </div><!-- End OF Firsrt Row -->

         <br>
         <br>
<!-- start of purchase Form-->
        <div class="row">   
        <div class="col-sm-12">  
          <div class="card text-white bg-success" >
            <div class="card-header">
            <h5 >
                  Please fill up the Purchase details
            </h5> 
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="addmoretable" class="table table-bordered">
                  <tbody id="tBody">
                    <tr id="addRow__0">                     
                      <td>

          <div class="form-group">
           <label>Product Catagory</label>
           <select class="form-control pd_cat" name="pd_cat[]" id="pd_cat-0" style="color: black;">
             <option>-Select-</option>
             <option value="MATERIAL">MATERIAL</option>
             <option value="DRINKS">DRINKS</option>
           </select>
           </div>

           <div class="form-group" style="width: 200px;">
           <label>Product Type/Drink Type</label>
         <select class="form-control item_catagory_class" name="item_catagory[]" id="item_catagory-0" style="color: black;">
             <option></option>
           </select>
           </div>


          
         </td>


         <td>





           <div class="form-group">
           <label>Pd Name/Drink Name</label>
           <select class="form-control item_name_class" name="item_name[]" id="item_name-0" style="color: black;">
             <option></option>
           </select>
           </div>

            <div class="form-group drink_size_class" id="drink_size_div-0" style="display: none;">
           <label>Drink Size</label>
           <select name="drink_quantity[]" id="drink_quantity-0" class="form-control drink_quantity_class">
             <option >Choose</option>
             <option value="NIB">NIB</option>
             <option value="HALF">HALF</option>
             <option value="QUATER">QUATER</option>
             <option value="OTHER">OTHER</option>
           </select>
           </div>


          

           <div class="form-group">
           <label>Product HSN</label>
           <input type="text" class="form-control" name="hsn[]" id="hsn-0" placeholder="hsn"  autofocus="">
           </div>
           </td>


             <td>
           <div class="form-group">
           <label>Product Unit</label>
           <input type="text" class="form-control" name="unit[]" id="unit-0" placeholder="unit"  autofocus="">
           </div>

            <div class="form-group" id="pd_quan">
           <label>Product Quantity</label>
           <input type="text" class="form-control" name="quantity[]" id="quantity-0" placeholder="quantity"  autofocus="">
           </div>

          
           </td>

           <td>

             <div class="form-group">
           <label>Unit Price</label>
           <input type="text" class="form-control unit_price" name="price[]" id="price-0" placeholder="price"  autofocus="">
           </div>


          
          
           <div class="form-group">
           <label>Total Amount</label>
           <input type="text" class="form-control" name="total_amount[]" id="total_amount-0" placeholder="total_amount"  autofocus="">
           </div>


          
           </td>
         </tr>

                <tr>
                 <td></td>
                 <td></td>

                    <td>
                        <div class="form-groupp">
                            <label for="product_type"></label>
                        <a id="addrow-0" class="btn btn-info add-row mr-15" data-toggle="tooltip" data-original-title="Purchase Product"> <i class="fa fa-plus text-inverse m-r-10"></i>
                        </a>
                        </div>
                      </td>
                 <td></td>
                <td></td>

                  </tr>
                   <tr class="add_tr"></tr>
                  </tbody>
                  <tfoot >                    
                    <tr> 
                      <td class="small font-italic text-info text-capitalize"></td>
                      <td>Total Purchase Amount</td>
                      <td><input type="number" step="any" required  class="form-control form-control-rounded" name="total_sale_amount" id="total_sale_amount"></td>
                      <td></td>
                      <td></td>

                    </tr>
                  </tfoot>
                </table>
               </div> 
            </div>
                         
            </div>
          </div>
        </div>

          <div class="modal-footer bg-secondary">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times"></i> Close
            </button>
            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
              <i class="fas fa-save"></i> Save
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>
  </div> 
  

  @push('scripts')
    <script type="text/javascript">
      $(function() {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('ajaxissue.index') }}",
          language: {
              processing: "<i class='fas fa-spin fa-3x fa-spinner'></i>",
              zeroRecords: "No Data Found",
              infoEmpty: "No Data Available",
              searchPlaceholder: "Type any word to search"
          },
          order: [[1 , "asc"]],
          columns: [{
              data: 'DT_RowIndex',
              name: 'DT_RowIndex',
              orderable: false,
              searchable: false 
            },

            {data:'branch_name', name:'branch_name'},
            {data:'branch_con_no', name:'branch_con_no'},
            {data:'isue_invoice_dt', name:'isue_invoice_dt'},
            {data:'tot_sale_amt', name:'tot_sale_amt'},

            {
              data: 'action',
              name: 'action',
              orderable: false,
              searchable: false,
              className: 'text-center'
            },
          ]
        });


        $('#createNew').click(function() {
          removeErrors();
          $('#_id').val('');
          $('#ajaxForm').trigger("reset");
          $('#modelHeading').html("Issue Product");
          $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editRecord', function() {
          removeErrors();
          var _id = $(this).data('id');
          // alert(_id);
          $.get("{{ route('ajaxpurchase.index') }}" + '/' + _id + '/edit', function(data) {
            $('#modelHeading').html("Edit Department");
            $('#ajaxModel').modal('show');
            $('#_id').val(data.id);
           $('#supplier_name').val(data.supplier_name);
              $('#contact_no').val(data.contact_no);
              $('#email').val(data.email);
              $('#address').val(data.address);
              $('#gstin ').val(data.gstin );
              $('#about').val(data.about);
          })
        });

        $('#saveBtn').click(function(e) {
          e.preventDefault();
          removeErrors();
          $(this).html("<i class='fas fa-spin fa-spinner'></i> Saving...");
          $.ajax({
            data: $('#ajaxForm').serialize(),
            url: "{{ route('ajaxissue.store') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
              if (data.success) {
                Toast.fire({
                  type: 'success',
                  title: data.message
                });
                $('#ajaxModel').modal('hide');
                $('#ajaxForm').trigger("reset");
                table.draw();
              } else {
                showErrors(data.errors);
              }
            },

            error: function(data) {
              console.log('Error:', data);
            }
          });
          $(this).html('<i class="fas fa-save"></i> Save');
        });

        $('body').on('click', '.deleteRecord', function() {
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value) {
              var _id = $(this).data("id");
              $.ajax({
                type: "DELETE",
                url: "{{ route('ajaxpurchase.store') }}" + '/' + _id,
                success: function(data) {
                  if (data.success) {
                    Toast.fire({
                      type: 'success',
                      title: 'Data successfully deleted.'
                    });
                    table.draw();
                  }
                },
                error: function(data) {
                  console.log('Error:', data);
                }
              });
            }
          });
        });

      

      });

      function showErrors(errors) {
        $.each(errors, function(key, val) {
          var ele = $("[name='"+key+"']");
          ele.addClass('is-invalid');
          ele.after('<div class="invalid-feedback">'+val+'</div>');
        });
      }

      function removeErrors() {
        $("#ajaxModel .form-control").removeClass("is-invalid");
        $(".invalid-feedback").remove();
      }

      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });


$(document).on('change','[name^=branch]',function()
{
// alert('1');
    var thisSelf=$(this);
    // alert(thisSelf);
      var branch = $(this).val();
      // alert(branch);
      // alert(supplier_name);
      // alert(item_catagory);
  
  $.ajax({
        type:"POST",
        url: "{{url('/')}}/ajax/getBranchByID",
        data:{
          "_token": "{{ csrf_token() }}",
          branch : branch,
        },
        dataType : 'html',
        cache: false,
        success: function(data){
          console.log(data);
          responseData=JSON.parse(data);
           $('#branch_address').val(responseData[0].address); 
           $('#branch_contact_number').val(responseData[0].contact_number);  

        }
      });
});



          var i=0;
          @php 
          $i=0;
          @endphp

  
$(document).on('click','.add-row',function()
{


        i=parseInt(i)+1;
        // alert(i);
        @php 
          $i=$i+1;
        @endphp

  $('.add_tr').replaceWith(' <tr id="addRow__0">                     \
                      <td>\
\
          <div class="form-group">\
           <label>Product Catagory</label>\
           <select class="form-control pd_cat" name="pd_cat[]" id="pd_cat-'+i+'" style="color: black;">\
             <option>-Select-</option>\
             <option value="MATERIAL">MATERIAL</option>\
             <option value="DRINKS">DRINKS</option>\
           </select>\
           </div>\
\
           <div class="form-group" style="width: 200px;">\
           <label>Product Type/Drink Type</label>\
         <select class="form-control item_catagory_class" name="item_catagory[]" id="item_catagory-'+i+'" style="color: black;">\
             <option></option>\
           </select>\
           </div>\
          \
         </td>\
\
         <td>\
\
           <div class="form-group">\
           <label>Pd Name/Drink Name</label>\
           <select class="form-control item_name_class" name="item_name[]" id="item_name-'+i+'" style="color: black;">\
             <option></option>\
           </select>\
           </div>\
\
            <div class="form-group drink_size_class" id="drink_size_div-'+i+'" style="display: none;">\
           <label>Drink Size</label>\
           <select name="drink_quantity[]" id="drink_quantity-'+i+'" class="form-control drink_quantity_class">\
             <option >Choose</option>\
             <option value="NIB">NIB</option>\
             <option value="HALF">HALF</option>\
             <option value="QUATER">QUATER</option>\
             <option value="OTHER">OTHER</option>\
           </select>\
           </div>\
\
           <div class="form-group">\
           <label>Product HSN</label>\
           <input type="text" class="form-control" name="hsn[]" id="hsn-'+i+'" placeholder="hsn"  autofocus="">\
           </div>\
           </td>\
\
             <td>\
           <div class="form-group">\
           <label>Product Unit</label>\
           <input type="text" class="form-control" name="unit[]" id="unit-'+i+'" placeholder="unit"  autofocus="">\
           </div>\
\
            <div class="form-group" id="pd_quan">\
           <label>Product Quantity</label>\
           <input type="text" class="form-control" name="quantity[]" id="quantity-'+i+'" placeholder="quantity"  autofocus="">\
           </div>\
\
          \
           </td>\
           <td>\
\
             <div class="form-group">\
           <label>Unit Price</label>\
           <input type="text" class="form-control unit_price" name="price[]" id="price-'+i+'" placeholder="price"  autofocus="">\
           </div>\
          \
           <div class="form-group">\
           <label>Total Amount</label>\
           <input type="text" class="form-control" name="total_amount[]" id="total_amount-'+i+'" placeholder="total_amount"  autofocus="">\
           </div>\
           </td>\
         </tr>\
\
                <tr>\
                 <td></td>\
                 <td></td>\
\
                    <td>\
                        <div class="form-groupp">\
                            <label for="product_type"></label>\
                        <a id="addrow-0" class="btn btn-info add-row mr-15" data-toggle="tooltip" data-original-title="Purchase Product"> <i class="fa fa-plus text-inverse m-r-10"></i>\
                        </a>\
                        </div>\
                      </td>\
                 <td></td>\
                <td></td>\
\
                  </tr>\
                   <tr class="add_tr"></tr>\
                  </tr>\
         ');

});


 $(document).on('change','.pd_cat', function(){ 
  // alert('hmgj');
    var thisSelf=$(this);

      var item_catagory = $(this).val();

      var pd_cat_id_name=$('.pd_cat').attr('id');

      var pd_cat_split=pd_cat_id_name.split('-');

      var pd_cat=$('#pd_cat-'+pd_cat_split[1]).val();

      // alert(pd_cat);

if(pd_cat=='DRINKS')
{ 
  // var tt=$('#drink_size_div-'+pd_cat_split[1]).attr('id');
  // alert(tt);

  $('.drink_size_class').show();
}
else
{
  $('.drink_size_class').hide();

}

 

           $.ajax({
        type:"POST",
        url: "{{url('/')}}/ajax/get_pdcat",
        data:{
          "_token": "{{ csrf_token() }}",
          pd_cat : pd_cat,
        },
        dataType : 'html',
        cache: false,
        success: function(data){
          responseData=JSON.parse(data);
           console.log(responseData);
           console.log(data);
           
           // alert(data);

            thisSelf.parent().parent().find('[name^=item_catagory]')
               .empty()
               .append('<option selected="selected" value="">-Select -</option>');

           for (index = 0; index < responseData.length; ++index) {
               thisSelf.parent().parent().find('[name^=item_catagory]').append(
                '<option value="'+responseData[index]['id']+'">'+responseData[index]['item_category_name']+'</option>'
              );   
            }  
        }
      });
        
      }); 

 $(document).on('change','.item_catagory_class', function()
 { 

  var thisSelf=$(this);
  // alert(thisSelf);
  var item_cat_name = $(this).val();
// alert(item_cat_name);

  var id_for_allrows=$(this).attr('id');
  var split_id_for_allrows=id_for_allrows.split("-");

           $.ajax({
        type:"POST",
        url: "{{url('/')}}/ajax/getitemNameById",
        data:{
          "_token": "{{ csrf_token() }}",
          item_cat_name : item_cat_name,
        },
        dataType : 'html',
        cache: false,
        success: function(data){
          responseData=JSON.parse(data);
           console.log(responseData);
           // alert(data);
       var hsn_code_db=responseData[0].hsn_code;

       // alert(hsn_code_db);


           thisSelf.parent().parent().parent().find('[name^=item_name]')
               .empty()
               .append('<option selected="selected">-Select -</option>');

           for (index = 0; index < responseData.length; ++index) {
               thisSelf.parent().parent().parent().find('[name^=item_name]').append(
                '<option value="'+responseData[index]['id']+'">'+responseData[index]['item_name']+'</option>'
              );   
            }

            $('#hsn-'+split_id_for_allrows).val('hsn_code_db');  

  
        }
      });
 });


$(document).on('change','.item_name_class', function()
 { 
  var id_for_allrows=$(this).attr('id');
  var split_id_for_allrows=id_for_allrows.split("-");

  // alert(split_id_for_allrows);
 
  var pd_cat_value=$('#pd_cat-'+split_id_for_allrows[1]).val();  

  var item_catagory_value=$('#item_catagory-'+split_id_for_allrows[1]).val(); 

  var item_name_value=$('#item_name-'+split_id_for_allrows[1]).val(); 



// alert(pd_cat_value);
// alert(item_catagory_value);
// alert(item_name_value);
if(pd_cat_value=='MATERIAL')
{

  $.ajax({
        type:"POST",
        url: "{{url('/')}}/ajax/getitemdetailById",
        data:{
          "_token": "{{ csrf_token() }}",
           pd_cat_value : pd_cat_value,item_catagory_value:item_catagory_value,item_name_value : item_name_value,
        },
        dataType : 'html',
        cache: false,
        success: function(data){
          responseData=JSON.parse(data);
           // alert(responseData);
           // alert(data);
       var hsn_code_db=responseData[0].hsn_code;

       var unit_val=responseData[0].unit;



            $('#hsn-'+split_id_for_allrows[1]).val(hsn_code_db); 
            $('#unit-'+split_id_for_allrows[1]).val(unit_val);  


  
        }
      });
}
//END OF IF STATEMENT
else
{

  $(document).on('change','.drink_quantity_class', function()
 { 
  
  var drink_size=$('#drink_quantity-'+split_id_for_allrows[1]).val();
  // alert(drink_size);
   $.ajax({
        type:"POST",
        url: "{{url('/')}}/ajax/getDrinkDetailsById",
        data:{
          "_token": "{{ csrf_token() }}",
           pd_cat_value : pd_cat_value,item_catagory_value:item_catagory_value,item_name_value : item_name_value,
        },
        dataType : 'html',
        cache: false,
        success: function(data){
          responseData=JSON.parse(data);
           // alert(responseData);
           // alert(data);
       var hsn_code_db=responseData[0].hsn_code;

       var unit_val=responseData[0].unit;

       var price_for_nib=responseData[0].price_for_nib;

       var price_for_half=responseData[0].price_for_half;

       var price_for_quat=responseData[0].price_for_quat;

       var price_for_other=responseData[0].price_for_other;



            $('#hsn-'+split_id_for_allrows[1]).val(hsn_code_db); 
            $('#unit-'+split_id_for_allrows[1]).val(unit_val);  

            if(drink_size=='NIB')
            {
            $('#price-'+split_id_for_allrows[1]).val(price_for_nib);  
            }
            if(drink_size == 'HALF')
            {
            $('#price-'+split_id_for_allrows[1]).val(price_for_half);  

            }

             if(drink_size == 'QUATER')
            {
            $('#price-'+split_id_for_allrows[1]).val(price_for_quat);  

            }

             if(drink_size == 'OTHER')
            {
            $('#price-'+split_id_for_allrows[1]).val(price_for_other);  
            }


  
        }
      });

 });


}



 });


 $(document).on('change click keydown keyup','.unit_price', function()
 { 
// alert('1');
  var id_for_allrows=$(this).attr('id');
  var split_id_for_allrows=id_for_allrows.split("-");
  var unit_price=$('#price-'+split_id_for_allrows[1]).val();

  var product_quantity=$('#quantity-'+split_id_for_allrows[1]).val();





  var total_amount=(parseInt(unit_price)*parseInt(product_quantity));



$('#total_amount-'+split_id_for_allrows[1]).val(total_amount);



 });

 setInterval(function(){
      var Total=0;
     $("[name^='total_amount']")
              .map(function(){
                if(!isNaN(parseFloat($(this).val())))
                {
                  Total+=parseFloat($(this).val());
                }
                return parseFloat($(this).val());

              }).get();
              if(!isNaN(Total))
              {
                 $('[name=total_sale_amount]').val(Total);
              } 

    },1000)



    </script>
  @endpush

@endsection
