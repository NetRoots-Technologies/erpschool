
<table id="users-table" class="table table-bordered table-striped datatable mt-3" style="width: 100%">
    <thead>
    {{--    <b style="border :1px solid #eee;"> Total: {{ $totalEmployees }}</b>--}}
    <tr>
        <th>Sr.#</th>
        <th>User Name</th>
        <th>Social Security</th>

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
                <input type="text" class="form-control" id="percentage-{{$employee->id}}" data-id="{{$employee->id}}"
                       name="percentage[]"  value="{{$percentage ?? ''}}">
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
                    <input type="text" class="form-control" id="percentage-{{$single->id}}" data-id="{{$single->id}}"
                           name="percentage[]"  value="{{$percentage ?? ''}}">
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
