
<table id="users-table" class="table table-bordered table-striped datatable mt-3" style="width: 100%">
    <thead>
    {{--    <b style="border :1px solid #eee;"> Total: {{ $totalEmployees }}</b>--}}
    <tr>
        <th>Sr.#</th>
        <th>User</th>
        <th>Gross Amount</th>
        <th>Net Amount</th>
        <th>Deducted Amount</th>
        <th>Total Welfare Amount</th>
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
                <input type="text" class="form-control" id="gross-{{$employee->id}}" data-id="{{$employee->id}}"
                       name="grossSalary[]" readonly value="{{$employee->grossSalary ?? ''}}">
            </td>
            <td>
                <input type="number" class="form-control"   id="net-{{$employee->id}}"  value="{{$employee->salary ?? ''}}" name="netSalary[]">
            </td>
            <td>
                <input type="number" class="form-control"   name="total[]" id="total-{{$employee->id}}">
            </td>

            <td>
                <input type="number" class="form-control"   name="remaining[]" id="remaining-{{$employee->id}}">
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

                <td>
                    <input type="text" class="form-control" id="gross-{{$single->id}}" data-id="{{$single->id}}"
                           name="grossSalary[]" readonly value="{{$single->grossSalary ?? ''}}">
                </td>
                <td>
                    <input type="number" class="form-control"   id="net-{{$single->id}}"  value="{{$single->salary ?? ''}}" name="netSalary[]">
                </td>
                <td>
                    <input type="number" class="form-control"   name="total[]" id="total-{{$single->id}}">
                </td>
                <td>
                    <input type="number" class="form-control"   name="remaining[]" id="remaining-{{$single->id}}">
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<script>
    $(document).ready(function() {
        calculateTotal();

        function calculateTotal() {
            document.querySelectorAll('tr').forEach(row => {
                let grossSalary = row.querySelector('input[id^="gross-"]');
                let netSalary = row.querySelector('input[id^="net-"]');
                let total = row.querySelector('input[id^="total-"]');
                let remaining = row.querySelector('input[id^="remaining-"]');

                if (grossSalary && netSalary && total && remaining) {
                    let grossSalaryValue = parseFloat(grossSalary.value) || 0;
                    let netSalaryValue = parseFloat(netSalary.value) || 0;

                    let deductedAmount = grossSalaryValue - netSalaryValue;
                    total.value = deductedAmount;

                    let times = 1.5;
                    remaining.value = deductedAmount * times;
                }
            });
        }
    });
</script>

