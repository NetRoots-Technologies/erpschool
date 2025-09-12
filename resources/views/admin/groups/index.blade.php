@inject('request', 'Illuminate\Http\Request')
@extends('admin.layouts.main')

@section('css')
@section('css')
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
          integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop

@section('content')
<div class="container">
    @if(session('successMessage'))
    <script>
            toastr.success("{{ session('successMessage') }}");
    </script>
    @endif

    <div class="row justify-content-center p-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"></div>
@if (Gate::allows('students'))
                    <div class="col-md-4">
                        <a href="{{ route('admin.groups.create') }}" class="btn btn-success mb-3 float-end"
                            type="submit">Add New Group</a>
                    </div>
                    @endif
                </div>
                <table class="table table-bordered permissions-table">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Account Number</th>
                            <th>Name</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($Groups) > 0)
                        @foreach ($Groups as $id => $Group)
                        <tr data-entry-id="{{ $id }}">
                            <th>{!! $Group['level'] !!}</th>
                            <td>
                                {!!$Group['number']!!}
                            </td>
                            <td>
                                {!!$Group['name']!!}
                            </td>
                            <td>

                                @if (!in_array($id, Config::get('constants.accounts_main_heads')))
                                <a href="{{ route('admin.groups.edit',[$id]) }}" class="ml-2 btn mb-1 btn-primary"><i
                                        class="fa fa-pencil-square" aria-hidden="true"></i></a>

                                {!! Form::open(array(
                                'class' => '',
                                'method' => 'DELETE',
                                'onsubmit' => "return confirm('".'Are you sure you want to Delete this?'.".');",
                                'route' => array('admin.groups.destroy', $id))) !!}
                                {{ csrf_field() }}
                                {{-- {!! Form::button('<i class="fa fa-trash"></i>', array('type' => 'submit','class' => 'btn
                                btn-danger ml-2 btn btn-danger')) !!} --}}
                                {!! Form::close() !!}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5">No data found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
       @if(session('successMessage'))
            toastr.success("{{ session('successMessage') }}");
        @endif
</script>
@endsection
