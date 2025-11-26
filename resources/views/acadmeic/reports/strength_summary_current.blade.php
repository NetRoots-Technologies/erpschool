@extends('admin.layouts.main')

@section('title', 'Strength Summary Current')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-leftheader"></div>
                    <h4 class="page-title mb-0">Strength Summary Current</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Acedemic</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Strength Summary Current</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{-- Filters --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Filter Options</h3></div>
            <div class="card-body">
                <div class="row">
                    {{-- Class --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Class</label>
                            <select class="form-control select2" id="class_id">
                                <option value="" selected>--Select Class--</option>
                                @foreach($classes as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Section</label>
                            <select class="form-control select2" id="section_id">
                                <option value="" selected>--Select Section--</option>
                                @foreach($sections as $sa)
                                    <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Section</label>
                            <select class="form-control select2" id="acadmeic_session_id">
                                <option value="" selected>--Select Session--</option>
                                @foreach($acadmeic_sessions as $sa)
                                    <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                            <button type="button" id="resetFilters" class="btn btn-sm btn-info">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Strength Summary Current</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-vcenter text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Academic Session</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Total Student</th>
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

@endsection
@section('js')
    <script>
        $("document").ready(function() {
            var table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('academic.report.strength-summary-current') }}",
                    data: function(d) {
                        d.section_id = $('#section_id').val();
                        d.acadmeic_session_id = $('#acadmeic_session_id').val();
                        d.class_id = $('#class_id').val();
                    }
                },

                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    {
                        extend: 'copy',
                        text: 'Copy'
                    },
                    {
                        extend: 'csv',
                        text: 'CSV'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF'
                    },
                    {
                        extend: 'print',
                        text: 'Print'
                    }
                ],

                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],


                columns: [

                    {
                        'data': 'DT_RowIndex',
                        'name': 'DT_RowIndex',
                        'orderable': false,
                        'searchable': false
                    },
                    
                    {
                         data: 'session_name',
                         name: 'session_name',
                         orderable: true,
                         searchable: true
                     },
                    {
                        data: 'class',
                        name: 'class',
                        orderable: true,
                        searchable: true
                    },

                    {
                        data: 'section',
                        name: 'section',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'number_of_students',
                        name: 'number_of_students',
                        orderable: true,
                        searchable: true
                    }
                ]
            });

            $('#class_id , #section_id , #acadmeic_session_id').change(function() {
                table.ajax.reload();
            });

            $('#resetFilters').click(function() {
                $('#class_id').val('').trigger('change');
                $('#section_id').val('').trigger('change');
                $('#acadmeic_session_id').val('').trigger('change');
                table.ajax.reload();
            });
        });
    </script>
@endsection
