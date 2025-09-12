@extends('admin.layouts.main')

@section('title')
    Student Create
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
                              id="form_validation"
                              autocomplete="off" method="post">
                            @csrf
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
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="campus"><b>Campus *</b></label>
                                    <select class="form-control select2 branch_select" name="branch_id">
                                        @foreach ($branches as $branch)
                                            <option value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">

                                <div class="col-md-4">
                                    <label for="student-id">Student ID:</label>
                                    <input type="text" class="form-control student-id" readonly name="student_id">
                                </div>

                                <div class="col-md-4">
                                    <label for="branches"><b>Class:</b></label>
                                    <select required name="class_id"
                                            class="form-select select2 basic-single mt-3 select_class"
                                            aria-label=".form-select-lg example">

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="branches"><b>Section:</b></label>
                                    <select required name="section_id"
                                            class="form-select select2 basic-single mt-3 select_section"
                                            aria-label=".form-select-lg example">
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">


                                {{-- <div class="col-md-4"> --}}
                                {{-- <label for="student-id">RollNo:</label> --}}
                                {{-- <input type="text" class="form-control student-rollNo" readonly name="roll_no">
                                --}}
                                {{-- </div> --}}

                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Admission:*</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input required name="admission_date" class="form-control datePicker"
                                               id="datepicker-date" placeholder="MM/DD/YYYY" type="text"
                                               value="{{ old('admission_date') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="special-child">
                                        <b>Does the child have any special needs or require additional support? *</b>
                                    </label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="special_child"
                                               id="special-yes"
                                               value="yes">
                                        <label class="form-check-label" for="special-yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="special_child"
                                               id="special-no"
                                               value="no" checked>
                                        <label class="form-check-label" for="special-no">No</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-4" id="specialNeeds" style="display: none;">
                                <div class="col-md-12">
                                    <label for="special-needs"><b>Special Needs:</b></label>
                                    <textarea class="form-control" id="special-needs" name="special_needs" rows="3"
                                              placeholder="Enter any special needs"></textarea>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Student Name</h5>
                                    <label for="first-name"><b>First Name</b></label>
                                    <input type="text" id="first-name" name="first_name" class="form-control"
                                           placeholder="First Name" required>
                                </div>
                                <div class="col-md-6">
                                    <h5>&nbsp;</h5>
                                    <label for="last-name"><b>Last Name</b></label>
                                    <input type="text" id="last-name" name="last_name" class="form-control"
                                           placeholder="Last Name" required>
                                </div>
                            </div>

                            <div class="row mt-4 align-items-center">
                                <div class="col-md-5">
                                    <label for="father-name"><b>Father’s Name *</b></label>
                                    <input type="text" id="father-name" name="father_name" class="form-control"
                                           placeholder="Father Name" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="father-name"><b>Father’s CNIC *</b></label>
                                    <input type="text" id="father-cnic" name="father_cnic"
                                           class="form-control cnic_card"
                                           data-inputmask="'mask': '99999-9999999-9'" placeholder="XXXXX-XXXXXXX-X"
                                           required
                                           onchange="checkCNIC(this)">
                                </div>
                                <div class="col-md-2">
                                    <label for="is-guardian" class="m-0"><b>Is Guardian</b></label>
                                    <div class="form-check">
                                        <input type="checkbox" id="is-guardian" name="is_guardian" 
                                               class="form-check-input" value="1">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4" id="guardian_row">
                                <div class="col-md-6">
                                    <label for="guardian-name"><b>Guardian’s Name *</b></label>
                                    <input type="text" id="guardian-name" name="guardian_name" class="form-control"
                                           placeholder="Guardian Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="guardian-cnic"><b>Guardian’s CNIC *</b></label>
                                    <input type="text" id="guardian-cnic" name="guardian_cnic"
                                           class="form-control cnic_card" data-inputmask="'mask': '99999-9999999-9'"
                                           placeholder="XXXXX-XXXXXXX-X" onchange="checkCNIC(this)">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="gender"><b>Gender *</b></label>
                                    <select class="form-select select2 basic-single" id="gender" name="student_gender"
                                            required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Birth*</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input required name="student_dob" class="form-control datePicker"
                                               id="datepicker-date" placeholder="MM/DD/YYYY" type="text"
                                               value="{{ old('student_dob') }}">
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="currentAddress"><b>Current Address *</b></label>
                                    <input type="text" class="form-control" required name="student_current_address">
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
                                    <input type="number" class="form-control" name="student_cell_no" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="landline"><b>Landline *</b></label>
                                    <input type="number" class="form-control" name="student_landline" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="email"><b>Email *</b></label>
                                    <input type="email" class="form-control" name="student_email" required>
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

                            <div class="studing_cornerstone_section" style="margin-bottom: 10px">
                                <div class="append_study_section" data-id="1">
                                    <div class="row mt-4 studing_cornerstone">
                                        <div class="col-md-12">
                                            <label for="Studied"><b>Sibling Studied in CornerStone</b></label>
                                            {{-- <select class="form-select select2 basic-single studied" name="studied[]"
                                                id="studied" required>
                                                <option value="">Select Option</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select> --}}
                                        </div>
                                    </div>

                                    <div id="cnic-data">

                                    </div>

                                </div>
                            </div>
                            {{-- <button class="btn btn-primary" type="button" id="add_sibling">Add More</button> --}}


                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="position-relative"
                                             style="background-color: #f0f0f0; padding: 10px;">
                                            <h5 class="mb-0 d-inline-block">SECTION-B
                                                <span>PREVIOUS SCHOOL INFORMATION</span>
                                            </h5>
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
                                            <label for="internationalSchool"><b>International School
                                                    System</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" id="nationalSchool" name="school_origin[]"
                                                   value="National School System">
                                            <label for="nationalSchool"><b>National School System</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" id="otherSchool" name="school_origin[]" value="Other">
                                            <label for="otherSchool"><b>Other</b></label>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="address"><b>Address of School</b></label>
                                            <input type="text" class="form-control school_address"
                                                   name="school_address">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="reason"><b>Reasons of leaving</b></label>
                                            <input type="text" class="form-control school_leaving"
                                                   name="leaving_reason">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <p style="font-weight: bold">
                                            If this school is overseas, please give name and address of any previous
                                            local
                                            (Pakistani) school attended (primary or secondary.)
                                        </p>
                                    </div>
                                    <div class="row mt-4">
                                        <h5>Easy Urdu Program (charges applied if selected)</h5>
                                        <div class="col-md-4">
                                            <input type="radio" id="easyUrduYes" name="easy_urdu" value="Yes">
                                            <label for="easyUrduYes"><b>Yes</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" id="easyUrduNo" name="easy_urdu" value="No">
                                            <label for="easyUrduNo"><b>No</b></label>
                                        </div>
                                    </div>
                                    {{-- <div class="row mt-2"> --}}
                                    {{-- <div class="col-md-12"> --}}
                                    {{-- <label for="localSchool"><b>Name of local school</b></label> --}}
                                    {{-- <input type="text" class="form-control" name="local_school_name"> --}}
                                    {{-- </div> --}}
                                    {{-- </div> --}}
                                    {{-- <div class="row mt-4"> --}}
                                    {{-- <div class="col-md-12"> --}}
                                    {{-- <label for="schoolAddress"><b>Address of local school</b></label> --}}
                                    {{-- <input type="text" class="form-control" name="local_school_address"> --}}
                                    {{-- </div> --}}
                                    {{-- </div> --}}
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
                                <p style="font-weight: bold"> Please provide details of two priority contacts in
                                    case of
                                    an emergency. Please let us know should you wish to change the information
                                    below or priority contacts</p>
                            </div>

                            <h4 class="text-center" style="background-color: #f0f0f0;">PRIORITY CONTACT</h4>

                            <div class="emergency-contact-container">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="name"><b>Name</b></label>
                                        <input type="text" class="form-control" name="name[]">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="name"><b>Relationship with the Student</b></label>
                                        <input type="text" class="form-control" name="relation[]">
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
                                        <label for="Email Address"><b>Email Address*</b></label>
                                        <input type="email" class="form-control" name="email_address[]" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="Work Address "><b>Work Address *</b></label>
                                        <input type="text" class="form-control" required name="work_address[]">
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
                                                <span>TRAVEL ARRANGEMENTS NEEDED</span>
                                            </h5>
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
                                        <label for="pickup_dropoff">
                                            <b>What mode of transport will your child normally use?</b>
                                        </label>


                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="self_pickup" value="Self Pickup">
                                            <label class="form-check-label" for="self_pickup">Self
                                                Pickup</label>
                                        </div>

                                        {{-- <div class="form-check form-check-inline">--}}
                                        {{-- <input class="form-check-input" type="radio" name="pickup_dropoff" --}}
                                        {{-- id="bike_cycle_pickup" value="Bike/Cycle Pickup">--}}
                                        {{-- <label class="form-check-label" for="bike_cycle_pickup">--}}
                                        {{-- Bike/Cycle Pickup--}}
                                        {{-- </label>--}}
                                        {{-- </div>--}}

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="other_pickup" value="Other Pickup">
                                            <label class="form-check-label" for="other_pickup">Other
                                                Pickup</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="transport_facility">
                                            <b>Do you require school transport? (charges applied if selected)</b>
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input transport_yes" type="radio"
                                                   name="transport_facility" id="transport_yes" value="yes">
                                            <label class="form-check-label" for="transport_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input transport_no" type="radio"
                                                   name="transport_facility" id="transport_no" value="no">
                                            <label class="form-check-label" for="transport_no">No</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4 pick_address_container" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="Work Address "><b>Add address below from where the child will be
                                                picked
                                                and drop </b></label>
                                        <textarea name="pick_address" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h5 style="background-color: #f0f0f0; padding: 10px;">SECTION – E Meal
                                            Option</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>
                                        <b>Meal Option Selection
                                            <br>(Please select one of the following by ticking the appropriate box. Once
                                            selected, this option cannot be changed) (charges applied if selected): </b>
                                    </label>
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="meal_option"
                                                   id="meal-option"
                                                   value="Yes">
                                            <label class="form-check-label" for="meal-option">Yes</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="meal_option"
                                                   id="meal-option1"
                                                   value="No">
                                            <label class="form-check-label" for="meal-option1">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h5 style="background-color: #f0f0f0; padding: 10px;">SECTION – F </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" id="photoPermission" name="picture_permission"
                                               value="yes"
                                               class="form-check-input">
                                        <label for="photoPermission" class="form-check-label" style="font-weight: bold">
                                            Please tick this box if you are happy for your child’s photograph to be
                                            used in display / media / exhibitions etc.
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

    <script src="{{ asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    {{-- <script src="{{asset('dist/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
    --}}


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

            $("#form_validation").validate({});
        });
    </script>
    <script>
        $(".cnic_card").inputmask();
        var checkCNIC = function (textBox) {
            var regexp = new RegExp('^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$');
            var check = textBox.value;
            if (!regexp.test(check)) {
                toastr.warning('Please Enter Valid 13 Digits CNIC with (-)');
                $(textBox).css('border-color', 'red');
                return false;
            } else {
                $(textBox).css('border-color', 'green');
                if ($(textBox).attr('name') === "father_cnic") {
                    getCnicData(check);
                } else {
                    $(textBox).value = check;
                }
                return true;
            }
        }

        // Function to make AJAX call
        function getCnicData(cnic) {
            $.ajax({
                url: '{{ route('academic.fetchCnicStundent') }}',
                type: 'GET',
                data: {cnic: cnic},
                success: function (response) {
                    if (response.length > 0) {
                        $('#cnic-data').html('');
                        response.forEach(function (student) {
                            var firstName = student.first_name || '';
                            var lastName = student.last_name || '';
                            var studentDob = student.student_dob || '';
                            var gender = student.gender || '';
                            var classId = student.academic_class.name || '';

                            $('#cnic-data').append(`
                                <div class="row mt-4">
                                    <div class="col-md-3">
                                        <label for="dob"><b>Name of Sibling</b></label>
                                        <div class="input-group">
                                            <input name="sibling_name[]" class="form-control" type="text" value="${firstName} ${lastName}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dob"><b>Date of Birth</b></label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                            <input name="sibling_dob[]" class="form-control datePicker dob-field" placeholder="MM/DD/YYYY" type="text" value="${studentDob}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="gender"><b>Gender</b></label>
                                        <input name="sibling_gender[]" class="form-control" type="text" value="${gender}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="sibling"><b>Class</b></label>
                                        <input type="text" name="sibling_class[]" class="form-control" value="${classId}" readonly>
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        toastr.error('No data found for this CNIC');
                    }
                },
                error: function () {
                    toastr.error('Error fetching CNIC data');
                }
            });
        }
    </script>


    <script>
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

            $(document).on('click', '.remove_study_cornerstone', function () {
                const contactId = $(this).data('id');
                $(`.append_study_section[data-id="${contactId}"]`).remove();
            });
        });
    </script>


    <script>
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
    </script>

    <!-- Append Emergency Contact -->
    <script>
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
                                        <input type="number" class="form-control" name="student_emergency_city[]" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="landline"><b>Landline *</b></label>
                                        <input type="number" class="form-control" name="student_emergency_landline[]" required>
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
    </script>




    <script>
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

    <script>
        $(document).ready(function () {
            $('.transport_yes').on('click', function () {
                $('.pick_address_container').show();
            });
            $('.transport_no').on('click', function () {
                $('.pick_address_container').hide();

            });

            // when Is Guardian seleted
            $('#is-guardian').click(function () {
                if ($('#is-guardian').is(':checked')) {
                    $('#guardian-name').val($('#father-name').val());
                    $('#guardian-cnic').val($('#father-cnic').val());
                } else {
                    $('#guardian-name').attr('required', 'required');
                    $('#guardian-cnic').attr('required', 'required');
                    $('#guardian-name').val("");
                    $('#guardian-cnic').val("");
                }
            });

            // when Is Special Child seleted
            $('input[name="special_child"]').change(function () {
                if ($('#special-yes').is(':checked')) {
                    $('#specialNeeds').show();
                    $('#specialNeeds textarea').attr('required', 'required');
                } else {
                    $('#specialNeeds').hide();
                    $('#specialNeeds textarea').removeAttr('required');
                }
            });

        });
    </script>

    @include('acadmeic.js.createJs')

@endsection
