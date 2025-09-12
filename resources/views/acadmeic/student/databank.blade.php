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
    {{--    @dd($studentDatabank)--}}
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Add Student </h3>
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
                            @csrf
                            <input type="hidden" name="student_databank_id" id="student_databank_id"
                                   value="{{ $studentDatabank->id }}">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h5 style="background-color: #f0f0f0; padding: 10px;">SECTION-A <span
                                                class="text-center">PERSONAL INFORMATION</span></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <label for="admission_class"><b>Admission Required (Class / Grade) *</b></label>
                                    <input name="admission_class" id="admission_class" type="text" class="form-control"
                                           value="{!! $studentDatabank->admission_for !!}">
                                </div>
                                <div class="col-md-6">
                                    <label for="campus"><b>Campus *</b></label>
                                    <select class="form-control select2" name="branch_id">
                                        @foreach($branches as $branch)
                                            <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Admission:*</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input required name="admission_date" class="form-control datePicker"
                                               id="datepicker-date" placeholder="MM/DD/YYYY"
                                               type="text" value="{{old('admission_date')}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="special-child"><b>Is your child special? *</b></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="special_child"
                                               id="special-yes" value="yes">
                                        <label class="form-check-label" for="special-yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="special_child"
                                               id="special-no" value="no" checked>
                                        <label class="form-check-label" for="special-no">No</label>
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Student Name</h5>
                                    <label for="first-name"><b>First Name</b></label>
                                    <input type="text" id="first-name"
                                           value="{!! $studentDatabank->first_name ?? '' !!}" name="first_name"
                                           class="form-control"
                                           placeholder="First Name" required>
                                </div>
                                <div class="col-md-6">
                                    <h5>&nbsp;</h5>
                                    <label for="last-name"><b>Last Name</b></label>
                                    <input type="text" id="last-name" name="last_name" class="form-control"
                                           placeholder="Last Name" value="{!! $studentDatabank->last_name ?? '' !!}"
                                           required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="father-name"><b>Father’s Name</b></label>
                                    <input type="text" id="father-name"
                                           value="{!! $studentDatabank->father_name ?? '' !!}" name="father_name"
                                           class="form-control"
                                           placeholder="Father Name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="last-name"><b>Guardian’s Name</b></label>
                                    <input type="text" id="guardian-name" name="guardian_name" class="form-control"
                                           placeholder="Guardian Name">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="gender"><b>Gender</b></label>
                                    <select class="form-select select2 basic-single" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option
                                            value="male" {!!$studentDatabank->gender == 'male' ? 'selected' : ''  !!}>
                                            Male
                                        </option>
                                        <option
                                            value="female" {!!$studentDatabank->gender == 'female' ? 'selected' : ''  !!}>
                                            Female
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Birth*</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input required name="student_dob" class="form-control datePicker"
                                               id="datepicker-date" placeholder="MM/DD/YYYY"
                                               type="text" value="{{old('student_dob')}}">
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="currentAddress"><b>Current Address *</b></label>
                                    <input type="text" class="form-control"
                                           value="{!! $studentDatabank->present_address !!}"
                                           name="student_current_address">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="currentAddress"><b>Permanent Address (If Any)</b></label>
                                    <input type="text" class="form-control" name="student_permanent_address">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="city"><b>City *</b></label>
                                    <input type="text" class="form-control" name="student_city" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="city"><b>Country of Origin *</b></label>
                                    <input type="text" class="form-control" name="student_country" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="Cell"><b>Cell No *</b></label>
                                    <input type="number" class="form-control"
                                           value="{!! $studentDatabank->student_phone !!}" name="student_cell_no"
                                           required>
                                </div>
                                <div class="col-md-4">
                                    <label for="landline"><b>Landline *</b></label>
                                    <input type="number" class="form-control" name="student_landline" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="email"><b>Email *</b></label>
                                    <input type="email" class="form-control"
                                           value="{!! $studentDatabank->student_email ?? '' !!}" name="student_email"
                                           required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <h4>Languages</h4>
                                <div class="col-md-4">
                                    <label for="native"><b>Native *</b></label>
                                    <input type="text" class="form-control" name="native" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="first"><b>First </b></label>
                                    <input type="text" class="form-control" name="first">
                                </div>
                                <div class="col-md-4">
                                    <label for="second"><b>Second </b></label>
                                    <input type="text" class="form-control" name="second">
                                </div>
                            </div>

                            <div class="have_siblings_section" style="margin-bottom: 10px">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="have_siblings"><b>Have siblings?</b></label>
                                        <select class="form-select select2 basic-single studied" name="have_siblings"
                                                id="have_siblings" required>
                                            <option value="">Select Option</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="studing_cornerstone_section" style="margin-bottom: 10px;display: none">
                                <div class="append_study_section" data-id="1">
                                    <div class="row mt-4 studing_cornerstone">
                                        <div class="col-md-12">
                                            <label for="Studied"><b>Sibling Studied in CornerStone?</b></label>
                                            <select class="form-select select2 basic-single studied" name="studied[]"
                                                    id="studied">
                                                <option value="">Select Option</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="sibling-container1" style="display: none">
                                        <div class="row mt-4">
                                            <div class="col-md-3">
                                                <label for="sibling"><b>Name Of Sibling</b></label>
                                                <select class="form-select select2 basic-single sibling_name"
                                                        name="sibling_name[]">
                                                    <option value="">Select Student</option>
                                                    @foreach($students as $student)
                                                        <option
                                                            value="{!! $student->id !!}">{!! $student->first_name.' '. $student->last_name !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="dob"><b>Date of Birth</b></label>
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input name="sibling_dob[]" class="form-control datePicker"
                                                           placeholder="MM/DD/YYYY" type="text"
                                                           value="{{old('sibling_dob')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="gender"><b>Gender</b></label>
                                                <select class="form-select select2 basic-single"
                                                        name="sibling_gender[]">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="sibling"><b>Class</b></label>
                                                {{--                                            <input type="text" name="sibling_class[]" class="form-control">--}}
                                                <select class="form-select select2 basic-single sibling_class"
                                                        name="sibling_class[]">

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sibling-container" style="display: none">
                                        <div class="row mt-4">
                                            <div class="col-md-4">
                                                <label for="sibling"><b>Name Of Sibling</b></label>
                                                <input type="text" name="sibling_name[]" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="dob"><b>Date of Birth</b></label>
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input name="sibling_dob[]" class="form-control datePicker"
                                                           placeholder="MM/DD/YYYY" type="text"
                                                           value="{{old('sibling_dob')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="gender"><b>Gender</b></label>
                                                <select class="form-select select2 basic-single"
                                                        name="sibling_gender[]">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" style="margin-top: 10px" type="button" id="add_sibling">
                                    Add More
                                </button>
                            </div>

                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="position-relative"
                                             style="background-color: #f0f0f0; padding: 10px;">
                                            <h5 class="mb-0 d-inline-block">SECTION-B
                                                <span>PREVIOUS SCHOOL INFORMATION</span></h5>
                                            <div class="position-absolute top-50 end-0 translate-middle-y">
                                                <button class="btn btn-sm btn-link minimize-icon" type="button"><i
                                                        class="fas fa-minus"></i></button>
                                                <button class="btn btn-sm btn-link close-icon" type="button"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="school-information-div">
                                <div class="school-info-container">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="school"><b>Name of School</b></label>
                                            <input type="text" class="form-control" name="school_name">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <h5>Origin of School</h5>
                                        <div class="col-md-4">
                                            <input type="radio" id="internationalSchool" name="school_origin[]"
                                                   value="International School System">
                                            <label for="internationalSchool"><b>International School System</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" id="nationalSchool" name="school_origin[]"
                                                   value="National School System">
                                            <label for="nationalSchool"><b>National School System</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" id="otherSchool" name="school_origin[]"
                                                   value="Other">
                                            <label for="otherSchool"><b>Other</b></label>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="address"><b>Address of School</b></label>
                                            <input type="text" class="form-control" name="school_address">
                                        </div>

                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="reason"><b>Reasons of leaving</b></label>
                                            <input type="text" class="form-control" name="leaving_reason">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <p style="font-weight: bold">If this school is overseas, please give name and
                                            address of
                                            any
                                            previous local (Pakistani) school attended (primary or secondary.)</p>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="localSchool"><b>Name of local school</b></label>
                                            <input type="text" class="form-control" name="local_school_name">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="schoolAddress"><b>Address of local school</b></label>
                                            <input type="text" class="form-control" name="local_school_address">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h5 style="background-color: #f0f0f0; padding: 10px;">SECTION – C <span
                                                class="text-center">EMERGENCY CONTACT INFORMATION</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <p style="font-weight: bold"> Please provide details of two priority contacts in case of
                                    an emergency. Please let us know should you wish to change the information
                                    below or priority contacts</p>
                            </div>

                            <h4 class="text-center" style="background-color: #f0f0f0;">PRIORITY CONTACT</h4>

                            <div class="emergency-contact-container">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="name"><b>Name *</b></label>
                                        <input type="text" class="form-control" name="name[]" required>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="name"><b>Relationship with the Student *</b></label>
                                        <input type="text" class="form-control" name="relation[]" required>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label for="special-child"><b>Parental Responsibility</b></label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="parent_responsibility[]"
                                                   id="special-yes" value="yes">
                                            <label class="form-check-label" for="special-yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="parent_responsibility[]"
                                                   id="special-no" value="no" checked>
                                            <label class="form-check-label" for="special-no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="home_address"><b>Home Address</b></label>
                                        <input type="text" class="form-control" name="home_address[]">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label for="Cell"><b>City *</b></label>
                                        <input type="text" class="form-control" name="student_emergency_city[]"
                                               required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="landline"><b>Landline *</b></label>
                                        <input type="number" class="form-control" name="student_emergency_landline[]"
                                               required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email"><b>Cell No*</b></label>
                                        <input type="number" class="form-control" name="cell_no[]" required>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label for="Email Address"><b>Email Address</b></label>
                                        <input type="email" class="form-control" name="email_address[]" required>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="Work Address "><b>Work Address </b></label>
                                        <input type="text" class="form-control" name="work_address[]">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label for="landline"><b>Work Landline No </b></label>
                                        <input type="text" class="form-control" name="work_landline[]">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="Work Address "><b>Work Cell No </b></label>
                                        <input type="text" class="form-control" name="work_cell_no[]">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="Work Address "><b>Work Email</b></label>
                                        <input type="text" class="form-control" name="work_email[]">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" type="button" id="addEmergencyContact">Add More
                            </button>


                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="position-relative"
                                             style="background-color: #f0f0f0; padding: 10px;">
                                            <h5 class="mb-0 d-inline-block">SECTION-D
                                                <span>TRAVEL ARRANGEMENTS NEEDED</span></h5>
                                            <div class="position-absolute top-50 end-0 translate-middle-y">
                                                <button class="btn btn-sm btn-link minimize-icon1" type="button"><i
                                                        class="fas fa-minus"></i></button>
                                                <button class="btn btn-sm btn-link close-icon1" type="button"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="travelling-div">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="pickup_dropoff"><b>What mode of transport will your child normally
                                                use?</b></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="public_pickup" value="Public Pickup">
                                            <label class="form-check-label" for="public_pickup">Public Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="private_pickup" value="Private Pickup">
                                            <label class="form-check-label" for="private_pickup">Private Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="bike_cycle_pickup" value="Bike/Cycle Pickup">
                                            <label class="form-check-label" for="bike_cycle_pickup">Bike/Cycle
                                                Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="others_pickup" value="Others Pickup" checked>
                                            <label class="form-check-label" for="others_pickup">Others Pickup</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <label for="transport_facility">
                                        <b>Do you need school transport facility?</b>
                                    </label>
                                    <div class="col-md-12 row" style="margin-left: 0">
                                        <div class="col-md-12 form-check">
                                            <input class="form-check-input transport_facility" type="radio"
                                                   name="transport_facility" checked
                                                   id="transport_no" value="no">
                                            <label class="form-check-label" for="transport_no">No</label>
                                        </div>
                                        <div class="col-md-1 form-check">
                                            <input class="form-check-input transport_facility" type="radio"
                                                   name="transport_facility"
                                                   id="transport_yes" value="yes">
                                            <label class="form-check-label" for="transport_yes">Yes</label>
                                        </div>
                                        <div class="col-md-11 pick_address_section" style="display: none">
                                            <input name="pick_address" class="form-control"
                                                   placeholder="Pick / Drop off Point?">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h5 style="background-color: #f0f0f0; padding: 10px;">SECTION – E </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" id="photoPermission" name="picture_permission"
                                               value="yes" class="form-check-input">
                                        <label for="photoPermission" class="form-check-label" style="font-weight: bold">
                                            Please tick this box if you are happy for your child’s photograph to be used
                                            in display / media / exhibitions etc.
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <h5>File Uploads</h5>
                            <div class="row mt-8">
                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <h6>Passport size photograph</h6>
                                    </div>
                                    <input type="file" name="passport_photos" id="passport_photos" class="dropify"
                                           data-height="200" accept="image/*, .pdf" multiple/>
                                </div>

                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <h6>Birth certificate / Family Form (NADRA B-Form)/ Smart Card</h6>
                                    </div>
                                    <input type="file" name="birth_certificate" id="birth_certificate" class="dropify"
                                           data-height="200" accept="image/*, .pdf , .jfif"/>
                                </div>
                            </div>

                            <div class="row mt-8">
                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <h6>Previous school leaving certificate (if applicable)</h6>
                                    </div>
                                    <input type="file" name="school_leaving_certificate" id="school_leaving_certificate"
                                           class="dropify" data-height="200" accept="image/*, .pdf, .jfif"/>
                                </div>

                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <h6>Parent / Guardian CNIC / Passport</h6>
                                    </div>
                                    <input type="file" name="guardian_document" id="guardian_document" class="dropify"
                                           data-height="200" accept="image/*, .pdf , .jfif"/>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <button class="btn btn-primary">Submit</button>
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

        $('.transport_facility').on('click', function () {
            if (this.value === 'yes') {
                $('.pick_address_section').show();
            } else {
                $('.pick_address_section').hide();
            }
        });

        $('#have_siblings').on('change', function () {
            if (this.value === 'yes') {
                $('.studing_cornerstone_section').show();
            } else {
                $('.studing_cornerstone_section').hide();
            }
        });

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

        var checkCNIC = function (textBox) {

            debugger;

            var regexp = new RegExp('^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$');
            var check = textBox.value;
            if (!regexp.test(check)) {

                alert('Please Enter Valid 13 Digits CNIC with (-)');
                $(textBox).css('border-color', 'red');
                return false;

            } else {
                $(textBox).css('border-color', 'green');
                $(textBox).value = check;
                return true;
            }
        }

        $(document).ready(function () {

            var siblingCounter = 2;

            $(document).on('change', '.studied', function () {
                var studyValue = $(this).val();
                var parentContainer = $(this).closest('.append_study_section');
                var parentId = parentContainer.data('id');
                console.log(parentId);
                var siblingContainer1 = parentContainer.find(`.sibling-container1`);
                var siblingContainer = parentContainer.find(`.sibling-container`);

                if (studyValue == 'yes') {
                    siblingContainer1.show();
                    siblingContainer.hide();
                } else {
                    siblingContainer.show();
                    siblingContainer1.hide();
                }
            });

            $('#add_sibling').click(function () {
                const siblingFieldContainer = $('.studing_cornerstone_section');
                const dataId = `data-id="${siblingCounter}"`;

                siblingFieldContainer.append(`
                <div class= "append_study_section" ${dataId}>
                <div class="row mt-4 studing_cornerstone">
                    <div class="col-md-12">
                     <button class="btn btn-danger position-absolute top-0 end-0 remove_study_cornerstone" data-id="${siblingCounter}" type="button" style="background-color: transparent; border: none;">
                            <i class="fas fa-trash-alt" style="color: red;"></i>
                        </button>
                    <label for="Studied"><b>Sibling Studied in CornerStone?</b></label>
                    <select class="form-select select2 basic-single studied" name = "studied[]" id="studied" required>
                        <option value="">Select Option</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>
            <div class="sibling-container1" style="display: none">
                                                        <div class="row mt-4">
                                        <div class="col-md-3">
                                            <label for="sibling"><b>Name Of Sibling</b></label>
                                            <select class="form-select select2 basic-single sibling_name" name="sibling_name[]">
                                                <option value="">Select Student</option>
                                                @foreach($students as $student)
                <option
                    value="{!! $student->id !!}">{!! $student->first_name.' '. $student->last_name !!}</option>
                                                @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="dob"><b>Date of Birth</b></label>
                <div class="input-group">
                    <div class="input-group-text">
                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                    </div>
                    <input  name="sibling_dob[]" class="form-control datePicker"
                           placeholder="MM/DD/YYYY" type="text"
                           value="{{old('sibling_dob')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="gender"><b>Gender</b></label>
                                            <select class="form-select select2 basic-single" name="sibling_gender[]">
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="sibling"><b>Class</b></label>
                                           <select class="form-select select2 basic-single sibling_class" name="sibling_class[]">

                                            </select>
                                        </div>
                                    </div>

                </div>
                <div class="sibling-container" style="display: none">
                                                <div class="row mt-4">
                                        <div class="col-md-4">
                                            <label for="sibling"><b>Name Of Sibling</b></label>
                                            <input type="text" name="sibling_name[]" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="dob"><b>Date of Birth</b></label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input name="sibling_dob[]" class="form-control datePicker"
                                                       placeholder="MM/DD/YYYY" type="text"
                                                       value="{{old('sibling_dob')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="gender"><b>Gender</b></label>
                                            <select class="form-select select2 basic-single" name="sibling_gender[]">
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>

                </div>
                </div>
                `);
                $('.select2').select2();
                $('.datePicker').bootstrapdatepicker({
                    format: "yyyy-mm-dd",
                    viewMode: "date",
                    multidate: false,
                    multidateSeparator: "-",
                });

                siblingCounter++;
            });
            $(document).on('click', '.remove_study_cornerstone', function () {
                const contactId = $(this).data('id');
                $(`.append_study_section[data-id="${contactId}"]`).remove();
            });
        });

        $(document).ready(function () {
            $('.minimize-icon').click(function () {
                $('.school-information-div').toggleClass('minimized');
                if ($('.school-information-div').hasClass('minimized')) {
                    $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
                } else {
                    $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
                }
            });

            $('.close-icon').click(function () {
                $('.school-information-div').hide();
            });
        });

        $(document).ready(function () {
            let contactCounter = 1;

            $('#addEmergencyContact').click(function () {
                const contactFieldContainer = $('.emergency-contact-container');
                const dataId = `data-id="${contactCounter}"`;
                contactFieldContainer.append(`
                <div class="emergency-contact mt-4" ${dataId}>
                    <div class="row">
                        <div class="col-md-12 position-relative">
                            <label for="name"><b>Name</b></label>
                            <input type="text" class="form-control" name="emergency_name[]">
                        <button class="btn btn-danger position-absolute top-0 end-0  removeEmergencyContact" data-id="${contactCounter}" style="background-color: transparent; border: none;">
                         <i class="fas fa-trash-alt" style="color: red;"></i>
                    </button>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label for="relation"><b>Relationship with the Student</b></label>
                            <input type="text" class="form-control" name="emergency_relation[]">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="parent_responsibility"><b>Parental Responsibility</b></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="emergency_parent_responsibility[${contactCounter}]" id="emergency_special-yes-${contactCounter}" value="yes">
                                <label class="form-check-label" for="emergency_special-yes-${contactCounter}">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="emergency_parent_responsibility[${contactCounter}]" id="emergency_special-no-${contactCounter}" value="no" checked>
                                <label class="form-check-label" for="emergency_special-no-${contactCounter}">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                 <div class="col-md-12">
                    <label for="emergency_home_address"><b>Home Address</b></label>
                     <input type="text" class="form-control" name="emergency_home_address[]">

                    </div>
                </div>
                         <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label for="Cell"><b>City *</b></label>
                                        <input type="number" class="form-control" name="student_emergency_city[]" >
                                    </div>
                                    <div class="col-md-4">
                                        <label for="landline"><b>Landline *</b></label>
                                        <input type="number" class="form-control" name="student_emergency_landline[]" >
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email"><b>Cell No*</b></label>
                                        <input type="number" class="form-control" name="cell_no[]" >
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label for="Email Address"><b>Email Address</b></label>
                                        <input type="email" class="form-control" name="email_address[]" >
                                    </div>

                                    <div class="col-md-8">
                                        <label for="Work Address "><b>Work Address </b></label>
                                        <input type="text" class="form-control" name="work_address[]">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label for="landline"><b>Work Landline No </b></label>
                                        <input type="text" class="form-control" name="work_landline[]">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="Work Address "><b>Work Cell No </b></label>
                                        <input type="text" class="form-control" name="work_cell_no[]">
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="Work Address "><b>Work Email</b></label>
                                        <input type="text" class="form-control" name="work_email[]">

                                    </div>
                                </div>
                </div>
                `);
                contactCounter++;
            });

            $(document).on('click', '.removeEmergencyContact', function () {
                const contactId = $(this).data('id');
                $(`.emergency-contact[data-id="${contactId}"]`).remove();
            });
        });

        $(document).ready(function () {
            $('.minimize-icon1').click(function () {
                $('.travelling-div').toggle();
                $(this).find('i').toggleClass('fa-minus fa-plus');
            });

            $('.close-icon1').click(function () {
                $('.travelling-div').hide();
            });
        });

    </script>

    @include('acadmeic.js.createJs')

@endsection

