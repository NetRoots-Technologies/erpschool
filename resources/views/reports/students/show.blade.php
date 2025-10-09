@extends('admin.layouts.main')

@section('title') View Student @stop

@section('content')
    <style>
        /* PRINT FRIENDLY */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .report-container {
                border: 0 !important;
            }
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .report-container {
            border: 1px solid #ccc;
            padding: 20px;
            background: #fff;
        }

        .school-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .line {
            border-top: 2px solid #444;
            margin: 10px 0;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 15px;
            font-size: 12px;
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

        /* Toolbar */
        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* .toolbar .btn {
            border: 1px solid #1f2937; background: #1f2937; color: #fff; padding: 8px 14px; border-radius: 6px;
            text-decoration: none; cursor: pointer; font-size: 13px;
        }
        .toolbar .btn.secondary { background: #fff; color: #1f2937; }
        .toolbar .btn:hover { opacity: 0.9; } */
        textarea[name="remarks"] {
            width: 100%;
            min-height: 90px;
            resize: vertical;
        }
    </style>

    <div class="container-fluid">

        <!-- Action Toolbar -->
        <div class="no-print toolbar">
            <button type="button" class="btn btn-primary" onclick="window.history.back();">Back</button>
            {{-- Print button --}}
            <button type="button" class="btn btn-warning" id="printBtn"
                data-url = "{{ route('reports.exam.print', $student->id) }}">Print Download</button>
            {{-- Save remarks form submit button (outside form via JS) --}}
            <button type="button" class="btn btn-success" id="saveBtn">Save Remarks</button>
        </div>

        {{-- Remarks form (POST) --}}
        <form id="remarksForm" method="POST" action="{{ route('students.progress.remarks.store', $student->id) }}">
            @csrf

            {{-- Hidden field if you want to pass context (term/session/etc.) --}}
            {{-- <input type="hidden" name="term" value="FINAL TERM"> --}}

            <div class="report-container" id="printArea">
                <div class="school-header">
                    <img src="https://staging.cornerstone.erpnetroots.com/image/css.png" alt="School Logo"
                        style="max-height:70px">
                    <h3 style="margin:8px 0 0;">Progress Report</h3>
                </div>

                <div class="line"></div>

                <div style="text-align: center;">
                    <p>
                        {{ $student->section->session->name }}<br>
                        Reporting Period: JANUARY–MAY<br>
                        PRE-SCHOOL<br>
                        Grade PREPARATORY<br>
                        FINAL TERM
                    </p>
                </div>

                {{-- Student Photo (fallback if missing) --}}
                @php
                    $photoPath = !empty($student->studentPictures->passport_photos)
                        ? public_path($student->studentPictures->passport_photos)
                        : public_path('report/std.jpg');

                    $imageBase64 = file_exists($photoPath) ? base64_encode(file_get_contents($photoPath)) : null;
                @endphp

                <div style="text-align: center; margin-bottom: 15px;">
                    @if ($imageBase64)
                        <img src="data:image/jpeg;base64,{{ $imageBase64 }}" alt="Student Photo" width="120"
                            style="display:inline-block; border:1px solid #ccc; border-radius:5px;">
                    @else
                        <img src="https://media.istockphoto.com/id/1298626342/photo/clever-young-boy-holding-folders-for-studing-at-school-isolated-over-yellow-background.jpg?s=2048x2048&w=is&k=20&c=Phd1ilUJcvquEWGeydA72sBjJB0yNS5PukTlfQNcPm4="
                            alt="Student Photo" width="120"
                            style="display:inline-block; border:1px solid #ccc; border-radius:5px;">
                    @endif
                </div>

                <div class="info-box">
                    <p><strong>Registration No.:</strong> <span
                            class="underline">{{ $student->registration_no ?? 'STD-001' }}</span></p>
                    <p><strong>Student’s Name:</strong> <span class="underline">{{ $student->first_name }}
                            {{ $student->last_name }}</span></p>
                    <p><strong>Parent/Guardian’s Name:</strong> <span class="underline">{{ $student->guardian_name }}</span>
                    </p>
                    <p><strong>Address:</strong> <span class="underline">{{ $student->student_current_address }}</span></p>
                    <p><strong>Phone:</strong> <span class="underline">{{ $student->landline }}</span></p>
                    <p><strong>Issue Date:</strong> <span
                            class="underline">{{ date('Y-m-d', strtotime($student->admission_date)) }}</span></p>

                    <p style="text-align:left;"><strong>Attendance</strong></p>
                    <p>Total No. of Days: <span class="underline">82</span></p>
                    <p>Days Attended: <span class="underline">88</span></p>
                    <p>Attendance Percentage: <span class="underline">92%</span></p>
                </div>

                <div class="line"></div>

                <h4 style="text-align:center;">Skills</h4>

                @foreach ($skills as $subjectId => $skillGroups)
                    @php
                        $subject = $skillGroups->first()->subject;
                        $eff = optional($efforts->get($student->id))->first();
                    @endphp

                    <table>
                        <thead>
                            <tr>
                                <th colspan="2">{{ $subject->name }}</th>
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
                                                    $evaluation = $skill->subject->EvolutionKeySkills
                                                        ->where('skill_id', $skill->skill_id)
                                                        ->where('student_id', $student->id)
                                                        ->first();
                                                @endphp
                                                <li>{{ $evaluation && $evaluation->key ? $evaluation->key->key : '-' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="fw-bold">
                                <td colspan="3">Effort in {{ $subject->name }}</td>
                                <td>{{ $eff->effort ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach

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

                {{-- Remarks (REQUIRED) --}}
                <div class="no-print" style="margin: 18px 0 10px;">
                    <label for="remarks" class="fw-bold">Remarks (required)</label>
                    <textarea id="remarks" name="remarks" placeholder="Enter concluding comments / teacher remarks..." required>{{ old('remarks', $student->remarks->remarks ?? '') }}</textarea>
                    @error('remarks')
                        <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Printable concluding comments (shows what you saved) --}}
                @if (!empty($student->remarks->remarks))
                    <div class="mt-4">
                        <p><strong>Concluding Teacher &nbsp;({!! $student->remarks->author->name !!})Comments:</strong>
                            {!! nl2br(e($student->remarks->remarks)) !!}</p>
                    </div>
                @endif

                <div class="mt-4">
                    <p style="text-align: center;"><input type="checkbox"> ESL: English as a Second Language </p>
                    <p style="text-align: center;"><input type="checkbox"> USL: Urdu as a Second Language </p>
                    <p style="text-align: center;"><input type="checkbox"> Promoted </p>
                    <p style="text-align: center;"><input type="checkbox"> Not Promoted </p>
                    <p style="text-align: center; margin-bottom: 100px;"><input type="checkbox"> ESL: Conditional Promotion
                    </p>

                    <p style="text-align:center; margin-bottom: 100px;">
                        Homeroom Teacher's Signature:
                        <span
                            style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                            (Mahay Abubaka)
                        </span>
                    </p>

                    <p style="text-align:center; margin-bottom: 100px;">
                        Academic Coordinator's Signature:
                        <span
                            style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                            (Rabiah Waleed)
                        </span>
                    </p>

                    <p style="text-align:center; margin-bottom: 100px;">
                        Headmistress's Signature:
                        <span
                            style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                            (Saima Rizvi)
                        </span>
                    </p>

                    <p style="text-align:center;">
                        School Head's Signature:
                        <span
                            style="display:inline-block; border-top:1px solid #000; min-width:200px; text-align:center; margin:0 10px;">
                            (Azeema Husnain)
                        </span>
                    </p>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {

            const remarks = document.getElementById('remarks');
            const printUrl = this.getAttribute('data-url');
            window.open(printUrl, '_blank');
        });

        document.getElementById('saveBtn').addEventListener('click', function() {
            const form = document.getElementById('remarksForm');
            if (!form.reportValidity()) return;
            form.submit();


        });
    </script>
@endsection
