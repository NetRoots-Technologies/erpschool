@extends('admin.layouts.main')

@section('title')
    Eobi
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Settings</h3>
                        <table class="table table-responsive table-bordered">
                            <thead>
                            <tr>
                                <th colspan="2" class="text-center">{!! $settings->key !!}</th>
                                <th colspan="2" class="text-center">{!! $settings->key !!}</th>
                                <th colspan="2" class="text-center">{!! $settings->key !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach($values as $key => $value)
                                    <td>
                                        <input name="text" class="form-control" value="{!! $key !!}">
                                    </td>
                                    <td>
                                        <input name="text" class="form-control" value="{!! $value !!}">
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
