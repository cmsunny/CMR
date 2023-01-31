
@extends('layouts.app')


@section('content2')
<div class="nk-block nk-block-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Employee Recycle bin</h1>
    </div>
<div class="card card-preview">
    <div class="card-inner table-responsive">
         <table class=" nowrap table dataTable no-footer dtr-inline"  id="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>FirstName</th>
                    <th>LastName</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>


                </tr>
            </thead>
            <tbody>
                @if(count($data) > 0)
                {{-- {{dd($data->all())}} --}}
                @foreach($data as $emp)
                {{-- {{dd($emp)}} --}}
                <tr>
                    <td>{{ $emp->id }}</td>
                    <td>{{ $emp->fname }}</td>
                    <td>{{ $emp->lname }}</td>
                    @if(!$emp->company->name)
                    {<td></td>}
                    @endif
                    <td>{{ $emp->company->name}}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->phone }}</td>

                    <td>
                        @if(request()->has('view_deleted'))

                            <a href="{{ route('employee.restore', $emp->id) }}" class="btn btn-success active btn-sm">Restore</a>

                            <a href="{{ route('employee.delete', [$emp->id, '3']) }}" class="btn btn-danger active btn-sm">Delete</a>

                        @endif
                    </td>
                </tr>

                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">No Post Found</td>
                </tr>

            @endif
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
