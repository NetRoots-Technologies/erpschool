@extends('admin.layouts.main')

@section('title')
    Assign Tools
@stop

@section('content')

    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Assign Tools </h3>
        </div>


        <div class="card">
            <div class="card-body">
                <div class="box-body" style="margin-top:50px; margin-bottom: 20px">
                    <h5>Student Data</h5>

                    <form action="{!! route('admin.assign_tools_post') !!}" enctype="multipart/form-data"
                          id="form_validation" autocomplete="off" method="post">
                        @csrf
                        <div class="row mt-2">

                            <div class="col-lg-6">
                                <label for="name">Student Name </label>
                                <input disabled name="student_name" id="name" type="text" class="form-control"
                                       @if (isset($student->student)) value="{!! $student->student->name !!}" @endif/>
                                <input type="hidden" name="student_id"
                                       @if (isset($student->student_id)) value="{!! $student->student_id !!} @endif">
                            </div>


                            <div class="col-lg-6">
                                <label for="session">Session</label>
                                <input disabled name="session" id="session" type="text" class="form-control"
                                       @if (isset($student->session)) value="{!!$student->session->title !!}" @endif/>
                                <input type="hidden" name="session_id"
                                       @if (isset($student->session_id)) value="{!! $student->session_id !!} @endif">


                            </div>
                        </div>
                        <div class="row mt-2">

                            <div class="col-lg-6">
                                <label for="course">Course</label>
                                <input disabled name="course" id="course" type="text" class="form-control"
                                       @if (isset($student->course)) value="{!! $student->course->name !!}" @endif/>
                                <input type="hidden" name="course_id"
                                       @if (isset($student->course_id)) value="{!! $student->course_id !!} @endif">
                                <input type="hidden" name="student_fee_id"
                                       @if (isset($student->id)) value="{!! $student->id !!} @endif">


                            </div>
                            <div class="col-lg-6">
                                <label for="tools">Tool</label>
                                <select required name="tools" id="tools"
                                        class="form-control">
                                    <option value="">Select Tool</option>
                                    @foreach($tools as $item)
                                        <option
                                            value="{!! $item->id !!}">{!! $item->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <div class="form-group text-right">

                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    <a href="{!! route('admin.student_fee.index') !!}" class=" btn btn-sm btn-danger">Back </a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form table-responsive">
                <div class="card-body">
                    <table class="table border-top-0 table-bordered   border-bottom" id="data_table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tool</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
@section('css')

@endsection
@section('js')

    <script type="text/javascript">
        var tableData;
        $(document).ready(function () {
            tableData = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "columnDefs": [
                    {"visible": false}
                ],
                ajax: {
                    "url": "{{ route('datatable.get_data_old_assign_tools',$student->student_id) }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'tool', name: 'tool'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });
        });


    </script>
@endsection
