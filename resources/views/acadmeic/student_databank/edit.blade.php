@extends('admin.layouts.main')

@section('title')
    Student  Create
@stop
<style>
    .minimized .school-info-container {
        display: none;
    }

    .minimized .travelling-div {
        display: none;
    }

</style>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Admission Form </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.studentDataBank.index') !!}" class="btn btn-primary btn-sm "> Back </a>
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
                        <form action="{!! route('academic.studentDataBank.update',$studentDatabank->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h5 style="background-color: #f0f0f0; padding: 10px;"> <span
                                                class="text-center">PRE ADMISSION FORM</span></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">

                                <div class="col-md-6">
                                    <label for="student_name"><b>First Name </b></label>
                                    <input type="text" class="form-control" name="first_name"  value="{!! $studentDatabank->first_name !!}" >
                                </div>
                                <div class="col-md-6">
                                    <label for="student_name"><b>Last Name </b></label>
                                    <input type="text" class="form-control" name="last_name" value="{!! $studentDatabank->last_name !!}">
                                </div>
{{--                                <div class="col-md-12">--}}
{{--                                    <label for="student_name"><b>Student Name  *</b></label>--}}
{{--                                    <input type="text" class="form-control" value="{!! $studentDatabank->student_name !!}" name="student_name">--}}
{{--                                </div>--}}

                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="student_name"><b>Age </b></label>
                                    <input type="number" class="form-control" value="{!! $studentDatabank->student_age !!}" name="student_age">
                                </div>

                                <div class="col-md-6">
                                    <label for="email"><b>Email </b></label>
                                    <input type="email" class="form-control" value="{!! $studentDatabank->student_email !!}" name="student_email">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="gender"><b>Gender </b></label>
                                    <select class="form-select select2 basic-single" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {!! $studentDatabank->gender == 'male' ? 'selected' : '' !!} >Male</option>
                                        <option value="female" {!! $studentDatabank->gender == 'male' ? 'selected' : '' !!}>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone"><b>Phone </b></label>
                                    <input type="number" class="form-control" value="{!! $studentDatabank->student_phone !!}" name="student_phone">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="css"><b>Student previously in CSS:  </b></label>
                                    <select class="form-select select2 basic-single" id="gender" name="study_perviously"
                                            required>
                                        <option value="">Select </option>
                                        <option value="yes" {!! $studentDatabank->study_perviously == 'yes' ? 'selected' : '' !!}>Yes</option>
                                        <option value="no" {!! $studentDatabank->study_perviously == 'no' ? 'selected' : '' !!}>No</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="seeking_admission"><b>Seeking admission for </b></label>
                                    <input type="text" class="form-control" value="{!! $studentDatabank->admission_for !!}" name="admission_for">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="reason"><b>If yes then please state the reason and year of leaving </b></label>
                                    <textarea name="reason_for_leaving"  class="form-control">{!! $studentDatabank->reason_for_leaving !!}</textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="father_name"><b>Father’s Name </b></label>
                                    <input type="text" class="form-control" value="{!! $studentDatabank->father_name !!}"  name="father_name">
                                </div>
                                <div class="col-md-6">
                                    <label for="father_name"><b>Mother’s Name </b></label>
                                    <input type="text" class="form-control" value="{!! $studentDatabank->mother_name !!}"  name="mother_name">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="Present Address "><b>Present Address </b></label>
                                    <textarea name="present_address" class="form-control" id="" cols="4" rows="4">{!! $studentDatabank->present_address !!}</textarea>
                                </div>
                            </div>

                             <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="father_cnic"><b>Father’s CNIC *</b></label>
                                    <input type="text" id="father_cnic" name="father_cnic"
                                           class="form-control cnic_card"
                                           data-inputmask="'mask': '99999-9999999-9'" placeholder="XXXXX-XXXXXXX-X" value="{!! $studentDatabank->father_cnic !!}"
                                           required
                                           onchange="checkCNIC(this)">
                                </div>
                                <div class="col-md-6">
                                    <label for="mother_cnic"><b>Mother’s CNIC *</b></label>
                                    <input type="text" id="mother_cnic" name="mother_cnic"
                                           class="form-control cnic_card"
                                           data-inputmask="'mask': '99999-9999999-9'" placeholder="XXXXX-XXXXXXX-X" value="{!! $studentDatabank->mother_cnic !!}"
                                           required
                                           onchange="checkCNIC(this)">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="b_form_no"><b>Student B-Form Number *</b></label>
                                    <input type="text" id="b_form_no" name="b_form_no"
                                        class="form-control bform_card"
                                        data-inputmask="'mask': '99999-9999999-9'" placeholder="XXXXX-XXXXXXX-X"
                                        required onchange="checkBForm(this)" value="{!! $studentDatabank->b_form_no !!}">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="landline_number"><b>Landline Number </b></label>
                                    <input type="number" class="form-control" value="{!! $studentDatabank->landline_number !!}"  name="landline_number">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="Present Address "><b>Previous School attended </b></label>
                                    <textarea name="previous_school" class="form-control" id="" cols="4" rows="4">{!! $studentDatabank->previous_school !!}</textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="reasonForSwitch"><b>Reason Of Switching </b></label>
                                    <textarea name="reason_of_switch" class="form-control" id="" cols="4" rows="4"> {!! $studentDatabank->reason_of_switch !!}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-4">
                                <button class="btn btn-primary" id="submitBtn">Submit</button>
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
    {{--    <script src="{{asset('dist/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>--}}


    <script>
         $(".cnic_card").inputmask();
        var checkCNIC = function (textBox) {
            var regexp = new RegExp('^[0-9]{5}-[0-9]{7}-[0-9]{1}$');
            var check = textBox.value.trim();
            var isValid = regexp.test(check);

            if (!isValid) {
                toastr.warning('Please enter a valid 13-digit CNIC with dashes (XXXXX-XXXXXXX-X)');
                $(textBox).css('border-color', 'red');
                $('#submitBtn').attr('disabled', true);
                return false;
            } else {
                $(textBox).css('border-color', 'green');
            }

            var fatherCNIC = $('#father_cnic').val().trim();
            var motherCNIC = $('#mother_cnic').val().trim();

            if (fatherCNIC !== '' && motherCNIC !== '') {
                if (fatherCNIC === motherCNIC) {
                    toastr.error("Father's CNIC and Mother's CNIC must not be the same.");
                    $('#father_cnic, #mother_cnic').css('border-color', 'red');
                    $('#submitBtn').attr('disabled', true); 
                    return false;
                } else {
                    $('#father_cnic, #mother_cnic').css('border-color', 'green');
                }
            }
            $('#submitBtn').attr('disabled', false);
            return true;
        }

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

        $(".bform_card").inputmask();

            var checkBForm = function (textBox) {
            var regexp = /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/;
            var bFormValue = textBox.value.trim();

            if (!regexp.test(bFormValue)) {
                toastr.warning('Please enter a valid 13-digit B-Form Number with dashes (XXXXX-XXXXXXX-X)');
                $(textBox).css('border-color', 'red');
                $('#submitBtn').attr('disabled', true);
                return false;
            }

            var fatherCNIC = $('#father_cnic').val().trim();
            var motherCNIC = $('#mother_cnic').val().trim();

            if (bFormValue === fatherCNIC || bFormValue === motherCNIC) {
                toastr.error("B-Form Number must be different from Father's and Mother's CNIC.");
                $(textBox).css('border-color', 'red');
                $('#submitBtn').attr('disabled', true);
                return false;
            }

            $(textBox).css('border-color', 'green');
            $('#submitBtn').attr('disabled', false);
            return true;
        }

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


        $('.datePicker').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });


        $(".bform_card").inputmask();

        var checkBForm = function (textBox) {
            var regexp = /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/;
            var value = textBox.value.trim();

            if (!regexp.test(value)) {
                toastr.warning('Please enter a valid 13-digit B-Form Number with dashes (XXXXX-XXXXXXX-X)');
                $(textBox).css('border-color', 'red');
                $('#submitBtn').attr('disabled', true);
                return false;
            } else {
                $(textBox).css('border-color', 'green');
                $('#submitBtn').attr('disabled', false);
                return true;
            }
        }


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
    </script>



@endsection

