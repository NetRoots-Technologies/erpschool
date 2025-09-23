@extends('admin.layouts.main')
@section('title', 'Edit Budget')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            Edit Budget
        </div>
        <div class="card-body">
            <form id="budgetForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="budgetId" value="{{ $budget->id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Budget Name</label>
                        <input type="text" name="title" class="form-control" 
                               value="{{ $budget->title }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Plan(Year)</label>
                        <input type="text" class="form-control" value="{{ $budget->timeFrame }}" 
                               name="timeFrame" id="timeFrame" readonly>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Annual Amount</label>
                        <input type="number" name="amount" id="annualAmount" class="form-control" 
                               value="{{ $budget->amount }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Start Date</label>
                        <input type="date" name="startDate" id="startDate" class="form-control" 
                               value="{{ $budget->startDate }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>End Date</label>
                        <input type="date" name="endDate" id="endDate" class="form-control" 
                               value="{{ $budget->endDate }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control">{{ $budget->description }}</textarea>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="button" id="generateTable" class="btn btn-primary">Next</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Monthly Breakdown Table --}}
    <div class="card mt-4 d-none" id="monthlyCard">
        <div class="card-header">Monthly Breakdown</div>
        <div class="card-body">
            <div id="monthlyTable"></div>
            <div class="text-end mt-3">
                <button class="btn btn-success" id="finalSave">Update</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>

    // Step 1: Generate breakdown table on Next click
    $('#generateTable').on('click', function() {
        let name = $('input[name="title"]').val();
        let amount = parseFloat($('#annualAmount').val());
        let start_date = $('#startDate').val();
        let end_date = $('#endDate').val();
        let description = $('#description').val();
        let timeFrame = $('#timeFrame').val();

        if (!name || !amount || !start_date || !end_date) {
            alert("Please fill all required fields");
            return;
        }

        let allowed_spend = (amount / 12).toFixed(2);

         let table = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                       <th style="width:5%">#</th>
                        <th style="width:10%">Month</th>
                        <th style="width:15%">Allocated Amount</th>
                        <th style="width:20%">Allowed Spend</th>
                    </tr>
                </thead>
                <tbody>
        `;

            let start = new Date(start_date);
            for (let i = 0; i < 12; i++) {
                let monthDate = new Date(start.getFullYear(), start.getMonth() + i, 1);
                let monthYear = ("0" + (monthDate.getMonth() + 1)).slice(-2) + "-" + monthDate.getFullYear();

                table += `
                <tr>
                    <td>${i+1}</td>
                    <td>${monthYear}</td>
                    <td><input type="number" class="form-control allocated_amount" name="allocated_amount" value="0" data-month="${monthYear}"></td>
                    <td>
                        <input type="number" class="form-control allowed-spend" name="allowed-spend" value="" placeholder="Enter amount" data-month="${monthYear}" readonly>
                    </td>
                </tr>
            `;
            }

            table += `</tbody></table>`;
            $('#monthlyTable').html(table);
            $('#monthlyCard').removeClass('d-none');
        });

    // Step 2: Final Save - Send all data with breakdown
    $('#finalSave').on('click', function() {
            let budgetData = {
                _token: "{{ csrf_token() }}",
                name: $('input[name="title"]').val(),
                amount: $('#annualAmount').val(),
                start_date: $('#startDate').val(),
                end_date: $('#endDate').val(),
                description: $('#description').val(),
                timeFrame: $('#timeFrame').val(),
                months: []
            };

            $('.allowed-spend').each(function() {
                budgetData.months.push({
                    month: $(this).data('month'),
                    allocated_amount: $(this).closest('tr').find('td:eq(2)').text(),
                    allowed_spend: $(this).val()
                });
            });

         var url = "{{ route('inventory.budget.update', $budget->id) }}";

            $.ajax({
                url: url,
                method: "PUT",
                data: budgetData,
                success: function(response) {
                    if (response.status == 200) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = "{{ url('inventory/budget') }}";
                        }, 1500);
                    }
                }
            });
    });

    $('#startDate').on('change', function () {
    let startDate = new Date($(this).val());

    if (!isNaN(startDate.getTime())) {
        // Ek saal add karo
        let endDate = new Date(startDate);
        endDate.setFullYear(endDate.getFullYear() + 1);

        // Format yyyy-mm-dd banana hai
        let day = ("0" + endDate.getDate()).slice(-2);
        let month = ("0" + (endDate.getMonth() + 1)).slice(-2);
        let year = endDate.getFullYear();

        let formattedDate = `${year}-${month}-${day}`;
        $('#endDate').val(formattedDate);
    }
    });

    $(document).on('input', '.allocated_amount', function() {
            let value = parseFloat($(this).val()) || 0;
            let row = $(this).closest('tr');
            row.find('.allowed-spend').val(value);
        });

        // Jab allowed spend edit karo, check karo ke allocated se zyada na ho
        $(document).on('input', '.allocated_amount', function() {
            let annualBudget = parseFloat($('#annualAmount').val()) || 0;
            let totalAllocated = 0;

            // saare allocated_amount ka sum nikalo
            $('.allocated_amount').each(function() {
                totalAllocated += parseFloat($(this).val()) || 0;
            });

            // agar total Annual se zyada ho gaya to alert
            if (totalAllocated > annualBudget) {
                alert("Total Allocated Amount cannot exceed Annual Budget!");

                // current value reset karo
                $(this).val(0);
            }

            // allocated value ko allowed-spend me copy karo
            let row = $(this).closest('tr');
            let value = parseFloat($(this).val()) || 0;
            row.find('.allowed-spend').val(value);
        });
</script>
@endsection
