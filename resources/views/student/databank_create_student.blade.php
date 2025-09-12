@extends('admin.layouts.main')

@section('title')
    Student  Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        @if (Gate::allows('students'))

                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Student </h3>
                        @endif
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('students.index') !!}" class="btn btn-primary btn-sm "> Back </a>
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
                        <form action="{!! route('students.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            <div class="w-100">

                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Student Data</h5>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="name">Student Name*</label>
                                            <input   name="name" id="name" type="text"
                                                   class="form-control" readonly
                                                   value="{!! $databank->name !!}"/>
                                        </div>

                                        <input type="hidden" value="{!! $databank->id !!}" name="data_bank_id">

                                        <div class="col-lg-6">
                                            <label for="gender">Gender*</label>
                                            <select   name="gender" id="gender"
                                                    class="select2 form-control">
                                                <option value="" disabled selected>Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="row mt-2">

                                            <div class="col-lg-6">
                                                <label for="marital_status">Marital Status*</label>
                                                <select name="marital_status"   id="marital_status"
                                                        class="select2 form-control">
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="married">Married</option>
                                                    <option value="single">Single</option>
                                                </select>
                                            </div>


                                            <div class="col-lg-6">
                                                <label for="dob">Date of Birth*</label>
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input name="student_dob" class="form-control"
                                                           id="datepicker-date" placeholder="MM/DD/YYYY"
                                                           type="text"   value="{{old('student_dob')}}">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="background-color: darkgray">
                            <div class="box-body" style="margin-top:50px;">

                                @if (Auth::user()->hasRole('Admin'))
                                <h5>Agent Information</h5>
                                <div class="row mt-2">
                                    <div class="col-lg-1" style="margin-top: 50px; margin-left: 35px;">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   id="flexSwitchCheckDefault">
                                            <label class="form-check-label"
                                                   for="flexSwitchCheckDefault">Agent</label>
                                        </div>
                                    </div>

                                    <div id="agent_ref" class="col-lg-5 mt-3"
                                         style="margin-left: -38px; display: none">
                                        <label for="agent">Select Agent</label>
                                        <select name="agent_id" id="agent" class="select2 form-control">
                                            <option value="">Select Agent</option>
                                            @foreach ($data['agent'] as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <hr class="mt-3" style="background-color: darkgray">
                                    @endif
                                    <div class="box-body" style="margin-top:50px;">
                                        <h5>Nationality Information</h5>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="nationality">Country of Nationality*</label>
                                                <select name="nationality" id="nationality"
                                                        class="select2 form-control">

                                                    <option value="" disabled selected>Select an Option</option>

                                                    @foreach($data['country'] as $item)
                                                        <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="passport_cnic">Passport No./CNIC No*</label>
                                                <input name="passport_cnic" id="passport_cnic" type="text"

                                                       class="form-control" value="{{old('passport_cnic')}}"/>
                                            </div>

                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-12">
                                                <label for="cnic_expiry">Passport/CNIC Expiry Date*</label>
                                                <div class="row">
                                                    <div class="col-lg-6">

                                                        <div class="input-group">
                                                            <div class="input-group-text">
                                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                            </div>
                                                            <input name="pass_cnic_expiry" class="form-control"
                                                                   id="datepicker-date1" placeholder="MM/DD/YYYY"

                                                                   type="text" value="{{old('pass_cnic_expiry')}}">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="background-color: darkgray">
                                    </div>

                                    <div class="box-body" style="margin-top:50px;">
                                        <h5>Address</h5>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="address_country">Country*</label>
                                                <select name="address_country" class="select2 form-control"

                                                        id="address_country">
                                                    <option value="" disabled selected>Select an Option</option>
                                                    @foreach($data['country'] as $item)
                                                        <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="address_state">State/Province*</label>
                                                <select name="address_state" class="select2 form-control"
                                                        id="address_state"  >
                                                    <option value="" disabled selected>Select an Option</option>
                                                    {{--                                            @foreach($data['state'] as $item)--}}
                                                    {{--                                                <option value="{!! $item->id !!}">{!! $item->name !!}</option>--}}
                                                    {{--                                            @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="address_city">City* <b> ( {!! $databank->city !!}
                                                        )</b></label>
                                                <select name="address_city" class="select2 form-control"
                                                        id="address_cities"  >
                                                    <option value="" disabled selected>Select an Option</option>
                                                    {{--                                            @foreach($data['city'] as $item)--}}
                                                    {{--                                                <option value="{!! $item->id !!}">{!! $item->name !!}</option>--}}
                                                    {{--                                            @endforeach--}}
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="address">Address*</label>
                                                <input name="address" id="address" type="text"
                                                       class="form-control " value="{{old('address')}}"/>
                                            </div>
                                        </div>
                                        <hr style="background-color: darkgray">
                                    </div>

                                    <div class="box-body" style="margin-top:50px;">
                                        <h5>Contact Details</h5>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="email">Email*</label>
                                                <input name="email" id="email" type="email" class="form-control"
                                                         readonly
                                                       value="{!! $databank->email !!}"/>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="mobile_no">Contact No*</label>
                                                <input name="mobile_no" id="mobile_no" type="tel"
                                                       readonly
                                                       class="form-control" value="{!! $databank->mobile_no !!}"/>

                                            </div>
                                        </div>    <hr style="background-color: darkgray">
                                    </div>

                                    <div class="box-body" style="margin-top:50px;">
                                        <h5>Guardian Information</h5>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label for="guardian_name">Guardian Name*</label>
                                                <input name="guardian_name" id="guardian_name" type="text"

                                                       class="form-control" value="{{old('guardian_name')}}"/>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="guardian_occupation">Guardian Occupation*</label>
                                                <input name="guardian_occupation" id="guardian_occupation"

                                                       type="text" class="form-control" value="{{old('guardian_occupation')}}"/>
                                            </div>
                                        </div>
                                        <div class="row mt-2 mb-4">
                                            <div class="col-lg-6">
                                                <label for="guardian_mobile_no">Guardian Contact No*</label>
                                                <input name="guardian_mobile_no" id="guardian_mobile_no" type="tel"

                                                       class="form-control" value="{{old('guardian_mobile_no')}}"/>

                                            </div>
                                            <div class="col-lg-6">
                                                <label for="guardian_relation_with_student">Relation With
                                                    Applicant*</label>
                                                <input name="guardian_relation_with_student"
                                                       id="guardian_relation_with_student" type="text"

                                                       class="form-control" value="{{old('guardian_relation_with_student')}}"/>
                                            </div>
                                        </div>
                                        <hr style="background-color: darkgray">
                                    </div>

                                    <h5>File Uploads</h5>
                                    <div class="row mt-8">
                                        <div class="col-lg-4">
                                            <h6>ID Card</h6>
                                            <input type="file" name="id_card" id="id_card" class="dropify"
                                                   data-height="200"/>
                                        </div>
                                        <div class="col-lg-4">
                                            <h6>Passport</h6>
                                            <input type="file" name="passport" id="passport" class="dropify"
                                                   data-height="200"/>
                                        </div>
                                        <div class="col-lg-4">
                                            <h6>Supporting Documents</h6>
                                            <input type="file" name="document" id="document" class="dropify"
                                                   data-height="200"/>
                                        </div>
                                    </div>

                                    {{--                                <hr style="background-color: darkgray">--}}
                                    {{--                                <div class="box-body" style="margin-top:50px;">--}}
                                    {{--                                    <h5>Create Fee</h5>--}}
                                    {{--                                    <div class="row mt-2">--}}
                                    {{--                                        <div class="col-lg-6">--}}
                                    {{--                                            <label for="course_fee">Course Fee*</label>--}}
                                    {{--                                            <input name="course_fee" type="number" class="form-control" id="course_fee"  --}}
                                    {{--                                                   readonly required/>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-lg-6">--}}
                                    {{--                                            <label for="student_fee">Student Pay Amount*</label>--}}
                                    {{--                                            <input name="student_fee" type="number" min="0" class="form-control"  --}}
                                    {{--                                                   id="student_fee" required/>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="row mt-2">--}}
                                    {{--                                        <div class="col-lg-6">--}}
                                    {{--                                            <label for="discount_amount">Discount Amount*</label>--}}
                                    {{--                                            <input name="discount_amount" id="discount_amount" type="number"    --}}
                                    {{--                                                   required--}}
                                    {{--                                                   class="form-control"/>--}}

                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-lg-6">--}}
                                    {{--                                            <label for="installement_type">Installement Type*</label>--}}
                                    {{--                                            <select name="installement_type" id="installement_type"  --}}
                                    {{--                                                    class="form-control">--}}
                                    {{--                                                <option>Select Installement Type</option>--}}
                                    {{--                                                <option value="1">1</option>--}}
                                    {{--                                                <option value="2">2</option>--}}
                                    {{--                                                <option value="3">3</option>--}}
                                    {{--                                                <option value="4">4</option>--}}
                                    {{--                                                <option value="5">5</option>--}}
                                    {{--                                                <option value="6">6</option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}


                                    {{--                                </div>--}}

                                    {{--                                <div id="installement_date_1"></div>--}}



                                    <div class="row mt-4 ">  <hr style="background-color: darkgray"></div>
                                    <div class="row mt-8 mb-3">

                                        <div class="col-12">
                                            <div class="form-group text-right">

                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                <a href="{!! route('students.index') !!}"
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
@stop
@section('css')

    <link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">
@endsection
@section('js')

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

    <script>
        $('#address_country').on('change', function () {

            $.ajax({
                method: 'GET',
                url: "{{ route('admin.states') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": this.value,
                },
                success: function (response) {
                    $('#address_state').html(response);


                }
            });
        });

        $('#address_state').on('change', function () {

            $.ajax({
                method: 'GET',
                url: "{{ route('admin.cities') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": this.value,
                },
                success: function (response) {
                    $('#address_cities').html(response);


                }
            });
        });

        $('#datepicker-date').bootstrapdatepicker({
            format: "dd-mm-yyyy",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

        $('#datepicker-date1').bootstrapdatepicker({
            format: "dd-mm-yyyy",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        })

        $(document).ready(function () {
            $('#flexSwitchCheckDefault').click(function () {
                var atLeastOneIsChecked = $('#flexSwitchCheckDefault:checkbox:checked').length > 0;
                if (atLeastOneIsChecked) {

                    $('#agent_ref').show();

                } else {
                    $('#agent_ref').hide();
                }
            });
        });


        {{--$('#course_id').on('change', function () {--}}


        {{--    var items = $(this).val();--}}

        {{--    $.ajax({--}}
        {{--        method: 'GET',--}}
        {{--        url: "{{ route('admin.fee') }}",--}}
        {{--        data: {--}}
        {{--            "id": items,--}}
        {{--        },--}}
        {{--        success: function (response) {--}}

        {{--            $('#course_fee').val(response);--}}

        {{--        }--}}
        {{--    });--}}

        {{--});--}}

        {{--$(document).ready(function () {--}}
        {{--    $("#student_fee").change(function () {--}}

        {{--        var discount_amount = parseFloat($('#course_fee').val()) - Number($(this).val());--}}

        {{--        $('#discount_amount').val(discount_amount);--}}


        {{--    });--}}
        {{--});--}}

        // $(document).ready(function () {
        //     $("#installement_type").change(function () {
        //
        //         var installement_type = $(this).val();
        //
        //         var student_per_installement_amount = $('#student_fee').val() / installement_type;
        //
        //         $('#student_per_installement_amount').val(student_per_installement_amount);
        //
        //
        //     });
        // });

        {{--$(document).ready(function () {--}}
        {{--    $("#installement_type").change(function () {--}}
        {{--        $("#installement_date_1").html('');--}}

        {{--        var installement_type = $(this).val();--}}

        {{--        for (let i = 1; i <= installement_type; i++) {--}}

        {{--            $("#installement_date_1").append("<div class='row mt-2'><div class='col-lg-4'><label'>Installement Amount</label><input name='installement_amount[]' id='installement_amount' type='number' min='0' class='form-control'/></div><div class='col-lg-4'><label for='id'>Start Date</label><input name='start_date[]' min='{!!$session->start_date!!}' max='{!!$session->end_date!!}' id='start_date' type='date'class='form-control'/></div><div class='col-lg-4'><label for='id'>Due Date</label><input name='due_date[]'min='{!!$session->start_date!!}' max='{!!$session->end_date!!}' id='due_date' type='date'class='form-control'/></div><input name='installement_id[]'id='installement_id' value='" + i + "'  type='hidden'class='form-control'readonly/></div>");--}}
        {{--        }--}}

        {{--    });--}}
        {{--});--}}


    </script>



@endsection

