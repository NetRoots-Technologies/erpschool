<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Detail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container">
    <table class="table">
        <thead>
        <tr>
            <th>Dates</th>
            <th>Day</th>
            <th>Status</th>
            <th>CheckIn Time</th>
            <th>CheckOut Time</th>
            <th>Working Hours</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['employees_data'] as $employeeId => $employee)
            @foreach($employee['attendance'] as $key => $attendance)
                @php
                    $attendanceData = $attendance ?? null;
                    $day = \Carbon\Carbon::parse($key)->format('l');
                    $date = \Carbon\Carbon::parse($key);
                    $formattedDate = $date->format('d F Y');
                @endphp
                @if($attendanceData)
                    <tr>
                        <td>{{ $formattedDate }}</td>
                        <td>{{ $day }}</td>
                        @if($attendanceData['present'] == true )
                            <td><p style="color: darkgreen;">Present</p></td>
                            <td style="color: darkgreen;">{!! $attendanceData['checkin_time'] ?? 'N/A' !!}</td>
                            <td style="color: darkgreen;">{!! $attendanceData['checkout_time'] ?? 'N/A' !!}</td>
                            <td style="color: darkgreen;">{!! $attendanceData['total_hours_worked'] ?? 'N/A' !!}</td>
                        @elseif($attendanceData['offDay'] == true)
                            <td><p style="color: blue;">Day off</p></td>
                            <td style="color: blue;">{!! $day !!}</td>
                            <td></td>
                            <td></td>
                        @elseif($attendanceData['absent'] == true)
                            <td><p style="color : red;">Absent</p></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @else
                            <td colspan="6">Unknown status</td>
                        @endif
                    </tr>
                @endif
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
