@extends('layouts.admin')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Departments</h1>
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
        <h3 class="card-title pb-2">Departments</h3>

        <div class="card-tools">
          <a class="btn btn-success" href="javascript:void(0)" id="createNew"><i class="fa fa-plus"></i> Add</a>
        </div>
      </div>
      <div class="card-body">

        <table class="table table-sm table-striped table-bordered table-hover data-table">
          <thead>
            <tr>
              <th width="100px">#</th>
              <th>Name</th>
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
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modelHeading"></h4>
        </div>

        <form id="ajaxForm" class="form-horizontal">
          <div class="modal-body">
            <input type="hidden" name="_id" id="_id">

            <div class="form-group">
              <label for="department_name" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-12">
                <input type="text" class="form-control" id="department_name" name="department_name" placeholder="Enter Name" value="" maxlength="50" required="required" >
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Description</label>
              <div class="col-sm-12">
                <textarea id="department_desc" name="department_desc" placeholder="Enter Description" class="form-control"></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Info</label>
              <div class="col-sm-12">
                <textarea id="department_info" name="department_info" placeholder="Enter Info" class="form-control"></textarea>
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
          ajax: "{{ route('ajaxdepartments.index') }}",
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
            {
              data: 'department_name',
              name: 'department_name'
            },
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
          $.get("{{ route('ajaxdepartments.index') }}" + '/' + _id + '/edit', function(data) {
            $('#modelHeading').html("Edit Department");
            $('#ajaxModel').modal('show');
            $('#_id').val(data.id);
            $('#department_name').val(data.department_name);
            $('#department_desc').val(data.department_desc);
            $('#department_info').val(data.department_info);
          })
        });

        $('#saveBtn').click(function(e) {
          e.preventDefault();
          removeErrors();
          $(this).html("<i class='fas fa-spin fa-spinner'></i> Saving...");
          $.ajax({
            data: $('#ajaxForm').serialize(),
            url: "{{ route('ajaxdepartments.store') }}",
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
                url: "{{ route('ajaxdepartments.store') }}" + '/' + _id,
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
