@extends('admin.layouts.main')

@section('title')
    Settings
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
        .select2.select2-container .select2-selection{
            height: 149px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Settings</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            {{--            @if (Gate::allows('Employee-create'))--}}

            {{--            @endif--}}
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">

                        <form action="{{ route('hr.update.setting') }}" method="POST">
                            @csrf
                            <table class="w-100 table border-top-0 table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Values</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($settingValue as $key => $value)
                                    <tr>
                                        <td width="300px"><label>{{ $value['name'] }}</label></td>
                                        <td>
                                            @if($key === 'designations_for_per_hour' || $key === 'designations_for_compensatory_leaves')
                                                <select name="{{ $key }}[]" class="form-select select2 basic-single mt-3" multiple style="width: 100%;">
                                                    @foreach($Designations as $designation)
                                                        <option value="{{ $designation->id }}" {{ in_array($designation->id, $value['values']) ? 'selected' : '' }}>
                                                            {{ $designation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif(is_array($value['values']))
                                                <table class="nested-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Value</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($value['values'] as $innerKey => $innerValue)
                                                        <tr>
                                                            <td><label>{{ $innerKey }}</label></td>
                                                            <td style="text-align: center;"> <input type="text" name="{{ $key }}[{{ $innerKey }}]" value="{{ $innerValue }}" id="{{"$key-$innerKey"}}"></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <input type="text" name="{{ $key }}" value="{{ $value['values'] }}">
                                            @endif
                                        </td>
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

<script>
$(document).ready(function () {
    console.log("page loaded");
    $("#eobi-total").attr("disabled", true);

    $("#eobi-company, #eobi-employee").on("input", function (e) {
        e.preventDefault();
        console.log("changed");
        var company = parseInt($("#eobi-company").val()) || 0;
        var employee = parseInt($("#eobi-employee").val()) || 0;

        var total = company + employee;

        $("#eobi-total").val(total);

        console.log(company, employee, total);
    });
});


</script>




@endsection
