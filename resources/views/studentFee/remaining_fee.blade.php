@extends('admin.layouts.main')

@section('title')
    Student Fee  Pay
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Fee</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.student_fee.index') !!}" class="btn btn-primary btn-sm ">
                                    Back </a>
                            </div>
                        </div>
                        {{--                        @dd($roles)--}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="w-100">
                            <form action="{!! route('admin.remaining_fee.post',$data['student_fee']->id) !!}"
                                  enctype="multipart/form-data"
                                  autocomplete="off" method="post">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Student Fee</h5>


                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="student_fee">Student Remaning Pay Amount*</label>
                                            <input name="Remaning" type="number" min="0" class="form-control"
                                                   required="required" readonly
                                                   id="Remaning" value="{!! $data['student_fee']->remaining_amount !!}"
                                                   required/>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="student_fee">Student To Pay Amount*</label>
                                            <input name="paid_amount" type="number" min="0" class="form-control"

                                               max="{!! $data['student_fee']->remaining_amount !!}"    id="paid_amount" value="" required/>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="student_fee">Payment Source*</label>
                                            <select required name="Payment_source" id="Payment_source"
                                                    class="  form-control">
                                                <option value="">Select Option</option>
                                                <option value="cash">Cash</option>
                                                <option value="bank">Bank (UBL)</option>
                                                <option value="jazzcash">Jazz Cash</option>
                                                <option value="easypaisa">Easy Paisa</option>
                                            </select>
                                        </div>
                                            <div class="col-lg-6">
                                                <label for="student_fee">Due Date*</label>
                                                <input name="due_date" type="date" class="form-control"
                                                       id="due_date" value="" required/>
                                            </div>
                                        </div>

                                        <div class=" row mt-5 mb-3">
                                            <div class="col-12">
                                                <div class="form-group text-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">Create Fee
                                                    </button>
                                                    <a href="{!! route('admin.student_fee.index') !!}"
                                                       class=" btn btn-sm btn-danger">Cancel </a>
                                                </div>
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
    </div>
@stop
@section('css')


@endsection
@section('js')

    <script>

        $(document).ready(function () {
            var items = $('#course_id').val();

            $.ajax({
                method: 'GET',
                url: "{{ route('admin.fee') }}",
                data: {
                    "id": items,
                },
                success: function (response) {

                    $('#course_fee').val(response);

                }
            });
        });

        $('#course_id').on('change', function () {


            var items = $(this).val();

            $.ajax({
                method: 'GET',
                url: "{{ route('admin.fee') }}",
                data: {
                    "id": items,
                },
                success: function (response) {

                    $('#course_fee').val(response);

                }
            });
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.session.get') }}",
                data: {
                    "id": items,
                },
                success: function (response) {

                    $('#session_id').html(response);

                }
            });

        });

        $(document).ready(function () {
            $("#student_fee").change(function () {

                var discount_amount = parseFloat($('#course_fee').val()) - Number($(this).val());
                $('#discount_amount').val(discount_amount);


            });
        });
        $(document).ready(function () {
            $("#installement_type").change(function () {
                $("#installement_date_1").html('');

                var installement_type = $(this).val();

                for (let i = 1; i <= installement_type; i++) {

                    $("#installement_date_1").append("<div class='row mt-2'><div class='col-lg-4'><label'>Installement Amount</label><input name='installement_amount[]' id='installement_amount' type='number' min='0' class='form-control'/></div><div class='col-lg-4'><label for='id'>Start Date</label><input name='start_date[]'   id='start_date' type='date'class='form-control'/></div><div class='col-lg-4'><label for='id'>Due Date</label><input name='due_date[]'  id='due_date' type='date'class='form-control'/></div><input name='installement_id[]'id='installement_id' value='" + i + "'  type='hidden'class='form-control'readonly/></div>");
                }

            });
            $("#advance").change(function () {
                var advance = $("#advance").val();
                var student_fee = $('#student_fee').val();
                var Remaning = Number(student_fee) - Number(advance);
                $('#Remaning').val(Remaning);

            });
            $("#discount_amount").change(function () {

                var course_id = $('#course_id').val();
                if (course_id) {

                    var course_fee = $('#course_fee').val();
                    var perc = $("#discount_amount").val();
                    discounted_price = course_fee - (course_fee * perc / 100)


                    $('#discounted_amount').val((course_fee * perc / 100));


                    $('#student_fee').val(discounted_price);
                    $('#Remaning').val(discounted_price);
                } else {
                    alert('Please select course');
                    $("#discount_amount").val('');
                }

                // var pPos = parseInt($('#pointspossible').val());
                // var pEarned = parseInt($('#pointsgiven').val());
                // var perc = "";
                // if (isNaN(pPos) || isNaN(pEarned)) {
                //     perc = " ";
                // } else {
                //     perc = ((pEarned / pPos) * 100).toFixed(3);
                // }
                //
                // $('#pointsperc').val(perc);


            });
        });


    </script>


@endsection

