<table id="users-table" class="table table-bordered table-striped datatable mt-3" style="width: 100%">
    <thead>
{{--    <b style="border :1px solid #eee;"> Total: {{ $totalEmployees }}</b>--}}
    <tr>
        <th>Sr.#</th>
        <th>User Name</th>
        <th>Status</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>Remarks</th>
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
                <select name="status[]" id="status-{{$employee->id}}" data-id="{{$employee->id}}"
                        class="form-control status" required>
                    <option value="" selected disabled>Select Status</option>
                    <option value="1">Present</option>
                    <option value="2">Absent</option>
                </select>
            </td>
            <td>
                <input name="timeIn[]" readonly id="timeIn-{{$employee->id}}" data-id="{{$employee->id}}" type="time"
                       class="form-control">
            </td>
            <td>
                <input name="timeOut[]" readonly id="timeOut-{{$employee->id}}" data-id="{{$employee->id}}" type="time"
                       class="form-control">
            </td>
            <td>
                <input name="remarks[]" readonly id="remarks-{{$employee->id}}" data-id="{{$employee->id}}"
                       placeholder="Remarks"
                       type="text" class="form-control">
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
                    <select name="status[]" id="status-{{$single->id}}" data-id="{{$single->id}}"
                            class="form-control status" required>
                        <option value="" selected>Select Status</option>
                        <option value="1">Present</option>
                        <option value="2">Absent</option>
                    </select>
                </td>
                <td>
                    <input name="timeIn[]" readonly id="timeIn-{{$single->id}}" data-id="{{$single->id}}" type="time"
                           class="form-control" >
                </td>
                <td>
                    <input name="timeOut[]" readonly id="timeOut-{{$single->id}}" data-id="{{$single->id}}" type="time"
                           class="form-control">
                </td>
                <td>
                    <input name="remarks[]" readonly id="remarks-{{$single->id}}" data-id="{{$single->id}}"
                           placeholder="Remarks"
                           type="text" class="form-control">
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

<script>
    $(document).on('change','.status', function () {
        var id = this.value;
        if (id == 1) {
    var dataid = $(this).attr("data-id");

    $("#timeIn-" + dataid).prop('readonly', false).attr('required', true);
    $("#timeOut-" + dataid).prop('readonly', false).attr('required', true);
    $("#remarks-" + dataid).prop('readonly', false).attr('required', true);
    } else {
        var dataid = $(this).attr("data-id");

        $("#timeIn-" + dataid).prop('readonly', true).removeAttr('required');
        $("#timeOut-" + dataid).prop('readonly', true).removeAttr('required');
        $("#remarks-" + dataid).prop('readonly', true).removeAttr('required');
    }

    });
</script>
