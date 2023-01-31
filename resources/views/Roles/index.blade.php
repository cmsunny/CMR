@extends('layouts.app')

@section('content')

<div class="nk-block nk-block-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Roles</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDefault" id="createNewProduct">Add New</button>
    </div>

    <div class="card card-preview">
        <div class="card-inner table-responsive">
            <table class=" nowrap table dataTable no-footer dtr-inline"  id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="ajaxModel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading">Modal Title</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="productForm" name="productForm" class="form-horizontal update-product">
                   <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" >
                            <span class="text-danger error-text name_err"></span>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" >
                            <span class="text-danger error-text email_err"></span>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="company" class="col-sm-2 control-label">Permission</label>
                        <div class="col-sm-12">
                            {{-- <select id="company_id" class="form-select" data-search="on" name="company_id" multiple> --}}
                                {{-- <option value="default_option" >Select Permissions </option> --}}
                                {{-- @foreach ($permissions as $permission)
                                    <option value="{{$permission->id}}">{{$permission->name}}</option>
                                @endforeach --}}
                              {{-- </select> --}}
                            <span class="text-danger error-text company_id_err"></span>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">

                     <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modalDefault" id="saveBtn">Save Data</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
    $(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
// Select2 function
    $('#company_id').select2({
        dropdownParent: $("#ajaxModel")
    });

        //   Click to Button
        $('#createNewProduct').click(function () {
              $('#saveBtn').val("create-product");
              $('#product_id').val('');
              $('#productForm').trigger("reset");
              $('#modelHeading').html("Create New Data");
              $('#ajaxModel').modal('show');
              resetErrorMessag();
        });

           //   Render DataTable

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('employee.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'name', name: 'name'},
              {data: 'title', name: 'title'},
              {data: 'action', name: 'action', orderable: false, searchable: false},

          ]
      });

    });
</script>
@endpush
