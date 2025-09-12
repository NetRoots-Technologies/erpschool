@extends('admin.layouts.main')

@section('title')
    Agent Commission Slabs
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">NEW SALES INCENTIVE
                        </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.new_incentive_index') !!}" class="btn btn-primary btn-sm ">
                                    Back </a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{!! route('hr.new_incentive_store') !!}" enctype="multipart/form-data"
                              autocomplete="off" method="post">
                            <div class="w-100">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="slab_name"> <b>Agent</b> <b>*</b> </label>
                                            <select class="select2 form-control" name="agent" id="agent_id">
                                                <option>Select Agent</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{$agent->id}}">{{$agent->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-12 mt-1">
                                            <label for="agent_type_id"><b> Student </b></label>
                                            {{--                                            <select--}}
                                            {{--                                                    class="select2 form-control" >--}}

                                            {{--                                            </select>--}}
                                            <table>

                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>

                                                </tr>
                                                </thead>
                                                <tbody id="student">

                                                </tbody>
                                                {{--                                                <tr>--}}
                                                {{--                                                    <th>Name</th>--}}
                                                {{--                                                    <th>Session</th>--}}
                                                {{--                                                </tr>--}}
                                                {{--                                                <tr>--}}
                                                {{--                                                    <td></td>--}}
                                                {{--                                                    <td></td>--}}
                                                {{--                                                </tr>--}}

                                            </table>

                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="min"><b> Student Count</b></label>
                                            <input readonly name="count" id="count" type="text" class="form-control"
                                                   style="color:black"
                                            />
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="max"><b> Student Fees </b></label>
                                            <input readonly name="student_fee" id="student_fee" type="text"
                                                   class="form-control" style="color:black"
                                            />
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="comission"><b>Percentage Incentive</b> </label>
                                            <input readonly="" id="percentage"
                                                    class="form-control" name="percentage" style="color:black">

                                        </div>

                                        <div class="col-lg-6">
                                            <label for="slab_type"><b> Commission </b></label>
                                            <input readonly name="commission" id="commission" type="text"
                                                   class="form-control" style="color:black">
                                        </div>
                                    </div>
                                </div>

                                <hr style="background-color: darkgray">
                                <div class="row mt-8 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">

                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

@endsection
@section('js')


    <script>
        $('#agent_id').on('change', function () {
            var agent_id = this.value;

            $("#student").html('');
            $.ajax({
                url: "{{route('hr.fetch_agent_student')}}",
                method: "POST",
                async: false,
                data: {
                    agent_id: agent_id,
                    _token: '{{csrf_token()}}'
                },
                success: function (data) {
                    $('#student').html(data.html);
                    $('#count').val(data.count);
                    $('#student_fee').val(data.fee);

                }
            });
        });


        $(document).ready(function () {

            $('#agent_id').on('change', function () {

                var studentCount = $('#count').val();

                if (studentCount = 9 && studentCount <= 9) {
                    $('#percentage').val('0');
                } else if (studentCount = 10 && studentCount <= 19) {
                    $('#percentage').val('3');
                } else if (studentCount = 20 && studentCount <= 29) {
                    $('#percentage').val('5');
                } else if (studentCount = 30 && studentCount <= 39) {
                    $('#percentage').val('7');
                } else if (studentCount = 40 && studentCount <= 49) {
                    $('#percentage').val('10');
                } else if (studentCount = 50 && studentCount <= 75) {
                    $('#percentage').val('12');
                } else if (studentCount = 76 && studentCount <= 99) {
                    $('#percentage').val('13');
                } else if (studentCount <= 100) {
                    $('#percentage').val('15');
                } else {
                    $('#percentage').val('null');
                }
            });

        });

        function calculateTotal() {
            var student_fee = $('#student_fee').val();
            var taxPercentage = parseFloat($('#percentage').val());
            var taxAmount = student_fee * (taxPercentage / 100);
            $('#commission').val(taxAmount.toFixed(2));
        }

        $(document).ready(function () {
            $('#agent_id').on('change', function () {

                calculateTotal();
            });
        });


    </script>
@endsection

