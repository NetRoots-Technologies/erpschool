@extends('admin.layouts.main')

@section('title', 'Students List — Consolidated by Year')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12"><h4>Students List (Consolidated by Year)</h4></div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <label>Year</label>
                    <select id="filter_year" class="form-select">
                        @for($y = now()->year; $y >= now()->year - 10; $y--)
                            <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <div class="mt-3 d-flex gap-2">
                        <button id="btn_load" class="btn btn-primary">Load</button>
                        <button id="btn_clear" class="btn btn-outline-secondary">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Opening / Closing counts by Class</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="summary_table">
                            <thead><tr><th>Class</th><th>Opening</th><th>Closing</th></tr></thead>
                            <tbody id="summary_body">
                                <tr><td colspan="3" class="text-center text-muted">No data — select year and click Load</td></tr>
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
            <div class="card">
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
.dt-buttons { margin-bottom: 10px; }
</style>

@endsection

@section('js')

<script>
$(function(){

    var table = $('#students_table').DataTable({
        processing: true,
        serverSide: true,

        dom: 'Bfrtip', // show buttons
        buttons: [
            'pageLength',
            'copy',
            'csv',
            'excel',
            'pdf',
            'print'
        ],

        ajax: {
            url: "{{ route('academic.reports.students.year') }}",
            data: function(d){
                d.year = $('#filter_year').val();
            }
        },

        columns: [
            { data: null, orderable:false, searchable:false, render: function(data,type,row,meta){ return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'student_id', name: 'student_id' },
            { data: 'name', name: 'name' },
            { data: 'father_name', name: 'father_name' },
            { data: 'class_section', name: 'class_section', orderable:false, searchable:false }
        ],

        order: [[1, 'desc']],
        lengthMenu: [10, 25, 50, 100],

        initComplete: function(){
            // Attempt to move buttons into a nicer spot if your layout uses Bootstrap columns
            try {
                var wrapper = $('#students_table_wrapper');
                if (wrapper.length) {
                    // Append to the wrapper's first column if present
                    var target = wrapper.find('.col-md-6:eq(0)');
                    if (target.length) {
                        table.buttons().container().appendTo(target);
                    } else {
                        // otherwise prepend to wrapper
                        table.buttons().container().prependTo(wrapper);
                    }
                }
            } catch (e) {
                console.warn('Buttons placement:', e);
            }
        }
    });


    /** LOAD SUMMARY COUNTS */
    function loadSummary(startDate, endDate){
        $('#summary_body').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');
        $.getJSON("{{ route('academic.reports.students.counts_summary') }}", {
            start_date: startDate,
            end_date: endDate
        }, function(data){
            if(!data || data.length === 0){
                $('#summary_body').html('<tr><td colspan="3" class="text-center text-muted">No records found</td></tr>');
                return;
            }

            var html = '';
            data.forEach(function(row){
                html += '<tr>';
                html += '<td>' + (row.class_name ?? '-') + '</td>';
                html += '<td class="text-center">' + (row.opening ?? 0) + '</td>';
                html += '<td class="text-center">' + (row.closing ?? 0) + '</td>';
                html += '</tr>';
            });

            $('#summary_body').html(html);
        }).fail(function(){
            $('#summary_body').html('<tr><td colspan="3" class="text-center text-danger">Failed to load summary</td></tr>');
        });
    }


    /** LOAD button */
    $('#btn_load').on('click', function(){
        var year = parseInt($('#filter_year').val());
        var start = year + '-01-01';
        var end   = year + '-12-31';

        table.ajax.reload();
        loadSummary(start, end);
    });


    /** CLEAR button */
    $('#btn_clear').on('click', function(){
        $('#filter_year').val({{ now()->year }});
        table.ajax.reload();
        $('#summary_body').html('<tr><td colspan="3" class="text-center text-muted">No data — select year and click Load</td></tr>');
    });

});
</script>
@endsection
