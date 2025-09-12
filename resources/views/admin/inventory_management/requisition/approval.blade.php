@extends('admin.layouts.main')

@section('title')
Requisition Approval
@stop

@section('content')


<div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="iForm" method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="iModalLabel">Rejection Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="status" value="REJECTED">
                        <input type="hidden" name="type" id="type" value="{{ $type }}">
                        <label for="comments" class="form-label">Reason</label>
                        <textarea class="form-control" id="comments" name="comments" placeholder="Write Reason"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="data_table">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script defer>
    $(document).ready(function(){
            'use strict';
            const uri = @json(route('datatable.data.requisitions'));
            const changeStatusUri = @json(route('inventory.requisitions.change.status'));
            const type = @json($type);
            console.log(type);

            const bdg = []
                bdg['HIGH'] = 'bg-danger',
                bdg['LOW'] = 'bg-secondary',
                bdg['MEDIUM'] = 'bg-warning';

            $(`#iForm`).validate({
                submitHandler: function(form) {
                    console.log(form);
                    const formData = $(form).serialize();
                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: formData,
                        beforeSend: function(xhr){
                            var token = $('meta[name="csrf-token"]').attr('content');
                            xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        },
                        success: function(response) {
                            if(response.success) {
                                $('#iModal').modal('hide');
                                dt.ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            if (xhr.responseJSON.errors) {
                                for (const [field, messages] of Object.entries(xhr.responseJSON.errors)) {
                                    messages.forEach(message => toastr.error(message));
                                }
                            } else if (xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('An unexpected error occurred.');
                            }

                        }
                    });
                }
            })

            let dt = $('#data_table').DataTable({
                ajax: {
                    url: uri,
                    type: 'POST',
                    dataSrc: function (json) {
                        return json.data;
                    },
                    data: function (d) {
                        d.type = type;
                        // d.year = $('#year').val();
                        // d.month = $('#month').val();
                        // d.employee_id = $('#employees_list').val(); ;

                    },
                    beforeSend: function (xhr) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                columns: [
                    { data: null, title: 'Sr', width: "2%", orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'item', title: 'Item',
                        render: function (data, type, row, meta) {
                            return row.item.name;
                        }

                    },
                    { data: 'quantity', title: 'Quantity' },
                    { data: 'justification', title: 'Justification' },
                    { data: 'priority', title: 'Priority', render: function(data, type, row, meta) {
                            return `<span class="badge ${bdg[data]} rounded-pill">${data}</span>`
                        }
                    },
                    { data: 'type', title: 'Type' },
                    { data: 'employee', title: 'Request By',
                        render: function (data, type, row, meta) {
                            return row.employee.name;
                        }

                    },
                    { data: 'branch', title: 'branch',
                        render: function (data, type, row, meta) {
                            return row.branch.name;
                        }

                    },
                    { data: null, title: 'Action', orderable: false,
                        render: function (data, type, row, meta) {
                            return row.status == 'APPROVED' ? `<span class="badge bg-success rounded-pill">${row.status}</span>` : (row.status == 'REJECTED' ? `<span class="badge bg-danger rounded-pill">${row.status}</span>` : `<span class="btn btn-sm btn-success accept" data-uri="${changeStatusUri}/${row.id}">Approve</span> <span data-uri="${changeStatusUri}/${row.id}" class="btn btn-sm btn-danger reject">Reject</i></span>` )
                        }
                    }

                ],
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    emptyTable: 'No data available in the table.'
                },
                // dom: `<"row"<"col-md-6"l><"col-md-6 text-end"B>>tipr`,
                // buttons: [
                //     {
                //         text: 'Add Item',
                //         className: 'btn btn-primary',
                //         action: function (e, dt, node, config) {
                //             $('#iModal').modal('show');
                //         }
                //     }
                // ],
                drawCallback: function(settings) {
                }
            });

            $(`#add-button`).on('click', function(e) {
                $('#iForm').trigger("reset");
                $('#id').val("");
                $('#quantity').val('')
                $('#justification').val('')
                $('#item').val('').trigger("change")
                $('#branch').val('').trigger("change")
                $('#priority').val('').trigger("change")
                $('#status').val('').trigger("change")
                $('#type').val('').trigger("change")
            });

            $(`#data_table`).on('click','.reject', function(e){
                e.preventDefault();
                $(`#iModal`).modal('show')
                $("#iForm").validate().resetForm();
                $(`#iForm`).attr('action', $(this).data('uri'))
            })

            $(`#data_table`).on('click','.accept', function(e){
                e.preventDefault();
                console.log("🚀 ~ e>>", e)

                $.ajax({
                        url: $(this).data('uri'),
                        type: 'GET',
                        data: {
                            status: 'APPROVED'
                        },
                        beforeSend: function(xhr){
                            var token = $('meta[name="csrf-token"]').attr('content');
                            xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        },
                        success: function(response) {
                            if(response.success) {
                                dt.ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("🚀 ~ error>>", error)
                            toastr.error('Something went wrong!');
                        }
                    });
            })
        })
</script>
@endsection
