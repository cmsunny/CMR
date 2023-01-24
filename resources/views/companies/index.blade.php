@extends('layouts.app')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Companies</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDefault" id="createNewProduct">Add Data</button>
</div>
    <div class="card card-preview">
        <div class="card-inner">
    <table class=" nowrap table dataTable no-footer dtr-inline"  id="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Logo</th>
                <th>Website</th>
                <th>Action</th>
            
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
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
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="" maxlength="50" >
                            <span class="text-danger error-text email_err"></span>
                        </div>
                       
                    </div>

                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">Logo</label>
                        <div class="col-sm-12">
                            <div class="custom-file">
                                <input type="file" name="image"id='image' class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                                <span class="text-danger error-text image_err"></span>
                            </div>
                        </div>
                    </div>
       
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Website</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="website" name="website"  placeholder="Enter Website URL" class="form-control">
                            <span class="text-danger error-text website_err"></span>
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
      
        /*------------------------------------------
        --------------------------------------------
        Pass Header Token
        --------------------------------------------
        --------------------------------------------*/ 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        /*------------------------------------------
        --------------------------------------------
        Click to Button
        --------------------------------------------
        --------------------------------------------*/
        $('#createNewProduct').click(function () {
            $('#saveBtn').val("create-product");
            $('#product_id').val('');
            $('#productForm').trigger("reset");
            $('#modelHeading').html("Create New Data");
            $('#ajaxModel').modal('show');
            resetErrorMessag();
            
          
        });
      
        /*------------------------------------------
        --------------------------------------------
        Click to Edit Button
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.editProduct', function () {
        var product_id = $(this).data('id');
                resetErrorMessag();
            $.get("{{ route('company.index') }}" +'/' + product_id +'/edit', function (data) {
                $('#modelHeading').html("Edit Company");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#website').val(data.website);
            });
        });
      
    /*------------------------------------------
    --------------------------------------------
    Create Update Code
    --------------------------------------------
    // --------------------------------------------*/
  
        $('#productForm').on('submit',(function(e) { 
            e.preventDefault();
            var formData = new FormData(this);
            var id = $("#product_id").val()
            // $('#createNewProduct').click(function(){
            //    location.reload(true);
            // })

            if(id) {
                updateCompnay(id,formData);
                
            } else {
                createCompmany(formData);
            }

        }))
        function updateCompnay(id, formData)
        {
            resetErrorMessag();
            formData.append('_method', 'PUT');
            var url = '{{ route("company.update", ":id") }}';
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
                    Swal.fire({
                    title: 'Do you want to save the changes?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    denyButtonText: `Don't save`,
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
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

      function createCompmany(formData)
       {
            resetErrorMessag();
            var url = '{{ route("company.store") }}';
                $.ajax({
                data: formData,
                url: url,
                type: "POST",
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    // console.log(data);
                    if(data.status == '402')
                    {
                        printErrorMsg(data.errors);
                    }
                    if(data.status == '200')
                    {
                        // $('#saveBtn').refreshPage();
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

      
    /*------------------------------------------
    --------------------------------------------
    Delete  Code
    --------------------------------------------
    --------------------------------------------*/
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
                var product_id = $(this).data("id");                
                
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('company.store') }}"+'/'+product_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your Data is safe :)',
                'error'
                )
            }
            })
       
         

        
     
        
    });
      /*------------------------------------------
    --------------------------------------------
    Render DataTable
    --------------------------------------------
    --------------------------------------------*/
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('company.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'image', name: 'image'},
                {data: 'website', name: 'website'},
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
        $('.email_err').text('');
        $('.image_err').text('');
        $('.website_err').text('');
      }
        
    });

  
  
</script>
@endpush