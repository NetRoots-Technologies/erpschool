@extends('admin.layouts.main')

@section('title','Approve Leaves')

@section('content')
<div class="container">
    <h3>Approve Leaves</h3>

    <table class="table table-bordered" id="leavesTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($leaves as $row)
            <tr id="row-{{ $row->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->employee->name ?? 'N/A' }}</td>
                <td>{{ $row->Quota->leave_type ?? 'N/A' }}</td>
                <td>{{ $row->start_date }}</td>
                <td>{{ $row->end_date }}</td>
                <td id="status-{{ $row->id }}">
                    {{ $row->status ?? 'Pending' }}
                </td>

                <td>
                    @if($row->status == null || $row->status == 'Pending')
                        <button 
                            class="btn btn-success btn-sm approve-btn"
                            data-id="{{ $row->id }}"
                            data-start="{{ $row->start_date }}"
                            data-end="{{ $row->end_date }}"
                        >
                            Approve
                        </button>

                        <button 
                            class="btn btn-danger btn-sm reject-btn"
                            data-id="{{ $row->id }}"
                            data-start="{{ $row->start_date }}"
                            data-end="{{ $row->end_date }}"
                        >
                            Reject
                        </button>
                    @else
                        <span class="badge bg-secondary">Completed</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {

    // 🔥 Remove row function
    function removeRow(id) {
        $('#row-' + id).fadeOut(400, function () {
            $(this).remove();
        });
    }

    // ✅ Approve Leave
    $('.approve-btn').click(function () {

        let id = $(this).data('id');
        let start_date = $(this).data('start');
        let end_date = $(this).data('end');

        let remarks = prompt('Enter remarks (optional):');

        $.post("{{ url('hr/approve_leaves/approve') }}/" + id, {
            _token: '{{ csrf_token() }}',
            start_date: start_date,
            end_date: end_date,
            remarks: remarks
        }, function (res) {
            if (res.status) {
                alert(res.message);
                removeRow(id);
            } else {
                alert(res.message ?? 'Already processed');
            }
        });
    });

    // ❌ Reject Leave
    $('.reject-btn').click(function () {

        let id = $(this).data('id');
        let start_date = $(this).data('start');
        let end_date = $(this).data('end');

        let remarks = prompt('Enter remarks (optional):');

        $.post("{{ url('hr/approve_leaves/reject') }}/" + id, {
            _token: '{{ csrf_token() }}',
            start_date: start_date,
            end_date: end_date,
            remarks: remarks
        }, function (res) {
            if (res.status) {
                alert(res.message);
                removeRow(id);
            } else {
                alert(res.message ?? 'Already processed');
            }
        });
    });

});
</script>
@endsection
