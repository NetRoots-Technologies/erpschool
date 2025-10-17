@extends('admin.layouts.main')

@section('title')
    Exam Detail Update
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops! Something went wrong.</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <h3 class="text-22 text-midnight text-bold mb-4">Update Exam Schedule</h3>

                        <div class="row mt-4 mb-4">
                            <div class="col-12 text-right">
                                <a href="{{ route('exam.exam_schedules.index') }}" class="btn btn-primary btn-md">Back</a>
                            </div>
                        </div>

                        <form action="{{ route('exam.exam_schedules.update', $exam_schedule_detail->id) }}" method="POST"
                            enctype="multipart/form-data" id="form_validation" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="w-100 p-3">
                                <div class="box-body mt-4">

                                    <div class="row mt-3">
                                        {{-- Company --}}
                                        <div class="col-md-3">
                                            <label for="company"><b>Company:</b></label>
                                            <select name="company_id" id="companySelect" class="form-select select2 mt-3"
                                                required>
                                                @foreach ($companies as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $exam_schedule_detail->company_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Branch --}}
                                        <div class="col-md-3">
                                            <label for="branches"><b>Branch:</b></label>
                                            <select name="branch_id" class="form-select select2 mt-3 branch_select"
                                                required>
                                                {{-- options via ajax --}}
                                            </select>
                                        </div>

                                        {{-- Exam Term --}}
                                        <div class="col-md-3">
                                            <label><b>Term: *</b></label>
                                            <select name="exam_term_id" class="form-select select2 mt-3 exam_term" required>
                                                <option value="" disabled selected>Select Exam Term</option>
                                                @foreach ($examTerms as $t)
                                                    <option value="{{ $t->id }}"
                                                        {{ $exam_schedule_detail->exam_term_id == $t->id ? 'selected' : '' }}>
                                                        {{ $t->term_desc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Test --}}
                                        <div class="col-md-3">
                                            <label><b>Test: *</b></label>
                                            <select name="test_type_id" class="form-select select2 mt-3" required>
                                                @foreach ($tests as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $exam_schedule_detail->test_type_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Class --}}
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <label><b>Class: *</b></label>
                                            <select name="class_id" class="form-select select2 mt-3 select_class" required>
                                                {{-- options via ajax --}}
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Dynamic area --}}
                                    <div class="row mt-5">
                                        <div id="loadData"></div>
                                    </div>

                                    <input type="hidden" name="getvalue" value="1" id="getvalue">

                                    {{-- Static fallback (visible before dynamic load) --}}
                                    <div class="row mt-5" id="loadData2">
                                        {{-- Subjects --}}
                                        <div class="col-md-3">
                                            <label><b>Subjects: *</b></label>
                                            <select name="course_id" class="form-select select2 mt-3">
                                                @if (count($classSubject) > 0)
                                                    @foreach ($classSubject as $item)
                                                        <option value="{{ $item->Subject->id }}"
                                                            {{ $exam_schedule_detail->course_id == $item->Subject->id ? 'selected' : '' }}>
                                                            {{ $item->Subject->name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="">Please Select Subject</option>
                                                @endif
                                            </select>
                                        </div>

                                        {{-- Component --}}
                                        <div class="col-md-3">
                                            <label><b>Component: *</b></label>
                                            <select name="component_id" class="form-select select2 mt-3">
                                                @foreach ($components as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $exam_schedule_detail->component_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Marks --}}
                                        <div class="col-md-3">
                                            <label><b>Marks: *</b></label>
                                            <input type="text" class="form-control" name="marks" id="marks"
                                                value="{{ $exam_schedule_detail->marks }}">
                                        </div>

                                        {{-- Grade --}}
                                        <div class="col-md-1 mt-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="grade"
                                                    id="grade" {{ $exam_schedule_detail->grade ? 'checked' : '' }}>
                                                <label class="form-check-label" for="grade">Grade</label>
                                            </div>
                                        </div>

                                        {{-- Pass --}}
                                        <div class="col-md-1 mt-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="pass"
                                                    id="pass" {{ $exam_schedule_detail->pass ? 'checked' : '' }}>
                                                <label class="form-check-label" for="pass">Pass</label>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <button type="submit" class="btn btn-primary">Submit</button>

                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script>
        $(function() {
            // Prefilled values from server
            const saved = {
                companyId: @json($exam_schedule_detail->company_id),
                branchId: @json($exam_schedule_detail->branch_id),
                classId: @json($exam_schedule_detail->class_id),
                examTermId: @json($exam_schedule_detail->exam_term_id),
                testTypeId: @json($exam_schedule_detail->test_type_id),
            };

            $('.select2').select2?.();

            // Helpers
            const esc = s => String(s ?? '').replace(/[&<>"'`=\/]/g, x => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '`': '&#96;',
                '=': '&#61;',
                '/': '&#47;'
            } [x]));

            // ---- Company → Branches ----
            function loadBranches(companyId) {
                if (!companyId) {
                    $('.branch_select').empty().append('<option value="" selected>Select Branch</option>');
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: companyId
                    },
                    success: function(data) {
                        const $branch = $('.branch_select').empty();
                        $branch.append('<option value="" selected>Select Branch</option>');
                        (data || []).forEach(function(b) {
                            const sel = String(b.id) === String(saved.branchId) ? 'selected' :
                                '';
                            $branch.append(
                                `<option value="${b.id}" ${sel}>${esc(b.name)}</option>`);
                        });
                        if (saved.branchId) {
                            $branch.trigger('change');
                        }
                    },
                    error: function(err) {
                        console.error('Error fetching branches:', err);
                    }
                });
            }

            // ---- Branch → Classes ----
            function loadClasses(branchId) {
                const $class = $('.select_class').empty();
                $class.append('<option value="" selected disabled>Select Class</option>');
                if (!branchId) return;

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branchId
                    },
                    success: function(data) {
                        (data || []).forEach(function(c) {
                            const sel = String(c.id) === String(saved.classId) ? 'selected' :
                            '';
                            $class.append(
                                `<option value="${c.id}" ${sel}>${esc(c.name)}</option>`);
                        });
                        if (saved.classId) {
                            $class.trigger('change');
                        }
                    },
                    error: function(err) {
                        console.error('Error fetching classes:', err);
                    }
                });
            }

            // ---- Branch → Exam Terms ----
            function loadTerms(branchId) {
                const $term = $('.exam_term').empty();
                $term.append('<option value="" selected disabled>Select Exam Term</option>');
                if (!branchId) return;

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchExamTerm') }}',
                    data: {
                        branch_id: branchId
                    },
                    success: function(data) {
                        (data || []).forEach(function(t) {
                            const sel = String(t.id) === String(saved.examTermId) ? 'selected' :
                                '';
                            $term.append(
                                `<option value="${t.id}" ${sel}>${esc(t.term_desc)}</option>`
                                );
                        });
                        if (saved.examTermId) {
                            $term.val(String(saved.examTermId)).trigger('change');
                        }
                    },
                    error: function(err) {
                        console.error('Error fetching exam terms:', err);
                    }
                });
            }

            // ---- Class → Load subjects/components (your existing) ----
            function loadData() {
                const branch_id = $('.branch_select').val();
                const class_id = $('.select_class').val();
                $.ajax({
                    url: "{{ route('exam.classSubject.data') }}",
                    type: 'POST',
                    data: {
                        branch_id,
                        class_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(html) {
                        $("#loadData2").hide();
                        $('#loadData').html(html);
                    },
                    error: function(request) {
                        console.log("Request error: ", request?.responseText || request);
                    }
                });
            }

            // Events
            $('#companySelect').on('change', function() {
                loadBranches($(this).val());
            });

            $('.branch_select').on('change', function() {
                const branchId = $(this).val();
                loadClasses(branchId);
                loadTerms(branchId);
            });

            $('.select_class').on('change', function() {
                $("#getvalue").val(0);
                if ($(this).val()) {
                    loadData();
                } else {
                    $('#loadData2').show();
                    $('#loadData').empty();
                }
            });

            // INIT on page load (Edit mode)
            // Company is already selected from Blade; kick off cascade:
            if ($('#companySelect').val()) {
                $('#companySelect').trigger('change');
            }

            // Ensure Test is preselected even if options are static
            if (saved.testTypeId) {
                $('select[name="test_type_id"]').val(String(saved.testTypeId)).trigger('change');
            }
        });
    </script>
@endsection
