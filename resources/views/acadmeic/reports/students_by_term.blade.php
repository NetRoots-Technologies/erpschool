@extends('admin.layouts.main')

@section('title', 'Students List — By Term (Bi-Annual)')

@section('content')
<div class="container-fluid">
    <div class="row mb-3"><div class="col-12"><h4>Students List (By Term — Bi-Annual)</h4></div></div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <label>Term</label>
                    <select id="filter_term" class="form-select">
                        <option value="1">Term 1 (Jan — Jun)</option>
                        <option value="2">Term 2 (Jul — Dec)</option>
                    </select>

                    <label class="mt-2">Year</label>
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
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Opening / Closing counts by Class</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead><tr><th>Class</th><th>Opening</th><th>Closing</th></tr></thead>
                            <tbody id="summary_body"><tr><td colspan="3" class="text-center text-muted">No data</td></tr></tbody>
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
                        <thead><tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Father Name</th><th>Class / Section</th></tr></thead>
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
            url: "{{ route('academic.reports.students.term') }}",
            data: function(d){
                d.term = $('#filter_term').val();
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
            // ensure buttons visible in case layout hides default location
            try {
                var btnContainer = $('#students_table_wrapper .col-md-6:eq(0)');
                if (btnContainer.length) {
                    table.buttons().container().appendTo(btnContainer);
                }
            } catch(e) { console.warn('Button placement failed', e); }
        }
    });

    function loadSummary(startStr, endStr){
        $('#summary_body').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');
        $.getJSON("{{ route('academic.reports.students.counts_summary') }}", { start_date: startStr, end_date: endStr }, function(data){
            if(!data || data.length === 0){ 
                $('#summary_body').html('<tr><td colspan="3" class="text-center text-muted">No records</td></tr>'); 
                return; 
            }
            var html = '';
            data.forEach(function(r){ 
                html += `<tr><td>${r.class_name}</td><td class="text-center">${r.opening}</td><td class="text-center">${r.closing}</td></tr>`; 
            });
            $('#summary_body').html(html);
        }).fail(function(){
            $('#summary_body').html('<tr><td colspan="3" class="text-center text-danger">Failed to load</td></tr>');
        });
    }

    $('#btn_load').on('click', function(){
        var term = parseInt($('#filter_term').val());
        var year = parseInt($('#filter_year').val());
        var start, end;
        if (term === 1) {
            start = year + '-01-01';
            end   = year + '-06-30';
        } else {
            start = year + '-07-01';
            end   = year + '-12-31';
        }

        table.ajax.reload();
        loadSummary(start, end);
    });

    $('#btn_clear').on('click', function(){
        $('#filter_term').val(1);
        $('#filter_year').val({{ now()->year }});
        table.ajax.reload();
        $('#summary_body').html('<tr><td colspan="3" class="text-center text-muted">No data</td></tr>');
    });
});
</script>
@endsection
