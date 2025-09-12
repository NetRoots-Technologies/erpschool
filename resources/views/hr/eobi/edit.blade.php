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
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Eobi</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.eobis.index') !!}" class="btn btn-primary btn-sm ">
                                    Back </a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('hr.eobis.update',$eobi->id) }}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" >
                                    <input type="hidden" name="branch_id" value="{!! @$eobi->branch_id !!}">
                                    <table id="users-table" class="table table-bordered table-striped datatable mt-3"
                                           style="width: 100%">
                                        <thead>

                                        <tr>
                                            <th>Sr.#</th>
                                            <th>User Name</th>
                                            <th>Total</th>
                                            <th>Employee</th>
                                            <th>Company</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{--@dd($attendance)--}}
                                        @php($i=1)

                                        <tr>
                                            <td>
                                                {{$i++}}
                                            </td>
                                            <td>
                                                {!! @$eobi->employee->name !!}
                                                <input type="hidden" name="employee_id"
                                                       value="{{$eobi->employee_id}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="total-{{$eobi->id}}" value="{!! $eobi->total !!}" data-id="{{$eobi->id}}"
                                                       name="total">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="employee-{{$eobi->id}}" value="{!! $eobi->employee_percent !!}" name="employee">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="company" value="{!! $eobi->company !!}" id="company-{{$eobi->id}}">
                                            </td>

                                        </tr>


                                        </tbody>
                                    </table>


                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary"
                                            style="margin-bottom: 10px;margin-left: 10px;">Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')

    <link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">

@endsection
@section('js')

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

    <script>
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

    </script>
    <script>
        $(document).ready(function () {
            $('.basic-multiple').select2();
        });
    </script>




@endsection

