@extends('admin.layouts.main')

@section('title')
    Supplier
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
                <form id="iForm" method="post" action="{{ route('inventory.suppliers.store') }}">
                        @csrf   {{-- ✅ very important --}}
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="type" id="type" value="{{ $type }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="iModalLabel">Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nameInput" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="nameInput" name="name"
                                placeholder="Enter supplier name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nameInput" class="form-label">Contact</label>
                            <input type="tel" class="form-control" id="contact" name="contact"
                                placeholder="Enter Contact" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nameInput" class="form-label">Address</label>
                            <input type="address" class="form-control" id="address" name="address"
                                placeholder="Enter address" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nameInput" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="ntn_number" class="form-label">NTN Number</label>
                            <input type="text" class="form-control" id="ntn_number" name="ntn_number"
                                placeholder="Enter NTN Number" maxlength="50">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="branches" class="form-label">Supplier Branch</label>
                            <select class="form-control select2" id="branches" name="branches[]" required multiple>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="branches" class="form-label">Supplier items</label>
                            <select class="form-control" id="items" name="items[]" required multiple>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
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
        @if (Gate::allows('Supplier-create'))
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
        $(document).ready(function() {
            'use strict';
            const uri = @json(route('datatable.data.suppliers'));
            const changeStatusUri = @json(route('inventory.suppliers.change.status'));
            const deleteUri = @json(route('inventory.suppliers.destroy'));
            const currectUri = window.location.href;
            const lastIndex = currectUri.split('/').pop();
            const type = @json($type);
            console.log(type);
            const editPermission = @json(Gate::allows('Supplier-edit'));

            

            $('#branches, #items').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal'),
                dropdownAutoWidth: true
            });

            $(`#iForm`).validate({

                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
           rules: {
                    name:        { required: true, minlength: 3 },
                    contact:     { required: true },
                    address:     { required: true },
                    email:       { required: true, email: true },
                    ntn_number:  { required: true, maxlength: 50, ntnPattern: true }
                },
                messages: {
                    name: {
                        required: "Supplier name is required",
                        minlength: "Supplier name should be at least 3 characters long"
                    },
                    contact: {
                        required: "Contact is required"
                    },
                    address: {
                        required: "Address is required"
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address"
                    },
                    ntn_number: {
                        required: "NTN Number is required",
                        maxlength: "Maximum 50 characters allowed",
                        ntnPattern: "Please enter a valid NTN (7–9 digits, e.g. 1234567-8)"
                    }
                },

                submitHandler: function(form) {
                    console.log(form);
                    const formData = $(form).serialize();
                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: formData,
                        beforeSend: function(xhr) {
                            var token = $('meta[name="csrf-token"]').attr('content');
                            xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#iModal').modal('hide');
                                dt.ajax.reload();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
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
                    dataSrc: function(json) {
                        let sData = new Map();
                        json.data.forEach(element => {
                            sData.set(element.id, element);
                        });
                        localStorage.setItem(lastIndex, JSON.stringify([...sData]));
                        return json.data;
                    },
                    data: function(d) {

                        d.type = type;
                        // d.year = $('#year').val();
                        // d.month = $('#month').val();
                        // d.employee_id = $('#employees_list').val(); ;

                    },
                    beforeSend: function(xhr) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }

                    
                },
                columns: [{
                        data: null,
                        title: 'Sr No',
                        width: "5%",
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        title: 'Name'
                    },
                    {
                        data: 'contact',
                        title: 'Contact'
                    },
                    {
                        data: 'email',
                        title: 'Email'
                    },
                    {
                        data: 'ntn_number',
                        title: 'NTN',
                        defaultContent: '-'
                        },
                    {
                        data: 'address',
                        title: 'Address'
                    },
                    {
                        data: 'branches',
                        title: 'Branches',
                        render: function(data, type, row, meta) {
                            let nData = row.branches.map(x =>
                                    `<span class="badge bg-success rounded-pill">${x.name}</span>`)
                                .join("<br>");
                            return `<div>${nData}</div>`
                        }

                    },
                    {
                        data: 'items',
                        title: 'Items',
                        render: function(data, type, row, meta) {
                            let nData = row.items.map(x =>
                                    `<span class="badge bg-success rounded-pill">${x.name}</span>`)
                                .join("<br>");
                            return `<div>${nData}</div>`
                        }
                    },
                    {
                        data: 'status',
                        title: 'Status',
                        render: function(data, type, row, meta) {
                            return data ?
                                `<a class="btn btn-success badge badge-success text-white changeStatus" data-uri="${changeStatusUri}/${row.id}">Active</a>` :
                                `<a class="btn btn-danger badge badge-danger changeStatus" data-uri="${changeStatusUri}/${row.id}">Inactive</a>`;
                        }

                    },
                    {
                        data: null,
                        title: 'Action',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let html = ''; 
                            if(editPermission){
                              html += `<span class="btn btn-sm btn-warning edit-item" data-id="${row.id}" data-name="${row.name}"><i class="fa fa-pencil"></i>`
                            }
                            return html;
                                // </span> <span data-uri="${deleteUri}/${row.id}" class="btn btn-sm btn-danger delete-item"><i class="fa fa-trash"></i></span>;
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
                drawCallback: function(settings) {}
            });

            $(`#add-button`).on('click', function(e) {
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $("#iForm").find(".is-invalid").removeClass("is-invalid");
                $('#nameInput').val("");
                $('#id').val("");
                $(`#contact`).val('')
                $(`#address`).val('')
                $(`#email`).val('')
                $(`#ntn_number`).val('')
                $(`#branches`).val('').trigger('change')
                $(`#items`).val('').trigger('change')
            });

            $(`#data_table`).on('click', '.edit-item', function(e) {
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
                     $('#ntn_number').val(sData.ntn_number || '');   // ✅ prefill NTN here
                    $(`#branches`).val(sData.branches.map(x => x.id)).trigger('change')
                    $(`#items`).val(sData.items.map(x => x.id)).trigger('change')
                }
            })

            $(`#data_table`).on('click', '.delete-item, .changeStatus', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).data('uri'),
                    type: 'GET',
                    beforeSend: function(xhr) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    },
                    success: function(response) {
                        if (response.success) {
                            dt.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
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
