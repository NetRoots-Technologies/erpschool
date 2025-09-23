@extends('admin.layouts.main')
@section('title', 'Add Budget Expense')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Expense</h3>

                        <div class="w-100">
                            <form action="{{ route('inventory.expense.store') }}" method="POST">
                                @csrf
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="budgetSelect">Budget</label>
                                            <select name="budget_id" id="budgetSelect" class="form-control">
                                                <option value="">-- Select Budget --</option>
                                                @foreach ($budgets as $budget)
                                                    <option value="{{ $budget->id }}">{{ $budget->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Category</label>
                                            <select name="category_id" id="categorySelect" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Subcategory</label>
                                            <select name="subcategory_id" id="subcategorySelect"
                                                class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label>Allowed Amount</label>
                                            <input type="text" id="allowedAmount" class="form-control" readonly>
                                            <span class="text-danger remaningAmount"></span>
                                        </div>
                                    </div>

                                </div>



                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Expense Date</label>
                                            <input type="date" name="expense_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Expense Amount</label>
                                            <input type="number" name="expense_amount" id="expenseAmount"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Remarks</label>
                                            <textarea name="description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary text-right">Save Expense</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            // Step 1: Budget select hone par categories load karna
            $("#budgetSelect").on("change", function() {
                var budgetId = $(this).val();

                $("#categorySelect").html('<option value="">-- Select Category --</option>');
                $("#subcategorySelect").html('<option value="">-- Select Subcategory --</option>');
                $("#allowedAmount").val("");

                if (budgetId) {
                    $.ajax({
                        url: "{{ route('inventory.get.categories.by.budget') }}", // apna route lagao
                        type: "GET",
                        data: {
                            budget_id: budgetId
                        },
                        success: function(list) {
                            $.each(list, function(_, item) {
                                $("#categorySelect").append('<option value="' + item
                                    .id + '">' + item.title + '</option>');
                            });
                        }
                    });
                }
            });


            // Step 2: Category select hone par subcategories load karna
            $("#categorySelect").on("change", function() {
                var categoryId = $(this).val();

                $("#subcategorySelect").html('<option value="">-- Select Subcategory --</option>');
                $("#allowedAmount").val("");

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('inventory.get.subcategories.by.category') }}", // apna route lagao
                        type: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(list) {
                            $.each(list, function(_, item) {
                                $("#subcategorySelect").append('<option value="' + item
                                    .id + '">' + item.title + '</option>');
                            });
                        }
                    });
                }
            });


            // Step 3: Subcategory select hone par allowedAmount load karna
            $("#subcategorySelect").on("change", function() {
                var subcategoryId = $(this).val();
                var budgetId = $("#budgetSelect").val();
                var categoryId = $("#categorySelect").val();
                $("#allowedAmount").val("");

                if (subcategoryId) {
                    $.ajax({
                        url: "{{ route('inventory.get.allowed.amount') }}", // apna route lagao
                        type: "GET",
                        data: {
                            budget_id: budgetId,
                            category_id: categoryId,
                            subcategory_id: subcategoryId,
                        },
                        success: function(response) {
                            
                            $("#allowedAmount").val(response.allowed_amount);
                            $(".remaningAmount").html('Remaining Amount : ' + response.rem_amount);
                        }
                    });
                }
            });






            // Delete functionality
            
              
        });
    </script>
@endsection
