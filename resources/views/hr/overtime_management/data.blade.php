<table id="users-table" class="table table-bordered table-responsive table-striped datatable mt-3" style="width: 100%">
    <thead>
    {{--    <b style="border :1px solid #eee;"> Total: {{ $totalEmployees }}</b>--}}
    <tr>
        <th>Sr.#</th>
        <th>User Name</th>
        <th>After how many hours</th>
        <th>Price</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Action</th>
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
                <input type="text" class="form-control" name="total_time[]" value="{{$calculate_overtime ?? ''}}"
                       id="total_time-{{$employee->id}}" data-id="{{$employee->id}}" required>
            </td>

            <td>
                <input type="text" class="form-control" id="total-{{$employee->id}}" data-id="{{$employee->id}}"
                       name="total[]" value="{{$overtimeHourPrice ?? ''}}" required>
            </td>

            <td>
                <input type="date" class="form-control" name="start_date[]" value="{{$start_date ?? ''}}" required>
            </td>
            <td>
                <input type="date" class="form-control" name="end_date[]" value="{{$end_date ?? ''}}" required>
            </td>
            <td>
                <select class="form-select " name="action[]">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </td>
        </tr>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;margin-left: 10px;">Save</button>
        </div>
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
                    <input type="text" class="form-control" name="total_time[]" value="{{$calculate_overtime ?? ''}}"
                           id="total_time-{{$single->id}}" data-id="{{$single->id}}" required>
                </td>
                <td>
                    <input type="text" class="form-control" id="total-{{$single->id}}" data-id="{{$single->id}}"
                           name="total[]" value="{{$overtimeHourPrice ?? ''}}" required>
                </td>
                <td>
                    <input type="date" class="form-control" name="start_date[]" value="{{$start_date ?? ''}}" required>
                </td>
                <td>
                    <input type="date" class="form-control" name="end_date[]" value="{{$end_date ?? ''}}" required>
                </td>

                <td>
                    <select class="form-select " name="action[]">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;margin-left: 10px;">Save</button>
            </div>
        @endforeach
    @endif
    </tbody>
</table>


<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
