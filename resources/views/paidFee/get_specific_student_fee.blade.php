@extends('admin.layouts.main')

@section('title')
    Students Fee
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Specific Students Fee </h3>
        </div>
        <div class="row    mt-4 mb-4 ">

        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form table-responsive">
                    <div class="card-body">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="data_table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Session</th>
                                <th>Student Name</th>
                                <th>Course Name</th>
                                <th>Course Fee</th>
                                <th>Student Fee</th>
                                <th>Installement Amount</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Paid Date</th>
                                <th>Paid Status</th>
                                <th>Payment Source</th>
                                <th width="200px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- The Modal for Edit -->
        <div class="modal modal1" id="myModal_pay">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editform">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Pay Installemt Date</h4>
                            <button type="button" id="close" class="close modalclose" data-dismiss="modal1">&times;
                            </button>
                        </div>
                        <!-- Modal body  -->
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <div class="input-label">
                                    <label>Paid Date</label>
                                </div>
                                <input type="date" name="paid_date" id="paid_date" class="form-control">
                                <label for="course_type">Source</label>
                                <select id="source"
                                        class="form-control" name="source">
                                    <option value="">Select Payment Source</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank">Bank</option>
                                    <option value="jazzcash">JazzCash</option>
                                </select>
                                <input type="hidden" name="id" id="id" class="form-control">
                                <input type="hidden" name="url" id="url" class="form-control">
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm" value="Submit">
                            <button type="button" class="btn btn-danger btn btn-sm modalclose" data-dismiss="modal1">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@stop
@section('css')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('js')

    <script type="text/javascript">

        $(document).ready(function () {
            $('#data_table').DataTable({
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
                    { "visible": false }
                ],

                ajax: {


                    "url": "{{ route('datatable.students_view_specific_installemet', $student_id) }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'session_title', name: 'session_titlephp '},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'course_name', name: 'course_name'},
                    {data: 'course_fee', name: 'course_fee'},
                    {data: 'student_fee', name: 'student_fee'},
                    // {data: 'installement_type', name: 'installement_type'},
                    // {data: 'installement_no', name: 'installement_no'},
                    {data: 'installement_amount', name: 'installement_amount'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'paid_date', name: 'paid_date'},
                    {data: 'paid_status', name: 'paid_status'},
                    {data: 'source', name: 'source'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#data_table tbody').on('click', '.paid_installement', function () {

                var id = $(this).data('id');
                var url = $(this).data('url');
                $('#id').val(id);
                $('#url').val(url);
                $('#myModal_pay').modal('show');

            });
            $(".modalclose").click(function () {

                $('#myModal_pay').modal('hide');
            });


            $('#tag-form-submit').on('click', function (e) {

                var id = $('#id').val();
                e.preventDefault();

                var url = $('#url').val();

                $.ajax({
                    type: "get",
                    "url": url,
                    data: $('#editform').serialize(),
                    success: function (response) {

                        $('#myModal_pay').modal('hide');

                        tableData.ajax.reload();
                    },
                    error: function () {
                        alert('Error');
                    }
                });
                return false;
            });


        });
    </script>
@endsection
