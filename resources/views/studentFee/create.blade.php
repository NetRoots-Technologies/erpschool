@extends('admin.layouts.main')

@section('title')
    Student Fee  Create
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
                            <form action="{!! route('admin.student_fee.store') !!}" enctype="multipart/form-data"
                                  autocomplete="off" method="post">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Student Fee</h5>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="data_bank_id">Select Student (by mobile no or by Name)*</label>
                                            <select name="data_bank_id" class="select2 form-control"
                                                    id="data_bank_id">
                                                <option value="" disabled selected>Select Databank Student</option>
                                                @foreach($data['databank'] as $item)
                                                    <option @if(isset($id)) @if($id==$item->id)  selected
                                                            @endif @endif  value="{!! $item->id !!}">{!! $item->name !!}
                                                        ({!! $item->mobile_no !!} )
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="course_id">Courses*</label>

                                            <select required name="course_id[]" id="course_id"
                                                    class="select2 form-control">
                                                <option value="">Select Option</option>
                                                @foreach ($data['course'] as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="student_fee">Session</label>
                                            <select name="Session" id="session_id"
                                                    class="  form-control">
                                                <option value="">Select Option</option>


                                            </select>
                                        </div>
                                    </div>


                                    <div class="box-body" style="margin-top:50px;">
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="course_fee">Course Fee*</label>
                                                <input name="course_fee" type="number" class="form-control"
                                                       id="course_fee" required="required"
                                                       readonly required/>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="discount_amount">Discount %*</label>
                                                <select name="discount_amount" id="discount_amount"
                                                        class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="manual">Manual</option>
                                                    <option value="1">1%</option>
                                                    <option value="2">2%</option>
                                                    <option value="3">3%</option>
                                                    <option value="4">4%</option>
                                                    <option value="5">5%</option>
                                                    <option value="6">6%</option>
                                                    <option value="7">7%</option>
                                                    <option value="8">8%</option>
                                                    <option value="9">9%</option>
                                                    <option value="10">10%</option>
                                                </select>

                                            </div>
                                            <div class="col-lg-3">
                                                <label for="discount_amount">Discount Amount*</label>
                                                <input name="discounted_amount" id="discounted_amount" type="number"
                                                       readonly
                                                       class="form-control"/>

                                            </div>

                                        </div>
                                        <div class="row mt-2">
                                            <div class="student_fee col-lg-6 ">
                                                <label for="student_fee">Student Pay Amount*</label>
                                                <input name="student_fee" type="number" min="0" class="form-control"
                                                       id="student_fee" readonly required="required"
                                                       required/>
                                            </div>

                                            <div class="col-lg-3 disc_perc" style="display: none">
                                                <label for="disc_perc" style="display: none" class="disc_perc">Discounted
                                                    %</label>
                                                <input name="disc_perc" type="number" min="0"
                                                       class="form-control disc_perc"
                                                       readonly style="display:none;"
                                                       required/>
                                            </div>


                                            <div class="col-lg-3">
                                                <label for="student_fee">Advance*</label>
                                                <input name="advance" type="number" min="0" class="form-control"
                                                       required="required"
                                                       id="advance" required/>
                                            </div>
                                            <div class="col-lg-3">
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

                                        </div>


                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="student_fee">Student Remaning Pay Amount*</label>
                                            <input name="Remaning" type="number" min="0" class="form-control"
                                                   required="required" readonly
                                                   id="Remaning" required/>
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
                    $("#discounted_amount").attr({
                        "max": response,
                        "min": 0
                    });
                    $("#advance").attr({
                        "max": response,
                        "min": 0
                    });
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
            $("#advance").keyup(function () {
                var advance = $("#advance").val();
                var student_fee = $('#student_fee').val();
                var Remaning = Number(student_fee) - Number(advance);
                $('#Remaning').val(Remaning);

            });
            $("#advance").change(function () {
                var advance = $("#advance").val();
                var student_fee = $('#student_fee').val();
                var Remaning = Number(student_fee) - Number(advance);
                $('#Remaning').val(Remaning);

            });
            $("#discounted_amount").keyup(function () {
                var perc = $("#discount_amount").val();

                if (perc == 'manual') {

                    var discounted_amount = $("#discounted_amount").val();
                    var course_fee = $('#course_fee').val();

                    percentage = 100 * discounted_amount / course_fee;
                    // alert(percentage);
                    discounted_price = course_fee - discounted_amount;
                    $('#student_fee').val(discounted_price);
                    $('#Remaning').val(discounted_price);
                    $('.disc_perc').val(percentage);
                }
            });
            $("#discounted_amount").change(function () {
                var perc = $("#discount_amount").val();

                if (perc == 'manual') {

                    var discounted_amount = $("#discounted_amount").val();
                    var course_fee = $('#course_fee').val();

                    percentage = 100 * discounted_amount / course_fee;
                    // alert(percentage);
                    discounted_price = course_fee - discounted_amount;
                    $('#student_fee').val(discounted_price);
                    $('#Remaning').val(discounted_price);
                    $('.disc_perc').val(percentage);
                }
            });
            $("#discount_amount").change(function () {

                var course_id = $('#course_id').val();
                if (course_id) {

                    var course_fee = $('#course_fee').val();
                    var perc = $("#discount_amount").val();
                    if (perc == 'manual') {

                        $("#discounted_amount").attr("readonly", false);
                        $(".student_fee").removeClass('col-lg-6');
                        $(".student_fee").addClass('col-lg-3');
                        $(".disc_perc").css('display', 'block')

                    } else {
                        $("#discounted_amount").attr("readonly", true);

                        discounted_price = course_fee - (course_fee * perc / 100)


                        $('#discounted_amount').val((course_fee * perc / 100));


                        $('#student_fee').val(discounted_price);
                        $('#Remaning').val(discounted_price);
                        $(".student_fee").removeClass('col-lg-3');
                        $(".student_fee").addClass('col-lg-6');
                        $(".disc_perc").css('display', 'none')
                    }

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

