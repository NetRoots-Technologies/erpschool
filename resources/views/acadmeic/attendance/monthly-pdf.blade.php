<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance - {{ $monthName }} {{ $year }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; transform: translateX(-24)}
        th, td { border: 1px solid #000; padding: 3px; text-align: center; }
        th { background-color: #f2f2f2; }
        .student-col { text-align: left; white-space: wrap; }
        .status-P { background-color: #c8e6c9; } /* green */
        .status-A { background-color: #ffcdd2; } /* red */
        .status-L { background-color: #fff9c4; } /* yellow */
    </style>
</head>
<body>
    <h3 style="text-align:center;">
        Monthly Attendance - {{ $monthName }} {{ $year }}
    </h3>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Student ID</th>
                <th rowspan="2">Name</th>
                {{-- <th rowspan="2">Gender</th> --}}
                @foreach($dates as $dateInfo)
                    <th>{{ date('d', strtotime($dateInfo['date'])) }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($dates as $dateInfo)
                    <th>{{ $dateInfo['day_name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td class="student-col">{{ $student->student_id }}</td>
                    <td class="student-col">{{ $student->first_name }} {{ $student->last_name }}</td>
                    {{-- <td>{{ ucfirst($student->gender) }}</td> --}}
                    @foreach($dates as $dateInfo)
                        @php
                            $status = $attendanceMatrix[$student->id][$dateInfo['date']] ?? '';
                        @endphp
                        <td class="status-{{ $status }}">
                            @if($status === 'P')
                                P
                            @elseif($status === 'A')
                                A
                            @elseif($status === 'L')
                                L
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
