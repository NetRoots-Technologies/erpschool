@extends('admin.layouts.main')


@section('content')
{{--    <div class="box box-primary">--}}
{{--        <div class="box-header with-border">--}}
{{--            <h3 class="box-title">Update Group</h3>--}}
{{--            <a href="{{ route('admin.groups.index') }}" class="btn btn-success pull-right">Back</a>--}}
{{--        </div>--}}
{{--        <!-- /.box-header -->--}}
{{--        <!-- form start -->--}}
{{--        {!! Form::model($Group, ['method' => 'PUT', 'route' => ['admin.groups.update', $Group->id]]) !!}--}}
{{--        <div class="box-body">--}}
{{--            @include('admin.groups.fields')--}}
{{--        </div>--}}
{{--        <!-- /.box-body -->--}}

{{--        <div class="box-footer">--}}
{{--            {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}--}}
{{--        </div>--}}
{{--        {!! Form::close() !!}--}}
{{--    </div>--}}





    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Update Group</strong> <span class="float-end"><a
                                    href="{{ route('admin.groups.index') }}" class="btn btn-primary">Back</a></span>
                        </div>
                        <div class="card-body">
                            {!! Form::model($group, ['method' => 'PUT', 'route' => ['admin.groups.update', $group->id]]) !!}
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
    <!-- Select2 -->
    {{--<script src="{{ url('adminlte') }}/bower_components/select2/dist/js/select2.full.min.js"></script>--}}
    <script src="{{ url('public/js/admin/groups/create_modify.js') }}" type="text/javascript"></script>
@endsection
