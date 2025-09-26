@extends('admin.layouts.main')

@section('title')
Product
@stop

@section('content')

{{-- imodal --}}
<div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="iForm" action="{{route('inventory.product.store')}}" method="POST">
                @csrf
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="type" id="type" value="{{ $type }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="iModalLabel">Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="product_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select name="branch_id" id="branch_id" required>
                                <option value="" selected disabled>Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="cost_amount" class="form-label">Cost Amount</label>
                            <input type="number" class="form-control" id="cost_amount" name="cost_amount" required
                                readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="sale_price" class="form-label">Sale Price</label>
                            <input type="number" class="form-control" name="sale_price" id="sale_price" required>
                        </div>
                        <div class="addProducts d-flex justify-content-end">
                          <select id="addItem" name="addItem" class="form-select">
                            <option value="" selected disabled>Select Item</option>
                            @foreach ($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" 
                                        data-quantity="{{ $ingredient->quantity }}">
                                    {{ "$ingredient->name ($ingredient->measuring_unit) - Available: $ingredient->quantity" }}
                                </option>
                            @endforeach
                        </select>

                        </div>
                    </div>
                    <div id="productItems" class="row p-2 m-1"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Inventory Products modal --}}
<div class="modal fade" id="inventoryProductsModal" tabindex="-1" aria-labelledby="inventoryProductsModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="width: 500px;">
        <div class="modal-content">
            <form id="inventoryProductsForm" action="{{route('inventory.product.productInventory')}}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="product_id" value="">
                <input type="hidden" name="type_product" id="type_product" value="{{ $type }}">
                <input type="hidden" name="branch_id" id="branch_id_form">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="productsModalLabel">Products From Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="inventoryProductsName" class="form-label">Products Name</label>
                            <input type="text" class="form-control inventoryProductsName" id="inventoryProductsName" name="inventoryProductsName" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="inventoryProductsQuantity" class="form-label">Enter Quantity</label>
                            <input type="number" class="form-control" id="inventoryProductsQuantity" name="inventoryProductsQuantity" value="" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="inventoryProductsMaxQuantity" class="form-label">Max Products Can made</label>
                            <input type="number" class="form-control" id="inventoryProductsMaxQuantity" name="inventoryProductsMaxQuantity" readonly>
                        </div>
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
<script>
    $(document).ready(function () {
        'use strict';

        const uri = @json(route('datatable.data.product'));
        const deleteUri = @json(route('inventory.product.destroy'));
        const calculationPath = @json(route('inventory.product.calculate', ['product' => ':id']));
        const items = @json($ingredients);
        const type = @json($type);
        const currectUri = window.location.href;
        const lastIndex = currectUri.split('/').pop();
        let global_count = 0

        $('#addItem').select2({
            placeholder: "Select an Ingredient",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#iModal'),
            dropdownAutoWidth: true
        });

        $("#branch_id").select2({
            placeholder: "Select a Branch",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#iModal'),
            dropdownAutoWidth: true,
        });


        $("#addItem").on("change", function () {
            let selectedItemId = $(this).val();
            $(this).val('');

            const item = items.find(x => x.id == selectedItemId);

            if ($("#productItems").find(`[data-item='${selectedItemId}']`).length > 0) {
                toastr.warning("Item already added!");
                return;
            }

            const itemsHtml = `
                <div class="row align-items-center item-row" data-item="${item.id}">
                    <input type="hidden" name="item_id[${global_count}]" value="${item.id}">
                    <input type="hidden" name="measuring_unit[${global_count}]" value="${item.measuring_unit}">

                    <div class="col-5 mb-3">
                        <label for="item-${item.id}" class="form-label">Item</label>
                        <input type="text" id="item-${item.id}" class="form-control"
                            value="${item.name} (${item.measuring_unit})" readonly>
                    </div>

                    <div class="col-2 mb-3">
                        <label for="itemQuantity-${item.id}" class="form-label">Quantity</label>
                        <input type="number" id="itemQuantity-${item.id}" class="form-control item-quantity"
                            data-id="${item.id}" name="quantity[${global_count}]"
                                min="0.01" step="any" value="0" required>
                               
                    </div>        

                    <div class="col-2 mb-3">
                        <label for="unit-${item.id}" class="form-label">Unit Price</label>
                        <input type="number" id="unit-${item.id}" class="form-control unit_price"
                            data-id="${item.id}" name="unit_price[${global_count}]"
                            value="${item.unit_price}" step="any" readonly>
                    </div>

                    <div class="col-2 mb-3">
                        <label for="itemTotal-${item.id}" class="form-label">Total</label>
                        <input type="number" id="itemTotal-${item.id}" class="form-control item-total"
                            data-id="${item.id}" name="total[${global_count}]" value="0" step="any" readonly>
                    </div>

                    <div class="col-1 mb-3 mt-4 d-flex justify-content-center">
                        <button type="button" class="btn btn-danger removeItem" aria-label="Remove item">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>`;

            global_count++
            $("#productItems").append(itemsHtml);

        });

        $('#productItems').on("click", ".removeItem", function () {
            $(this).parent().parent().remove();
        });

        $(`#add-button`).on('click', function (e) {
            e.preventDefault();
            $('#iForm').trigger("reset");
            $("#iForm").validate().resetForm();
            $(".error").removeClass("error");
            $('#id').val("");
            $("#productItems").html("");
        });

        $(`#data_table`).on('click','.edit-item' ,function (e) {
            e.preventDefault();
            let itemId = $(this).data('id');
            let branchId = $(this).data('branch');
            // let itemName = $(this).data('name');
            global_count = 0;
            $(`#iModal`).modal('show');
            $('#iForm').trigger("reset");
            $("#iForm").validate().resetForm();
            $(".error").removeClass("error");
            $('#id').val(itemId)
            $("#productItems").html("");
            $("#branch_id").val("").trigger("change");
            $("#branch_id").val(branchId).trigger("change");
            let products = new Map(JSON.parse(localStorage.getItem(lastIndex)));
            let product = products.get(itemId);

            $('#product_name').val(product.name)
            $('#cost_amount').val(product.cost_amount)
            $('#sale_price').val(product.sale_price)

            let itemsHtml = ""
            product.product_items.forEach((v,i) => {
                console.log("🚀 ~ v,i>>", v,i)
                console.log(global_count);
                itemsHtml += `
                    <div class="row align-items-center item-row" data-item="${v.inventory_items.id}">
                        <input type="hidden" name="item_id[${global_count}]" value="${v.inventory_items.id}">
                        <input type="hidden" name="measuring_unit[${global_count}]" value="${v.measuring_unit}">

                        <div class="col-5 mb-3">
                            <label for="item-${v.inventory_items.id}" class="form-label">Item</label>
                            <input type="text" id="item-${v.inventory_items.id}" class="form-control"
                                value="${v.inventory_items.name} (${v.measuring_unit})" readonly>
                        </div>

                        <div class="col-2 mb-3">
                            <label for="itemQuantity-${v.inventory_items.id}" class="form-label">Quantity</label>
                            <input type="number" id="itemQuantity-${v.inventory_items.id}" class="form-control item-quantity"
                                data-id="${v.inventory_items.id}" name="quantity[${global_count}]"
                                min="1" value="${v.quantity}" required>
                        </div>

                        <div class="col-2 mb-3">
                            <label for="unit-${v.inventory_items.id}" class="form-label">Unit Price</label>
                            <input type="number" id="unit-${v.inventory_items.id}" class="form-control unit_price"
                                data-id="${v.inventory_items.id}" name="unit_price[${global_count}]"
                                value="${v.inventory_items.unit_price}" readonly>
                        </div>

                        <div class="col-2 mb-3">
                            <label for="itemTotal-${v.inventory_items.id}" class="form-label">Total</label>
                            <input type="number" id="itemTotal-${v.inventory_items.id}" class="form-control item-total"
                                data-id="${v.inventory_items.id}" name="total[${global_count}]" value="${v.inventory_items.unit_price * v.quantity}" readonly>
                        </div>

                        <div class="col-1 mb-3 mt-4 d-flex justify-content-center">
                            <button type="button" class="btn btn-danger removeItem" aria-label="Remove item">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>`;
            global_count++

            })

            $("#productItems").html(itemsHtml);
        });

        $(`#iForm`).validate({
            submitHandler: function (form) {

                if($("#productItems").find(".item-row").length === 0){
                    toastr.error("Please add atleast one item");
                    return;
                }
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: $(form).serialize(),
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
            }
        })

        $(`#inventoryProductsForm`).validate({
            submitHandler: function (form) {
                let data = $(form).serialize();
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: $(form).serialize(),
                    beforeSend: function (xhr) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    },
                    success: function (response) {
                        if (response.success) {
                            $("#inventoryProductsModal").modal("hide");
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
                    d.type = type;
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
                data: 'branch_id',
                title: 'Hidden ID',
                visible: false,
                render: function (data, type, row, meta) {
                    return `<input type="hidden" name="branch_id" value="${data}`;
                }
            },
            {
                data: 'name',
                title: 'Name'
            },
            {
                data: 'cost_amount',
                title: 'Cost Amount'
            },
            {
                data: 'sale_price',
                title: 'Sale Price'
            },
            {
                data: null,
                title: 'Action',
                className: 'text-center',
                orderable: false,
                render: function (data, type, row, meta) {
                    $(".inventoryProductsName").val(row.name);
                    return `
                        <div class="text-center">
                            <button type="button" class="btn btn-sm btn-success inventoryProductBtn" data-id="${row.id}" data-name="${row.name}" data-branch="${row.branch_id}" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#inventoryProductsModal">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                            <span class="btn btn-sm btn-warning edit-item" data-id="${row.id}" data-name="${row.name}" data-branch="${row.branch_id}">
                                <i class="fa fa-pencil"></i>
                            </span>
                            <span data-uri="${deleteUri}/${row.id}" class="btn btn-sm btn-danger delete-item">
                                <i class="fa fa-trash"></i>
                            </span>
                        </div>`;
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

        $(`#data_table`).on('click', '.delete-item', function(e) {
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

        function updateTotal() {
            let grandTotal = 0;

            $(".item-row").each(function () {
                const quantity = parseFloat($(this).find(".item-quantity").val()) || 0;
                const unitPrice = parseFloat($(this).find(".unit_price").val()) || 0;
                const total = quantity * unitPrice;

                $(this).find(".item-total").val(total.toFixed(2));
                grandTotal += total;
            });

            $('#cost_amount').val(grandTotal.toFixed(2));
        }

        $(document).on("input", ".item-quantity", function () {
            updateTotal();
        });

        $(document).on("click", ".removeItem", function () {
            $(this).closest(".item-row").remove();
            updateTotal()
        });

        updateTotal();

        $("#inventoryProductsForm").on('keydown',  function(event) {
            if (event.key === "Enter" && event.target.tagName !== "TEXTAREA") {
                event.preventDefault();
            }
        });


        $(document).on("click", ".inventoryProductBtn", function () {

            $("#inventoryProductsMaxQuantity").val("");
            $("#inventoryProductsQuantity").val("");
            $("#inventoryProductsForm").validate().resetForm();
            $(".error").removeClass("error");
            let productId = $(this).data('id');
            let productName = $(this).data('name');
            let branchId = $(this).data('branch');
            $("#product_id").val(productId);
            $("#inventoryProductsName").val(productName);
            $('#branch_id_form').val(branchId);
            let urlPath = calculationPath.replace(':id', productId);

            $.ajax({
                url: urlPath,
                type: 'GET',
                data: { id: productId },
                beforeSend: function (xhr) {
                    var token = $('meta[name="csrf-token"]').attr('content');
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                },
                success: function (response) {
                    $("#inventoryProductsMaxQuantity").val(response.max_products);
                    $("#inventoryProductsQuantity").attr("max", response.max_products);
                },
                error: function (xhr, status, error) {
                    toastr.error("Not Enough Items in Inventory");
                }
            });

            $("#inventoryProductsMaxQuantity").val("");

        });

    });
</script>
@endsection
