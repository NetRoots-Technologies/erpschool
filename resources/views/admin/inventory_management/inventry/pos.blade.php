@extends('admin.layouts.main')

@section('title')
POS Inventory System
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">POS Inventory System - Uniform</h3>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="form-group col-10">
                            <select class="form-control" id="itemSelect">
                                <option value="" selected>
                                    </option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-primary btn-block" id="addItemButton"><i
                                    class="fa fa-plus"> </i> Add Item</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <form method="POST" action="{{route('inventory.pos.save')}}" id="iForm" class="p-2">
                        <div class="row">
                            <div class="col-8">
                                <div class="row item-head">
                                    <div class="col-3">Name</div>
                                    <div class="col-3">Price</div>
                                    <div class="col-3">Quantity</div>
                                    <div class="col-3">Action</div>
                                </div>
                                <div id="item-lists">

                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    {{-- <label class="col-form-label" for="ledger_id">Student</label>
                                    <select type="text" maxLength="100" name="ledger_id" id="ledger_id"
                                        class="form-control" required>
                                        <option value=""></option>
                                        @foreach ($ledgers as $ledger)
                                        <option value="{{$ledger->id}}">{{"$ledger->first_name $ledger->last_name
                                            [$ledger->class_name-$ledger->section_name]"}}
                                        </option>
                                        @endforeach
                                    </select> --}}
                                    <label class="col-form-label" for="total">Price</label>
                                    <input type="number" name="total" id="total" class="form-control"
                                        placeholder="total price" readonly>

                                    <label class="col-form-label" for="discount">Discount Percentage</label>
                                    <input type="number" name="discount" id="discount" class="form-control"
                                        placeholder="Enter discount (%)" value="0" min="0" max="100" step="0.1"
                                        required>

                                    <label class="col-form-label" for="total_price">Total Sum</label>
                                    <input type="number" name="total_price" id="total_price" class="form-control"
                                        placeholder="Total Sum" value="0" readonly required>

                                    <label class="col-form-label" for="payment_method">Payment Method</label>
                                    <select name="payment_method" id="payment_method" required>
                                        <option value="cash" selected>Cash</option>
                                    </select>
                                    
                                    <label class="payment-field col-form-label d-none" for="voucher"
                                        id="voucher-label">Voucher</label>
                                    <input type="text" name="voucher" id="voucher"
                                        class="payment-field form-control d-none" placeholder="Enter voucher Id">
                                    <label class="payment-field col-form-label d-none" for="card"
                                        id="card-label">Card</label>
                                    <input type="text" name="card" id="card" class="payment-field form-control d-none"
                                        placeholder="Enter last 4 digits" maxlength="4" pattern="\d{4}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);">
                                </div>
                                <hr>
                                <div class="row">
                                    <button class="btn btn-lg btn-primary" type="submit" id="completeBtn">
                                        Complete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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

        const listing = @json(route('inventory.inventry.stationery.listing'));
        const pos = @json(route('inventory.pos.save'));
        const type = @json($type);
        const currectUri = window.location.href;
        const lastIndex = currectUri.split('/').pop();
        let globalCount = 0;
        let itemsList = [];

        $('#itemSelect').select2({
            placeholder: 'Select an item',
            allowClear: true,
        })

        $('#ledger_id').select2({
            placeholder: 'Select an student',
            allowClear: true,
        })

        $('#payment_method').select2({
            placeholder: 'Select a Paymnet Method',
            allowClear: true,
        })


        $('#addItemButton').on('click', function () {
            let item_id = $('#itemSelect').val();

            if (isInventoryIdExists(item_id)) {
                toastr.error("This item is already in the list!");
                return;
            }

            if (!item_id) {
                toastr.error('Please Select an Item')
                return;
            }

            $('#itemSelect').val("").trigger('change')
            let items = JSON.parse(localStorage.getItem(lastIndex))
            let item = items.find(x => x.id == item_id)

            if (!item) {
                toastr.error('Item Not found')
            }

            let html = `
                <div class="row m-1 p-1">
                    <input type="hidden" name="inventory_id[${globalCount}]" class="inventory_id" value="${item.id}">
                    <div class="col-3"><input type="text" class="form-control" name="name[${globalCount}]"
                            value="${item.name}" readonly required></div>
                    <div class="col-3"><input type="number" class="form-control price" name="price[${globalCount}]"
                            value="${item.sale_price}" readonly required></div>
                    <div class="col-3"><input type="number" class="form-control quantity" name="quantity[${globalCount}]"
                            value="1" max="${item.quantity}" step="1" required></div>
                    <div class="col-3"><button class="btn btn-sm btn-danger removeItem"><i
                                class="fa fa-times"></i></button></div>
                </div>`
            $('#item-lists').append(html)
            ++globalCount
            calculateTotal();

        });

        $('#item-lists').on('click', '.btn-danger', function () {
            $(this).parent().parent().remove();
            calculateTotal();
        })

        $("#payment_method").on('change', function () {
            let method = $(this).val()
            $(".payment-field").addClass("d-none").removeAttr("required");
            $(`#${method}, #${method}-label`).removeClass("d-none").attr("required", true);
        })

        $("#iForm").on("submit", function (e) {
            let allFilled = true;

            if ($("#item-lists").children().length === 0) {
                e.preventDefault();
                toastr.error("Please add at least one item before submitting.");
                return;
            }

            $(".quantity").each(function () {
                if ($(this).val() <= 0) {
                    allFilled = false;
                }
            });

            if (!allFilled) {
                e.preventDefault();
                toastr.error("Please ensure all quantity fields have a valid quantity.");
                return;
            }

        });


        $(`#iForm`).validate({

            rules: {
                customer_name: {
                    required: true,
                    maxlength: 100
                },
                total: {
                    required: true,
                    number: true
                },
                discount: {
                    required: true,
                    number: true
                },
                total_price: {
                    required: true,
                    number: true
                },
                payment_method: {
                    required: true
                },
                voucher: {
                    required: false
                },
                card: {
                    required: false
                }
            },
            messages: {
                customer_name: {
                    required: "Please enter customer name",
                    maxlength: "Customer name should not exceed 100 characters"
                },
                total: {
                    required: "Please enter total price",
                    number: "Please enter a valid number"
                },
                discount: {
                    required: "Please enter discount percentage",
                    number: "Please enter a valid number"
                },
                total_price: {
                    required: "Please enter total price",
                    number: "Please enter a valid number"
                },
                payment_method: {
                    required: "Please select a payment method"
                }
            },
            errorPlacement: function (error, element)
            {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
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
                            toastr.success(response.message);
                            $('#item-lists').html("")
                            getListing()
                            $(`#iForm`).reset();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        toastr.error(xhr.responseJSON.message ?? "Something went wrong");
                    }
                });
            }
        })

        function getListing() {
            $.ajax({
                url: listing,
                method: 'GET',
                dataType: 'json',
                data: {
                    "type": type,
                },
                success: function (response) {
                    console.log("Response Data",response);
                    if (response.success) {
                        localStorage.setItem(lastIndex, JSON.stringify(response.data))
                        $('#itemSelect').empty();
                        $('#itemSelect').append(new Option("Select an item", "", 1, 1));
                        response.data.sort((a,b) => b.quantity - a.quantity ).forEach(item => {
                            let newOption = new Option(`${item.name} (${item.quantity})`, item.id, false, false);
                            if(item.quantity <= 0)
                                newOption.disabled = true;

                            $('#itemSelect').append(newOption);
                        });
                        $('#itemSelect').trigger('change');
                    }
                },
                error: function () {
                    console.error("Error fetching the listing data.");
                }
            });
        };

        function isInventoryIdExists(inventoryId) {
            return $("#item-lists input[name='inventory_id'][value='" + inventoryId + "']").length > 0;
        }

        function calculateTotal() {

            var tempSum = 0;

            $("#item-lists .row").each(function () {
                var quantity = $(this).find(".quantity").val();
                var price = $(this).find(".price").val();
                var total = quantity * price;


                tempSum += total;

            });
            let discountPercentage = parseFloat($("#discount").val()) || 0;

            let discountAmount = (tempSum * discountPercentage) / 100;
            let totalPrice = tempSum - discountAmount;

            $("#total").val(tempSum.toFixed(2));
            $("#total_price").val(totalPrice.toFixed(2));
        }

        $(document).on("input", ".quantity, #discount", function (e) {
            e.preventDefault();
            calculateTotal();
        });





        // $(document).on("click", ".completeOrder", function () {
        //     $.ajax({
        //         url: pos,
        //         method: 'POST',
        //         contentType: 'application/json',
        //         data: JSON.stringify({ items: data }),
        //         dataType: 'json',
        //         beforeSend: function(xhr) {
        //             var token = $('meta[name="csrf-token"]').attr('content');
        //             xhr.setRequestHeader('X-CSRF-TOKEN', token);
        //         },
        //         success: function (response) {
        //             toastr.success(response.message);
        //         },
        //         error: function (xhr, status, error) {
        //             const response = xhr?.responseJSON;
        //             if (response?.errors) {
        //                 Object.entries(response.errors).forEach(([field, messages]) => {
        //                     if (Array.isArray(messages)) {
        //                         messages.forEach(message => toastr.error(message));
        //                     } else {
        //                         toastr.error(messages);
        //                     }
        //                 });
        //             } else if (response?.message) {
        //                 toastr.error(response.message);
        //             } else {
        //                 toastr.error(`Unexpected error: ${error || 'Unknown error occurred.'}`);
        //             }
        //         }
        //     });
        //     });


        getListing()
        calculateTotal();

    })

</script>

@endsection