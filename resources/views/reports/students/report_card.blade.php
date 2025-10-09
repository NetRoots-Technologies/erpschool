    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Progress Report</title>
        <style>
            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }

            .report-container {
                border: 1px solid #ccc;
                padding: 20px;
            }

            .school-header {
                text-align: center;
                margin-bottom: 15px;
            }

            .line {
                border-top: 2px solid #444;
                margin: 10px 0;
            }

            /* .student-photo {
                width: 100px;
                height: 100px;
                border: 1px solid #333;
                margin: 10px auto;
                display: block;
            } */

            .info-box {
                border: 1px solid #000;
                padding: 10px;
                margin-top: 15px;
                font-size: 12px;
                text-align: "center" !important;
            }

            .info-box p {
                margin: 4px 0;
            }

            .underline {
                display: inline-block;
                min-width: 180px;
                border-bottom: 1px solid #000;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 12px;
            }

            table th,
            table td {
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
                vertical-align: top;
            }

            table th {
                background: #f0f0f0;
                text-align: center;
            }

            ul {
                margin: 0;
                padding-left: 15px;
            }

            .fw-bold {
                font-weight: bold;
            }
        </style>
    </head>

    <body>

        <div class="report-container">
            <!-- Header -->
            <div class="school-header">
                <img src="https://staging.cornerstone.erpnetroots.com/image/css.png" alt="School Logo">

                <h3>Progress Report</h3>
            </div>

            <div class="line"></div>

            <!-- Report Info -->
            <div style="text-align: center;">
                <p>{{ $student->section->session->name }}<br>
                    Reporting Period: JANUARY-MAY<br>
                    PRE-SCHOOL<br>
                    Grade PREPARATORY<br>
                    FINAL TERM</p>
            </div>

            @php
                $path = public_path(
                    !empty($student->studentPictures->passport_photos)
                        ? $student->studentPictures->passport_photos
                        : 'report\std.jpg',
                );
                $image = base64_encode(file_get_contents($path));
            @endphp

            <div class="line"></div>
            {{-- @dd(public_path($student->studentPictures->passport_photos)); --}}
            <!-- Student Photo -->
            {{-- <img src="https://media.istockphoto.com/id/1298626342/photo/clever-young-boy-holding-folders-for-studing-at-school-isolated-over-yellow-background.jpg?s=2048x2048&w=is&k=20&c=Phd1ilUJcvquEWGeydA72sBjJB0yNS5PukTlfQNcPm4=" alt="Student Photo" class="student-photo" width="100" style="text-align: center">  --}}
            <div style="text-align: center; margin-bottom: 15px;">
                <img src="https://media.istockphoto.com/id/1298626342/photo/clever-young-boy-holding-folders-for-studing-at-school-isolated-over-yellow-background.jpg?s=2048x2048&w=is&k=20&c=Phd1ilUJcvquEWGeydA72sBjJB0yNS5PukTlfQNcPm4="
                    alt="Student Photo" width="120"
                    style="display: inline-block; border: 1px solid #ccc; border-radius: 5px;">
            </div>




            <!-- Student Information -->
            <div class="info-box">
                <p><strong>Registration No.:</strong> <span class="underline">STD-001</span></p>
                <p><strong>Student’s Name:</strong> <span class="underline">{{ $student->first_name }}
                        {{ $student->last_name }}</span></p>
                <p><strong>Parent/Guardian’s Name:</strong> <span class="underline">{{ $student->guardian_name }}</span>
                </p>
                <p><strong>Address:</strong> <span class="underline">{{ $student->student_current_address }}</span></p>
                <p><strong>Phone:</strong> <span class="underline">{{ $student->landline }}</span></p>
                <p><strong>Issue Date:</strong> <span
                        class="underline">{{ date('Y-m-d', strtotime($student->admission_date)) }}</span></p>
                <p style="text-align: left;"><strong>Attendance</strong></p>
                <p>Total No. of Days: <span class="underline">82</span></p>
                <p>Days Attended: <span class="underline">88</span></p>
                <p>Attendance Percentage: <span class="underline">92%</span></p>
            </div>

            <div class="line"></div>

            <h4 style="text-align:center;">Skills</h4>

            <!-- Skills -->
            @foreach ($skills as $subjectId => $skillGroups)
                @php
                    $subject = $skillGroups->first()->subject;
                    $effort = $efforts->has($subjectId) ? $efforts[$subjectId]->first() : null;
                @endphp

                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{ $subject->name }}</th>
                            @php $eff = optional($efforts->get($student->id))->first(); @endphp

                            <th>Level: {{ $eff->level ?? '-' }}</th>
                            <th>Skill</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skillGroups->groupBy('group_id') as $groupId => $groupSkills)
                            <tr>
                                <td colspan="3">{{ $groupSkills->first()->group->skill_group ?? '' }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <ul>
                                        @foreach ($groupSkills as $skill)
                                            <li>{{ $skill->skill->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td></td>
                                <td>
                                    <ul style="list-style:none; padding:0; margin:0;">
                                        @foreach ($groupSkills as $skill)
                                            @php
                                                // Check if this skill has evaluation for this student
                                                $evaluation = $skill->subject->EvolutionKeySkills
                                                    ->where('skill_id', $skill->skill_id)
                                                    ->where('student_id', $student->id)
                                                    ->first();
                                            @endphp
                                             {{-- @dd($evaluation->key); --}}

                                            <li>
                                                {{ $evaluation && $evaluation->key ? $evaluation->key->key : '-' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                            </tr>
                        @endforeach


                        <tr class="fw-bold">
                            <td colspan="3">Effort in {{ $subject->name }}</td>
                            @php $eff = optional($efforts->get($student->id))->first(); @endphp
                            <td>{{ $eff->effort ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            @endforeach

            <!-- Static Notes -->
            <table>
                <tr>
                    <td>
                        <strong>LEVELS OF ACHIEVEMENT</strong><br>
                        <strong>N/A = Not Applicable:</strong> Not applicable for this student.<br>
                        <strong>3 = Fully Meets Expectations:</strong> completes assignments independently.<br>
                        <strong>2 = Meets Expectations:</strong> completes assignments with occasional assistance.<br>
                        <strong>1 = Minimally Meets Expectations:</strong> needs more assistance.<br>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td><strong>Effort Level:</strong></td>
                    <td>Very Good / Good / Satisfactory / Needs Improvement / N/A</td>
                </tr>
            </table>

                 @if(!empty($student->remarks->remarks))
                <div class="mt-4">
                    <p><strong>Concluding Teacher &nbsp;({!! $student->remarks->author->name !!})Comments:</strong> {!! nl2br(e($student->remarks->remarks)) !!}</p>
                </div>
                @endif

            <div class="mt-4">

                <p><strong>Concluding Comments:</strong>
                    In addition to her academic achievements, Rayan Ahmad Dar has shown
                    great character
                    and integrity. He has consistently acted with kindness and respect
                    towards others, and has
                    been a positive influence in our classroom. I know that he will continue
                    to make a positive
                    impact on the world around him. I am excited to see what he will
                    accomplish in the years to
                    come. Best wishes! for the next academic year.</p>

                <p style="text-align: center;"><input type="checkbox"> ESL: English as a
                    Second Language </p>
                <p style="text-align: center;"> <input type="checkbox"> USL: Urdu as a
                    Second Language </p>
                <p style="text-align: center;"> <input type="checkbox"> Promoted </p>
                <p style="text-align: center;"> <input type="checkbox"> Not Promoted </p>
                <p style="text-align: center; margin-bottom: 100px;"> <input type="checkbox"> ESL: Conditional Promotion
                </p>

                <p style="text-align: center; margin-bottom: 100px;"> Homeroom Teacher's
                    Signature:

                    <span
                        style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                        (Mahay Abubaka)
                    </span>

                </p>

                <p style="text-align: center;    margin-bottom: 100px; "> Academic
                    Coordinator's Signature:

                    <span
                        style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                        (Rabiah Waleed)
                    </span>

                </p>

                <p style="text-align: center; margin-bottom: 100px;"> Headmistress's
                    Signature:

                    <span
                        style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                        (Saima Rizvi)
                    </span>

                </p>

                <p style="text-align: center;"> School Head's Signature:

                    <span
                        style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                        (Azeema Husnain)
                    </span>

                </p>

            </div>

        </div>

    </body>

    </html>
