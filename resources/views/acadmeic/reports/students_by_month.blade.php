@extends('admin.layouts.main')

@section('title', 'Students List — Consolidated by Month')

@section('css')
<style>
/* --- Layout & general --- */
.container-fluid > .row + .row { margin-top: .75rem; }

/* Top filter bar */
.top-filter {
    display:flex;
    gap:1rem;
    align-items:center;
    justify-content:space-between;
    padding:.6rem  .75rem;
    border-radius: .5rem;
    background: #fff;
    box-shadow: 0 1px 2px rgba(15,23,42,0.03);
    border: 1px solid #eef2f6;
}

/* filter controls */
.filter-controls { display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; }
.filter-actions { display:flex; gap:.5rem; align-items:center; }

/* Summary table visuals */
#summary_table thead th {
    background: #fbfcfe;
    border-bottom: 2px solid #eef2f6;
    font-weight: 600;
    color:#374151;
}
#summary_table tbody tr td { vertical-align: middle; }

/* change badges */
.badge-increase { background:#e6f8ed; color:#0b7a3e; font-weight:600; padding:.35rem .6rem; border-radius:.375rem; }
.badge-decrease { background:#fff1f0; color:#b02a37; font-weight:600; padding:.35rem .6rem; border-radius:.375rem; }
.badge-zero    { background:#f3f4f6; color:#6b7280; padding:.35rem .6rem; border-radius:.375rem; }

/* Summary totals row */
#summary_totals td { font-weight:700; background:#fbfbfc; }

/* Card header standardization */
.card-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:.5rem;
    padding: .7rem 1rem;
    border-bottom: 1px solid #eef2f6;
    background: #fff;
}
.card-title { margin:0; font-size:1rem; font-weight:700; color:#111827; }
.card-subtitle { font-size:.85rem; color:#6b7280; }

/* give Class column extra breathing room (start space) */
.class-name {
    padding-left: 20px;      /* more left space for professional look */
    letter-spacing: 0.2px;
    white-space: nowrap;
    max-width: 420px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* export button disabled state */
#export_summary[disabled] { pointer-events: none; opacity: .6; }

/* DataTables button spacing */
.dt-buttons { margin-bottom: .75rem; }

/* Responsive tweaks */
@media (max-width: 767px) {
    .top-filter { flex-direction: column; align-items: stretch; gap:.5rem; }
    .filter-controls { width:100%; }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
     <div class="row mb-3">
        <div class="col-12"><h4>Students List (Consolidated by Month)</h4></div>
    </div>

    {{-- Top filter bar --}}
    <div class="row">
        <div class="col-12">
            <div class="top-filter">
                <div class="filter-controls">
                    <label class="me-2 d-none d-sm-inline text-muted" style="font-size:.9rem; margin-top:6px;">Filters</label>

                    <select id="filter_month" class="form-select form-select-sm" style="width:180px;">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>

                    <select id="filter_year" class="form-select form-select-sm" style="width:120px;">
                        @for($y = now()->year; $y >= now()->year - 10; $y--)
                            <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <small class="text-muted d-none d-md-inline" style="margin-left:.5rem;">Select month & year → click Load</small>
                </div>

                <div class="filter-actions">
                    <button id="btn_load" class="btn btn-sm btn-primary" title="Load report">Load</button>
                    <button id="btn_clear" class="btn btn-sm btn-outline-secondary" title="Clear filters">Clear</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Students (Yajra DataTable) --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div>
                        <div class="card-title">Students</div>
                        <div class="card-subtitle">Detailed student list for the selected month</div>
                    </div>
                    <div class="card-actions">
                        {{-- keep small action area (DataTable buttons will appear above table) --}}
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover table-sm" id="students_table" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
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

    {{-- Summary below the students table --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div>
                        <div class="card-title">Opening / Closing counts by Class</div>
                        <div class="card-subtitle">Change = Closing − Opening</div>
                    </div>

                    <div class="card-actions">
                        <small id="summary-range" class="text-muted me-2"></small>
                        <button id="export_summary" class="btn btn-sm btn-outline-primary" title="Export summary CSV" disabled>Export CSV</button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0" id="summary_table">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th style="width:100px" class="text-center">Opening</th>
                                    <th style="width:100px" class="text-center">Closing</th>
                                    <th style="width:120px" class="text-center">In Students</th>
                                </tr>
                            </thead>
                            <tbody id="summary_body">
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-4">
                                        No data — select month & year and click Load
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot id="summary_totals_container"></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
$(function(){

    // helper to parse integer safely
    function fmt(n) { return (n === null || n === undefined || isNaN(n)) ? 0 : parseInt(n,10); }

    // Yajra DataTable (students)
    var table = $('#students_table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
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
        lengthMenu: [10, 25, 50, 100],
        language: {
            processing: '<div style="padding:6px">Loading students…</div>'
        }
    });

    // summary cache for CSV export
    let summaryCache = [];

    /** Export summary CSV (client-side) */
    function exportSummaryCSV(filename){
        if(!summaryCache || summaryCache.length === 0){
            alert('No summary data to export. Load a month first.');
            return;
        }
        let csv = 'Class,Opening,Closing,In Students\n';
        summaryCache.forEach(function(r){
            // escape double-quotes in class name
            let name = (''+r.class_name).replace(/"/g, '""');
            csv += `"${name}",${r.opening},${r.closing},${r.change}\n`;
        });

        // totals row
        let totalOpening = summaryCache.reduce((s,i)=>s+i.opening,0);
        let totalClosing = summaryCache.reduce((s,i)=>s+i.closing,0);
        let totalChange  = summaryCache.reduce((s,i)=>s+i.change,0);
        csv += `Totals,${totalOpening},${totalClosing},${totalChange}\n`;

        // download
        let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        let url = URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = filename || 'students_summary.csv';
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    }

    /** LOAD SUMMARY COUNTS */
    function loadSummary(startDate, endDate){
        $('#summary-range').text(`${startDate} → ${endDate}`);
        $('#summary_body').html(`<tr><td colspan="4" class="text-center p-3">Loading...</td></tr>`);
        $('#summary_totals_container').html('');
        summaryCache = [];
        $('#export_summary').attr('disabled', true);

        $.getJSON("{{ route('academic.reports.students.counts_summary') }}", {
            start_date: startDate,
            end_date: endDate
        }, function(data){

            if(!data || data.length === 0){
                $('#summary_body').html(`<tr><td colspan="4" class="text-center text-muted p-3">No records found</td></tr>`);
                return;
            }

            let html = '';
            let totalOpening = 0, totalClosing = 0, totalChange = 0;

            data.forEach(function(row){
                let opening = fmt(row.opening);
                let closing = fmt(row.closing);
                let inStudents = closing - opening;

                totalOpening += opening;
                totalClosing += closing;
                totalChange += inStudents;

                let badgeClass = inStudents > 0 ? 'badge-increase' : (inStudents < 0 ? 'badge-decrease' : 'badge-zero');

                html += `
                    <tr>
                        <td class="class-name">${row.class_name}</td>
                        <td class="text-center">${opening}</td>
                        <td class="text-center">${closing}</td>
                        <td class="text-center"><span class="${badgeClass}">${inStudents}</span></td>
                    </tr>
                `;

                summaryCache.push({
                    class_name: row.class_name,
                    opening: opening,
                    closing: closing,
                    change: inStudents
                });
            });

            let totalsHtml = `
                <tr id="summary_totals">
                    <td class="text-end pe-3">Totals</td>
                    <td class="text-center">${totalOpening}</td>
                    <td class="text-center">${totalClosing}</td>
                    <td class="text-center">${totalChange}</td>
                </tr>
            `;
            $('#summary_body').html(html);
            $('#summary_totals_container').html(totalsHtml);

            if(summaryCache.length > 0){
                $('#export_summary').attr('disabled', false);
            }

        }).fail(function(){
            $('#summary_body').html(`<tr><td colspan="4" class="text-center text-danger p-3">Failed to load summary</td></tr>`);
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
        $('#filter_month').val("{{ now()->month }}");
        $('#filter_year').val("{{ now()->year }}");

        table.ajax.reload();

        $('#summary_body').html(`<tr><td colspan="4" class="text-center text-muted p-3">No data — select month & year and click Load</td></tr>`);
        $('#summary-range').text('');
        $('#summary_totals_container').html('');
        summaryCache = [];
        $('#export_summary').attr('disabled', true);
    });

    // Export handler
    $('#export_summary').on('click', function(){ exportSummaryCSV('students_summary.csv'); });

    // Optionally auto-load current month on open:
    // $('#btn_load').trigger('click');

});
</script>
@endsection



