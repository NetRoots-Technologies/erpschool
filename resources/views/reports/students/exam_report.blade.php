@extends('admin.layouts.main')

@section('title') Student Exam Report @stop

@section('content')
<div class="card">
  <div class="card-header bg-primary" style="color: #212529 !important;">Exam Report Filter</div>
  <div class="card-body">
    <div class="row">

      <!-- Branch -->
      <div class="col-md-3">
        <label>Branch</label>
        <select id="branch_id" class="form-control select2">
          <option value="">Select Branch</option>
          @foreach($branches as $branch)
            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
          @endforeach
        </select>
      </div>


      {{-- Academic Session --}}
       <div class="col-md-3">
        <label>Academic Session</label>
        <select id="academic_id" class="form-control select2">
          <option value="">Select Academic Session</option>
        </select>
      </div>

      <!-- Class -->
      <div class="col-md-3">
        <label>Class</label>
        <select id="class_id" class="form-control select2">
          <option value="">Select Class</option>
        </select>
      </div>

      <!-- Section -->
      <div class="col-md-3">
        <label>Section</label>
        <select id="section_id" class="form-control select2">
          <option value="">Select Section</option>
        </select>
      </div>
    </div>

    <div class="mt-4">
      <table id="studentsTable" class="table table-bordered w-100">
        <thead class="thead-dark">
          <tr>
            
            <th>Name</th>
            <th>Branch</th>
            <th>Class</th>
            <th>Section</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
$(function () {

  // Init DataTable
  let table = $('#studentsTable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    ajax: {
      url: "{{ route('reports.exam.studentsTable') }}",
      data: function (d) {
        d.branch_id   = $('#branch_id').val();
        d.academic_id = $('#academic_id').val();
        d.class_id    = $('#class_id').val();
        d.section_id  = $('#section_id').val();
      }
    },
    columns: [
      { data: 'name', name: 'name' },
      { data: 'branch', name: 'branch' },
      { data: 'class', name: 'class' },
      { data: 'section', name: 'section' },
      { data: 'action', name: 'action', orderable:false, searchable:false, width:'90px' },
    ]
  });

  // Step 1: Branch → Academic Sessions
  $('#branch_id').on('change', function(){
    const branch_id = $(this).val();
    $('#academic_id').html('<option value="">Select Academic Session</option>');
    $('#class_id').html('<option value="">Select Class</option>');
    $('#section_id').html('<option value="">Select Section</option>');

    if(branch_id){
      $.post("{{ route('reports.exam.getAcademics') }}", {
        _token: '{{ csrf_token() }}',
        branch_id: branch_id
      }, function(list){
        $.each(list, function(_, item){
          $('#academic_id').append('<option value="'+item.id+'">'+item.name+'</option>');
        });
      });
    }
    table.ajax.reload(); // clear table
  });

  // Step 2: Academic Session → Classes
  $('#academic_id').on('change', function(){
    const academic_id = $(this).val();
    $('#class_id').html('<option value="">Select Class</option>');
    $('#section_id').html('<option value="">Select Section</option>');

    if(academic_id){
      $.post("{{ route('reports.exam.getClasses') }}", {
        _token: '{{ csrf_token() }}',
        academic_id: academic_id
      }, function(list){
        $.each(list, function(_, item){
          $('#class_id').append('<option value="'+item.id+'">'+item.name+'</option>');
        });
      });
    }
    table.ajax.reload(); // clear table
  });

  // Step 3: Class → Sections
  $('#class_id').on('change', function(){
    const class_id = $(this).val();
    $('#section_id').html('<option value="">Select Section</option>');

    if(class_id){
      $.post("{{ route('reports.exam.getSections') }}", {
        _token: '{{ csrf_token() }}',
        class_id: class_id
      }, function(list){
        $.each(list, function(_, item){
          $('#section_id').append('<option value="'+item.id+'">'+item.name+'</option>');
        });
      });
    }
    table.ajax.reload(); // clear table
  });

  // Step 4: Section → Students Table
  $('#section_id').on('change', function(){
    table.ajax.reload(); 
  });

});

// Student report view
$(document).on('click', '.view-student', function () {
    let studentId = $(this).data('id');
    let url = "{{ route('reports.exam.view', ':id') }}".replace(':id', studentId);
    window.location.href = url;
});
</script>
@endsection

