@extends('admin.layouts.main')
@section('title', 'Assign Department Budget')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Assign Department Budget - <b>{{ $budget->title }}</b>
            </div>
            <div class="card-body">
                <form id="deptBudgetForm">
                    @csrf
                    <input type="hidden" name="budget_id" value="{{ $budget->id }}">

                    <div class="accordion" id="monthAccordion">
                        @foreach ($budget->details as $key => $detail)
                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="heading{{ $key }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $key }}">
                                        {{ $detail->month }} (Allowed Spend: {{ $detail->allowed_spend }})
                                    </button>
                                </h2>
                                <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                                    data-bs-parent="#monthAccordion">
                                    <div class="accordion-body">
                                        <table class="table table-bordered dept-table" data-month="{{ $detail->month }}"
                                            data-allowed="{{ $detail->allowed_spend }}">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Sub Category</th>
                                                    <th>Allocated Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>


                                            <tbody>
                                                @if (isset($dpartBudget[$detail->month]))
                                                    @foreach ($dpartBudget[$detail->month] as $db)
                                                        <tr>
                                                            <td>
                                                                <select name="categories[{{ $detail->month }}][]"
                                                                    class="form-control category-select">
                                                                    <option value="" selected>Select Category</option>
                                                                    @foreach ($categories as $cat)
                                                                        <option value="{{ $cat->id }}"
                                                                            {{ $db->category_id == $cat->id ? 'selected' : '' }}>
                                                                            {{ $cat->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <select name="subcategories[{{ $detail->month }}][]" 
                                                                        class="form-control subcategory-select"
                                                                        data-selected="{{ $db->sub_category_id }}">
                                                                    <option value="">Select Sub Category</option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number"
                                                                    name="amounts[{{ $detail->month }}][]"
                                                                    class="form-control dept-amount"
                                                                    value="{{ $db->amount }}" placeholder="Enter amount">
                                                            </td>

                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-success addRow">+</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            {{-- Parent category --}}
                                                            <select name="categories[{{ $detail->month }}][]"
                                                                class="form-control category-select">
                                                                <option value="" selected>Select Category</option>
                                                                @foreach ($categories as $cat)
                                                                    <option value="{{ $cat->id }}">
                                                                        {{ $cat->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                           
                                                            <select name="subcategories[{{ $detail->month }}][]"
                                                                class="form-control subcategory-select">
                                                                <option value="" selected>Select Sub Category</option>
                                                            </select>
                                                        </td>

                                                       


                                                        <td>
                                                            <input type="number" name="amounts[{{ $detail->month }}][]"
                                                                class="form-control dept-amount" placeholder="Enter amount">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-success addRow">+</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        


        $(document).ready(function() {
    // Page load par update case ke liye subcategories load karo
            $('.category-select').each(function() {
                var parentId = $(this).val(); // selected category
                var subcategoryDropdown = $(this).closest('tr').find('.subcategory-select');
                var selectedSubcat = subcategoryDropdown.data('selected'); // prefilled subcat id

                if (parentId) {
                    $.ajax({
                        url: '/inventory/get-subcategories/' + parentId,
                        type: 'GET',
                        success: function(data) {
                            subcategoryDropdown.html('<option value="">Select Sub Category</option>');
                            $.each(data, function(key, subcat) {
                                subcategoryDropdown.append(
                                    '<option value="' + subcat.id + '" ' +
                                    (selectedSubcat == subcat.id ? 'selected' : '') + '>' +
                                    subcat.title + '</option>'
                                );
                            });
                        }
                    });
                }
            });
        });


        $(document).on('change', '.category-select', function() {
            var parentId = $(this).val();
            var subcategoryDropdown = $(this).closest('tr').find('.subcategory-select');

            // clear old options
            subcategoryDropdown.html('<option value="">Select Sub Category</option>');

            if (parentId) {
                $.ajax({
                    url: '/inventory/get-subcategories/' + parentId,
                    type: 'GET',
                    success: function(data) {
                        $.each(data, function(key, subcat) {
                            subcategoryDropdown.append('<option value="' + subcat.id + '">' +
                                subcat.title + '</option>');
                        });
                    }
                });
            }
        });



        // row clone karte waqt select values reset kar do
        $(document).on('click', '.addRow', function() {
            let row = $(this).closest('tr').clone();
            row.find('input.dept-amount').val('');
            row.find('select').val(''); // select ko reset kare
            row.find('.addRow')
                .removeClass('btn-success addRow')
                .addClass('btn-danger removeRow')
                .text('X');
            $(this).closest('tbody').append(row);
        });

       

        // subcategories
        $(document).on('change', 'select[name^="subcategories"]', function() {
            let selectedsubcategories = $(this).val();
            let tbody = $(this).closest('tbody');
            let duplicate = 0;

            tbody.find('select[name^="subcategories"]').each(function() {
                if ($(this).val() == selectedsubcategories && selectedsubcategories != "") {
                    duplicate++;
                }
            });

            if (duplicate > 1) {
                alert("This subcategories is already selected in this month!");
                $(this).val(""); // reset dropdown
            }
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });

        $(document).on('input', '.dept-amount', function() {
            let table = $(this).closest('.dept-table');
            let allowed = parseFloat(table.data('allowed'));
            let total = 0;

            table.find('.dept-amount').each(function() {
                total += parseFloat($(this).val()) || 0;
            });

            if (total > allowed) {
                alert("Total allocation exceeds allowed spend for " + table.data('month'));
                $(this).val('');
            }
        });

        $('#deptBudgetForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('inventory.budget.storeDepartment', $budget->id) }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success("Department budgets saved");
                    setTimeout(() => {
                        window.location.href = "{{ url('inventory/budget') }}";
                    }, 1500);
                },
                error: function() {
                    toastr.error("Error saving data");
                }
            });
        });
    </script>
@endsection
