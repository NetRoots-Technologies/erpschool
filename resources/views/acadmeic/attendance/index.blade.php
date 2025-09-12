@extends('admin.layouts.main')

@section('title')
Attendance
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

        .alert {
            position: relative;
            padding-right: 50px;
        }

        .alert .close {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #000;
            cursor: pointer;
        }

        .alert-danger {
            color: #c60e07;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>
@endsection

@section('content')



<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Attendance</h3>
    </div>

    @if (session('import_errors'))
        <div class="alert alert-danger alert-dismissible fade show">
            <span class="close" data-dismiss="alert">&times;</span>
            <ul>
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row    mt-4 mb-4 ">
@if (Gate::allows('AttendanceReport-list'))
        <div class="col-12 text-right">
            <a href="{!! route('academic.student_attendance.create') !!}" class="btn btn-primary btn-md"><b>Add
                    Attendance</b></a>
        </div>
        <div class="col-12 text-right">
            <form action="{{ route('academic.import.studentAttendance') }}" id="fileForm" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Choose Excel File</label>
                    <input type="file" name="file" id="file" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{route('academic.montly-list')}}" id="form_validation" autocomplete="off"
                        method="post">
                        @csrf
                        <div class="w-100">
                            <div class="box-body" style="margin-top:30px;">

                                <div class="row mt-3  gy-2">

                                    <div class="col-md-6">
                                        <label for="campus"><b>Branch *</b></label>
                                        <select class="form-control select2 branch_select" name="branch_id">
                                            @foreach($branches as $branch)
                                                <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="branches"><b>Class: *</b></label>
                                        <select name="class_id"
                                            class="form-select select2 basic-single mt-3 select_class"
                                            aria-label=".form-select-lg example">
                                        </select>
                                    </div>



                                    <div class="col-md-6">
                                        <label for="branches"><b>Section: *</b></label>
                                        <select required name="section_id"
                                            class="form-select select2 basic-single mt-3 select_section"
                                            aria-label=".form-select-lg example">
                                            <option value="">Select Section</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="branches"><b>Report Type</b></label>
                                        <select name="report_type" class="form-select select2 basic-single mt-3"
                                            aria-label=".form-select-lg example">
                                            <option value="d">Daily</option>
                                            <option value="m">Monthly</option>
                                        </select>
                                    </div>


                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </div>

                                <div class="clearfix"></div>

                                <div class="panel-body pad table-responsive">
                                    <div id="loadData"></div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body table-responsive">
                    <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">
                                    <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                </th>
                                <th class="heading_style">Sr No</th>
                                <th class="heading_style">Branch</th>
                                <th class="heading_style">Class</th>
                                <th class="heading_style">Section</th>
                                <th class="heading_style">Date</th>
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
    {{--
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{--
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {
            var dataTable = $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'collection',
                        className: "btn-light",
                        text: 'Export',
                        buttons: [
                            {
                                extend: 'excel',
                                exportOptions: { columns: ':visible' }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: { columns: ':visible' }
                            },
                            {
                                extend: 'print',
                                exportOptions: { columns: ':visible' }
                            }
                        ]
                    },
                    {
                        extend: 'collection',
                        text: 'Bulk Action',
                        className: 'btn-light',
                        buttons: [
                            {
                                text: '<i class="fas fa-trash"></i> Delete',
                                className: 'btn btn-danger delete-button',
                                action: function () {
                                    var selectedIds = [];

                                    $('#data_table').find('.select-checkbox:checked').each(function () {
                                        selectedIds.push($(this).val());
                                    });

                                    if (selectedIds.length > 0) {
                                        $('.dt-button-collection').hide();

                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: 'You are about to perform a bulk action!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Yes, delete it!',
                                            cancelButtonText: 'Cancel'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: '{{ route('academic.studentAttendance-bulk') }}',
                                                    type: 'POST',
                                                    data: {
                                                        ids: selectedIds,
                                                        "_token": "{{ csrf_token() }}",
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        dataTable.ajax.reload();
                                                        Swal.fire('Deleted!', 'Your data has been deleted.', 'success');
                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.error(xhr.responseText);
                                                        toastr.error('AJAX request failed: ' + error);
                                                    }
                                                });
                                            }
                                        });
                                    } else {
                                        toastr.warning('No checkboxes selected.');
                                    }
                                }
                            },
                        ],
                    },
                    {
                        extend: 'colvis',
                        columns: ':not(:first-child)'
                    }
                ],
                columnDefs: [
                    { 'visible': false }
                ],
                ajax: {
                    url: "{{ route('datatable.get-student-Attendance') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.branch_id = $('.branch_select').val();
                        d.class_id = $('.select_class').val();
                        d.section_id = $('.select_section').val();
                        d.report_type = $('select[name="report_type"]').val(); // daily or monthly
                    }
                },
                columns: [
                    {
                        data: "checkbox",
                        render: function (data, type, row) {
                            return '<input type="checkbox" value="' + row.id + '" class="select-checkbox">';
                        },
                        orderable: false, searchable: false
                    },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'branch', name: 'branch' },
                    { data: 'AcademicClass', name: 'AcademicClass' },
                    { data: 'section', name: 'section' },
                    { data: 'attendance_date', name: 'attendance_date' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [2, 'desc']
            });

            // Reload DataTable on form submit
            $('#form_validation').on('submit', function (e) {
                e.preventDefault();
                dataTable.ajax.reload();
            });


            $("#fileForm").validate({
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
                rules: {
                    file: {
                        required: true,
                        extension: "xlsx|xls"
                    }
                },
                messages: {
                    file: {
                        required: "Please select a file",
                        extension: "Please select an Excel file (xlsx or xls)"
                    }
                }
            });

            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_class').empty();
                        sectionDropdown.append('<option value="" disabled selected>Select Class</option>');
                        data.forEach(function (academic_class) {
                            sectionDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();

            $('.select_class').on('change', function () {

                var class_id = $('.select_class').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_section').empty();
                        sectionDropdown.append('<option value="">Select Section</option>');

                        data.forEach(function (section) {
                            sectionDropdown.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });

        });

        function checkAll(source) {
            var checkboxes = $('.select-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        $(document).on("click", ".deleteBtn", function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            url = $(this).data('url');
            confirmDelete(id, url);
        });

        function confirmDelete(id, url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            toastr.success('Student deleted Sucessfully.');
                            location.reload();
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }


    </script>

    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('.alert').alert('close');
            }, 5000);
            $('.close').click(function () {
                $(this).parent().alert('close');
            });
        });
    </script>
@endsection
