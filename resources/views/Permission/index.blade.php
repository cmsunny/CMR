@extends('layouts.app')

@section('content')

<div class="nk-block nk-block-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Permission</h1>
        <button type="button" class="btn btn-primary createNewRole" id="NewRole">Add New</button>
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
<div class="modal fade" tabindex="-1" id="RoleModel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading">Modal Title</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="roleForm" name="roleForm" class="form-horizontal modal_form">
                   <input type="hidden" name="roleModel_id" id="roleModel_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control name" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" >
                            <span class="text-danger error-text name_err"></span>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control title" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" >
                            <span class="text-danger error-text title_err"></span>
                        </div>

                    </div>

                    <div class="col-sm-offset-2 col-sm-10">

                     <button type="submit" class="btn btn-primary saveButton" data-toggle="modal" data-target="#modalDefault" id="saveBtn">Save Data</button>
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
    // $('#company_id').select2({
    //     dropdownParent: $("#RoleModel")
    // });

        //   Click to Button
        $('#NewRole').click(function () {
              $('#saveBtn').val("create-role");
              $('#roleModel_id').val('');
              $('#roleForm').trigger("reset");
              $('#modelHeading').html("Add New");
              $('#RoleModel').modal('show');
              resetErrorMessag();
        });

        $('.createNewRole').click(function(e){

            e.preventDefault();
                var url = '{{ route("permission.create") }}';
                $.ajax({
                url: url,
                type: "GET",
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#RoleModel").modal('show');
                    if(data.status == '402')
                    {
                        printErrorMsg(data.errors);
                    }
                }
            });
        })

        // Edit Role

        $('body').on('click', '.editProduct', function () {
        var roleModel_id = $(this).data('id');
                resetErrorMessag();
            $.get("{{ route('permission.index') }}" +'/' + roleModel_id +'/edit', function (data) {

                $('#modelHeading').html("Edit Permissions");
                $('#saveBtn').val("edit-user");
                $('#roleModel_id').val(data.id);
                $('#name').val(data.name);
                $('.title').val(data.title);
                $('#RoleModel').modal('show');
            });
        });

        // MOdal Submit

        $('.modal_form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $("#roleModel_id").val();

            if(id) {
                updateCompnay(id,formData);

            } else {
                createCompmany(formData);
            }
        }))

        //update Role Function
        function updateCompnay(id, formData)
        {
            resetErrorMessag();
            formData.append('_method', 'PUT');
            var url = '{{ route("permission.update", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
            data: formData,
            url: url,
            type: "POST",
            cache:false,
            contentType: false,
            processData: false,
            success: function (data) {
                if(data.status == '402')
                {
                    printErrorMsg(data.errors);
                }
                if(data.status == '200')
                {
                    // permission(data, data.permissions);
                    Swal.fire({
                    title: 'Do you want to save the changes?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    denyButtonText: `Don't save`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Saved!', '', 'success')

                        $('#roleForm').trigger("reset");
                        $('#RoleModel').modal('hide');
                        table.draw();
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                    })
                }
            }
            });
        }

        // create Role function
            function createCompmany(formData)
            {
                resetErrorMessag();
                var url = '{{ route("permission.store") }}';
                $.ajax({
                data:formData,
                url: url,
                type: "POST",
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data.status == '402')
                    {
                        printErrorMsg(data.errors);
                    }
                    if(data.status == '200')
                    {

                        Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                        })

                        $('#roleForm').trigger("reset");
                        $('#RoleModel').modal('hide');
                        table.draw();
                    }
                }
           });

        }


        // Delete Role

        $('body').on('click', '.deleteProduct', function () {
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false})
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
            var roleModel_id = $(this).data("id");
            $.ajax({
                type: "DELETE",
                url: "{{ route('permission.store') }}"+'/'+roleModel_id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    // console.log('Error:', data);
                }
            });

        } else if (result.dismiss === Swal.DismissReason.cancel){
            swalWithBootstrapButtons.fire(
            'Cancelled',
            'Your Data is safe :)',
            'error'
            )
        }
        })
    });

           //   Render DataTable

      var table = $('#data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('permission.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'name', name: 'name'},
              {data: 'title', name: 'title'},
              {data: 'action', name: 'action', orderable: false, searchable: false},

          ]
      });

      // Error fUNCTION
      function printErrorMsg (msg) {
            $.each( msg, function( key, value ) {
            $('.'+key+'_err').text(value);
            });
        }
        // Reset Error
        function resetErrorMessag() {
            $('.name_err').text('');
            $('.title_err').text('');
        }

    });
</script>
@endpush
