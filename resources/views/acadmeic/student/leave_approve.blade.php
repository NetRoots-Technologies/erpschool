@extends('admin.layouts.main')

@section('title', 'Student Leave Approval')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-3">

    <div class="row mb-2">
        <div class="col-12">

    <div class="page-header">
        <div class="page-leftheader"></div>
        <h4 class="page-title mb-0">Student Leave Approval</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Student Leave Approval</li
        </ol>  
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <table id="leaveApproveTable" class="table table-bordered table-striped" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Class</th>
                        <th>Session</th>
                        <th>Campus</th>
                        <th>Leave Reason</th>
                        <th>Approve By</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>

     </div>
    </div>

</div>

@endsection

{{-- ================== JS SECTION ================== --}}
@section('js')

<script>
$(function () {

    let table = $('#leaveApproveTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('academic.students.leave.aprove') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'student_id', name: 'student_id'},
            {data: 'class', name: 'class'},
            {data: 'session', name: 'session'},
            {data: 'campus', name: 'campus'},
            {data: 'leave_reason', name: 'leave_reason'},
            {data: 'approved_by', name: 'approved_by'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });


    // ========== Approve Button Action ==========
    $(document).on('click', '.approveBtn', function () {

        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('academic.students.leave.approve.submit') }}",
            type: "POST",
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                toastr.success(res.message);
                $('#leaveApproveTable').ajax().reload();
            },
            error: function (xhr) {
                toastr.error("Error approving student");
                console.log(xhr);
            }
        });

    });

});
</script>


@endsection

