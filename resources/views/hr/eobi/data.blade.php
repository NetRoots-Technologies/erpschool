
<table id="users-table" class="table table-bordered table-striped datatable mt-3" style="width: 100%">
    <thead>
    {{--    <b style="border :1px solid #eee;"> Total: {{ $totalEmployees }}</b>--}}
    <tr>
        <th>Sr.#</th>
        <th>User Name</th>
        <th>Total</th>
        <th>Employee</th>
        <th>Company</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($employee))
        <tr>
            <td>
                1
            </td>
            <td>
                {{$employee->name}}
                <input type="hidden" name="employee_id[]" value="{{$employee->id}}">
            </td>
            <td>
                <input type="text" class="form-control" id="total-{{$employee->id}}" data-id="{{$employee->id}}"
                       name="total[]" readonly value="{{$total ?? ''}}">
            </td>
            <td>
                <input type="number" class="form-control" oninput="calculateTotal()"  id="employee-{{$employee->id}}" value="{{$employee_value ?? ''}}" name="employee_percentage[]">
            </td>
            <td>
                <input type="number" class="form-control" oninput="calculateTotal()"  name="company[]" id="company-{{$employee->id}}" value="{{$company ?? ''}}">
            </td>
        </tr>

    @else
        @php($i=1)
        @foreach($employees as $single)
            <tr>
                <td>
                    {{$i++}}
                </td>
                <td>
                    {{$single->name}}
                    <input type="hidden" name="employee_id[]" value="{{$single->id}}">
                </td>

{{--                <td>--}}
{{--                    <input type="number" class="form-control" id="fix_amount-{{$single->id}}" name="fix_amount[]"--}}
{{--                           data-id="{{$single->id}}">--}}
{{--                </td>--}}
                <td>
                    <input type="text" class="form-control" id="total-{{$single->id}}" data-id="{{$single->id}}"
                           name="total[]" readonly value="{{$total ?? ''}}">
                </td>
                <td>
                    <input type="number" class="form-control" id="employee-{{$single->id}}" oninput="calculateTotal()"  name="employee_percentage[]" value="{{$employee_value ?? ''}}">
                </td>
                <td>
                    <input type="number" class="form-control" name="company[]" oninput="calculateTotal()"  id="company-{{$single->id}}" value="{{$company ?? ''}}">
                </td>
                <td class="error" style="color: red;"></td>

            </tr>
        @endforeach
    @endif
    </tbody>
</table>


<script>
    function calculateTotal() {
        document.querySelectorAll('tr').forEach(row => {
            let totalInput = row.querySelector('input[id^="total-"]');
            let employeeInput = row.querySelector('input[id^="employee-"]');
            let companyInput = row.querySelector('input[id^="company-"]');
            let errorCell = row.querySelector('.error');

            if (totalInput && employeeInput && companyInput) {
                let totalValue = parseFloat(totalInput.value) || 0;
                let employeeValue = parseFloat(employeeInput.value) || 0;
                let companyValue = parseFloat(companyInput.value) || 0;

                let combinedPercentage = employeeValue + companyValue;

                if (combinedPercentage > totalValue) {
                    errorCell.innerText = "Total Value exceeds the maximum.";


                    let excessPercentage = combinedPercentage - totalValue;

                    if (employeeValue > companyValue) {
                        employeeInput.value = employeeValue - excessPercentage;
                    } else {
                        companyInput.value = companyValue - excessPercentage;
                    }


                } else {
                    errorCell.innerText = "";
                    employeeInput.dataset.previousValue = employeeValue;
                    companyInput.dataset.previousValue = companyValue;
                }
            }
        });
    }
</script>
