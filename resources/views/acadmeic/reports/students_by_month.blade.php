@extends('admin.layouts.main')

@section('title', 'Students List — Consolidated by Month')

@section('content')
<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <h4>Students List (Consolidated by Month)</h4>
        </div>
    </div>

    {{-- Filter card --}}
    <div class="row mb-3">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">

                    <label class="form-label">Select Month</label>
                    <div class="d-flex gap-2">
                        <select id="filter_month" class="form-select">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endfor
                        </select>

                        <select id="filter_year" class="form-select">
                            @for($y = now()->year; $y >= now()->year - 10; $y--)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mt-3 d-flex justify-content-between">
                        <button id="btn_load" class="btn btn-primary">Load</button>
                        <button id="btn_clear" class="btn btn-outline-secondary">Clear</button>
                    </div>

                    <small class="text-muted d-block mt-2">
                        Shows students admitted during the selected month.
                    </small>

                </div>
            </div>
        </div>

        {{-- Summary card --}}
        <div class="col-md-6 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-2">Opening / Closing counts by Class</h6>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0" id="summary_table">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Opening</th>
                                    <th>Closing</th>
                                </tr>
                            </thead>
                            <tbody id="summary_body">
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No data — select month & year and click Load
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Student table --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <table class="table table-striped table-bordered" id="students_table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Class / Section</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection 

@section('css')
<style>
.dt-buttons {
    margin-bottom: 10px; 
}
</style>
@endsection 


@section('js')
<script>
$(function(){

    var table = $('#students_table').DataTable({
        processing: true,
        serverSide: true,

        dom: 'Bfrtip',  // THIS LINE SHOWS BUTTONS

        buttons: [
            'pageLength',
            'copy',
            'csv',
            'excel',
            'pdf',
            'print'
        ],

        ajax: {
            url: "{{ route('academic.reports.students.month') }}",
            data: function(d){
                d.month = $('#filter_month').val();
                d.year  = $('#filter_year').val();
            }
        },

        columns: [
            { 
                data: null, 
                orderable:false, 
                searchable:false,
                render: function (data, type, row, meta){
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'student_id', name: 'student_id' , orderable: false, searchable: true },
            { data: 'name', name: 'name' , orderable: false, searchable: true },
            { data: 'father_name', name: 'father_name' , orderable: false, searchable: true},
            { data: 'class_section', orderable:false, searchable:false }
        ],

        order: [[1, 'desc']],

        lengthMenu: [10, 25, 50, 100]
    });


    /** LOAD SUMMARY COUNTS */
    function loadSummary(startDate, endDate){
        $('#summary_body').html(`
            <tr><td colspan="3" class="text-center">Loading...</td></tr>
        `);

        $.getJSON("{{ route('academic.reports.students.counts_summary') }}", {
            start_date: startDate,
            end_date: endDate
        }, function(data){

            if(!data || data.length === 0){
                $('#summary_body').html(`
                    <tr><td colspan="3" class="text-center text-muted">No records found</td></tr>
                `);
                return;
            }

            let html = '';
            data.forEach(function(row){
                html += `
                    <tr>
                        <td>${row.class_name}</td>
                        <td class="text-center">${row.opening}</td>
                        <td class="text-center">${row.closing}</td>
                    </tr>
                `;
            });

            $('#summary_body').html(html);

        }).fail(function(){
            $('#summary_body').html(`
                <tr><td colspan="3" class="text-center text-danger">Failed to load summary</td></tr>
            `);
        });
    }


    /** LOAD BUTTON */
    $('#btn_load').on('click', function(){

        var month = parseInt($('#filter_month').val());
        var year  = parseInt($('#filter_year').val());

        var start = new Date(year, month - 1, 1);
        var end   = new Date(year, month, 0);

        var s = start.toISOString().slice(0,10);
        var e = end.toISOString().slice(0,10);

        table.ajax.reload();
        loadSummary(s, e);
    });


    /** CLEAR BUTTON */
    $('#btn_clear').on('click', function(){
        $('#filter_month').val({{ now()->month }});
        $('#filter_year').val({{ now()->year }});

        table.ajax.reload();

        $('#summary_body').html(`
            <tr><td colspan="3" class="text-center text-muted">
                No data — select month & year and click Load
            </td></tr>
        `);
    });

});
</script>
@endsection
