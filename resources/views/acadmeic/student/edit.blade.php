@extends('admin.layouts.main')

@section('title')
    Student Edit
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
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Student </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.students.index') !!}" class="btn btn-primary btn-md ">
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

                        <form action="{!! route('academic.students.update', $student->id) !!}" enctype="multipart/form-data" id="form_validation"
                            autocomplete="off" method="post">
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
                                    <select class="form-control select2  branch_select" name="branch_id" id="selectBranch">
                                        @foreach ($branches as $branch)
                                            <option value="{!! $branch->id !!}" {!! $student->branch_id == $branch->id ? 'selected' : '' !!}>
                                                {!! $branch->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="student-id">Student ID:</label>
                                    <input type="text" class="form-control student-id" value="{!! $student->student_id ?? '' !!}"
                                        readonly name="student_id">
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
                                    <select name="section_id" class="form-select select2 basic-single mt-3 select_section"
                                        aria-label=".form-select-lg example">
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Admission:</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input name="admission_date" class="form-control datePicker" id="datepicker-date"
                                            placeholder="MM/DD/YYYY" type="text" value="{!! $student->admission_date !!}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="special-child"><b>Does the child have any special needs or require
                                            additional support? * </b></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="special_child" id="special-yes"
                                            value="yes" {{ strtolower(trim($student->special_child)) == "yes" ? 'checked' : '' }}>
                                        <label class="form-check-label" for="special-yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="special_child" id="special-no"
                                            value="no" {!! strtolower(trim($student->special_child))== "no" ? 'checked' : '' !!}>
                                        <label class="form-check-label" for="special-no">No</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-4" id="specialNeeds" style="display: none;">
                                <div class="col-md-12">
                                    <label for="special-needs"><b>Special Needs:</b></label>
                                    <textarea class="form-control" id="special-needs" name="special_needs" rows="3"
                                        placeholder="Enter any special needs">{!! $student->special_needs !!}</textarea>
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
                                <div class="col-md-5">
                                    <label for="father-name"><b>Father’s Name</b></label>
                                    <input type="text" id="father-name" name="father_name" class="form-control"
                                        placeholder="Father Name" value="{!! $student->father_name !!}">
                                </div>
                                <div class="col-md-5">
                                    <label for="father-name"><b>Father’s CNIC *</b></label>
                                    <input type="text" id="father-cnic" name="father_cnic"
                                        class="form-control cnic_card" data-inputmask="'mask': '99999-9999999-9'"
                                        placeholder="XXXXX-XXXXXXX-X" required onchange="checkCNIC(this)"
                                        value="{!! $student->father_cnic !!}">
                                </div>
                                <div class="col-md-2">
                                    <label for="is_guardian" class="m-0"><b>Is Guardian</b></label>
                                    <div class="form-check">
                                        <input type="checkbox" id="is_guardian" name="is_guardian" value="{!! $student->is_guardian === 1 ? 1 : 0 !!}"
                                            class="form-check-input" {!! $student->is_guardian === 1 ? 'checked' : '' !!}>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-4" id="guardian_row" style="display: none;">
                                <div class="col-md-6">
                                    <label for="guardian-name"><b>Guardian’s Name *</b></label>
                                    <input type="text" id="guardian-name" name="guardian_name" class="form-control"
                                        placeholder="Guardian Name" value="{!! $student->guardian_name !!}">
                                </div>
                                <div class="col-md-6">
                                    <label for="guardian-cnic"><b>Guardian’s CNIC *</b></label>
                                    <input type="text" id="guardian-cnic" name="guardian_cnic"
                                        class="form-control cnic_card" data-inputmask="'mask': '99999-9999999-9'"
                                        placeholder="XXXXX-XXXXXXX-X" onchange="checkCNIC(this)"
                                        value="{!! $student->guardian_cnic !!}">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="gender"><b>Gender</b></label>
                                    <select class="form-select select2 basic-single" id="gender"
                                        name="student_gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {!! strtolower(trim($student->gender)) == 'male' ? 'selected' : '' !!}>Male</option>
                                        <option value="female" {!! strtolower(trim($student->gender)) == 'female' ? 'selected' : '' !!}>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="dob"><b>Date of Birth</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input name="student_dob" class="form-control datePicker" id="datepicker-date"
                                            placeholder="MM/DD/YYYY" type="text" value="{!! $student->student_dob !!}">
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="currentAddress"><b>Current Address </b></label>
                                    <input type="text" class="form-control" value="{!! $student->student_current_address !!}"
                                        name="student_current_address">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="currentAddress"><b>Permanent Address (If Any)</b></label>
                                    <input type="text" class="form-control" value="{!! $student->student_permanent_address !!}"
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
                            <div class="row mt-4 studing_cornerstone">
                                <div class="col-md-12">
                                    <label for="Studied"><b>Sibling Studied in CornerStone</b></label>
                                </div>
                            </div>
                            <div id="cnic-data">

                            </div>
                            <hr style="background-color: darkgray">

                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="position-relative" style="background-color: #f0f0f0; padding: 10px;">
                                            <h5 class="mb-0 d-inline-block">SECTION-B
                                                <span>PREVIOUS SCHOOL INFORMATION</span>
                                            </h5>
                                            <div class="position-absolute top-50 end-0 translate-middle-y">
                                                <button class="btn btn-sm btn-link minimize-icon" type="button">
                                                    <i class="fas fa-minus"></i></button>
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
                                            <input type="text" class="form-control" value="{!! $student->student_schools->school_name ?? '' !!}"
                                                name="school_name">
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
                                            <label for="nationalSchool"><b>National School
                                                    System</b></label>
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
                                            <input type="text" value="{!! $student->student_schools->leaving_reason ?? '' !!}" class="form-control"
                                                name="leaving_reason">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <p style="font-weight: bold">
                                            If this school is overseas, please give name and address of any previous local
                                            (Pakistani) school attended (primary or secondary.)
                                        </p>
                                    </div>
                                    <div class="row mt-4">
                                        <h5>Easy Urdu Program (charges applied if selected)</h5>
                                        <div class="col-md-4">
                                            <input type="radio" id="easyUrduYes" name="easy_urdu" value="Yes"
                                                {!! @$student->easy_urdu === 1 ? 'checked' : '' !!}>
                                            <label for="easyUrduYes"><b>Yes</b></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" id="easyUrduNo" name="easy_urdu" value="No"
                                                {!! @$student->easy_urdu === 0 ? 'checked' : '' !!}>
                                            <label for="easyUrduNo"><b>No</b></label>
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
                                @if ($student->student_emergency_contacts->isNotEmpty())
                                    @foreach ($student->student_emergency_contacts as $studentContact)
                                        @php($count = $studentContact->id)
                                        <div class="emergency-contact mt-4" data-id="{!! $studentContact->id !!}">
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
                                                        value="{!! $studentContact->relation !!}" name="relation[]">
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <label for="special-child"><b>Parental
                                                            Responsibility</b></label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="parent_responsibility[]" id="special-yes"
                                                            value="yes" {!! $studentContact->parent_responsibility == 'yes' ? 'checked' : '' !!}>
                                                        <label class="form-check-label" for="special-yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="parent_responsibility[]" id="special-no" value="no"
                                                            {{ $studentContact->parent_responsibility == 'no' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="special-no">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="home_address"><b>Home Address</b></label>
                                                    <input type="text" value="{!! $studentContact->home_address !!}"
                                                        class="form-control" name="home_address[]">
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-4">
                                                    <label for="Cell"><b>City </b></label>
                                                    <input type="text" value="{!! $studentContact->city !!}"
                                                        class="form-control" name="student_emergency_city[]">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="landline"><b>Landline </b></label>
                                                    <input type="number" value="{!! $studentContact->landline !!}"
                                                        class="form-control" name="student_emergency_landline[]">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="email"><b>Cell No</b></label>
                                                    <input type="number" class="form-control"
                                                        value="{!! $studentContact->cell_no !!}" name="cell_no[]">
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="Email Address"><b>Email Address</b></label>
                                                    <input type="email" value="{!! $studentContact->email_address !!}"
                                                        class="form-control" name="email_address[]">
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <label for="Work Address "><b>Work Address </b></label>
                                                    <input type="text" value="{!! $studentContact->work_address !!}"
                                                        class="form-control" name="work_address[]">
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-4">
                                                    <label for="landline"><b>Work Landline No </b></label>
                                                    <input type="text" value="{!! $studentContact->work_landline !!}"
                                                        class="form-control" name="work_landline[]">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="Work Address "><b>Work Cell No </b></label>
                                                    <input type="text" value="{!! $studentContact->work_cell_no !!}"
                                                        class="form-control" name="work_cell_no[]">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="Work Address "><b>Work Email</b></label>
                                                    <input type="text" class="form-control"
                                                        value="{!! $studentContact->work_email !!}" name="work_email[]">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @php($count++)
                                @else
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
                                            <label for="special-child"><b>Parental
                                                    Responsibility</b></label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    name="parent_responsibility[]" id="special-yes" value="yes">
                                                <label class="form-check-label" for="special-yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    name="parent_responsibility[]" id="special-no" value="no"
                                                    checked>
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
                                            <label for="Cell"><b>City </b></label>
                                            <input type="number" class="form-control" name="student_emergency_city[]">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="landline"><b>Landline </b></label>
                                            <input type="number" class="form-control"
                                                name="student_emergency_landline[]">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="email"><b>Cell No</b></label>
                                            <input type="number" class="form-control" name="cell_no[]">
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label for="Email Address"><b>Email Address</b></label>
                                            <input type="email" class="form-control" name="email_address[]">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
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
                                @endif
                            </div>
                            <button class="btn btn-primary mt-3" type="button" id="addEmergencyContact">Add More
                            </button>


                            <hr style="background-color: darkgray">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="position-relative" style="background-color: #f0f0f0; padding: 10px;">
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
                                        <label for="pickup_dropoff"><b>What mode of transport will your child normally
                                                use?</b></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                id="self_pickup" value="Self Pickup" {!! @$student->student_transports->pickup_dropoff == 'Self Pickup' ? 'checked' : '' !!}>
                                            <label class="form-check-label" for="self_pickup">Self
                                                Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                id="bike_cycle_pickup" value="Bike/Cycle Pickup" {!! @$student->student_transports->pickup_dropoff == 'Bike/Cycle Pickup' ? 'checked' : '' !!}>
                                            <label class="form-check-label" for="bike_cycle_pickup">Bike/Cycle
                                                Pickup</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pickup_dropoff"
                                                id="other_pickup" value="Other Pickup" {!! @$student->student_transports->pickup_dropoff == 'Other Pickup' ? 'checked' : '' !!}>
                                            <label class="form-check-label" for="other_pickup">Other
                                                Pickup</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="transport_facility"><b>Do you require school transport? (charges
                                                applied if selected)</b></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="transport_facility"
                                                id="transport_yes" value="yes" {!! @$student->student_transports->transport_facility == 'yes' ? 'checked' : '' !!}>
                                            <label class="form-check-label" for="transport_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="transport_facility"
                                                id="transport_no" value="no" {!! @$student->student_transports->transport_facility == 'no' ? 'checked' : '' !!}>
                                            <label class="form-check-label" for="transport_no">No</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4 pick_address_container" style="display: none">
                                    <div class="col-md-12">
                                        <label for="Work Address "><b>Add address below from where the child will be picked
                                                and drop</b></label>
                                        <textarea name="pick_address" class="form-control">{!! @$student->student_transports->pick_address !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr style="background-color: darkgray">


                            <div class="d-none" id="mealSection">
                                <div class="w-100">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <h5 style="background-color: #f0f0f0; padding: 10px;">SECTION – E Meal Option</h5>
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
                                                    id="meal-option" value="1" {!! @$student->meal_option === '1' ? 'checked' : '' !!}>
                                                <label class="form-check-label" for="meal-option">Opt-In for School
                                                    Lunch</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="meal_option"
                                                    id="meal-option-no" value="0"
                                                    {!! @$student->meal_option === '0' ? 'checked' : '' !!}>
                                                <label class="form-check-label" for="meal-option-no">Not Opt-In for School
                                                    Lunch</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr style="background-color: darkgray">
                            </div>

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
                                            value="yes" class="form-check-input">
                                        <label for="photoPermission" class="form-check-label" style="font-weight: bold">
                                            Please tick this box if you are happy for your child’s photograph to
                                            be used
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
                                        data-height="200" accept="image/*, .pdf" multiple />
                                </div>

                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <h6>Birth certificate / Family Form (NADRA B-Form)/ Smart Card</h6>
                                    </div>
                                    <input type="file" name="birth_certificate" id="birth_certificate"
                                        class="dropify" data-height="200" accept="image/*, .pdf , .jfif" />
                                </div>
                            </div>

                            <div class="row mt-8">
                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <h6>Previous school leaving certificate (if applicable)</h6>
                                    </div>
                                    <input type="file" name="school_leaving_certificate"
                                        id="school_leaving_certificate" class="dropify" data-height="200"
                                        accept="image/*, .pdf, .jfif" />
                                </div>

                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <h6>Parent / Guardian CNIC / Passport</h6>
                                    </div>
                                    <input type="file" name="guardian_document" id="guardian_document"
                                        class="dropify" data-height="200" accept="image/*, .pdf , .jfif" />
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                            <input type="hidden" value={{ $id }} id="student_id">
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

    <script>
        $(document).ready(function() {
            var fatherCnic = $('#father-cnic').val();
            if (fatherCnic !== '') {
                $.ajax({
                    url: '{{ route('academic.fetchCnicStundent') }}',
                    type: 'GET',
                    data: {
                        cnic: fatherCnic
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            $('#cnic-data').html('');
                            response.forEach(function(student) {
                                var student_id = $('#student_id').val();
                                if (student.id != student_id) {

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
                                }

                            });
                        } else {
                            toastr.error('No data found for this CNIC');
                        }
                    },
                    error: function() {
                        toastr.error('Error fetching CNIC data');
                    }
                });
            }
        });
    </script>

    <script>
        $('#address_country').on('change', function() {

            $.ajax({
                method: 'GET',
                url: "{{ route('admin.states') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": this.value,
                },
                success: function(response) {
                    $('#address_state').html(response);
                }
            });
        });

        $('#address_state').on('change', function() {

            $.ajax({
                method: 'GET',
                url: "{{ route('admin.cities') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": this.value,
                },
                success: function(response) {
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


        $(document).ready(function() {
            $('#flexSwitchCheckDefault').click(function() {
                var atLeastOneIsChecked = $('#flexSwitchCheckDefault:checkbox:checked').length > 0;
                if (atLeastOneIsChecked) {

                    $('#agent_ref').show();

                } else {
                    $('#agent_ref').hide();
                }
            });

            $("#selectBranch").on("change", function () {
                var branchName = $(this).find(":selected").text().trim().toLowerCase();

                if (branchName === "global campus") {
                    $("#mealSection").removeClass("d-none");
                } else {
                    $("#mealSection").addClass("d-none");
                }
            });

        });
    </script>
    <script>
        var checkCNIC = function(textBox) {
            var regexp = new RegExp('^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$');
            var check = textBox.value;
            if (!regexp.test(check)) {

                toastr.warning('Please Enter Valid 13 Digits CNIC with (-)');
                $(textBox).css('border-color', 'red');
                return false;

            } else {
                $(textBox).css('border-color', 'green');
                $(textBox).value = check;
                return true;
            }
        }
    </script>

    <script>
        $(document).ready(function() {

            var siblingCounter = {!! $count ?? 2 !!};

            $(document).on('change', '.studied', function() {
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
            $('.select2').select2();
            $('.datePicker').bootstrapdatepicker({
                format: "yyyy-mm-dd",
                viewMode: "date",
                multidate: false,
                multidateSeparator: "-",
            });

            siblingCounter++;
        });
        $(document).on('click', '.remove_study_cornerstone', function() {
            const contactId = $(this).data('id');
            $(`.append_study_section[data-id="${contactId}"]`).remove();
        });
        //  });
    </script>

    <script>
        $(document).ready(function() {
            $('.minimize-icon').click(function() {
                $('.school-information-div').toggleClass('minimized');
                if ($('.school-information-div').hasClass('minimized')) {
                    $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
                } else {
                    $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
                }
            });

            $('.close-icon').click(function() {
                $('.school-information-div').hide();
            });
        });
    </script>

    <!-- Append Emergency Contact -->
    <script>
        $(document).ready(function() {
            let contactCounter = {!! $count ?? 1 !!};

            $('#addEmergencyContact').click(function() {
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
                                        <label for="Cell"><b>City </b></label>
                                        <input type="number" class="form-control" name="student_emergency_city[]">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="landline"><b>Landline </b></label>
                                        <input type="number" class="form-control" name="student_emergency_landline[]">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email"><b>Cell No</b></label>
                                        <input type="number" class="form-control" name="cell_no[]" >
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="Email Address"><b>Email Address</b></label>
                                        <input type="email" class="form-control" name="email_address[]" >
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
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

            $(document).on('click', '.removeEmergencyContact', function() {
                const contactId = $(this).data('id');
                $(`.emergency-contact[data-id="${contactId}"]`).remove();
            });
        });
    </script>




    <script>
        $(document).ready(function() {
            $('.minimize-icon1').click(function() {
                $('.travelling-div').toggle();
                $(this).find('i').toggleClass('fa-minus fa-plus');
            });

            $('.close-icon1').click(function() {
                $('.travelling-div').hide();
            });
        });
    </script>



    <script>
        $(document).ready(function() {

            $('#companySelect').on('change', function() {
                var selectedCompanyId = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function(data) {
                        var branchesDropdown = $('.branch_select').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function(branch) {

                            var selectedBranch = branch.id ==
                                '{!! $student->branch_id !!}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id +
                                '" ' + selectedBranch + '>' + branch.name +
                                '</option>');

                        });
                    },
                    error: function(error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();

        })
    </script>


    <script>
        var branch_id;

        $(document).ready(function() {

            $('.branch_select').on('change', function() {
                branch_id = $(this).val();
                console.log(branch_id);
                if (!branch_id) {
                    branch_id = {!! json_encode($student->branch_id) !!};
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function(data) {
                        var classDropdown = $('.select_class').empty();
                        // alert(data);
                        data.forEach(function(academic_class) {
                            var selectedClass = academic_class.id ==
                                '{{ $student->class_id }}' ? 'selected' : '';

                            classDropdown.append('<option value="' + academic_class.id +
                                '" ' + selectedClass + '>' + academic_class.name +
                                '</option>');

                        });
                    },
                    error: function(error) {
                        console.log('not get');
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        });
    </script>

    <script>
        var class_id;

        $(document).ready(function() {

            $('.select_class').on('change', function() {
                class_id = $('.select_class').val();
                // console.log(class_id);
                if (!class_id) {
                    class_id = {!! json_encode($student->class_id) !!};
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function(data) {
                        var sectionDropdown = $('.select_section').empty();

                        data.forEach(function(section) {
                            var selectedSection = section.id ==
                                '{{ $student->section_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + section.id +
                                '" ' + selectedSection + '>' + section.name +
                                '</option>');

                        });
                    },
                    error: function(error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.sibling_name').on('change', function() {
                var studentId = $(this).val();
                var siblingDOBField = $(this).closest('.row').find('.dob-field');
                var siblingGenderField = $(this).closest('.row').find('.gender-field');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetch_siblingDob') }}',
                    data: {
                        student_id: studentId
                    },
                    success: function(data) {
                        siblingDOBField.val(data.student_dob);
                        siblingGenderField.val(data.gender).trigger('change');
                    },
                    error: function(error) {
                        console.error('Error fetching sibling data:', error);
                    }
                });
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            var branch_id = {!! $student->branch_id ?? 'null' !!};

            $('.branch_select').on('change', function() {
                branch_id = $(this).val();
                // console.log(branch_id);

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetch_studentId') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function(data) {
                        var student_id = $('.student-id').val();
                        if (student_id != '') {
                            $('.student-id').val(student_id);
                        } else {
                            $('.student-id').val(data.new_student_id);
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching new student ID:', error);
                    }
                });
            }).change();
        });
    </script>



    <script>
        $(document).ready(function() {
            var section = $('.select_section').val();
            var class_id = $('.select_class').val();
            var branch_id = $('.branch_select').val();

            $('.select_section').on('change', function() {
                section = $('.select_section').val() || {!! $student->section_id ?? 'null' !!};
                class_id = $('.select_class').val() || {!! $student->class_id ?? 'null' !!};
                branch_id = $('.branch_select').val() || {!! $student->branch_id ?? 'null' !!};

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchRollNo') }}',
                    data: {
                        section: section,
                        branch_id: branch_id,
                        class_id: class_id,
                    },
                    success: function(data) {
                        var studentRollNo = $('.student-rollNo').val();
                        if (studentRollNo != '') {
                            $('.student-rollNo').val(studentRollNo);
                        } else {
                            $('.student-rollNo').val(data.studentRollNo);
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching student roll number:', error);
                    }
                });
            }).change();
        });
    </script>

    <script>
        $(document).ready(function() {
            if ($('#transport_yes').is(':checked')) {
                $('.pick_address_container').show();
            } else {
                $('.pick_address_container').hide();
            }

            if ($('#special-yes').is(':checked')) {
                $('#specialNeeds').show();
            } else {
                $('#specialNeeds').hide();
            }

            if ($('#is_guardian').is(':checked')) {
                $('#guardian_row').hide();
            } else {
                $('#guardian_row').show();
            }

            $('input[name="transport_facility"]').on('change', function() {
                if ($('#transport_yes').is(':checked')) {
                    $('.pick_address_container').show();
                } else {
                    $('.pick_address_container').hide();
                }
            });

            // when Is Guardian seleted
            $('#is_guardian').click(function() {
                if ($('#is_guardian').is(':checked')) {
                    $('#guardian_row').hide();
                    $('#guardian_row input').removeAttr('required');
                } else {
                    $('#guardian_row').show();
                    $('#guardian_row input').attr('required', 'required');
                }
            });

            // when Is Special Child seleted
            $('input[name="special_child"]').change(function() {
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

@endsection
