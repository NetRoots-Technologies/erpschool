@extends('admin.layouts.main')

@section('title')
Requisition
@stop

@section('content')

<div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="iForm" method="post" action="{{route('inventory.requisitions.store')}}">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="type" id="type" value="{{ $type }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="iModalLabel">Requisition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="item" class="form-label">Item</label>
                        <select class="form-control" id="item" name="item_id" required>
                            <options value=""></options>
                            @foreach ($items as $key => $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" min='1' class="form-control" id="quantity" name="quantity"
                            placeholder="Enter Quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="nameInput" class="form-label">Justification</label>
                        <textarea row='3' type="Email" class="form-control" id="justification" name="justification"
                            placeholder="Enter justification" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="branch" class="form-label">Branch</label>
                        <select class="form-control" id="branch" name="branch_id" required>
                            <options value=""></options>
                            @foreach ($branches as $key => $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-control" id="priority" name="priority" required>
                            <options value=""></options>
                            @foreach ($priorities as $key => $priority)
                            <option value="{{ $priority }}">{{ $priority }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="requisition_to" class="form-label">Requisition To</label>
                        <select class="form-control" id="requisition_to" name="requisition_to" required>
                            <option value="supplier" selected>Supplier</option>
                            @if($type == 'stationary')
                            <option value="stationery_shop">Stationery Shop</option>
                            @endif
                        </select>
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

    @if (Gate::allows('Requisitions-create'))
         <button type="button" id="add-button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#iModal">
        <i class="fa fa-plus"></i> Add
    </button>
    @endif
   

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
            const editPermission = @json(Gate::allows("Requisitions-edit"));
            const deletePermission = @json(Gate::allows("Requisitions-delete"));
            const uri = @json(route('datatable.data.requisitions'));
            const changeStatusUri = @json(route('inventory.requisitions.change.status'));
            const deleteUri = @json(route('inventory.requisitions.destroy'));
            const currectUri = window.location.href;
            const lastIndex = currectUri.split('/').pop();
            const type = @json($type);
            const bdg = []
                bdg['HIGH'] = 'bg-danger',
                bdg['LOW'] = 'bg-secondary',
                bdg['MEDIUM'] = 'bg-warning';

            $(`#item`).select2({
                placeholder: "Select item",
                allowClear: true,
                width: '100%',
            });
            $(`#branch`).select2({
                placeholder: "Select branch",
                allowClear: true,
                width: '100%',
            });
            $(`#priority`).select2({
                placeholder: "Select priority",
                allowClear: true,
                width: '100%',
            });
            $(`#status`).select2({
                placeholder: "Select status",
                allowClear: true,
                width: '100%',
            });

            $(`#iForm`).validate({
                errorPlacement: function(error, element) {
                    element.parent().after(error);
                },
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
                        let sData = new Map();
                        json.data.forEach(element => {
                        if (element.id != null) {
                            sData.set(element.id, element);
                        }
                        });
                        localStorage.setItem(lastIndex, JSON.stringify([...sData]));
                        console.log(json.data);
                        return json.data;
                    },
                    data: function (d) {
                        d.type = type;
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
                            return row?.item?.name ?? null;
                        }

                    },
                    { data: 'quantity', title: 'Quantity' },
                    { data: 'justification', title: 'Justification' },
                    { data: 'status', title: 'Status', render: function(data, type, row, meta) {
                        return `<span class="badge bg-secondary rounded-pill">${data}</span>`
                        }
                    },
                    { data: 'priority', title: 'Priority', render: function(data, type, row, meta) {
                            return `<span class="badge ${bdg[data]} rounded-pill">${data}</span>`
                        }
                    },
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
                            let html = '';
                            if(editPermission){
                                html += `<span class="btn btn-sm btn-warning edit-item" data-id="${row.id}" data-name="${row.name}"><i class="fa fa-pencil"></i></span>`;
                              
                            }
                            if(deletePermission){
                                html +=  `<span data-uri="${deleteUri}/${row.id}" class="btn btn-sm btn-danger delete-item"><i class="fa fa-trash"></i></span>`;
                            }
                            return html;
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
                $("#iForm").validate().resetForm();
                $('#id').val("");
                $('#quantity').val('')
                $('#justification').val('')
                $('#item').val('').trigger("change")
                $('#branch').val('').trigger("change")
                $('#priority').val('').trigger("change")
                $('#status').val('').trigger("change")
            });

            $(`#data_table`).on('click','.edit-item', function(e){
                $(`#iModal`).modal('show');
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                let id = $(this).data('id');
                $('#id').val(id)
                $('#nameInput').val($(this).data('name'));

                let sDatas = new Map(JSON.parse(localStorage.getItem(lastIndex)));
                if(sDatas.has(id)){
                    let sData = sDatas.get(id);
                    console.log("🚀 ~ sData>>", sData)
                    $('#quantity').val(sData.quantity)
                    $('#justification').val(sData.justification)
                    $('#item').val(sData.item_id).trigger("change")
                    $('#branch').val(sData.branch_id).trigger("change")
                    $('#priority').val(sData.priority).trigger("change")
                    $('#status').val(sData.status).trigger("change")
                }
            })

            $(`#data_table`).on('click','.delete-item, .changeStatus', function(e){
                e.preventDefault();

                $.ajax({
                        url: $(this).data('uri'),
                        type: 'GET',
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
            })
        })
</script>
@endsection
