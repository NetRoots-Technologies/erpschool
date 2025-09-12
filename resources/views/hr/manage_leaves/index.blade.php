@extends('admin.layouts.main')

@section('title')
    Leave Request
@stop

@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
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
        .hide {
            display: none;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Manage Leave</h3>
    </div>
    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body table-responsive">
                    <table class="w-100 table border-top-0 table-bordered border-bottom" id="data_table">
                        <thead>
                            <tr>
                                <th class="heading_style">No</th>
                                <th class="heading_style">Employee</th>
                                <th class="heading_style">Leave Type</th>
                                <th class="heading_style">Start Date</th>
                                <th class="heading_style">End Date</th>
                                <th class="heading_style">Total Days</th>
                                <th class="heading_style">Approved By</th>
                                <th class="heading_style">Status</th>
                                <th class="heading_style">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Approval Trail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="approvalModalBody">
        <!-- Dynamic Content -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')


<script type="text/javascript">
$(document).ready(function () {
    $('#data_table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', exportOptions: { columns: ':visible' } },
            { extend: 'pdf', exportOptions: { columns: ':visible' } },
            { extend: 'print', exportOptions: { columns: ':visible' } },
            'colvis'
        ],
        ajax: {
            url: "{{ route('datatable.manage.leaves.getdata') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'employee', name: 'employee' },
            { data: 'Leave Type', name: 'Leave Type' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'days', name: 'days' },
            { data: 'approved_by', name: 'approved_by' },
            { data: 'status', name: 'status' },
           {
                data: 'approval_requests',
                name: 'approval_requests',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    const modalId = 'approvalModal_' + row.id;
                    const approvals = JSON.parse(data || '[]');

                    let modalHtml = `<button class="btn btn-sm btn-info" data-toggle="modal" data-target="#${modalId}">View</button>`;

                    modalHtml += `
                    <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog" aria-labelledby="${modalId}_Label" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="${modalId}_Label">Approval Trail</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">`;

                    if (approvals.length > 0) {
                        modalHtml += `
                            <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="heading_style">Approver Name</th>
                                    <th class="heading_style">Date</th>
                                    <th class="heading_style">Remarks</th>
                                    <th class="heading_style">Status</th>
                                </tr>
                                </thead>
                                <tbody>`;
                        approvals.forEach(item => {
                            modalHtml += `<tr>
                                <td class="heading_style">${item.approver_name}</td>
                                <td class="heading_style">${item.created_at}</td>
                                <td class="heading_style">${item.remarks}</td>
                                <td class="heading_style">${item.status}</td>
                            </tr>`;
                        });
                        modalHtml += `
                                </tbody>
                            </table>
                            </div>`;
                    } else {
                        modalHtml += '<p class="text-muted">No approval records found.</p>';
                    }

                    modalHtml += `
                        </div>
                        </div>
                    </div>
                    </div>`;

                    return modalHtml;
                }

            },
        ]
    });


});
</script>
@endsection
