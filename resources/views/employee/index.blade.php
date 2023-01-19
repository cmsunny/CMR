@extends('layouts.app')

@section('content') 
                    
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Employee</h1>
        <a class="btn btn-sm btn-dark" href="javascript:void(0)" id="createNewProduct"> Add new </a>
    </div>
</div>
    <div class="datatable-wrap my-3">
 <table class=" data-table nowrap table dataTable no-footer  collapsed" aria-describedby='DataTables_Table_0_info'>
  <thead>
     <tr>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">No</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">First Name</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">Last Name</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">Company</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">Email</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">Phone</th>
        <th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">Auction</th>
       
     </tr>
  </thead>
 </table>
</div>


{{-- Modal --}}
<div class="modal" id="ajaxModel" tabindex="-1">
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
            <div class="modal-footer bg-light">
                <span class="sub-text"></span>
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
              });
          });
        
      
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
                  $('#productForm').trigger("reset");
                  $('#ajaxModel').modal('hide');
                  table.draw();
                  }           
              }
              });
         }
  
         function createCompmany()
           {
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
                      $('#productForm').trigger("reset");
                      $('#ajaxModel').modal('hide');
                      table.draw();
                      }
                  
                  }
             });
        };
  
        
      
        //   Delete Product Code
      
      $('body').on('click', '.deleteProduct', function () {
       
          var product_id = $(this).data("id");
          confirm("Are You sure want to delete !");
          
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
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
  
  
      function printErrorMsg (msg) {
          $.each( msg, function( key, value ) {
            $('.'+key+'_err').text(value);
          });
      }
         
    });
    
  </script>
  @endpush