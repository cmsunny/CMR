@extends('layouts.app')

@section('content') 
                    

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Employee</h1>
        {{-- <button type="button" class="btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#modalDefault" id="createNewProduct">Add Data</button> --}}
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDefault" id="createNewProduct">Add Data</button>
    </div>
    <div class="card card-preview">
    <div class="card-inner">
 <table class=" data-table nowrap table dataTable no-footer  collapsed" >
  <thead>
     <tr>
        <th>No</th>
        <th>First Name</th>
        <th>Last Name</th>
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
                   <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="fname" class="col-sm-2 control-label">FirstName</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter FirstName" value="" maxlength="50">
                            <span class="text-danger error-text fname_err"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lname" class="col-sm-2 control-label">LastName</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter LastName" value="" maxlength="50">
                            <span class="text-danger error-text lname_err"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company" class="col-sm-2 control-label">Company</label>
                        <div class="col-sm-12">
                            <select id="company" class="form-control" name="company_id" placeholder="Enter company Name" value="">
                                
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                <option value="{{$company->id}}" id="option">{{$company->name}}</option>                                    
                                @endforeach
                              </select>
                            <span class="text-danger error-text company_err"></span>
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
        
    
        
        
            //   Click to Button
   
          $('#createNewProduct').click(function () {
              $('#saveBtn').val("create-product");
              $('#product_id').val('');
              $('#productForm').trigger("reset");
              $('#modelHeading').html("Create New Data");
              $('#ajaxModel').modal('show');
              resetErrorMessag();
          });
        
   
           //   Click to Edit Button
     
          $('body').on('click', '.editProduct', function () {
          var product_id = $(this).data('id');
          
              $.get("{{ route('employee.index') }}" +'/' + product_id +'/edit', function (data) {
                  $('#modelHeading').html("Edit Employee");
                  $('#saveBtn').val("edit-user");
                  $('#ajaxModel').modal('show');
                  $('#product_id').val(data.id);
                  $('#fname').val(data.fname);
                  $('#lname').val(data.lname);
                  $('#company').val(data.company_id);
                  $('#email').val(data.email);
                  $('#phone').val(data.phone);

                  resetErrorMessag();
              });
          });


        //   $('.editProduct').click(function(e){
        //     e.preventDefault();
        //     console.log('edit button');
        //     $('.fname_err').text('');
        //     $('.lname_err').text('');
        //     $('.company_err').text('');
        //   })
        
      
            //   Create Product Code
     
          $('#saveBtn').click(function (e) {
              e.preventDefault();
              $(this).html('Save');
              var id = $("#product_id").val()
              if(id) {
                  updateCompnay(id);
              } else {
                  createCompmany();
              }
          });

  
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
                var product_id = $(this).data("id");
                
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('employee.store') }}"+'/'+product_id,
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
        
       //   Render DataTable
  
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('employee.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'fname', name: 'fname'},
              {data: 'lname', name: 'lname'},
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
        $('.fname_err').text('');
        $('.lname_err').text('');
        $('.company_err').text('');
      }
         
    });
    
  </script>
  @endpush