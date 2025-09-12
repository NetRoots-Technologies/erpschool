@extends('admin.layouts.main')

@section('title')
    Students Fee Detail
@stop

@section('content')

    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Students Fee Detail </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                @if (Gate::allows('remaning_fee'))
                    @if($data['student_fee']->remaining_amount>0 &&    $data['student_fee']->defaulter==0 ) <a
                        href="{!! route('admin.remaining_fee.get',$data['student_fee']->id) !!}"
                        class="btn btn-primary btn-sm ">Remaining
                        Fee</a> @endif
                @endif
                @if (Gate::allows('discount_on_instalment'))
                    @if($data['student_fee']->remaining_amount>0) <a
                        href="{!! route('admin.discount_on_instalment',$data['student_fee']->id) !!}"
                        class="btn btn-primary btn-sm ">Discount</a>
                    @endif
                @endif

                @if (Gate::allows('fee_defaulter'))
                    @if($data['student_fee']->defaulter==0) <a
                        href="{!! route('admin.make_defaulter',$data['student_fee']->id) !!}"
                        onclick="return confirm('Are you sure you want toMake Defaulter  ?')"
                        class="btn btn-danger btn-sm ">Make Defaulter
                    </a>
                    @endif
                @endif
                @if (Gate::allows('fee_defaulter_reactive'))
                    @if($data['student_fee']->defaulter==1) <a
                        href="{!! route('admin.make_defaulter_reactive',$data['student_fee']->id) !!}"
                        class="btn btn-primary btn-sm ">Reactive Defaulter
                    </a>
                    @endif
                @endif

            </div>
        </div>
        @if($data['student_fee']->defaulter == 1)
            <div class="col-lg-12">
                <div class="alert alert-danger text-center" role="alert">
                    <h4>This Student is DEFAULTER!!!</h4>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="box-body" style="margin-top:50px; margin-bottom: 20px">

                    <form action="{!! route('admin.fee_paid_detail_edit_post',$data['student_fee']->id) !!}"
                          enctype="multipart/form-data"
                          id="form_validation" autocomplete="off" method="post">

                        @csrf
                        <h5>Student Data</h5>

                        <div class="row mt-2">


                            <div class="col-lg-6">
                                <label for="name">Student Name </label>
                                <input disabled name="name" id="name" type="text" class="form-control"
                                       @if (isset($data['student_fee']->student)) value="{!! $data['student_fee']->student->name !!}" @endif/>
                            </div>


                            <div class="col-lg-6">
                                <label for="session">Session</label>
                                <select name="session_id" id="session_id"
                                        @if ( !Gate::allows('student_fee-edit')) disabled @endif
                                        class="select2 form-control">
                                    <option value="">Select Option</option>
                                    @foreach ($data['session'] as $item)
                                        <option
                                            @if(isset($data['student_fee']->session))  @if ($data['student_fee']->session->id==$item->id) selected
                                            @endif  @endif value="{{$item->id}}">{{$item->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">

                            <div class="col-lg-6">
                                <label for="course">Course</label>
                                <input disabled name="course" id="course" type="text" class="form-control"
                                       @if (isset($data['student_fee']->course)) value="{!! $data['student_fee']->course->name !!}" @endif/>

                            </div>


                            <div class="col-lg-6">
                                <label for="course_fee">Course Fee</label>
                                <input disabled name="course_fee" id="course_fee" type="text" class="form-control"
                                       value="{!! $data['student_fee']->course_fee !!}"/>
                            </div>
                        </div>
                        <div class="row mt-2">

                            <div class="col-lg-6">
                                <label for="course">Student Fee</label>
                                <input disabled name="course" id="course" type="text" class="form-control"
                                       value="{!!  $data['student_fee']->student_fee !!}"/>

                            </div>
                            <div class="col-lg-6">
                                <label for="course_fee">Remaining Fee</label>
                                <input disabled name="course_fee" id="course_fee" type="text" class="form-control"
                                       @if( $data['student_fee']->defaulter==0)  value="{!! $data['student_fee']->remaining_amount !!}"
                                       @else value="0" @endif />
                            </div>
                        </div>
                        @if (Gate::allows('student_fee-edit'))
                            <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                        @endif
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
                            <th>Installment Amount</th>
                            <th>Paid Date</th>
                            <th>Type</th>
                            <th>Source</th>
                            <th>Status</th>
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
                    "url": "{{ route('datatable.get_data_student_paid_fee_detail', $data['student_fee']->id) }}",
                    "type": "get",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'installement_amount', name: 'installement_amount'},
                    {data: 'paid_date', name: 'paid_date'},
                    {data: 'type', name: 'type'},
                    {data: 'source', name: 'source'},
                    {data: 'paid_status', name: 'paid_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });
        });

        $('#data_table tbody').on('click', '.fee_paid', function () {
            var paid_amount = $(this).data('fee_paid');


            var id = $(this).data('fee_paid').id;
            var route = $(this).data('route');
            $.ajax({
                url: route,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id,
                    paid_amount,
                },

                success: function (data) {
                    tableData.ajax.reload();
                },

            });
        });

        {{--$('#data_table tbody').on('click', '.fee_paid_voucher', function () {--}}
        {{--    var id = $(this).data('id');--}}

        {{--    var route =  $(this).data('route');--}}

        {{--    $.ajax({--}}
        {{--        url: route,--}}
        {{--        type: 'POST',--}}
        {{--        data:{--}}
        {{--            "_token": "{{ csrf_token() }}",--}}
        {{--            id,--}}

        {{--        },--}}

        {{--        success: function (data) {--}}
        {{--            tableData.ajax.reload();--}}
        {{--        },--}}

        {{--    });--}}

        {{--});--}}

    </script>
@endsection
