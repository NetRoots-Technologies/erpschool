<div style="border: 1px solid #DDE2EF; padding: 10px;">

    @if($dataAvailable)
        <table class="table table-bordered col-md-12">
            <tbody>
            <tr>
                <th width="30%">Employee</th>
                <td>{!!  $result['employeeName'] !!}</td>
            </tr>
            <tr>
                <th>Leave Type</th>
                <td>{!! $result['leaveType'] !!}</td>
            </tr>
            <tr>
                <th>As of Date</th>
                <td>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</td>
            </tr>
            </tbody>
        </table>

        <table class="table table-striped col-md-12">
            <tbody>
            <tr>
                <th width="70%">Total</th>
                <td>{!! $result['total_days'] !!}</td>
            </tr>
            <tr>
                <th>Approved</th>
                <td>{!!  $result['approved_days'] !!}</td>
            </tr>
            <tr>
                <th>pending</th>
                <td>{!! $result['totalPending'] !!}</td>
            </tr>
            <tr>
                <th>Rejected</th>
                <td>{!! $result['totalRejected'] !!}</td>
            </tr>
            <tr>
                <th>Balance</th>
                <th>
                    @if(($result['total_days'] - ($result['approved_days'] + $result['totalPending'])) < 0)
                        0
                    @else
                        {{ $result['total_days'] - ($result['approved_days'] + $result['totalPending']) }}
                    @endif
                </th>
            </tr>

            </tbody>
        </table>
    @else

        <div class="alert alert-warning alert-dismissible fade show" role="alert"
             style="background-color: #dc3545; color: white;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="fas fa-exclamation-circle"></span>
            Please select an employee and leave type to view the leave balance.
        </div>



    @endif

</div>
