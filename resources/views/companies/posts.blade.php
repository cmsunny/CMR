@extends('layouts.app')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Company Recycle bin</h1>
    </div>
<div class="card card-preview">
    <div class="card-inner table-responsive">

        <table class=" nowrap table dataTable no-footer dtr-inline"  id="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Logo</th>
                    <th>Action</th>


                </tr>
            </thead>
            <tbody>
                @if(count($data) > 0)
                {{-- {{dd($data->all())}} --}}
                @foreach($data as $row)

                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td>  <img src="/storage/{{$row->image}}" style=" border:2px; width:35px; height:25px;" class="img-rounded" align='center' /></td>

                    <td>
                        @if(request()->has('view_deleted'))

                            <a href="{{ route('company.restore', $row->id) }}" class="btn btn-success active btn-sm">Restore</a>

                            <a href="{{ route('company.delete', [$row->id, '3']) }}" class="btn btn-danger active btn-sm">Delete</a>

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


