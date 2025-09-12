@extends('admin.layouts.main')

@section('title')
Quote
@stop

<head>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
@section('content')

<div class="modal fade" id="iModal" tabindex="-1" aria-labelledby="iModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="iForm" method="post" action="{{route('inventory.quotes.store')}}">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="type" id="type" value="{{ $type }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="iModalLabel">Quote</h5>
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
                            <label for="quote_date" class="form-label">Quote Date</label>
                            <input type="date" class="form-control" id="quote_date" name="quote_date"
                                max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date"
                                min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
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
<script type="text/javascript" defer>
    $(document).ready(function(){
            'use strict';

            const uri = @json(route('datatable.data.quotes'));
            const changeStatusUri = @json(route('inventory.quotes.change.status'));
            const deleteUri = @json(route('inventory.quotes.destroy'));
            const branches = @json($branches);
            const type = @json($type);

            const suppliers = [];
            branches.forEach(x => {
                x.suppliers.forEach((y) => {
                    suppliers[y.id]={name: y.name,items: y.items}
                })
            });

            $(`#item, #branch, #supplier`).select2().val('').trigger('change');

            $(`#branch, #supplier, #item`).select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#iModal')
            });


            $(`#branch`).on('change', function() {
                let branch_id = $(this).val()
                let branche = branches.find(x => x.id == branch_id)
                if(branche){
                    $(`#supplier`).empty()
                    branche.suppliers.forEach(x => {
                        var newOption = new Option(x.name, x.id, false, false);
                        $(`#supplier`).append(newOption);
                    })
                    $(`#supplier`).val('')
                    $(`#supplier`).trigger('change')
                }
            })

            $('#supplier').on('change', function() {
                let supplier_id = $(this).val();
                let supplier = suppliers[supplier_id]
                $(`#items`).empty();
                if(supplier)
                    renderItems(supplier, false)
            })

            $('#items').on('click','.btn-remove-item', function(e){
                e.preventDefault();
                $(this).parent().parent().remove();
            })

            $(`#items`).on('keyup', '.quantity,.item_price', function(){
                let closestQuantity = $(this).parent().parent().find('.quantity').val();
                let closestPrice = $(this).parent().parent().find('.item_price').val();
                $(this).parent().parent().find('.total').val(closestQuantity * closestPrice);
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
                        localStorage.setItem('data_table', JSON.stringify(json.data))
                        return json.data;
                    },
                    data: function (d) {
                        d.type = type;
                        // d.year = $('#year').val();
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
                    { data: 'supplier', title: 'Supplier name', width: "10%",
                        render: function (data, type, row, meta) {
                            return row.supplier.name;
                        },
                    },
                    { data: 'quote_date', title: 'Quote Date',width: "11%", },
                    { data: 'due_date', title: 'Due Date',width: "11%", },
                    { data: 'comments', title: 'Comment',width: "15%", },
                    { data: 'items', title: 'Items', width: "20%",
                        render: function(data, type, row, meta){
                            let nData = row.items.map(x => `<span class="badge bg-success rounded-pill m-1">${x.name}</span>`).sort((a,b) => b.length - a.length).join('&nbsp')
                            return nData
                        }
                    },
                    { data: null, title: 'Action', width: "10%", orderable: false,
                        render: function (data, type, row, meta) {
                            return `<span class="btn btn-sm btn-warning edit-item" data-id="${row.id}" "><i class="fa fa-pencil"></i></span> <span data-uri="${deleteUri}/${row.id}" class="btn btn-sm btn-danger delete-item"><i class="fa fa-trash"></i></span>`;
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
                drawCallback: function(settings) {
                }
            });

            $(`#add-button`).on('click', function(e) {
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $('#nameInput').val("");
                $('#id').val("");
                $("#supplier").val("").trigger('change')
                $("#branch").val("").trigger('change')
                $("#quote_date").val("")
                $("#due_date").val("")
                $("#comments").val("")
            });

            $(`#data_table`).on('click','.edit-item', function(e){
                $(`#iModal`).modal('show');
                $('#iForm').trigger("reset");
                $("#iForm").validate().resetForm();
                $('#id').val($(this).data('id'));
                let data = JSON.parse(localStorage.getItem('data_table'))
                data = data.find(x => x.id == $(this).data('id'))
                $("#branch").val(data.branch_id).trigger('change')
                $("#supplier").val(data.supplier_id).trigger('change')
                $("#quote_date").val(data.quote_date)
                $("#due_date").val(data.due_date)
                $("#comments").val(data.comments)

                renderItems(data, true)

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
                            toastr.error('Something went wrong!');
                        }
                    });
            })

            function renderItems(dt, isEdit = false){
                let html = ``;
                dt.items.map((v,i) => {
                    html +=`<div class="row m-3">
                    <span class="col-5 mb-3">
                                <label for="item" class="form-label">Item</label>
                                <input type="hidden" value="${v.id}" name="item_id[${i}]">
                                <input  type="number" class="form-control" name="itemName[]"
                                    placeholder="${v.name} (${v.measuring_unit})" value="${v.name}" disabled required>
                            </span>
                            <span class="col-2 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" min='1' step='1' class="form-control quantity" name="quantity[${i}]"
                                    placeholder="Quantity" value="${v?.quote_item?.quantity ?? 0}" required>
                            </span>
                            <span class="col-2 mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" min="1" class="form-control item_price" name="price[${i}]"
                                    placeholder="Price" value="${v?.quote_item?.unit_price ?? 0}" required>
                            </span>
                            <span class="col-2 mb-3">
                                <label for="total" class="form-label">Total</label>
                                <input type="number" class="form-control total" name="total[${i}]"
                                    placeholder="Total" value="${v?.quote_item?.total_price ?? 0}" readonly>
                            </span>
                            <span class="col-1 mb-3">
                                 ${!isEdit ? `<button type="button" class="btn btn-mx btn-danger btn-remove-item mt-4">
                                                <i class="fa fa-times"></i>
                                            </button>` : ''}
                                </span>
                            </div>
                            `
                })
                $(`#items`).html(html);
            }

        })
</script>
@endsection
