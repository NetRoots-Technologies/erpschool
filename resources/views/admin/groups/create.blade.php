@extends('admin.layouts.main')


@section('content')
    <div class="container">
        <div class="row justify-content-center p-4">

        @if ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Create Chart of Accounts</strong> <span class="float-end"><a
                                    href="{{ route('admin.groups.index') }}" class="btn btn-primary">Back</a></span>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['method' => 'POST', 'route' => ['admin.groups.store'], 'id' => 'validation-form']) !!}
                            @csrf
                            <div class="row">
                                @include('admin.groups.fields')
                            </div>
                            {!! Form::submit('Save', ['class' => 'btn btn-danger globalSaveBtn']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script src="{{ url('public/js/admin/groups/create_modify.js') }}" type="text/javascript"></script>
@endsection

