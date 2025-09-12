@extends('admin.layouts.main')

@section('title')
Vendor Categories
@stop

@section('css')
<style>
    .select2-container--default .select2-selection--multiple {
        overflow: auto !important;
        min-height: 40px !important;
        border: 1px solid #ccc !important;
        border-radius: 4px;
    }

    label.error {
        display: block;
        font-size: 14px;
        margin-top: 5px;
    }
</style>
@stop

@section('content')

    <div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="iForm" method="post" action="{{ route('inventory.vendor-category.store') }}">
                    @CSRF
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="iModalLabel">Vendor Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nameInput" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="nameInput" name="name"
                                    placeholder="Enter category name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nameInput" class="form-label">Code</label>
                                <input type="tel" class="form-control" id="code" name="code" placeholder="Enter code"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-around">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <button type="button" id="add-button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#iModal">
            <i class="fa fa-plus"></i> Add
        </button>

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
        $(document).ready(function () {
            'use strict';
            const uri = @json(route('datatable.get-vendor-category'));
            const currectUri = window.location.href;
            const lastIndex = currectUri.split('/').pop();

            $('#branches, #items').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal'),
                dropdownAutoWidth: true
            });

            $(`#iForm`).validate({

                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function (error, element) {
                    if (element.hasClass("select2-hidden-accessible")) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        error.insertAfter(element);
                    }
                },
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
                submitHandler: function (form) {
                    console.log(form);
                    const formData = $(form).serialize();
                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: formData,
                        beforeSend: function (xhr) {
                            var token = $('meta[name="csrf-token"]').attr('content');
                            xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        },
                        success: function (response) {
                            if (response.success) {
                                $('#iModal').modal('hide');
                                dt.ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("🚀 ~ error>>", error)
                            if (xhr.responseJSON.errors) {
                                for (const [field, messages] of Object.entries(xhr
                                    .responseJSON.errors)) {
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
                            sData.set(element.id, element);
                        });
                        localStorage.setItem(lastIndex, JSON.stringify([...sData]));
                        return json.data;
                    },
                    data: function (d) {
                        // no extra payload required
                    },
                    beforeSend: function (xhr) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                columns: [{
                    data: null,
                    title: 'Sr No',
                    width: "5%",
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name',
                    title: 'Name'
                },
                {
                    data: 'code',
                    title: 'code'
                },
                {
                    data: null,
                    title: 'Action',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        let destroyUrl = "{{ route('inventory.vendor-category.destroy', ':id') }}";
                        destroyUrl = destroyUrl.replace(':id', row.id);

                        return `<span class="btn btn-sm btn-warning edit-item" data-id="${row.id}" data-name="${row.name}"><i class="fa fa-pencil"></i>
                                </span > <span data-uri="${destroyUrl}" class="btn btn-sm btn-danger delete-item"><i class="fa fa-trash"></i></span>`;
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
                drawCallback: function (settings) { }
            });

            $(`#add-button`).on('click', function (e) {
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $("#iForm").find(".is-invalid").removeClass("is-invalid");
                $('#nameInput').val("");
                $('#id').val("");
                $(`#contact`).val('')
                $(`#address`).val('')
                $(`#email`).val('')
                $(`#branches`).val('').trigger('change')
                $(`#items`).val('').trigger('change')
            });

            $(`#data_table`).on('click', '.edit-item', function (e) {
                $(`#iModal`).modal('show');
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $("#iForm").find('.is-invalid').removeClass('is-invalid');
                let id = $(this).data('id');
                $('#id').val(id)
                $('#nameInput').val($(this).data('name'));

                let sDatas = new Map(JSON.parse(localStorage.getItem(lastIndex)));
                if (sDatas.has(id)) {
                    let sData = sDatas.get(id);
                    $(`#contact`).val(sData.contact)
                    $(`#address`).val(sData.address)
                    $(`#email`).val(sData.email)
                    $(`#branches`).val(sData.branches.map(x => x.id)).trigger('change')
                    $(`#items`).val(sData.items.map(x => x.id)).trigger('change')
                }
            })

            $(`#data_table`).on('click', '.delete-item, .changeStatus', function (e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).data('uri'),
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    beforeSend: function (xhr) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    },
                    success: function (response) {
                        if (response.success) {
                            dt.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        if (xhr.responseJSON.errors) {
                            for (const [field, messages] of Object.entries(xhr.responseJSON
                                .errors)) {
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