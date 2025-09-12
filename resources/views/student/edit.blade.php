@extends('admin.layouts.main')

@section('title')
    Student  Edit
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Student</h3>
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
                        <form action="{!! route('students.update',$data['student']->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">

                            <div class="w-100">

                                @csrf
                                @method('PUT')
                                <div class="box-body" style="margin-top:50px;">


                                    <h5>Student Data</h5>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="name">Student Name</label>


                                            <input name="name" id="name" type="text" class="form-control"
                                                   @if(isset($data['student']->name))   value="{!! $data['student']->name !!}" @endif/>

                                        </div>


                                        <div class="col-lg-6">


                                            <label for="gender">Gender</label>
                                            <select name="gender" id="gender" class="select2 form-control">
                                                <option disabled selected>Select</option>

                                                <option
                                                    @if(isset($data['student']->student_detail->gender))   @if  ($data['student']->student_detail->gender == "male")  selected
                                                    @endif @endif value="male">Male
                                                </option>
                                                <option
                                                    @if(isset($data['student']->student_detail->gender))   @if( $data['student']->student_detail->gender == "female") selected
                                                    @endif   @endif value="female">Female
                                                </option>


                                            </select>

                                        </div>
                                        <div class="row mt-2">

                                            <div class="col-lg-6">
                                                <label for="marital_status">Marital Status</label>
                                                <select name="marital_status" id="marital_status"
                                                        class="select2 form-control">
                                                    <option disabled selected>Select</option>
                                                    <option
                                                        @if(isset($data['student']->student_detail->marital_status))    @if ($data['student']->student_detail->marital_status == "married") selected
                                                        @endif @endif value="married">Married
                                                    </option>
                                                    <option
                                                        @if(isset($data['student']->student_detail->marital_status))   @if ($data['student']->student_detail->marital_status == "single") selected
                                                        @endif @endif value="single">Single
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="dob">Date of Birth</label>
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>


                                                    <input name="student_dob" class="form-control"
                                                           id="datepicker-date" placeholder="MM/DD/YYYY"
                                                           type="text"
                                                           @if(isset($data['student']->student_detail->student_dob)) value="{!! $data['student']->student_detail->student_dob !!} @endif">

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->hasRole('Admin'))
                                <h5>Agent Information</h5>
                                <div class="row mt-2">
                                    <div class="col-lg-1" style="margin-top: 50px; margin-left: 35px;">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   @if($data['student']->agent_id!=null) checked @endif type="checkbox"
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
                                                <option value="{{$item->id}}"
                                                        @if($data['student']->agent_id==$item->id) selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        <br/>
                                        <br/>
                                        <br/>
                                    </div>
                                    @endif

                                    <hr style=" background-color: darkgray">
                                    <div class="box-body" style="margin-top:50px;">
                                        <div class="box-body" style="margin-top:50px;">
                                            <h5>Nationality Information</h5>
                                            <div class="row mt-2">
                                                <div class="col-lg-6">
                                                    <label for="nationality">Country of
                                                        Nationality</label>
                                                    <select name="nationality" id="nationality"
                                                            class="select2 form-control">

                                                        <option value="" disabled selected>Select an Option</option>

                                                        @foreach ( $data['country'] as $item)


                                                            <option
                                                                @if(isset($data['student']->student_detail->nationality))  @if ($data['student']->student_detail->nationality==$item->id) selected
                                                                @endif  @endif
                                                                value="{{$item->id}}">{{$item->name}}</option>


                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="passport_cnic">CNIC
                                                        No. <span class="small">(with (-) )</span></label>


                                                    <input name="passport_cnic" id="passport_cnic"
                                                           type="text"
                                                           class="form-control"
                                                           onchange="checkCNIC(this)"
                                                           placeholder="12345-1234567-1"
                                                           @if(isset($data['student']->student_detail->passport_cnic))
                                                           value="{!! $data['student']->student_detail->passport_cnic !!}" @endif/>

                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-lg-12">
                                                    <label for="cnic_expiry">CNIC Expiry
                                                        Date</label>
                                                    <div class="row">
                                                        <div class="col-lg-6">

                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>

                                                                </div>


                                                                <input name="pass_cnic_expiry"
                                                                       class="form-control"
                                                                       id="datepicker-date1"
                                                                       placeholder="MM/DD/YYYY"
                                                                       type="text"
                                                                       @if(isset($data['student']->student_detail->pass_cnic_expiry)) value="{!! $data['student']->student_detail->pass_cnic_expiry !!} @endif">

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="background-color: darkgray">
                                        <div class="box-body" style="margin-top:50px;">
                                            <h5>Address</h5>
                                            <div class="row mt-2">
                                                <div class="col-lg-6">
                                                    <label for="address_country">Country</label>
                                                    <select name="address_country" id="address_country"
                                                            class="select2 form-control">
                                                        <option value="" disabled selected>Select an
                                                            Option
                                                        </option>

                                                        @foreach ( $data['country'] as $item)


                                                            <option
                                                                @if(isset($data['student']->student_detail->address_country))       @if ($data['student']->student_detail->address_country==$item->id) selected
                                                                @endif @endif
                                                                value="{{$item->id}}">{{$item->name}}</option>

                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="address_province">State/Province</label>
                                                    <select name="address_state" id="address_state"
                                                            class="select2 form-control">
                                                        <option value="" disabled selected>Select an
                                                            Option
                                                        </option>
                                                        @foreach ( $data['state'] as $item)


                                                            <option
                                                                @if(isset($data['student']->student_detail->address_state))       @if ($data['student']->student_detail->address_state==$item->id) selected
                                                                @endif @endif
                                                                value="{{$item->id}}">{{$item->name}}</option>

                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-lg-6">
                                                    <label for="address_city">City</label>

                                                    <select name="address_city" id="address_city"
                                                            class="select2 form-control">
                                                        <option value="" disabled selected>Select an
                                                            Option
                                                        </option>
                                                        @foreach ( $data['city'] as $item)


                                                            <option
                                                                @if(isset($data['student']->student_detail->address_city))       @if ($data['student']->student_detail->address_city==$item->id) selected
                                                                @endif @endif
                                                                value="{{$item->id}}">{{$item->name}}</option>

                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="address">Address</label>
                                                    <input name="address" id="address" type="text"
                                                           class="form-control"
                                                           @if(isset($data['student']->student_detail->address)) value="{!! $data['student']->student_detail->address !!}" @endif/>

                                                </div>
                                            </div>
                                        </div>
                                        <hr style=" background-color: darkgray">
                                        <div class="box-body" style="margin-top:50px;">
                                            <h5>Contact Details</h5>
                                            <div class="row mt-2">
                                                <div class="col-lg-6">
                                                    <label for="email">Email</label>


                                                    <input name="email" id="email"
                                                           type="email" class="form-control"
                                                           @if(isset($data['student']->email)) value="{!! $data['student']->email !!}" @endif/>

                                                </div>
                                                <div class=" col-lg-6">
                                                    <label for="mobile_no">Contact
                                                        No.</label>


                                                    <input name="mobile_no" id="mobile_no"
                                                           type="tel"
                                                           class="form-control"
                                                           @if(isset($data['student']->mobile_no))  value="{!! $data['student']->mobile_no !!}" @endif/>


                                                </div>
                                            </div>
                                        </div>
                                        <hr style=" background-color: darkgray">
                                        <div class="box-body"
                                             style="margin-top:50px;">
                                            <h5>Guardian Information</h5>
                                            <div class="row mt-2">
                                                <div class="col-lg-6">
                                                    <label for="guardian_name">Guardian
                                                        Name</label>


                                                    <input name="guardian_name"
                                                           id="guardian_name"
                                                           type="text"
                                                           class="form-control"
                                                           @if(isset($data['student']->student_detail->guardian_name))  value="{!! $data['student']->student_detail->guardian_name !!}" @endif/>

                                                </div>
                                                <div class=" col-lg-6">
                                                    <label
                                                        for="guardian_occupation">Guardian
                                                        Occupation</label>


                                                    <input name="guardian_occupation"
                                                           id="guardian_occupation"
                                                           type="text"
                                                           class="form-control"
                                                           @if(isset($data['student']->student_detail->guardian_occupation))  value="{!! $data['student']->student_detail->guardian_occupation !!}" @endif/>

                                                </div>
                                            </div>
                                            <div class=" row mt-2">
                                                <div class="col-lg-6">
                                                    <label
                                                        for="guardian_mobile_no">Guardian
                                                        Contact No.</label>

                                                    <input
                                                        name="guardian_mobile_no"
                                                        id="guardian_mobile_no"
                                                        type="tel"
                                                        class="form-control"
                                                        @if(isset($data['student']->student_detail->guardian_mobile_no))
                                                        value="{!! $data['student']->student_detail->guardian_mobile_no !!}" @endif/>

                                                </div>
                                                <div class=" col-lg-6">
                                                    <label
                                                        for="guardian_relation_with_student">Relation
                                                        With
                                                        Applicant</label>


                                                    <input
                                                        name="guardian_relation_with_student"
                                                        id="guardian_relation_with_student"
                                                        type="text"
                                                        class="form-control"
                                                        @if(isset($data['student']->student_detail->guardian_relation_with_student)) value="{!! $data['student']->student_detail->guardian_relation_with_student !!}" @endif/>

                                                </div>
                                            </div>
                                        </div>
                                        <hr style=" background-color: darkgray">
                                        <h5>File Uploads</h5>
                                        <div class="row mt-8">
                                            <div
                                                class="col-lg-4">
                                                <h6>ID Card</h6>


                                                <img
                                                    @if(isset($data['student']->student_detail->id_card)) src="{!! asset($data['student']->student_detail->id_card) !!} @endif">

                                                <input
                                                    type="file"
                                                    name="id_card"
                                                    id="id_card"
                                                    class="dropify"
                                                    data-height="200"/>
                                            </div>
                                            <div
                                                class="col-lg-4">
                                                <h6>
                                                    Passport</h6>


                                                <img
                                                    @if(isset($data['student']->student_detail->passport)) src="{!! asset($data['student']->student_detail->passport) !!}  @endif">

                                                <input
                                                    type="file"
                                                    name="passport"
                                                    id="passport"
                                                    class="dropify"
                                                    data-height="200"/>
                                            </div>
                                            <div
                                                class="col-lg-4">
                                                <h6>Supporting
                                                    Documents</h6>


                                                <img
                                                    @if(isset($data['student']->student_detail->document)) src="{!! asset($data['student']->student_detail->document) !!}  @endif">


                                                <input
                                                    type="file"
                                                    name="document"
                                                    id="document"
                                                    class="dropify"
                                                    data-height="200"/>
                                            </div>
                                        </div>
                                        <hr style="background-color: darkgray">
                                        <div
                                            class="row mt-8 mb-3">
                                            <div class="col-12">
                                                <div
                                                    class="form-group text-right">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-primary">
                                                        Save
                                                    </button>
                                                    <a href="{!! route('students.index') !!}"
                                                       class=" btn btn-sm btn-danger">Cancel </a>
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
    {{--    <script src="{{asset('dist/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>--}}

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
                    $('#address_city').html(response);


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
            var atLeastOneIsChecked = $('#flexSwitchCheckDefault:checkbox:checked').length > 0;
            if (atLeastOneIsChecked) {

                $('#agent_ref').show();

            } else {
                $('#agent_ref').hide();
            }
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
        var checkCNIC = function (textBox) {

            debugger;

            var regexp = new RegExp('^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$');
            var check = textBox.value;
            if (!regexp.test(check)) {

                alert('Invalid CNIC');
                $(textBox).css('border-color', 'red');
                return false;

            } else {
                $(textBox).css('border-color', 'green');
                $(textBox).value = check;
                return true;
            }
        }
    </script>


@endsection

