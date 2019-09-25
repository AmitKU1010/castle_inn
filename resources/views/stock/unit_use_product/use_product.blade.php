@extends('layouts.admin')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Use Product</h1>
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
        <h3 class="card-title pb-2">Use Product</h3>

        <div class="card-tools">
          <a class="btn btn-success" href="javascript:void(0)" id="createNew"><i class="fa fa-plus"></i>USE</a>
        </div>
      </div>
      <div class="card-body">

        <table class="table table-sm table-striped table-bordered table-hover data-table">
          <thead>
            <tr>
              <th width="100px">#</th>
               <th>BRANCH NAME</th>
                <th>PRD. USE DATE</th>
                <th>INVOICE NO</th>
                <th>TOTAL QUANTITY USED</th>
                <th>REMARKS</th>

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
        <div class="modal-header bg-secondary">
          <h4 class="modal-title" id="modelHeading"></h4>
        </div>

        <form id="ajaxForm" class="form-horizontal">
          <div class="modal-body">
            <input type="hidden" name="_id" id="_id">

           <input type="hidden" class="form-control" name="auth_id" id="" value="{{ Auth::user()->id }}"  autofocus="">
        
        <div class="row" >
        <div class="col-sm-4">
         <div class="form-group">
           <label>Invoice Date</label>
           <input type="date" class="form-control" name="use_invoice_date" id="use_invoice_date" placeholder="invoice_date"  autofocus="">
         </div>
         </div>

          <div class="col-sm-4">
         <div class="form-group">
           <label>Invoice Number</label>
           <input type="text" class="form-control" name="use_invoice_number" id="use_invoice_number" placeholder="invoice_number"  autofocus="">
         </div>
         </div>

         <div class="col-sm-4">
         <div class="form-group">
           <label>Remraks/Description</label>
           
          <input type="text" class="form-control" name="use_invoice_remarks" id="use_invoice_remarks" placeholder="issue_description"  autofocus="">

         </div>
         </div>
      </div><!-- End OF Firsrt Row -->

        
<!-- start of purchase Form-->
        <div class="row">   
        <div class="col-sm-12">  
          <div class="card text-white bg-success" >
            <div class="card-header">
            <h5 >
                  Please fill up the Issue details
            </h5> 
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="addmoretable" class="table table-bordered">
                  <tbody id="tBody">
                    <tr id="addRow__0">                     
                      <td style="width: 250px;">

           <div class="form-group" >
           <label>Product Type</label>
         <select class="form-control item_catagory_class" name="use_item_catagory[]" id="use_item_catagory-0" style="color: black;">
            <option>Choose</option>
            @if(count($itemCatagory)>0)
            @foreach($itemCatagory as $itemCatagorys)
             <option value="{{$itemCatagorys->id}}">
               {{$itemCatagorys->item_category_name}}
             </option>
             @endforeach
             @endif
           </select> 
           </div>
           <div class="form-group">
           <label>Product Name</label>
           <select class="form-control item_name_class" name="use_item_name[]" id="use_item_name-0" style="color: black;">
             <option></option>
           </select>
           </div>
         </td>


         <td>
            <div class="form-group">
           <label>Product HSN</label>
           <input type="text" class="form-control" name="use_hsn[]" id="use_hsn-0" placeholder="hsn"  autofocus="">
           </div>

            <div class="form-group">
           <label>Product Unit</label>
           <input type="text" class="form-control" name="use_unit[]" id="use_unit-0" placeholder="unit"  autofocus="">
           </div>
           </td>

            <td>
            <div class="form-group">
           <label>Product Quantity</label>
           <input type="text" class="form-control" name="use_quantity[]" id="use_quantity-0" placeholder="quantity"  autofocus="">
           </div>

           <div class="form-group">
           <label>Remarks</label>
           <input type="text" class="form-control" name="remarks[]" id="remarks-0" placeholder="remarks"  autofocus="">
           </div>
           </td>

         </tr>

                <tr>
                 <td></td>

                    <td>
                        <div class="form-groupp">
                            <label for="product_type"></label>
                        <a id="addrow-0" class="btn btn-info add-row mr-15" data-toggle="tooltip" data-original-title="Purchase Product"> <i class="fa fa-plus text-inverse m-r-10"></i>
                        </a>
                        </div>
                      </td>
                 <td></td>

                  </tr>
                   <tr class="add_tr"></tr>
                  </tbody>
                  <tfoot >                    
                    <tr> 
                      <td class="small font-italic text-info text-capitalize"></td>
                      <td>Total Quantity</td>
                      <td><input type="number" step="any" required  class="form-control form-control-rounded" name="total_quantity" id="total_quantity"></td>
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
          ajax: "{{ route('ajax_use_unit_stock.index') }}",
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
            {data:'pd_use_dt', name:'pd_use_dt'},
            {data:'invoiceno', name:'invoiceno'},
            {data:'use_quantity', name:'use_quantity'},
            {data:'use_remarks', name:'use_remarks'},
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
          $('#modelHeading').html("Use Product");
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
            url: "{{ route('ajax_use_unit_stock.store') }}",
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

  $('.add_tr').replaceWith('<tr id="addRow__0">                     \
                      <td style="width: 250px;">\
\
           <div class="form-group" >\
           <label>Product Type</label>\
         <select class="form-control item_catagory_class" name="use_item_catagory[]" id="use_item_catagory-0" style="color: black;">\
            <option>Choose</option>\
            @if(count($itemCatagory)>0)\
            @foreach($itemCatagory as $itemCatagorys)\
             <option value="{{$itemCatagorys->id}}">\
               {{$itemCatagorys->item_category_name}}\
             </option>\
             @endforeach\
             @endif\
           </select> \
           </div>\
           <div class="form-group">\
           <label>Product Name</label>\
           <select class="form-control item_name_class" name="use_item_name[]" id="use_item_name-0" style="color: black;">\
             <option></option>\
           </select>\
           </div>\
         </td>\
         <td>\
            <div class="form-group">\
           <label>Product HSN</label>\
           <input type="text" class="form-control" name="use_hsn[]" id="use_hsn-0" placeholder="hsn"  autofocus="">\
           </div>\
\
            <div class="form-group">\
           <label>Product Unit</label>\
           <input type="text" class="form-control" name="use_unit[]" id="use_unit-0" placeholder="unit"  autofocus="">\
           </div>\
           </td>\
\
            <td>\
            <div class="form-group">\
           <label>Product Quantity</label>\
           <input type="text" class="form-control" name="use_quantity[]" id="use_quantity-0" placeholder="quantity"  autofocus="">\
           </div>\
\
           <div class="form-group">\
           <label>Remarks</label>\
           <input type="text" class="form-control" name="remarks[]" id="remarks-0" placeholder="remarks"  autofocus="">\
           </div>\
           </td>\
\
         </tr>\
\
                <tr>\
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
\
                  </tr>\
                   <tr class="add_tr"></tr>\
                  </tr>\
         ');

});



 $(document).on('change','.item_catagory_class', function(){ 
  // alert('hmgj');
    var thisSelf=$(this);

      var item_catagory = $(this).val();
      console.log(item_catagory);


           $.ajax({
        type:"POST",
        url: "{{url('/')}}/ajax/getItemCatagory",
        data:{
          "_token": "{{ csrf_token() }}",
          item_catagory : item_catagory,
        },
        dataType : 'html',
        cache: false,
        success: function(data){
          responseData=JSON.parse(data);
           console.log(responseData);
           console.log(data);
            thisSelf.parent().parent().find('[name^=use_item_name]')
               .empty()
               .append('<option selected="selected" value="">-Select -</option>');

           for (index = 0; index < responseData.length; ++index) {
               thisSelf.parent().parent().find('[name^=use_item_name]').append(
                '<option value="'+responseData[index]['id']+'">'+responseData[index]['item_name']+'</option>'
              );   
            }  
        }
      });
        
      }); 

 $(document).on('change','.item_name_class', function()
 { 

  var thisSelf=$(this);
  // alert(thisSelf);
  var item_name = $(this).val();
  console.log(item_name);

  var id_for_allrows=$(this).attr('id');

  var split_id_for_allrows=id_for_allrows.split("-");

           $.ajax({
        type:"POST",
        url: "{{url('/')}}/ajax/getproductDetailById",
        data:{
          "_token": "{{ csrf_token() }}",
          item_name : item_name,
        },
        dataType : 'html',
        cache: false,
        success: function(data){
          responseData=JSON.parse(data);
           console.log(responseData);
           // console.log(data);

          $('#use_hsn-'+split_id_for_allrows[1]).val(responseData.hsn_code);
          $('#use_unit-'+split_id_for_allrows[1]).val(responseData.unit);

  
        }
      });
 });



 setInterval(function(){
      var Total=0;
     $("[name^='use_quantity']")
              .map(function(){
                if(!isNaN(parseFloat($(this).val())))
                {
                  Total+=parseFloat($(this).val());
                }
                return parseFloat($(this).val());

              }).get();
              if(!isNaN(Total))
              {
                 $('[name=total_quantity]').val(Total);
              } 

    },1000)



 





    </script>
  @endpush

@endsection
