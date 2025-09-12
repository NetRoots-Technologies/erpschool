<table id="users-table" class="table table-bordered table-striped datatable mt-3" style="width: 100%">
    <thead>
        {{-- <b style="border :1px solid #eee;"> Total: {{ $totalEmployees }}</b>--}}
        <tr>
            <th>Sr.#</th>
            <th>User Name</th>
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
                <input type="number" class="form-control" oninput="calculateTotal()" id="employee-{{$employee->id}}"
                    value="{{$employee->salary ?? ''}}" name="employee_percentage[]">
            </td>
            <td>
                <input type="number" class="form-control" oninput="calculateTotal()" name="fund[]"
                    id="fund-{{$employee->id}}" value="{{$provit ?? ''}}">
            </td>
        </tr>

        @else
        @foreach($employees as $key => $single)
        <tr>
            <td>
                {{++$key}}
            </td>
            <td>
                {{$single->name}}
                <input type="hidden" name="employee_id[]" value="{{$single->id}}">
            </td>
            <td>
                <input type="number" class="form-control" id="employee-{{$single->id}}" oninput="calculateTotal()"
                    name="employee_percentage[]" value="{{$single->salary ?? ''}}">
            </td>
            <td>
                <input type="number" class="form-control" name="fund[]" id="fund-{{$single->id}}"
                    oninput="calculateTotal()" value="{{$provit ?? ''}}">
            </td>

            <td>
                <input type="number" class="form-control" name="total[]" id="total-{{$single->id}}" readonly>

            </td>
            <td class="error" style="color: red;"></td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>

<script>
    function calculateTotal() {

    }
</script>