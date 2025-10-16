@extends('admin.layouts.main')

@section('title')
    Purchase Order
@stop

@section('content')

    <div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="iForm" method="post" action="{{ route('inventory.purchase_order.store') }}">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="type" id="type" value="{{ $type }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="iModalLabel">Purchase Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="branch" class="form-label">Branch</label>
                                <select class="form-control" id="branch" name="branch_id" required>
                                    <options value="" selected></options>
                                    @foreach ($branches as $key => $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="supplier" class="form-label">Supplier</label>
                                <select class="form-control" id="supplier" name="supplier_id" required>
                                    <options value="" selected></options>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="delivery_status" class="form-label">Delivery Status</label>
                                <select class="form-control" id="delivery_status" name="delivery_status" required>
                                    <options value="" selected></options>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="order_date" class="form-label">Order Date</label>
                                <input type="date" class="form-control" id="order_date" name="order_date"
                                    max="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" readonly
                                    required>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date"
                                    min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="total_amount" class="form-label">Total Amount</label>
                                <input type="number" min="0" value="0" class="form-control" id="total_amount"
                                    name="total_amount" readonly required>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="description" class="form-label">Comments</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>


                            <div class="col-12 mb-3">
                                <div class="items" id="items">

                                </div>
                            </div>
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            @if (Gate::allows('PurchaseOrders-create'))
                <button type="button" id="add-button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#iModal">
                    <i class="fa fa-plus"></i> Add
                </button>
            @endif


            <form action="{{ route('inventory.purchase_order.uploadPurchase') }}" method="POST" id="upload-form"
                enctype="multipart/form-data">
                @csrf
                <div class="d-flex align-items-center">
                    <label for="upload" class="btn btn-outline-secondary me-2">
                        <i class="fa fa-upload"></i> Upload Purchase Order
                    </label>
                    <input type="file" id="upload" name="file" class="d-none"
                        accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                </div>
            </form>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Purchase Orders</h5>
                    </div>
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
    <script type="text/javascript" defer>
        $(document).ready(function() {
            'use strict';

            const uri = @json(route('datatable.data.purchaseOrder'));
            const changeStatusUri = @json(route('inventory.quotes.change.status'));
            const deleteUri = @json(route('inventory.purchase_order.destroy'));

            const pdfUri = @json(route('inventory.purchase_order.pdf'));
            const printUri = @json(route('inventory.purchase_order.print'));
            const showData = @json(route('inventory.purchase_order.show'));


            const getQuote = @json(route('inventory.get.quote'));
            const branches = @json($branches);
            const delivery_status = @json($delivery_status);

            const deletePermissions = @json(Gate::allows('PurchaseOrders-delete'));
            const viewPermissions = @json(Gate::allows('PurchaseOrders-view'));
            const pdfPermissions = @json(Gate::allows('PurchaseOrders-pdf'));
            const printPermissions = @json(Gate::allows('PurchaseOrders-print'));

            // const viewPath = @json(route('inventory.purchase_order.view'));
            const type = @json($type);

            $("#upload").on("change", function() {
                $("#upload-form").submit();
            });

            const suppliers = [];
            branches.forEach(x => {
                x.suppliers.forEach((y) => {
                    suppliers[y.id] = {
                        name: y.name,
                        items: y.items
                    }
                })
            });

            $(`#item, #branch, #supplier, #delivery_status`).select2().val('').trigger('change');

            $(`#item`).select2({
                placeholder: "Select Item",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal')
            });

            $(`#delivery_status`).select2({
                placeholder: "Select Delivery Status",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal')
            });

            $(`#branch`).select2({
                placeholder: "Select Branch",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal')
            });

            $(`#supplier`).select2({
                placeholder: "Select Supplier",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal')
            });

            $(`#branch`).on('change', function(e) {
                let branch_id = $(this).val();

                let branche = branches.find(x => x.id == branch_id)
                if (branche) {
                    $(`#supplier`).empty()
                    branche.suppliers.forEach(x => {
                        var newOption = new Option(x.name, x.id, false, false);
                        $(`#supplier`).append(newOption);
                    })
                    $(`#supplier`).val('')
                    $(`#supplier`).trigger('change');
                    $("#items").empty();
                }
            })

            $('#supplier').on('change', function() {
                let supplier_id = $(this).val();

                if (!supplier_id) {
                    return false
                }

                const data = {
                    "supplier_id": supplier_id,
                    "branch_id": $("#branch").val()
                }

                $.ajax({
                    url: getQuote,
                    method: 'POST',
                    data: data,
                    beforeSend: function(xhr) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    },
                    success: function(response) {
                        $(`#items`).empty();
                        const items = response.data;

                        console.log(items);
                        if (items)
                            renderItems(items)
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        if (supplier)
                            renderItems(supplier)
                    }
                })
            })

            $('#items').on('click', '.btn-remove-item', function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            })

            $('#data_table').on('click', '.generate-pdf', function() {
                const url = $(this).data('uri');
                window.open(url, '_blank');
            });

            $('#data_table').on('click', '.print-item', function() {
                const url = $(this).data('uri');
                window.open(url, '_blank');
            });


            $(`#items`).on('keyup', '.quantity,.item_price', function() {
                let closestQuantity = $(this).parent().parent().find('.quantity').val();
                let closestPrice = $(this).parent().parent().find('.item_price').val();
                $(this).parent().parent().find('.total').val(closestQuantity * closestPrice);

                let total = 0;
                $(".total").each(function() {
                    total += parseFloat($(this).val());
                })
                $(`#total_amount`).val(total)
                $(`#total_amount`).val()
            })

            $(`#iForm`).validate({
                errorPlacement: function(error, element) {
                    if (element.hasClass("select2-hidden-accessible")) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    if ($("#items .row").length === 0) {
                        toastr.error('Please add atleast one item');
                        return;
                    }
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
                            if (xhr?.responseJSON?.errors ?? false) {
                                for (const [field, messages] of Object.entries(xhr
                                        .responseJSON.errors)) {
                                    messages.forEach(message => toastr.error(message));
                                }
                            } else if (xhr?.responseJSON?.message ?? false) {
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
                        localStorage.setItem('data_table', JSON.stringify(json.data))
                        return json.data;
                    },
                    data: function(d) {
                        d.type = type;
                        // d.year = $('#year').val();
                        // d.type = $('#type').val();
                        // d.month = $('#month').val();
                        // d.employee_id = $('#employees_list').val(); ;

                    },
                    beforeSend: function(xhr) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                columns: [{
                        data: null,
                        title: 'Sr No',
                        width: "7%",
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'supplier',
                        title: 'Supplier name',
                        width: "10%",
                        render: function(data, type, row, meta) {
                            return row.supplier.name;
                        },
                    },
                    {
                        data: 'branch',
                        title: 'branch name',
                        width: "10%",
                        render: function(data, type, row, meta) {
                            return row.branch.name;
                        },
                    },
                    {
                        data: 'delivery_status',
                        title: 'Delivery Status',
                        width: "10%",
                        render: function(data, type, row, meta) {
                            return `<span class="badge bg-warning rounded-pill m-1">${row.delivery_status}</span>`;
                        },
                    },
                    {
                        data: 'description',
                        title: 'Comments',
                        render: function(data, type, row, meta) {
                            return `<span class="badge bg-warning rounded-pill m-1">${row.description}</span>`;
                        },
                    },

                    {
                        data: 'order_date',
                        title: 'Order date',
                        width: "11%",
                    },
                    {
                        data: 'purchaseOrderItems',
                        title: 'Items',
                        width: "20%",
                        render: function(data, type, row, meta) {
                            let nData = row.purchase_order_items.filter(x => x.item != null).map(
                                x =>
                                `<span class="badge bg-success rounded-pill m-1">${x.item.name} ${x.quantity}</span>`
                            ).sort((a, b) => b.length - a.length).join('&nbsp')
                            return nData
                        }
                    },
                    {
                        data: null,
                        title: 'Action',
                        width: "10%",
                        orderable: false,
                        render: function(data, type, row, meta) {

                            let html = "";

                            if (deletePermissions) {
                                html += `<span data-uri="${deleteUri}/${row.id}" class="btn btn-sm btn-danger delete-item">
                                        <i class="fa fa-trash"></i>
                                        </span>`;
                            }
                            if (viewPermissions) {
                                html += `<a href="${showData}/${row.id}" class="btn btn-sm btn-info view-item" title="View">
                            <i class="fa fa-eye"></i>
                        </a>`;
                            }
                            if (pdfPermissions) {
                                html += `<span data-uri="${pdfUri}/${row.id}" class="btn btn-sm btn-secondary generate-pdf">
                        <i class="fa fa-file-pdf-o"></i>
                        </span>`;
                            }
                            if (printPermissions) {
                                html += `<span data-uri="${printUri}/${row.id}" class="btn btn-sm btn-info print-item">
                        <i class="fa fa-print"></i>
                        </span>`;
                            }
                            return html;
                        }
                    },
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
                $(".error").removeClass("error");
                $('#nameInput').val("");
                $('#id').val("");
                $("#supplier").val("").trigger('change')
                $("#branch").val("").trigger('change')
                $(`#delivery_status`).empty();
                let newOption = new Option(delivery_status[1], delivery_status[1], false, false);
                $(`#delivery_status`).append(newOption).trigger('change');
                $(`#items`).empty('');
            });

            $(`#data_table`).on('click', '.edit-item', function(e) {
                $(`#iModal`).modal('show');
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $(".error").removeClass("error");
                $('#id').val($(this).data('id'));
                let data = JSON.parse(localStorage.getItem('data_table'))
                data = data.find(x => x.id == $(this).data('id'))
                $("#branch").val(data.branch_id).trigger('change')
                $("#supplier").val(data.supplier_id).trigger('change')

                for (let x in delivery_status) {
                    let newOption = new Option(delivery_status[x], delivery_status[x], false, false);
                    $(`#delivery_status`).append(newOption);
                }
                $(`#delivery_status`).val("").trigger('change');

                renderItems(data)
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
                        toastr.error('Something went wrong!');
                    }
                });
            })

            function renderItems(dt) {
                let html = ``;
                dt.map((v, i) => {
                    html += `<div class="row m-2">
                        <span class="col-3 mb-3">
                            <label for="item" class="form-label">Item</label>
                            <input type="hidden" value="${v.id}" name="item_id[${i}]">
                            <input type="hidden" value="${v.measuring_unit}" name="measuringUnit[${i}]"/>
                            <input type="number" class="form-control" name="itemName[${i}]"
                                placeholder="${v.name} (${v.measuring_unit})" value="${v.name}"  tabindex="-1" disabled required>
                        </span>
                        <span class="col-2 mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" min='1' class="form-control quantity" name="quantity[${i}]"
                                placeholder="Quantity" value="${0}" required>
                        </span>
                        <span class="col-2 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" min="1" class="form-control item_price" name="price[${i}]"
                                placeholder="Price" value="${0}" required>
                        </span>
                        <span class="col-2 mb-3">
                            <label for="quoted_price" class="form-label">Quoted Price</label>
                            <input type="number" class="form-control item_price" name="quoted_price[${i}]"
                                placeholder="Price" value="${v?.unit_price ?? 0}" readonly tabindex="-1">
                        </span>
                        <span class="col-2 mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input type="number" class="form-control total" name="total[${i}]"
                                placeholder="Total" value="${0}" tabindex="-1" readonly>
                        </span>
                        <span class="col-1 mb-3">
                            <button type="button" tabindex="-1" class="btn btn-mx btn-danger btn-remove-item mt-4"><i class="fa fa-times"></i></button>
                        </span>
                        </div>
                        `
                })
                $(`#items`).html(html);
            }

        });

        $(document).on('click', '.view-item', function() {
            const url = $(this).data('uri');
            window.location.href = url;
        });
    </script>
@endsection
