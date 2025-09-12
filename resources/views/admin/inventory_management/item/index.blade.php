@extends('admin.layouts.main')

@section('title')
Item
@stop

@section('content')

<div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="iForm" method="post" action="{{ route('inventory.items.store') }}">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="iModalLabel">Item (RM)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nameInput" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter item name"
                            required aria-describedby="nameHelp">
                    </div>
                    @if($type == "food")
                    <div class="mb-3">
                        <label for="measuring_unit" class="form-label">Measuring Unit</label>
                        <select id="measuring_unit" name="measuring_unit" class="form-control" required>
                            <option value=""></option>
                            @foreach ($UNITS as $key => $unit)
                            <option value="{{$key}}">{{$key}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="container-fluid">
@if (Gate::allows('students'))
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

            const uri = @json(route('datatable.data.items'));
            const changeStatusUri = @json(route('inventory.items.change.status'));
            const deleteUri = @json(route('inventory.items.destroy'));
            const type = @json($type);

            $(`#measuring_unit`).select2({
                placeholder: "Select A Unit",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal')
            });

            $(`#iForm`).validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    name: {
                        required: "Please enter item name",
                        minlength: "Item name should be at least 3 characters long"
                    }
                },
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
                            toastr.error(xhr.responseJSON.message ?? "Something went wrong");
                        }
                    });
                }
            });

            let dt = $('#data_table').DataTable({
                ajax: {
                    url: uri,
                    type: 'POST',
                    dataSrc: function (json) {
                        return json.data;
                    },
                    data: function (d) {
                        d.type = type;
                        // d.type = $('#type').val();
                        // d.month = $('#month').val();
                        // d.employee_id = $('#employees_list').val(); ;

                    },
                    beforeSend: function (xhr) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                columns: [
                    { data: null, title: 'Sr No', width: "7%", orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'name', title: 'Name' },
                    { data: 'measuring_unit', title: 'Measuring Unit' },
                    { data: 'status', title: 'Status',
                        render: function (data, type, row, meta) {
                            return data ? `<a class="btn btn-success badge badge-success text-white changeStatus" data-uri="${changeStatusUri}/${row.id}">Active</a>` : `<a class="btn btn-danger badge badge-danger changeStatus" data-uri="${changeStatusUri}/${row.id}">Inactive</a>`;
                        },
                    },

                    { data: null, title: 'Action', orderable: false,
                        render: function (data, type, row, meta) {
                            return `<a class="btn btn-sm btn-warning edit-item" data-id="${row.id}" data-name="${row.name}" data-unit="${row.measuring_unit}"><i class="fa fa-pencil"></i></a>`;
                            // <span data-uri="${deleteUri}/${row.id}" class="btn btn-sm btn-danger delete-item"><i class="fa fa-trash"></i></span>
                        }},
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
                $(".error").removeClass("error");
                $('#nameInput').val("");
                $('#id').val("");
            });

            $(`#data_table`).on('click','.edit-item', function(e){
                console.log("🚀 ~ $(this).attr('data-name')>>", $(this).attr('data-name'), $(this).attr('data-unit'))
                $(`#iModal`).modal('show');
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $(".error").removeClass("error");
                $('#nameInput').val($(this).data('name'));
                $("#measuring_unit").val($(this).data('unit')).trigger('change');
                $('#id').val($(this).data('id'));

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
                        error: function (xhr, status, error) {
                            const response = xhr?.responseJSON;

                            if (response?.errors) {
                                Object.entries(response.errors).forEach(([field, messages]) => {
                                    if (Array.isArray(messages)) {
                                        messages.forEach(message => toastr.error(message));
                                    } else {
                                        toastr.error(messages);
                                    }
                                });
                            } else if (response?.message) {
                                toastr.error(response.message);
                            } else {
                                toastr.error(`Unexpected error: ${error || 'Unknown error occurred.'}`);
                            }
                        }
                    });
            })



        })
</script>
@endsection
