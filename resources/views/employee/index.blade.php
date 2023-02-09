@extends('layouts.app')

@section('content')


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Employee</h1>
        @can('create_employee')

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDefault" id="createNewProduct">Add Data</button>
        @endcan
    </div>
    <div class="card card-preview">
        <div class="card-inner table-responsive">
            <table class=" data-table nowrap table dataTable no-footer  collapsed" >
                <thead>
                    <tr>
                        <th>No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        {{-- <th>Password</th> --}}
                        {{-- <th>Role</th> --}}
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        @can('edit_employee','delete_employee')
                            <th>Action</th>
                        @endcan
                    </tr>
                </thead>
            </table>
        </div>
    </div>

{{-- Modal --}}
<div class="modal fade" tabindex="-1" id="ajaxModel" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading"></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form id="productForm" name="productForm" class="form-horizontal">
                   <input type="hidden" name="employee_id" id="employee_id">
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label">FirstName</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter FirstName" value="" maxlength="50">
                            <span class="text-danger error-text first_name_err"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-2 control-label">LastName</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter LastName" value="" maxlength="50">
                            <span class="text-danger error-text last_name_err"></span>
                            <div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="" >
                            <span class="text-danger error-text password_err"></span>
                            <div></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="" maxlength="50">
                            <span class="text-danger error-text email_err"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role" class="col-sm-2 control-label">Role</label>
                        <div class="col-sm-12">
                            <select id="role_id" class="form-control" placeholder="Select Roles" name="role_id" >
                                <option value="" >Select role </option>
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                              </select>
                            <span class="text-danger error-text role_id_err"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company" class="col-sm-2 control-label">Company</label>
                        <div class="col-sm-12">
                            <select id="company_id" class="form-select" data-search="on" name="company_id" >
                                <option value="" >Select Companies </option>
                                @foreach ($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                              </select>
                            <span class="text-danger error-text company_id_err"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="phone" name="phone"  placeholder="Enter Phone" class="form-control">
                            <span class="text-danger error-text phone_err"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                     <button type="button" class="btn btn-primary add-employee" id="saveBtn" value="create">Save
                     </button>
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

        //   Pass Header Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#company_id').select2({
            dropdownParent: $("#ajaxModel")
        });



        //   Click to Button
        $('#createNewProduct').click(function () {
            $('#saveBtn').val("create-product");
            $('#employee_id').val('');
            $('#productForm').trigger("reset");
            $('#modelHeading').html("Create New Data");
            $("#company_id").val('').trigger("change");
            // $("#role_id").val('').trigger("change");
            $('#ajaxModel').modal('show');
            resetErrorMessag();
        });

           //   Click to Edit Button
          $('body').on('click', '.editProduct', function () {
            resetErrorMessag();
            var employee_id = $(this).data('id');
            $.get("{{ route('employee.index') }}" +'/' + employee_id +'/edit', function (data) {
                $('#modelHeading').html("Edit Employee");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#employee_id').val(data.id);
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#role_id').val(data.roles[0].id);
                $('#company_id').val(data.company_id);
                $('#company_id').trigger('change')
                $('#email').val(data.email);
                $('#phone').val(data.phone);

            });
          });

            //   Create Product Code
          $('#saveBtn').click(function (e) {
              e.preventDefault();
              $(this).html('Save');
              var id = $("#employee_id").val()
              if(id) {

                updateCompnay(id);

              } else {
                  createCompmany();
              }
          });

          // Update Function
          function updateCompnay(id)
           {
            resetErrorMessag();
            var url = '{{ route("employee.update", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
            data: $('#productForm').serialize(),
            url: url,
            type: "PUT",
            dataType: 'json',
            success: function (data) {
                if(data.status == '402'){
                    printErrorMsg(data.errors);
                }
                if(data.status == '200'){
                Swal.fire({
                    title: 'Do you want to save the changes?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    denyButtonText: `Don't save`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Saved!', '', 'success')
                            $('#productForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        } else if (result.isDenied) {
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })
                }
                }
            });
           }
           // Create Function
        function createCompmany()
        {
            resetErrorMessag();
            var url = '{{ route("employee.store") }}';
            $.ajax({
                data: $('#productForm').serialize(),
                url: url,
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    // console.log(data);
                    if(data.status == '402'){
                        printErrorMsg(data.errors);
                    }
                    if(data.status == '200'){
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    $('#productForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                    }
                }
            });
        };

        //   Delete Product Code

      $('body').on('click', '.deleteProduct', function () {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You want to delete it !",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'No, cancel!',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {
                swalWithBootstrapButtons.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                )
                var employee_id = $(this).data("id");
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('employee.store') }}"+'/'+employee_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your Data is safe :)',
                'error')
                 }
            })
      });

       //   Render DataTable

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('employee.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'first_name', name: 'first_name'},
              {data: 'last_name', name: 'last_name'},
            //   {data: 'password', name: 'password'},
            //   {data: 'role', name: 'role_id'},
              {data: 'company', name: 'company_id'},
              {data: 'email', name: 'email'},
              {data: 'phone', name: 'phone'},
              @can('edit_employee','delete_employee')
              {data: 'action', name: 'action', orderable: false, searchable: false},
              @endcan
          ]
      });


      function printErrorMsg (msg) {
          $.each( msg, function( key, value ) {
            $('.'+key+'_err').text(value);
          });
      }

      function resetErrorMessag() {
        $('.first_name_err').text('');
        $('.last_name_err').text('');
        $('.company_id_err').text('');
        $('.password_err').text('');
        $('.role_id_err').text('');
        $('.email_err').text('');
        $('.phone_err').text('');
      }

    });

  </script>
  @endpush
