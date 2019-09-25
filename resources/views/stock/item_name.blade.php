@extends('layouts.admin')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Suppliers</h1>
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
        <h3 class="card-title pb-2">Suppliers</h3>

        <div class="card-tools">
          <a class="btn btn-success" href="javascript:void(0)" id="createNew"><i class="fa fa-plus"></i> Add</a>
        </div>
      </div>
      <div class="card-body">

        <table class="table table-sm table-striped table-bordered table-hover data-table">
          <thead>
            <tr>
                    <th width="100px">#</th>
                    <th >Product Catagory</th>
                    <th >Product Type/Drink Type</th>
                    <th >Product Name/Drink Name</th>
                    <th >Hsn Code</th>
                    <th >Unit</th>
                    <th >Price Of Nib</th>
                    <th >Price of Half</th>
                    <th >Price of Quat</th>
                    <th >Price of Others</th>


              <th width="100px">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>
    </div>

  </section>

  <div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content text-white  bg-success">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title" id="modelHeading"></h4>
        </div>

        <form id="ajaxForm" class="form-horizontal">
          <div class="modal-body">
            <input type="hidden" name="_id" id="_id">

       <div class="row">
       <div class="col-md-6" id="one">
         <div class="form-group">
          <input type="hidden" name="id" id="id">
          <label>Product Catagory</label>
          <select class="form-control" style="color: black;" id="pd_cat" name="pd_cat">
                    <option >Select</option>
                    <option value="MATERIAL">MATERIAL</option>
                    <option value="DRINKS" >DRINKS</option>
          </select>
         </div>
         </div>

         <div class="col-md-6" id="two">
         <div class="form-group">
          <input type="hidden" name="id" id="id">
           <label>Product Type/Drink Type</label>
          <select class="form-control" style="color: black;" id="item_cat_name" name="item_cat_name">
                     <option></option>
          </select>
         </div>
         </div>

         <div class="col-md-6" id="three">
         <div class="form-group">
        
           <label>Product Name/Drink Name</label>
           <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Item Description"  autofocus="">
         </div>
         </div>

         <div class="col-md-6" id="four">
         <div class="form-group">
        
           <label>HSN Code</label>
           <input type="text" class="form-control" name="hsn_code" id="hsn_code" placeholder="Hsn Code"  autofocus="">
         </div>
         </div>

         <div class="col-md-6" id="five">
         <div class="form-group">
           <label>Unit</label>
           <input type="text" class="form-control" name="unit" id="unit" placeholder="Product Unit"  autofocus="">
         </div>
         </div>


         <div class="col-md-6" id="drink_price_nib">
         <div class="form-group">
           <label>Drink Price(NIB)</label>
           <input type="text" class="form-control" name="price_for_nib" id="price_for_nib" placeholder="Item Description"  autofocus="">
         </div>
         </div>


         <div class="col-md-6" id="drink_price_half">
         <div class="form-group">
           <label>Drink Price(HALF)</label>
           <input type="text" class="form-control" name="price_for_half" id="price_for_half" placeholder="Item Description"  autofocus="">
         </div>
         </div>


          <div class="col-md-6" id="drink_price_quat">
         <div class="form-group">
           <label>Drink Price(QUAT)</label>
           <input type="text" class="form-control" name="price_for_quat" id="price_for_quat" placeholder="Item Description"  autofocus="">
         </div>
         </div>

          <div class="col-md-6" id="drink_price_other">
         <div class="form-group">
           <label>Drink Price(OTHER)</label>
           <input type="text" class="form-control" name="price_for_other" id="price_for_other" placeholder="Item Description"  autofocus="">
         </div>
         </div>

 
           <div class="col-md-6" id="six">
         <div class="form-group">
        
           <label>Remarks</label>
           <input type="text" class="form-control" name="specification" id="specification" placeholder="Product Description"  autofocus="">
         </div>
         </div>

          </div>

          <div class="modal-footer">
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
          ajax: "{{ route('ajaxitem_name.index') }}",
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

              {data:'pd_catagory', name:'pd_catagory'},
              {data:'item_cat_name', name:'item_cat_name'},

              {data:'item_name', name:'item_name'},
              {data:'hsn_code', name:'hsn_code'},
              {data:'unit', name:'unit'},
              {data:'price_for_nib', name:'price_for_nib'},
              {data:'price_for_half', name:'price_for_half'},
              {data:'price_for_quat', name:'price_for_quat'},
              {data:'price_for_other', name:'price_for_other'},


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
          $('#modelHeading').html("Create New Department");
          $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editRecord', function() {
          removeErrors();
          var _id = $(this).data('id');          
          $.get("{{ route('ajaxitem_name.index') }}" + '/' + _id + '/edit', function(data) {
            $('#modelHeading').html("Edit Department");
            $('#ajaxModel').modal('show');
            $('#_id').val(data.id);
              $('#item_name').val(data.item_name);
              $('#hsn_code').val(data.hsn_code);
              $('#specification').val(data.specification);
              $('#unit').val(data.unit);
              $('#details').val(data.details);
          })
        });

        $('#saveBtn').click(function(e) {
          e.preventDefault();
          removeErrors();
          $(this).html("<i class='fas fa-spin fa-spinner'></i> Saving...");
          $.ajax({
            data: $('#ajaxForm').serialize(),
            url: "{{ route('ajaxitem_name.store') }}",
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
                url: "{{ route('ajaxitem_name.store') }}" + '/' + _id,
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

 $(document).on('change','#pd_cat', function()
 { 


    var thisSelf=$(this);
    // alert(thisSelf);
    var pd_cat=$('#pd_cat').val();
    // alert(pd_cat);

   


  //show and hide
   if(pd_cat=='MATERIAL')
    {
      $('#drink_price_nib').hide();
      $('#drink_price_half').hide();
      $('#drink_price_quat').hide();
      $('#drink_price_other').hide();
    }

    if(pd_cat=='DRINKS')
    {

      $('#drink_price_nib').show();
      $('#drink_price_half').show();
      $('#drink_price_quat').show();
      $('#drink_price_other').show();
      $('#one').show();
      $('#two').show();
      $('#three').show();
      $('#four').show();
      $('#five').show();
    }
  //show and hide


      

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
           // console.log(data);

           // alert(data);


               $('[name^=item_cat_name]')
               .empty()
               .append('<option selected="selected" value="">-Select -</option>');

           for (index = 0; index < responseData.length; ++index) {
               $('[name^=item_cat_name]').append(
                '<option value="'+responseData[index]['id']+'$'+responseData[index]['item_category_name']+'">'+responseData[index]['item_category_name']+'</option>'
              );   
            }  

         

  
        }
      });
 });

    </script>
  @endpush

@endsection
