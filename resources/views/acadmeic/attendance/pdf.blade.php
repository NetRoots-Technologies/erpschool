<!-- resources/views/pdf/attendance.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Attendance</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .counts-row p {
            max-width: 30%;
            width: 100%;
            display: inline-block;
            margin-block: 4px !important
        }
    </style>
</head>

<body>
    <h3 style="float: left;">Student Attendance for Date :</h3>
    <h4> {!! $attendance['attendance_date'] !!} </h4>

    <table>
        <thead>
            <tr>
                <th>Sr.#</th>
                <th>Class</th>
                <th>Section</th>
                <th>Student Name</th>
                <th>Gender</th>
                <th>Attendance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $maleCount = 0;
                $femaleCount = 0;
                $presentCount = 0;
                $absentCount = 0;
                $leaveCount = 0;
            @endphp
            @foreach($attendance->AttendanceData as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ @$attendance->AcademicClass->name }}</td>
                    <td>{{ @$attendance->section->name }}</td>
                    <td>{{$data->student->student_id }} - {{ $data->student->first_name }} {{ $data->student->last_name }}
                    </td>
                    <td>{{ $data->student->gender === 'male' ? 'Male' : ($data->student->gender === 'female' ? 'Female' : '') }}
                    </td>
                    <td> {{ $data->attendance === 'P' ? 'Present' : ($data->attendance === 'A' ? 'Absent' : $data->attendance) }}
                    </td>
                    @if($data->student->gender === 'male')
                        @php
                            $maleCount++;
                        @endphp
                    @elseif($data->student->gender === 'female')
                        @php
                            $femaleCount++;
                        @endphp
                    @else
                        0
                    @endif
                    @if($data->attendance === 'P')
                        @php
                            $presentCount++;
                        @endphp
                    @elseif($data->attendance === 'A')

                        {{$absentCount++}}
                    @else
                        @php
                            $leaveCount++;
                        @endphp
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>


    <table style="margin-top: 20px; border: 2px solid black;">
        <tr>
            <td colspan="4">
                <div class="counts-row">
                    <p>Total Students: {{ $totalStudents }}</p>
                    <p>Present Students: {{ $presentStudentsCount }}</p>
                    <p>Absent Students: {{ $absentCount }}</p>
                    <p>Leave Students: {{ $leaveCount }}</p>
                    <p>Total Boys Present: {{  $maleCount }} </p>
                    <p>Total Girls Present: {{  $femaleCount  }} </p>
                    <p>Total Boys Present: {{  round(($maleCount/$totalStudents)*100,2)  }} %</p>
                    <p>Total Girls Present: {{   round(($femaleCount/$totalStudents)*100,2)   }} %</p>
                    <p>Total Present: {{ round(($presentStudentsCount/$totalStudents)*100,2)  }} %</p>
                    <p>Total Absents: {{ round(($absentCount/$totalStudents)*100,2)  }} %</p>
                </div>
            </td>
        </tr>
    </table>



</body>

</html>