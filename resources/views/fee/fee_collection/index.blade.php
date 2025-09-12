@extends('admin.layouts.main')

@section('title')
    Fee Term
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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row w-100">
            <div class="card">
                <form id="searchForm">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="Academic"><b>Academic Session *</b></label>
                            <select name="session_id" class="form-control session_select select2 basic-single"
                                    required id="session_id">
                                <option value="" selected disabled>Select Session</option>
                                @foreach($sessions as $key => $item)
                                    <option value="{!! $key !!}">{!! $item !!}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="branches"><b>Company *</b></label>
                            <select name="company_id" class="form-control select2 basic-single company_select"
                                    required id="companySelect">
                                <option value="" selected disabled>Select Company</option>
                                @foreach($companies as $item)
                                    <option value="{{$item->id}}">{{ $item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-label">
                                    <label class="branch_Style"><b>Branch*</b></label>
                                </div>
                                <select name="branch_id" class="form-control select2 basic-single branch_select"
                                        required id="branch_id">
                                    <option value="" selected disabled>Select Branch</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branches"><b>Class: *</b></label>
                            <select required name="class_id"
                                    class="form-select select2 basic-single mt-3 class_select"
                                    aria-label=".form-select-lg example">
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <div class="row">
@if (Gate::allows('students'))
            {{--            <div class="col-12 text-right">--}}
            {{--                <a href="{!! route('admin.fee-category.create') !!}" class="btn btn-primary btn-md"><b>Add  Fee Category</b></a>--}}
            {{--            </div>--}}
                        @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body table-responsive">



                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>

                                <th class="heading_style">Sr No</th>
                                <th class="heading_style">Student Name</th>
                                <th class="heading_style">Student Class</th>
                                <th class="heading_style">Due Date</th>
                                <th class="heading_style">Valid Date</th>
                                <th class="heading_style">Fees</th>
                                <th class="heading_style">Voucher Number</th>
                                <th class="heading_style">Status</th>
                                <th class="heading_style">Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
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

    <script type="text/javascript">
        function initDataTable(data) {
            $('#data_table').DataTable({
                destroy: true,
                data: data,
                columns: [
                    {data: null, render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'student_class', name: 'student_class'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'valid_date', name: 'valid_date'},
                    {data: 'fees', name: 'fees'},
                    {data: 'voucher_number', name: 'voucher_number'},
                    { "data": "status", "name": "status", "escape": false },
                    {data: 'action', name: 'action'},
                ]
            });
        }

        $(document).ready(function () {
            initDataTable([]);
        });

        $('#searchForm').submit(function (e) {

            e.preventDefault();
            var formData = $(this).serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("admin.fee_collection.search") }}',
                type: 'POST',
                data: formData,
                success: function (response) {
                var table = $('#data_table').DataTable();
                table.clear().draw();
                table.rows.add(response.data).draw();
                toastr.success('Success', response.message);
                },
                error: function (xhr, status, error) {
                    var table = $('#data_table').DataTable();
                    table.clear().draw();
                    if (xhr.status === 404) {
                        toastr.error('Data not found', 'Not Found');
                    } else {
                        toastr.error('Error Fetching data', xhr.statusText);
                    }
                    console.error('Error fetching data:', xhr.statusText);
                }
                    });
                });
    </script>






    <script>
        $(document).ready(function () {

            $('#companySelect').on('change', function () {
                var selectedCompanyId = $('#companySelect').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var branchesDropdown = $('.branch_select').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {
                            branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.class_select').empty();
                        classDropdown.append('<option value="">Select class</option>');

                        data.forEach(function (academic_class) {
                            classDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });
        })
    </script>

@endsection
