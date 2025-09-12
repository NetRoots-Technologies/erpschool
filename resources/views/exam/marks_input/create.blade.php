@extends('admin.layouts.main')

@section('title', 'Marks Input')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card basic-form">
        <div class="card-body">
          <h3 class="text-22 text-midnight text-bold mb-4">Marks Input</h3>
          <div class="row mt-4 mb-4">
            <div class="col-12 text-right">
              <a href="{{ route('exam.marks_input.index') }}" class="btn btn-primary btn-md">Back</a>
            </div>
          </div>

          <form action="{{ route('exam.marks_input.store') }}" method="post" id="form_validation">
            @csrf

            <div class="row mt-3">
              <div class="col-md-3">
                <label><b>Company:</b></label>
                <select name="company_id" id="companySelect" class="form-select select2" required>
                  @foreach($companies as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="col-md-3">
                <label><b>Academic Session:</b></label>
                <select name="session_id" class="form-select select2 session_select" required>
                  <option disabled selected>Select Session</option>
                  @foreach($sessions as $k=>$s)
                    <option value="{{ $k }}">{{ $s }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label><b>Branch:</b></label>
                <select name="branch_id" class="form-select select2 branch_select" required>
                  <option disabled selected>Select Branch</option>
                </select>
              </div>
              <div class="col-md-3">
                <label><b>Class:</b></label>
                <select name="class_id" class="form-select select2 select_class" required>
                  <option disabled selected>Select Class</option>
                </select>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-4">
                <label><b>Section:</b></label>
                <select name="section_id" class="form-select select2 select_section" required>
                  <option disabled selected>Select Section</option>
                </select>
              </div>
              <div class="col-md-4">
                <label><b>Subject:</b></label>
                <select name="course_id" class="form-select select2 select_course" required>
                  <option disabled selected>Select Subject</option>
                </select>
              </div>
              <div class="col-md-4">
                <label><b>Component:</b></label>
                <select name="component_id" class="form-select select2 component_id" required>
                  <option disabled selected>Select Component</option>
                </select>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-6">
                <label><b>Sub-Component:</b></label>
                <select name="sub_component_id" class="form-select select2 sub_component_id" required>
                  <option disabled selected>Select Sub-Component</option>
                </select>
              </div>
            </div>

            {{-- Container where sub-component-specific info (like marks grid) will go --}}
            <div class="row mt-5">
              <div id="loadData"></div>
            </div>

            {{-- Students grid + marks fields --}}
            <div id="studentsGrid" class="mt-4"></div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('js')
<script src="{{ asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
  $(function () {
    $('.select2').select2();

    $('#companySelect').change(function () {
      $.get('{{ route("hr.fetch.branches") }}', {companyid: this.value})
        .done(data => {
          let opts = '<option disabled selected>Select Branch</option>';
          data.forEach(b => opts += `<option value="${b.id}">${b.name}</option>`);
          $('.branch_select').html(opts);
        });
    }).trigger('change');

    $('.branch_select').change(function () {
      $.get('{{ route("academic.fetchClass") }}', {branch_id: this.value})
        .done(data => {
          let opts = '<option disabled selected>Select Class</option>';
          data.forEach(c => opts += `<option value="${c.id}">${c.name}</option>`);
          $('.select_class').html(opts);
        });
    });

    $('.select_class').change(function () {
      const classId = this.value;

      $.get('{{ route("academic.fetchSections") }}', {class_id: classId})
        .done(sec => {
          let o = '<option disabled selected>Select Section</option>';
          sec.forEach(s => o += `<option value="${s.id}">${s.name}</option>`);
          $('.select_section').html(o);
        });

      $.get('{{ route("academic.fetchSubject") }}', {class_id: classId})
        .done(sub => {
          let o = '<option disabled selected>Select Subject</option>';
          sub.forEach(s => o += `<option value="${s.id}">${s.name}</option>`);
          $('.select_course').html(o);
        });
    });

    $('.select_course').change(function () {
      $.get('{{ route("academic.fetchComponentSubject") }}', {course_id: this.value})
        .done(comps => {
          let o = '<option disabled selected>Select Component</option>';
          comps.forEach(c => o += `<option value="${c.id}">${c.name}</option>`);
          $('.component_id').html(o);
        });
    });

    $('.component_id').change(function () {
      let branch = $('.branch_select').val(),
        cls = $('.select_class').val(),
        sect = $('.select_section').val(),
        comp_id = this.value;

      if (!branch || !cls || !sect) {
        $('#studentsGrid').html('<p class="text-danger">Select Branch, Class & Section first.</p>');
        return;
      }

      $.get('{{ route("fetch-students") }}', {
        branch_id: branch,
        class_id: cls,
        section_id: sect
      }).done(students => {
        if (!students.length) {
          $('#studentsGrid').html('<p class="text-danger">No students found.</p>');
          return;
        }

        let html = `<table class="table table-bordered">
            <thead><tr>
              <th>#</th><th>Name</th>
              <th>Max Marks</th><th>Allocated Marks</th>
            </tr></thead><tbody>`;
        students.forEach((s, i) => {
          html += `<tr data-student-id="${s.id}">
              <td>${i + 1}</td>
              <td>${s.full_name}</td>
              <td>
                <input type="number" name="max_marks[${s.id}]" class="form-control max-marks" readonly>
              </td>
              <td>
                <input type="number" name="allocated_marks[${s.id}]" class="form-control allocated-marks" min="0">
                <span class="text-danger mark-error d-none">Exceeds max marks</span>
              </td>
            </tr>`;
        });
        html += `</tbody></table>`;
        $('#studentsGrid').html(html);
      });

      $.get('{{ route("exam.fetchSubComponent") }}', {component_id: comp_id})
        .done(subs => {
          let o = '<option disabled selected>Select Sub-Component</option>';
          subs.forEach(sc => {
            o += `<option value="${sc.id}" data-max="${sc.comp_number}">
                    ${sc.comp_name} | ${sc.comp_number}%
                  </option>`;
          });
          $('.sub_component_id').html(o);
        });
    });

    // Handle Sub-Component change and validate marks
    $(document).on('change', '.sub_component_id', function () {
      const max = $(this).find('option:selected').data('max') || 0;

      $('.max-marks').val(max);
      $('.allocated-marks').attr('max', max).each(function () {
        const val = parseFloat($(this).val()) || 0;
        const errorSpan = $(this).siblings('.mark-error');

        if (val > max) {
          errorSpan.removeClass('d-none');
          $(this).addClass('is-invalid');
        } else {
          errorSpan.addClass('d-none');
          $(this).removeClass('is-invalid');
        }
      });
    });

    // Real-time validation on allocated mark input
    $(document).on('input', '.allocated-marks', function () {
      const max = parseFloat($(this).closest('tr').find('.max-marks').val()) || 0;
      const val = parseFloat($(this).val()) || 0;
      const errorSpan = $(this).siblings('.mark-error');

      if (val > max) {
        errorSpan.removeClass('d-none');
        $(this).addClass('is-invalid');
      } else {
        errorSpan.addClass('d-none');
        $(this).removeClass('is-invalid');
      }
    });

    // Final form validation on submit
    $('#form_validation').on('submit', function (e) {
      let hasError = false;
      $('.allocated-marks').each(function () {
        const $input = $(this);
        const max = parseFloat($input.closest('tr').find('.max-marks').val()) || 0;
        const val = parseFloat($input.val()) || 0;
        const errorSpan = $input.siblings('.mark-error');

        if (val > max) {
          hasError = true;
          errorSpan.removeClass('d-none');
          $input.addClass('is-invalid');
        }
      });

      if (hasError) e.preventDefault();
    });

  });
</script>

@endsection
