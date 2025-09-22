@extends('admin.layouts.main')

@section('title')
Inventry
@stop

@section('content')

<div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="iForm" method="post" action="{{ route('inventory.inventry.store') }}">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="iModalLabel">Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" min="1" class="form-control" id="sale_price" name="sale_price" placeholder="Sale Price"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date"  name="expiry_date" placeholder="Expiry Date" min="{{ date('Y-m-d') }}"
                            required>
                    </div>
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
{{-- const changeStatusUri = @json(route('inventory.Inventry.change.status')); --}}
{{-- const deleteUri = @json(route('inventory.Inventry.destroy')); --}}
<script defer>
    $(document).ready(function(){
            'use strict';

            const uri = @json(route('datatable.data.inventory'));
            const type = @json($type);
            const changeStatusUri = "";
            const deleteUri = "";


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
                    },
                    expiry_date: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter item name",
                        minlength: "Item name should be at least 3 characters long"
                    },
                    expiry_date: {
                        required: "Please enter date",
                    },

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
                { data: 'branch.name', title: 'Branch Name'},
                { data: 'measuring_unit', title: 'Measuring Unit', render: function(data, type, row) {
                        return data ?? 'None';
                }},
                { data: 'quantity', title: 'Quantity' },
                    { data: 'unit_price', title: 'Unit Price' },
                    { data: 'cost_price', title: 'Cost Price' },
                    // { data: 'sale_price', title: 'Sale Price',
                    // render: function (data, type, row, meta) {
                    //     return data;
                    // }},
                    { data: null, title: 'Action', orderable: false,
                        render: function (data, type, row, meta) {
                            return `<a class="btn btn-sm btn-warning edit-item" data-id="${row.id}" data-sale_price="${row.sale_price}" data-expiry_date="${row.expiry_date}"><i class="fa fa-pencil"></i></a>`;
                    }},
                ],
                paging: true,
                searching: true,
                processing: true,
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

            $(`#data_table`).on('click','.edit-item', function(e){
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $(".error").removeClass("error");
                $('#id').val($(this).data('id'));
                $('#sale_price').val($(this).data('sale_price'));
                $('#expiry_date').val($(this).data('expiry_date'));
                $(`#iModal`).modal('show');

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
                            toastr.error(xhr.responseJSON.message ?? "Something went wrong");
                        }
                    });
            })

            $('#data_table').on('click','.add-price', function(e){
                e.preventDefault();
                $(this).attr('contenteditable','true');
            })



        })
</script>
@endsection
