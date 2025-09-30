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
                        <form action="{!! route('academic.studentDataBank.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
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
                                    <label for="reference_no"><b>Reference No. *</b></label>
                                    <input type="text" class="form-control" name="reference_no" value="{{ $referenceNo }}" readonly>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="student_name"><b>First Name  *</b></label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="student_name"><b>Last Name  *</b></label>
                                    <input type="text" class="form-control" name="last_name" required>
                                </div>

                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="student_name"><b>Age *</b></label>
                                    <input type="number" class="form-control" name="student_age" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email"><b>Email *</b></label>
                                    <input type="email" class="form-control" name="student_email" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="gender"><b>Gender *</b></label>
                                    <select class="form-select select2 basic-single" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone"><b>Phone *</b></label>
                                    <input type="number" class="form-control" name="student_phone" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="css"><b>Student previously in CSS: * </b></label>
                                    <select class="form-select select2 basic-single" id="previous" name="study_perviously"
                                            required>
                                        <option value="">Select Gender</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="seeking_admission"><b>Seeking admission for *</b></label>
                                    <input type="text" class="form-control" name="admission_for" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="academic_session_id"><b>Academic Session *</b></label>
                                    <select class="form-select select2 basic-single" id="academic_session_id" name="academic_session_id" required>
                                        <option value="">Select Session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}" {{ old('academic_session_id') == $session->id ? 'selected' : '' }}>
                                                {{ $session->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <!-- Empty column for spacing -->
                                </div>
                            </div>

                            <div class="row mt-4 resason_for_leaving"  style="display: none">
                                <div class="col-md-12">
                                <label for="reason"><b>If yes then please state the reason and year of leaving </b></label>
                                <textarea name="reason_for_leaving" class="form-control" ></textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="father_name"><b>Father’s Name *</b></label>
                                    <input type="text" class="form-control" name="father_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="father_name"><b>Mother’s Name *</b></label>
                                    <input type="text" class="form-control" name="mother_name" required>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="father_cnic"><b>Father’s CNIC *</b></label>
                                    <input type="text" id="father_cnic" name="father_cnic"
                                           class="form-control cnic_card"
                                           data-inputmask="'mask': '99999-9999999-9'" placeholder="XXXXX-XXXXXXX-X"
                                           required
                                           onchange="checkCNIC(this)">
                                </div>
                                <div class="col-md-6">
                                    <label for="mother_cnic"><b>Mother’s CNIC *</b></label>
                                    <input type="text" id="mother_cnic" name="mother_cnic"
                                           class="form-control cnic_card"
                                           data-inputmask="'mask': '99999-9999999-9'" placeholder="XXXXX-XXXXXXX-X"
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
                                        required onchange="checkBForm(this)">
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="Present Address "><b>Present Address * </b></label>
                                    <textarea name="present_address" required class="form-control" id="" cols="4" rows="4"></textarea>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="landline_number"><b>Landline Number *</b></label>
                                    <input type="number" class="form-control" required name="landline_number">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="Present Address "><b>Previous School attended *</b></label>
                                    <textarea name="previous_school" class="form-control" required id="" cols="4" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="reasonForSwitch"><b>Reason Of Switching *</b></label>
                                    <textarea name="reason_of_switch" class="form-control"  required id="" cols="4" rows="4"></textarea>
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


<script>
    $(document).ready(function(){
       $('#previous').on('change',function () {
            var Gender_val = $(this).val();
            console.log(Gender_val);
            if(Gender_val == 'yes'){
                $('.resason_for_leaving').show();
            }
       })else{
            $('.resason_for_leaving').hide();
        }
    });
</script>
@endsection

