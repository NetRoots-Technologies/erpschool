@extends('admin.layouts.main')

@section('title')
    Employee Allowance
@stop
@section('css')
    <style>
        .bg-info {
            background-color: #525252 !important;
        }

        .dt-button.buttons-columnVisibility {
            background: blue !important;
            color: white !important;
            opacity: 0.5;
        }

        .dt-button.buttons-columnVisibility.active {
            background: lightgrey !important;
            color: black !important;
            opacity: 1;
        }

        .select2.select2-container .select2-selection {
            height: 149px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        @if (Gate::allows('students'))

        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Employee Allowance</h3>
        </div>
        @endif
        <div class="row    mt-4 mb-4 ">
            {{--            @if (Gate::allows('Employee-create'))--}}

            {{--            @endif--}}
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">

                        <form action="" method="POST">
                            @csrf
                            <table class="w-100 table border-top-0 table-bordered">
                                <thead>
                                <tr>
                                    @foreach($employeeTypes as $type)
                                        <th>{!! $type !!}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($allowances as $allowance)
                                    <tr>
                                        @foreach($employeeTypes as $type)
                                            <td>
                                                {!! $allowance->type !!}
                                                &nbsp;
                                                <input type="text" name="{{ $type }}[]">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection
@section('js')

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    {{--<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{--<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
@endsection
