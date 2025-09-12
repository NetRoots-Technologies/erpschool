<!doctype html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 2;
        }

        .row {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
        }

        .col-md-3 {
            width: calc(25% - 20px); /* Adjust width as needed */
            padding: 0 10px;
        }

        label {
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        select.form-control {
            height: 30px;
        }

        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 5px;
        }


        .input-group {
            width: 100%;
            margin-top: 5px;
        }

        .input-group-text {
            background-color: #ccc;
            border: 1px solid #ccc;
            border-radius: 3px 0 0 3px;
        }

        .form-check-input {
            margin-top: 5px;
        }
    </style>
</head>
<body>


<!-- Latest compiled JavaScript -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Show Student </h3>
                        <form>
                            @csrf
                            @method('put')
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
                                    <label for="admission_class"><b>Admission Required (Class / Grade) </b></label>
                                    <input name="admission_class" id="admission_class" type="text" class="form-control"
                                           value="{!! $student->admission_class !!}">
                                </div>
                                <div class="col-md-6">
                                    <label for="campus"><b>Campus </b></label>
                                    <select class="form-control branch_select" name="branch_id">
                                        @foreach($branches as $branch)
                                            <option
                                                value="{!! $branch->id !!}" {!! $student->branch_id == $branch->id ? 'selected' : '' !!}>{!! $branch->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">

                                <div class="col-md-6">
                                    <label for="branches"><b>Class:</b></label>
                                    <select required name="class_id"
                                            class="form-select  mt-3 select_class"
                                            aria-label=".form-select-lg example">

                                    </select>
                                </div>


                                <div class="col-md-6">
                                    <label for="branches"><b>Section:</b></label>
                                    <select name="section_id"
                                            class="form-select  mt-3 select_section"
                                            aria-label=".form-select-lg example">
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Admission:</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input name="admission_date" class="form-control datePicker"
                                               id="datepicker-date" placeholder="MM/DD/YYYY"
                                               type="text" value="{!! $student->admission_date !!}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="special-child"><b>Is your child special? </b></label><br>
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
                                    <input type="text" id="first-name" name="first_name" class="form-control"
                                           placeholder="First Name" value="{!! $student->first_name !!}">
                                </div>
                                <div class="col-md-6">
                                    <h5>&nbsp;</h5>
                                    <label for="last-name"><b>Last Name</b></label>
                                    <input type="text" id="last-name" name="last_name" class="form-control"
                                           placeholder="Last Name" value="{!! $student->last_name !!}">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="father-name"><b>Father’s Name</b></label>
                                    <input type="text" id="father-name" name="father_name" class="form-control"
                                           placeholder="Father Name" value="{!! $student->father_name !!}">
                                </div>
                                <div class="col-md-6">
                                    <label for="last-name"><b>Guardian’s Name</b></label>
                                    <input type="text" id="guardian-name" name="guardian_name" class="form-control"
                                           placeholder="Guardian Name" value="{!! $student->guardian_name !!}">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="gender"><b>Gender</b></label>
                                    <select class="form-select " id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Birth</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input name="student_dob" class="form-control datePicker"
                                               id="datepicker-date" placeholder="MM/DD/YYYY"
                                               type="text" value="{!! $student->student_dob !!}">
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="currentAddress"><b>Current Address </b></label>
                                    <input type="text" class="form-control"
                                           value="{!! $student->student_current_address !!}"
                                           name="student_current_address">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="currentAddress"><b>Permanent Address (If Any)</b></label>
                                    <input type="text" class="form-control"
                                           value="{!! $student->student_permanent_address !!}"
                                           name="student_permanent_address">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="city"><b>City </b></label>
                                    <input type="text" class="form-control" value="{!! $student->city !!}"
                                           name="student_city">
                                </div>
                                <div class="col-md-6">
                                    <label for="city"><b>Country of Origin </b></label>
                                    <input type="text" class="form-control" value="{!! $student->country !!}"
                                           name="student_country">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="Cell"><b>Cell No </b></label>
                                    <input type="number" class="form-control" value="{!! $student->cell_no !!}"
                                           name="student_cell_no">
                                </div>
                                <div class="col-md-4">
                                    <label for="landline"><b>Landline </b></label>
                                    <input type="number" class="form-control" value="{!! $student->landline !!}"
                                           name="student_landline">
                                </div>
                                <div class="col-md-4">
                                    <label for="email"><b>Email </b></label>
                                    <input type="email" class="form-control" value="{!! $student->student_email !!}"
                                           name="student_email">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <h4>Languages</h4>
                                <div class="col-md-4">
                                    <label for="native"><b>Native </b></label>
                                    <input type="text" class="form-control" value="{!! $student->native_language !!}"
                                           name="native">
                                </div>
                                <div class="col-md-4">
                                    <label for="first"><b>First </b></label>
                                    <input type="text" class="form-control" value="{!! $student->first_language !!}"
                                           name="first">
                                </div>
                                <div class="col-md-4">
                                    <label for="second"><b>Second </b></label>
                                    <input type="text" class="form-control" value="{!! $student->second_language !!}"
                                           name="second">
                                </div>
                            </div>

                            @if(($student->student_siblings)->isNotEmpty())
                                <div class="studing_cornerstone_section" style="margin-bottom: 10px">
                                    @foreach($student->student_siblings as $studentSibling)
                                        @php($count=$studentSibling->id)
                                        <div class="append_study_section" data-id="{{ $studentSibling->id }}">
                                            <div class="row mt-4 studing_cornerstone">
                                                <div class="col-md-12">
                                                    <label for="Studied"><b>Sibling Studied in CornerStone?</b></label>
                                                    <select class="form-select  studied"
                                                            name="studied[]" id="studied"
                                                            required>
                                                        <option value="">Select Option</option>
                                                        <option
                                                            value="yes" {!! $studentSibling->studied == 'yes' ? 'selected' : '' !!}>
                                                            Yes
                                                        </option>
                                                        <option
                                                            value="no" {!! $studentSibling->studied == 'no' ? 'selected' : '' !!}>
                                                            No
                                                        </option>
                                                    </select>
                                                    <button class="btn btn-danger mt-2 remove_study_cornerstone"
                                                            type="button"
                                                            data-id="{{ $studentSibling->id }}"
                                                            style="position: absolute; top: -10px; right: 0; background-color: transparent; border: none;">
                                                        <i class="fas fa-trash-alt" style="color: red;"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            @if($studentSibling->studied == 'yes')
                                                <div class="sibling-container1">
                                                    <div class="row mt-4">
                                                        <div class="col-md-3">
                                                            <label for="sibling"><b>Name Of Sibling</b></label>
                                                            <select class="form-select"
                                                                    name="sibling_name[]">
                                                                <option value="">Select Student</option>
                                                                @foreach($students as $student)
                                                                    <option
                                                                        value="{!! $student->id !!}" {!! $studentSibling->sibling_name == $student->id ? 'selected' : '' !!}>{!! $student->first_name.' '. $student->last_name !!}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="dob"><b>Date of Birth</b></label>
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                </div>
                                                                <input name="sibling_dob[]"
                                                                       class="form-control datePicker"
                                                                       placeholder="MM/DD/YYYY" type="text"
                                                                       value="{!! $studentSibling->sibling_dob !!}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="gender"><b>Gender</b></label>
                                                            <select class="form-select"
                                                                    name="sibling_gender[]">
                                                                <option value="">Select Gender</option>
                                                                <option
                                                                    value="male" {!! $studentSibling->sibling_gender == 'male' ? 'selected' : '' !!}>
                                                                    Male
                                                                </option>
                                                                <option
                                                                    value="female" {!! $studentSibling->sibling_gender == 'female' ? 'selected' : '' !!}>
                                                                    Female
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label for="sibling"><b>Class</b></label>
                                                            <select class="form-select sibling_class" name="sibling_class[]">

                                                            </select>
                                                            {{--                                                            <input type="text" name="sibling_class[]"--}}
                                                            {{--                                                                   class="form-control" value="{!! $studentSibling->sibling_class !!}">--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($studentSibling->studied == 'no')
                                                <div class="sibling-container">
                                                    <div class="row mt-4">
                                                        <div class="col-md-4">
                                                            <label for="sibling"><b>Name Of Sibling</b></label>
                                                            <input type="text"
                                                                   value="{!! $studentSibling->sibling_name!!}"
                                                                   name="sibling_name[]"
                                                                   class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="dob"><b>Date of Birth</b></label>
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                </div>
                                                                <input name="sibling_dob[]"
                                                                       class="form-control datePicker"
                                                                       placeholder="MM/DD/YYYY" type="text"
                                                                       value="{!! $studentSibling->sibling_dob!!}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="gender"><b>Gender</b></label>
                                                            <select class="form-select"
                                                                    name="sibling_gender[]">
                                                                <option value="">Select Gender</option>
                                                                <option
                                                                    value="male" {!! $studentSibling->sibling_gender == 'male' ? 'selected' : '' !!}>
                                                                    Male
                                                                </option>
                                                                <option
                                                                    value="female" {!! $studentSibling->sibling_gender == 'male' ? 'selected' : '' !!}>
                                                                    Female
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @php($count++)
                                    @endforeach
                                </div>
                          @endif
                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="position-relative"
                                             style="background-color: #f0f0f0; padding: 10px;">
                                            <h5 class="mb-0 d-inline-block">SECTION-B
                                                <span>PREVIOUS SCHOOL INFORMATION</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="school-information-div">
                                <div class="school-info-container">
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="school"><b>Name of School</b></label>
                                            <input type="text" class="form-control"
                                                   value="{!! $student->student_schools->school_name ?? '' !!}"
                                                   name="school_name">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <h5>Origin of School</h5>
                                        <div class="col-md-4">
                                            <input type="checkbox" id="internationalSchool"
                                                   name="school_origin[]"
                                                   value="International School System">
                                            <label for="internationalSchool"><b>International School
                                                    System</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="checkbox" id="nationalSchool"
                                                   name="school_origin[]"
                                                   value="National School System">
                                            <label for="nationalSchool"><b>National School
                                                    System</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="checkbox" id="otherSchool"
                                                   name="school_origin[]"
                                                   value="Other">
                                            <label for="otherSchool"><b>Other</b></label>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="address"><b>Address of School</b></label>
                                            <input type="text" class="form-control"
                                                   name="school_address">
                                        </div>

                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="reason"><b>Reasons of leaving</b></label>
                                            <input type="text"
                                                   value="{!!$student->student_schools->leaving_reason ?? '' !!}"
                                                   class="form-control"
                                                   name="leaving_reason">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <p style="font-weight: bold">If this school is overseas, please
                                            give
                                            name and
                                            address of
                                            any
                                            previous local (Pakistani) school attended (primary or
                                            secondary.)</p>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="localSchool"><b>Name of local school</b></label>
                                            <input type="text"
                                                   value="{!! $student->student_schools->local_school_name ?? '' !!}"
                                                   class="form-control"
                                                   name="local_school_name">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="schoolAddress"><b>Address of local
                                                    school</b></label>
                                            <input type="text"
                                                   value="{!! $student->student_schools->local_school_address ?? '' !!}"
                                                   class="form-control"
                                                   name="local_school_address">
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
                                <p style="font-weight: bold"> Please provide details of two priority contacts in
                                    case of
                                    an emergency. Please let us know should you wish to change the information
                                    below or priority contacts</p>
                            </div>

                            <h4 class="text-center" style="background-color: #f0f0f0;">PRIORITY CONTACT</h4>

                            <div class="emergency-contact-container">
                                @if(($student->student_emergency_contacts)->isNotEmpty())
                                    @foreach($student->student_emergency_contacts as $studentContact)
                                        @php($count = $studentContact->id)
                                        <div class="emergency-contact mt-4"
                                             data-id="{!! $studentContact->id !!}">
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="name"><b>Name</b></label>
                                                    <input type="text" class="form-control"
                                                           value="{!! $studentContact->name !!}" name="name[]">
                                                    <button
                                                        class="btn btn-danger position-absolute top-0 end-0  removeEmergencyContact"
                                                        data-id="{!! $studentContact->id !!}"
                                                        style="background-color: transparent; border: none;">
                                                        <i class="fas fa-trash-alt" style="color: red;"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="name"><b>Relationship with the
                                                            Student</b></label>
                                                    <input type="text" class="form-control"
                                                           value="{!! $studentContact->relation !!}"
                                                           name="relation[]">
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <label for="special-child"><b>Parental
                                                            Responsibility</b></label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="parent_responsibility[]"
                                                               id="special-yes"
                                                               value="yes" {!! $studentContact->parent_responsibility == 'yes' ? 'checked' : '' !!}>
                                                        <label class="form-check-label"
                                                               for="special-yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="parent_responsibility[]"
                                                               id="special-no"
                                                               value="no" {{ $studentContact->parent_responsibility == 'no' ? 'checked' : '' }} >
                                                        <label class="form-check-label"
                                                               for="special-no">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="home_address"><b>Home Address</b></label>
                                                    <input type="text"
                                                           value="{!! $studentContact->home_address !!}"
                                                           class="form-control"
                                                           name="home_address[]">
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-4">
                                                    <label for="Cell"><b>City </b></label>
                                                    <input type="text" value="{!! $studentContact->city !!}"
                                                           class="form-control" name="student_emergency_city[]"
                                                    >
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="landline"><b>Landline </b></label>
                                                    <input type="number"
                                                           value="{!!  $studentContact->landline !!}"
                                                           class="form-control"
                                                           name="student_emergency_landline[]"
                                                    >
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="email"><b>Cell No</b></label>
                                                    <input type="number" class="form-control"
                                                           value="{!!  $studentContact->cell_no !!}"
                                                           name="cell_no[]"
                                                    >
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="Email Address"><b>Email Address</b></label>
                                                    <input type="email"
                                                           value="{!!  $studentContact->email_address !!}"
                                                           class="form-control"
                                                           name="email_address[]"
                                                    >
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="Work Address "><b>Work Address </b></label>
                                                    <input type="text"
                                                           value="{!!  $studentContact->work_address !!}"
                                                           class="form-control"
                                                           name="work_address[]">
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-4">
                                                    <label for="landline"><b>Work Landline No </b></label>
                                                    <input type="text"
                                                           value="{!!  $studentContact->work_landline !!}"
                                                           class="form-control"
                                                           name="work_landline[]">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="Work Address "><b>Work Cell No </b></label>
                                                    <input type="text"
                                                           value="{!!  $studentContact->work_cell_no !!}"
                                                           class="form-control"
                                                           name="work_cell_no[]">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="Work Address "><b>Work Email</b></label>
                                                    <input type="text" class="form-control"
                                                           value="{!!  $studentContact->work_email !!}"
                                                           name="work_email[]">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @php($count++)
                              @endif
                            </div>



                            <hr style="background-color: darkgray">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="position-relative"
                                             style="background-color: #f0f0f0; padding: 10px;">
                                            <h5 class="mb-0 d-inline-block">SECTION-D
                                                <span>TRAVEL ARRANGEMENTS NEEDED</span></h5>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="travelling-div">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="pickup_dropoff"><b>What mode of transport will your child
                                                normally
                                                use?</b></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="public_pickup"
                                                   value="Public Pickup" {!! @$student->student_transports->pickup_dropoff == 'Public Pickup' ? 'checked' : ''!!}>
                                            <label class="form-check-label" for="public_pickup">Public
                                                Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="private_pickup"
                                                   value="Private Pickup" {!! @$student->student_transports->pickup_dropoff == 'Private Pickup' ? 'checked' : ''!!}>
                                            <label class="form-check-label" for="private_pickup">Private
                                                Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="bike_cycle_pickup"
                                                   value="Bike/Cycle Pickup" {!! @$student->student_transports->pickup_dropoff == 'Bike/Cycle Pickup' ? 'checked' : ''!!}>
                                            <label class="form-check-label" for="bike_cycle_pickup">Bike/Cycle
                                                Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                   id="others_pickup"
                                                   value="Others Pickup" {!! @$student->student_transports->pickup_dropoff == 'Others Pickup' ? 'checked' : ''!!}>
                                            <label class="form-check-label" for="others_pickup">Others
                                                Pickup</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="transport_facility"><b>Do you need school transport
                                                facility?</b></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="transport_facility"
                                                   id="transport_yes"
                                                   value="yes" {!! @$student->student_transports->transport_facility == 'yes' ? 'checked' : ''!!}>
                                            <label class="form-check-label" for="transport_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="transport_facility"
                                                   id="transport_no"
                                                   value="no" {!! @$student->student_transports->transport_facility == 'no' ? 'checked' : ''!!}>
                                            <label class="form-check-label" for="transport_no">No</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="Work Address "><b>Pick / Drop off Point? </b></label>
                                        <textarea name="pick_address"
                                                  class="form-control">{!! @$student->student_transports->pick_address !!}</textarea>
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

                            <h5>File Uploads</h5>
                            <div class="row mt-8">
                                @foreach($imageUrls as $label => $url)
                                    @if($url)
                                        <div class="col-md-6">
                                            <div>
                                                <h6>{{ $label }}</h6>
                                                <img src="{{ asset($url) }}" alt="{{ $label }}" style="max-width: 100%; height: auto;">
                                            </div>
                                        </div>
                                        @if ($loop->iteration % 2 == 0)
                            </div><div class="row mt-8">
                                @endif
                                @endif
                                @endforeach
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    {{--    <script src="{{asset('dist/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>--}}





