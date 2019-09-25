@extends('layouts.admin')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Drinks</h1>
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
        <h3 class="card-title pb-2">Drinks Name</h3>

        <div class="card-tools">
          <a class="btn btn-success" href="javascript:void(0)" id="createNew"><i class="fa fa-plus"></i> Add</a>
        </div>
      </div>
      <div class="card-body">

        <table class="table table-sm table-striped table-bordered table-hover data-table">
          <thead>
            <tr>
                    <th width="100px">#</th>
                    <th >Drink Name</th>
                    <th >Drink Type</th>
                    <th >Hsn Code</th>
                    <th >Drink Price(Nib)</th>
                    <th >Drink Price(Half)</th>
                    <th >Drink Price(Quater)</th>
                    <th >Drink Price(Other)</th>

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
         <div class="col-md-6">
         <div class="form-group">
          <input type="hidden" name="id" id="id">
           <label>Drink Type</label>
             <select class="form-control" style="color: black;" id="item_type_kot" name="item_type_kots">
              @if(count($item_type)>0)
              @foreach($item_type as $item_types)
               <option  value="{{$item_types->id.'$'.$item_types->item_type_kot}}">
                 {{$item_types->item_type_kot}}
               </option>
               @endforeach
               @endif
             </select>
         </div> 
         </div>

         <div class="col-md-6">
         <div class="form-group">
        
           <label>Drink Name</label>
           <input type="text" class="form-control" name="item_name_kot" id="item_name_kot" placeholder="Drink Name"  autofocus="">
         </div>
         </div>



         <div class="col-md-6">
         <div class="form-group">
        
           <label>HSN Code</label>
           <input type="text" class="form-control" name="hsn_code_kot" id="hsn_code_kot" placeholder="Hsn Code"  autofocus="">
         </div>
         </div>


         <div class="col-md-6">
         <div class="form-group">
        
           <label>Drink Price(Nib)</label>
           <input type="text" class="form-control" name="item_price_nib" id="item_price_nib" placeholder="Item Price"  autofocus="">
         </div>
         </div>

         <div class="col-md-6">
         <div class="form-group">
           <label>Drink Price(Half)</label>
           <input type="text" class="form-control" name="item_price_half" id="item_price_half" placeholder="Item Price"  autofocus="">
         </div>
         </div>

         <div class="col-md-6">
         <div class="form-group">
           <label>Drink Price(Quater)</label>
           <input type="text" class="form-control" name="item_price_quater" id="item_price_quater" placeholder="Item Price"  autofocus="">
         </div>
         </div>

         <div class="col-md-6">
         <div class="form-group">
           <label>Drink Price(Other)</label>
           <input type="text" class="form-control" name="item_price_other" id="item_price_other" placeholder="Item Price"  autofocus="">
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
          ajax: "{{ route('ajax_items_kot.index') }}",
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

              {data:'item_name_kot', name:'item_name_kot'},
              {data:'item_type_kots', name:'item_type_kots'},
              {data:'hsn_code_kot', name:'hsn_code_kot'},
              {data:'item_price_nib', name:'item_price_nib'},
              {data:'item_price_half', name:'item_price_half'},
              {data:'item_price_quater', name:'item_price_quater'},
              {data:'item_price_other', name:'item_price_other'},
              
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
          $('#modelHeading').html("Create New Drinks");
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
            url: "{{ route('ajax_items_kot.store') }}",
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

    </script>
  @endpush

@endsection
