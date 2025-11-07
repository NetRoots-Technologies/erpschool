@extends('admin.layouts.main')

@section('title')
    Employee Create
@stop

@section('content')

    @php
        //    for welfare
    $employeeWelfareValue = \App\Helpers\GeneralSettingsHelper::getSetting('employeeWelfare');

          $employeeWelfare = 0;
        if ($employeeWelfareValue != null){
           $employeeWelfare = floatval($employeeWelfareValue['value']);
        }
    $provitendFundValue = \App\Helpers\GeneralSettingsHelper::getSetting('providentFund');
        if($provitendFundValue != null){
            $provitendFund = floatval($provitendFundValue['percentage']);
        }else{
        $provitendFund = 0;
     }

   $salary = \App\Helpers\GeneralSettingsHelper::getSetting('salaryValue');
        if ($salary != null){
            $salaryValue = floatval($salary['value']);
        }
        else{
        $salaryValue = 0;
    }
    @endphp


    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Employee</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.employee.index') !!}" class="btn btn-primary btn-md "> Back </a>
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
                        <form action="{!! route('hr.employee.store') !!}" enctype="multipart/form-data"
                              id="form_validation2" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Employee Data</h5>
                                    <div class="row mt-2">
                                        <div class="col-lg-4">
                                            <label for="name">Applicant Name <b>*</b></label>
                                            <input required
                                                   name="name"
                                                   id="name"
                                                   type="text"
                                                   class="form-control"
                                                   value="{{ old('name') ?? env('APP_ENV') == 'local' ? 'test name' : ''}}"
                                                   onkeydown="return /[a-zA-Z\s]/.test(event.key) || event.key == 'Backspace'"

                                                   maxlength="70"
                                                   title="Please enter alphabets only (maximum 70 characters)"/>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="name"> Father Name <b>*</b> </label>
                                            <input required name="father_name"
                                                   onkeydown="return /[a-zA-Z\s]/.test(event.key) || event.key == 'Backspace'"

                                                   maxlength="70" id="name" type="text"
                                                   class="form-control"
                                                   value="{{old('father_name')  ?? env('APP_ENV') == 'local' ? 'test father name' : ''}}"/>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="cnic_card"> CNIC <b>*</b> <span
                                                    class="small">(with (-) )</span></label>
                                            <input required name="cnic_card" id="cnic_card" type="text"
                                                   class="form-control cnic_card"
                                                   data-inputmask="'mask': '99999-9999999-9'"
                                                   placeholder="XXXXX-XXXXXXX-X" onchange="checkCNIC(this)"
                                                   value="{{ old('cnic_card') ?? env('APP_ENV') == 'local' ? '1234512345671' : ''}}"/>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-4 mt-2">
                                            <label for="image">Employee Picture</label>
                                            <input class="form-control" type="file" name="image" id="image">
                                        </div>
                                        <div class="col-lg-4 mt-2">
                                            <label for="image">Mobile No <b>*</b></label>
                                            <input class="form-control" pattern="[0-9]+"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   maxlength="20" required value="{{ old('mobile_no')  ?? env('APP_ENV') == 'local' ? '0321-1234567' : '' }}"
                                                   type="number" name="mobile_no"
                                                   id="mobile_no">
                                        </div>
                                        <div class="col-lg-4 mt-2">
                                            <label for="tell_no">Emeregency Contact Number <b>*</b></label>
                                            <input class="form-control" pattern="[0-9]+"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   maxlength="20" value="{{ old('tell_no')  ?? env('APP_ENV') == 'local' ? '0321-1234567' : '' }}" required
                                                   type="text" name="tell_no"
                                                   id="tell_no">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-4 mt-2">
                                            <label for="role_id">Role<b>*</b></label>
                                            <select class="form-control select2" name="role_id" id="role_id" required>
                                                <option value="">Select Role</option>    
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                                </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-4 mt-2">
                                            <label for="personal_email_address">Personal Email Address <b>*</b></label>
                                            <input class="form-control" required type="text"
                                                   id="personal_email_address" name="personal_email_address" value="{{ old('personal_email_address') ?? env('APP_ENV') == 'local' ? 'admin1@gmail.com' : '' }}">
                                        </div>
                                        <div class="col-lg-4 mt-2">
                                            <label for="email_address">Professional Email Address <b>*</b></label>
                                            <input class="form-control"  type="text"
                                                   value="{{ old('email_address') ?? env('APP_ENV') == 'local' ? 'admin1@gmail.com' : ''}}" name="email_address"
                                                   id="email_address" required>
                                        </div>


                                        <div class="col-lg-4 mt-2">
                                            <label for="email">Password</label>
                                            <input class="form-control" type="password" name="password" id="password" value="{{ old('password') ?? env('APP_ENV') == 'local' ? '12345678' : '' }}">
                                        </div>
                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-lg-12">
                                            <label for="email">Present Address <b>*</b></label>
                                            <input class="form-control" type="text" name="present_address"
                                                   id="present_address" value="{{ old('present_address') ?? env('APP_ENV') == 'local' ? 'present address' : '' }}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <label for="email">Permanent Address <b>*</b></label>
                                            <input class="form-control" value="{{ old('permanent_address') ?? env('APP_ENV') == 'local' ? 'permanent address' : ''}}" required
                                                   type="text" name="permanent_address"
                                                   id="permanent_address">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label for="dob">Date of Birth*</label>
                                            <div class="input-group">
                                                <div class="input-group-text ">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input required name="dob" class="form-control datePicker employee_dob"
                                                       id="datepicker-date" placeholder="MM/DD/YYYY"
                                                       type="text" value="{{ old('dob') ?? env('APP_ENV') == 'local' ? '01-01-2000' : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="gender">Gender*</label>
                                            <div class="input-group">
                                                <select required name="selectGender" class="form-control select2"
                                                        id="gender">
                                                    <option value="">Select Gender *</option>
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                    <option value="O">Other</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <label for="start-work">Date of joining?</label>
                                            <input type="date" id="startDate" class="form-control joiningDate"
                                                   name="start_date"
                                                   value="{{ now()->format('Y-m-d') }}">
                                        </div>

                                    </div>



                                    <div class="row" style="display: none">
                                        <div class="col-md-6">
                                            <label for="remainingSalary"><b>Remaining</b></label>
                                            <input type="number" name="deductedAmount" class="form-control"
                                                   id="deductedAmount">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="start-work"><b>Employee Welfare:</b></label>
                                            <input type="number" class="form-control"
                                                   name="employeeWelfare" id="employeeWelfare">
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="display: none">
                                        <label for="start-work"><b>Provident Fund:</b></label>
                                        <input type="number" class="form-control"
                                               name="providentFund" id="providentFund">
                                    </div>

                                    <div class="col-md-6" style="display: none">
                                        <label for="start-work"><b>Medical Allowance:</b></label>
                                        <input type="number" class="form-control" value="0"
                                            name="medicalAllowance" id="medical_allowance">
                                    </div>
                                    {{--                                <div class="row mt-3">--}}
                                    {{--                                    <label for="allowance_id"><b>Allowance</b></label>--}}
                                    {{--                                    @foreach($allowances as $allowance)--}}
                                    {{--                                        <div class="col-md-6">--}}

                                    {{--                                            <input type="text" name="allowance[]" class="form-control" value="{!! $allowance->type !!}" readonly style="margin-bottom: 20px">--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-md-6">--}}
                                    {{--                                            <input type="number" name="allowance_price[]" class="form-control allowance_price" value="0">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    @endforeach--}}
                                    {{--                                </div>--}}


                                    <div class="row mt-3">
                                        <div class="col-lg-12" style="margin-top: 15px">
                                            <label for="Education"><b>Education</b></label>
                                            <div class="row" id="education-fields-container">
                                                <div class="col-md-2">
                                                    <label for="institution">Institution</label>
                                                    <input type="text" name="education_institution[]"
                                                           class="form-control">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="year">Year</label>
                                                    <input type="text" name="year[]" maxlength="4"
                                                           class="form-control education_year">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="certification">Certification/Degree</label>
                                                    <input type="text" name="certification[]" class="form-control">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="cgpa">CGPA</label>
                                                    <input type="text" name="cgpa[]" class="form-control cgpa_feild"
                                                           onkeypress="CheckNumber(this)">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="specialization">Specialization</label>
                                                    <input type="text" name="specialization[]" class="form-control">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="files">Upload Files</label>
                                                    <input type="file" name="education_image[]" class="form-control"
                                                           multiple>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2 offset-md-10 mt-2">
                                                    <button id="add-more-btn" type="button" class="btn btn-primary">Add
                                                        more
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="Company-name">Company Name <b>*</b></label>
                                            <select id="companySelect" required name="companySelect"
                                                    class="form-select select2 basic-single mt-3"
                                                    aria-label=".form-select-lg example">
                                                @foreach($companies as $company)
                                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches">Branch Name <b>*</b></label>
                                            <select id="branchSelect" required name="branchSelect"
                                                    class="form-select select2 basic-single mt-3 branch_select"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Branch</option>
                                            </select>
                                        </div>


                                        <div class="col-md-4">
                                            <label for="student-id">Emp ID:</label>
                                            <input type="text" class="form-control emp-id" readonly   name="emp_id">
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <div class="form-group col-lg-12">
                                            <label for="otherBranchSelect">Select Other Branch</label>
                                            <select class="form-select select2 basic-single"
                                                    id="otherBranchSelect" aria-label="Default select example"
                                                    name="otherBranchSelect[]"
                                                    multiple="multiple">
                                                <option value="">Select Branch</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mt-2">

                                        <div class="col-lg-6 mt-2">
                                            <label for="departmentDropdown">Department<b>*</b></label>
                                            <select id="departmentDropdown" required name="departmentSelect"
                                                    class="form-select select2 basic-single departmentDropdown">
                                                <option>Select Department</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-6 mt-2">
                                            <label for="email">Designation <b>*</b></label>
                                            <select id="designationSelect" required name="designationSelect"
                                                    class="form-select select2 basic-single">
                                                <option>Select Designation</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-4" id="specialization_div" style="display: none">
                                        <div class="col-md-12">
                                            <label for="Work Shift">Specialization subject<b>*</b></label>
                                            <input type="text" class="form-control" name="specialization_subject">
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label for="Work Shift">Work Shift <b>*</b></label>
                                            <select id="shiftSelect" required name="workShift"
                                                    class="form-select select2 basic-single">
                                                <option>Select WorkShift</option>
                                                @foreach($workShifts as $workShift)
                                                    <option value="{{$workShift->id}}">{{$workShift->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="shiftSelect">Report To <b>*</b></label>
                                            <select id="shiftSelect"  name="employee_id"
                                                    class="form-select select2 basic-single">
                                                <option value="" select disabled>Report To</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-12 mt-4">
                                        <label><b>Job type?</b></label>
                                        <div class="row">
                                            {{--                                            @foreach($employeeTypes as $type)--}}
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="job_seeking"
                                                           type="radio"
                                                           value="probation" id="partTime">
                                                    <label class="form-check-label" for="partTime">
                                                        Probation
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="job_seeking"
                                                           type="radio"
                                                           value="Permanent" id="permanent">
                                                    <label class="form-check-label" for="permanent">
                                                        Permanent
                                                    </label>
                                                </div>
                                            </div>

                                            {{--                                            @endforeach--}}

                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="job_seeking"
                                                           type="radio"
                                                           value="VisitingLecturer" id="visitingLecturer">
                                                    <label class="form-check-label" for="visitingLecturer">
                                                        Visiting Faculty
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="job_seeking"
                                                           type="radio"
                                                           value="Offsite" id="offside">
                                                    <label class="form-check-label" for="offside">
                                                        Offsite
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12 mt-2" id="visitngContainer" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="visiting"><b>Working Hours</b></label>
                                                <input type="number" class="form-control working_hour"
                                                       name="working_hour"
                                                       id="working_hour">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="hour_salary"><b>Per Hour salary</b></label>
                                                <input type="number" class="form-control" placeholder="RS"
                                                       name="hour_salary"
                                                       id="hour_salary">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="margin-top: 15px">
                                        <label><b>Provident Fund Applicable?</b></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="provident_fund" value="1" id="providentFundYes">
                                                    <label class="form-check-label"
                                                           for="providentFundYes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="provident_fund" value="0" id="providentFundNo" checked>
                                                    <label class="form-check-label" for="providentFundNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top: 15px">
                                        <label><b>Applied before at cornerstone?</b></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="applied_before" value="Yes" id="appliedBeforeYes">
                                                    <label class="form-check-label"
                                                           for="appliedBeforeYes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="applied_before" value="No" id="appliedBeforeNo">
                                                    <label class="form-check-label" for="appliedBeforeNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2" id="appliedWhenContainer" style="display: none;">
                                        <label for="appliedWhen">If yes, When?</label>
                                        <input type="month" class="form-control" name="applied_when"
                                               id="appliedWhen">
                                    </div>
                                    <div class="col-md-12" style="margin-top: 15px">
                                        <label><b>Were you ever employed here?</b></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="employed_here" value="Yes" id="employedHereYes">
                                                    <label class="form-check-label"
                                                           for="employedHereYes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="employed_here" value="No" id="employedHereNo">
                                                    <label class="form-check-label" for="employedHereNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2" id="employedWhenContainer" style="display: none;">
                                        <label for="employedWhen">If yes, When?</label>
                                        <input type="text" class="form-control" value="{{old('employed_when')}}"
                                               name="employed_when"
                                               id="employedWhen">
                                    </div>
                                    <div class="col-md-12 " style="margin-top: 15px">
                                        <label><b>Engagements in any other business or employment?</b></label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="engaged_in_other_employment" value="Yes"
                                                           id="engagedYes">
                                                    <label class="form-check-label" for="engagedYes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="engaged_in_other_employment" value="No"
                                                           id="engagedNo">
                                                    <label class="form-check-label" for="engagedNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2" id="otherEmploymentDetailsContainer"
                                         style="display: none;">
                                        <label for="otherEmploymentDetails">If yes, please explain:</label>
                                        <textarea class="form-control" name="other_employment_details"
                                                  id="otherEmploymentDetails"></textarea>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label for="skills"><b>Skills or training related to the position?</b></label>
                                        <textarea class="form-control" name="skills" id="skills"
                                                  rows="4"></textarea>
                                    </div>
                                    @php
                                        $index = 0;
                                    @endphp
                                    <div class="col-lg-12" style="margin-top: 15px">
                                        <label for="work-experince"><b>Work Experince</b></label>
                                        <div class="row" id="work-experience-fields-container">
                                            <div class="col-md-2">
                                                <label for="sno">S.No</label>
                                                <input type="text" value="{{$index +1}}" name="work_sno[]"
                                                       class="form-control">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="institution">Name of Institution</label>
                                                <input type="text" name="work_institution[]" class="form-control">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="designation">Designation</label>
                                                <input type="text" name="designation[]" class="form-control">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="duration">Duration</label>
                                                <select name="duration[]" class="form-select select2 basic-single"
                                                        id="ageDropdown">
                                                    <option> select Duration</option>
                                                    <option value="0-6months">0-6 months</option>
                                                    <option value="6months-1year">6 months - 1 year</option>
                                                    <option value="1-2years">1-2 years</option>
                                                    <option value="2-3years">2-3 years</option>
                                                    <option value="3+years">3+ years</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="from">From</label>
                                                <input type="date" name="from[]" class="form-control"
                                                       onclick="CheckDate(this)">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="till">To</label>
                                                <input type="date" name="till[]" class="form-control"
                                                       onclick="CheckDate(this)">
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-2 offset-md-10 mt-2">
                                                <button id="add-more-work-btn" type="button"
                                                        class="btn btn-primary">Add more
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12 mt-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="nationality">Nationality</label>
                                                <select name="nationality" id="nationality"
                                                        class="form-control select2">
                                                    <option value="">--- Select One ---</option>
                                                    @foreach($nationalities as $nationality)
                                                        <option value="{{ $nationality }}">{{ $nationality }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="religion">Religion</label>
                                                <select name="religion" id="religion" class="form-control select2">
                                                    <option value="">--- Select One ---</option>
                                                    @foreach($religions as $religion)
                                                        <option value="{{ $religion }}">{{ $religion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="blood_group">Blood Group</label>
                                                <select name="blood_group" id="blood_group"
                                                        class="form-control select2">
                                                    <option value="">--- Select One ---</option>
                                                    @foreach($bloodGroups as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="marital_status">Marital Status</label>
                                                <select name="marital_status" class="form-control select2">
                                                    <option value="Married">Married</option>
                                                    <option value="Unmarried">Unmarried</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-12" style="margin-top:20px">
                                        <label for="family-info"><b>Family Information (Specific Dependents
                                                Only)</b></label>
                                        <div class="row mt-3" id="family-fields-container">
                                            <div class="col-md-3">
                                                <label for="sr_no">Sr. No</label>
                                                <input type="text" name="sr_no[]" value="{{$index +1}}"
                                                       class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="name">Name</label>
                                                <input type="text" name="family_name[]" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="relation">Relation</label>
                                                <input type="text" name="relation[]" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="dob">DOB</label>
                                                <input type="date" name="family_dob[]" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="gender">Gender</label>
                                                <select name="gender[]" class="form-select">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">

                                                <label for="cnic_card"> CNIC <span
                                                        class="small">(with (-) )</span></label>
                                                <input name="cnic[]" id="cnic_card" type="text"
                                                       class="form-control cnic_card"
                                                       data-inputmask="'mask': '99999-9999999-9'"
                                                       placeholder="XXXXX-XXXXXXX-X" onchange="checkCNIC(this)"
                                                       name="cnic_card"
                                                />
                                            </div>

                                            <div class="col-md-3">
                                                <label for="workplace">Workplace/Institution</label>
                                                <input type="text" name="workplace[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-3 offset-md-9">
                                                <button id="add-family-btn" type="button" class="btn btn-primary">
                                                    Add more
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top:20px">
                                        <label for="family-info"><b>Is your child a student at Cornerstone?</b></label>

                                        <div id="student-fields-wrapper">
                                            <!-- Template (to be cloned) -->
                                            <div class="row mt-3 student-fields">
                                                <div class="col-md-4">
                                                    <label>Company Name <b>*</b></label>
                                                    <select class="form-select select2 company-select" data-branch=".branch-select">
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Branch Name </label>
                                                    <select class="form-select select2 branch-select" data-class=".class-select">
                                                        <option value="" disbaled>Select Branch</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Class Name </label>
                                                    <select class="form-select select2 class-select" data-section=".section-select">
                                                        <option value="" disbaled>Select Class</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Section Name </label>
                                                    <select class="form-select select2 section-select" data-student=".student-select">
                                                        <option value="" disabled>Select Section</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label>Student </label>
                                                    <select class="form-select select2 student-select" name="student_id[]">
                                                        <option value="" disabled>Select Student</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-3 offset-md-9">
                                                <button id="add-family-btn2" type="button" class="btn btn-primary">
                                                    Add more
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
@section('js')

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>

    <script>
        $('#datepicker-date').bootstrapdatepicker({
            format: "dd-mm-yyyy",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });
        // $('#form_validation2').validate()
        // $('#form_validation2').on('submit', function (e){
        //     console.log("🚀 ~ e>>", e)
        //     e.preventDefault();
        //     return false;
        // })

        </script>
    <script>
        var checkCNIC = function (textBox) {
            var regexp = new RegExp('^[0-9]{5}-[0-9]{7}-[0-9]{1}$');
            var check = textBox.value;

            if (!regexp.test(check)) {

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
        };
    </script>
    <script>
$(document).ready(function () {
    function bindStudentFieldEvents($context) {

        // Helper function to reset select options
        function resetSelect($select, placeholder = 'Select') {
            $select.empty().append(`<option value="">${placeholder}</option>`);
        }

        $context.find('.company-select').off().on('change', function () {
            const companyId = $(this).val();
            const $branchSelect = $context.find($(this).data('branch'));
            const $classSelect = $context.find('.class-select');
            const $sectionSelect = $context.find('.section-select');
            const $studentSelect = $context.find('.student-select');

            // Reset all dependent fields
            resetSelect($branchSelect, 'Select Branch');
            resetSelect($classSelect, 'Select Class');
            resetSelect($sectionSelect, 'Select Section');
            resetSelect($studentSelect, 'Select Student');

            if (!companyId) return;

            $.ajax({
                type: 'GET',
                url: '{{ route('hr.fetch.branches') }}',
                data: { companyid: companyId },
                success: function (branches) {
                    branches.forEach(branch => {
                        $branchSelect.append(`<option value="${branch.id}">${branch.name}</option>`);
                    });
                }
            });
        });

        $context.find('.branch-select').off().on('change', function () {
            const branchId = $(this).val();
            const $classSelect = $context.find($(this).data('class'));
            const $sectionSelect = $context.find('.section-select');
            const $studentSelect = $context.find('.student-select');

            resetSelect($classSelect, 'Select Class');
            resetSelect($sectionSelect, 'Select Section');
            resetSelect($studentSelect, 'Select Student');

            if (!branchId) return;

            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchClass') }}',
                data: { branch_id: branchId },
                success: function (classes) {
                    classes.forEach(cls => {
                        $classSelect.append(`<option value="${cls.id}">${cls.name}</option>`);
                    });
                }
            });
        });

        $context.find('.class-select').off().on('change', function () {
            const classId = $(this).val();
            const $sectionSelect = $context.find($(this).data('section'));
            const $studentSelect = $context.find('.student-select');

            resetSelect($sectionSelect, 'Select Section');
            resetSelect($studentSelect, 'Select Student');

            if (!classId) return;

            $.ajax({
                type: 'GET',
                url: '{{ route('fetch-section') }}',
                data: { class_id: classId },
                success: function (sections) {
                    sections.forEach(section => {
                        $sectionSelect.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                }
            });
        });

        $context.find('.section-select').off().on('change', function () {
            const sectionId = $(this).val();
            const classId = $context.find('.class-select').val();
            const branchId = $context.find('.branch-select').val();
            const $studentSelect = $context.find($(this).data('student'));

            resetSelect($studentSelect, 'Select Student');

            if (!sectionId || !classId || !branchId) return;

            $.ajax({
                type: 'GET',
                url: '{{ route('fetch-students') }}',
                data: {
                    branch_id: branchId,
                    class_id: classId,
                    section_id: sectionId,
                },
                success: function (students) {
                    students.forEach(student => {
                        $studentSelect.append(`<option value="${student.id}">${student.first_name} ${student.last_name}</option>`);
                    });
                }
            });
        });

        $context.find('.select2').select2({ width: '100%' });
    }

    bindStudentFieldEvents($('#student-fields-wrapper .student-fields').first());
    $('#student-fields-wrapper .student-fields').first().find('.company-select').val('').trigger('change');

    $('#add-family-btn2').on('click', function () {
        let $template = $('#student-fields-wrapper .student-fields').first();
        let $clone = $template.clone(false, false);

        $clone.find('select').each(function () {
            $(this).val('');
            $(this).removeAttr('data-select2-id').removeClass('select2-hidden-accessible');
            $(this).next('.select2').remove(); // remove select2 container
        });

        $('#student-fields-wrapper').append($clone);
        bindStudentFieldEvents($clone);
    });
});
</script>

    <script>
        $(document).ready(function () {
            let counter = 1;

            $("#myForm").validate({})


            function addMoreFields() {
                const educationFieldsContainer = $('#education-fields-container');
                const dataId = `data-id="${counter}"`;

                educationFieldsContainer.append(`
                <div class="form-row mt-1">
                    <div class="col-md-2 position-relative" ${dataId}>
                        <label for="institution">Institution</label>
                        <input type="text" name="education_institution[]" class="form-control">
                    </div>

                    <div class="col-md-2" ${dataId}>
                        <label for="year">Year</label>
                        <input type="text" name="year[]"  maxlength = "4" class="form-control education_year">
                    </div>

                    <div class="col-md-2" ${dataId}>
                        <label for="certification">Certification/Degree</label>
                        <input type="text" name="certification[]" class="form-control">
                    </div>

                    <div class="col-md-2" ${dataId}>
                        <label for="cgpa">CGPA</label>
                        <input type="text" name="cgpa[]" class="form-control cgpa_feild" onkeypress="CheckNumber(this)">
                    </div>

            <div class="col-md-2 position-relative" ${dataId}>
                <label for="specialization" style="margin-right: 25px;">Specialization</label>
                <input type="text" name="specialization[]" class="form-control">
             </div>

        <div class="col-md-2 position-relative" ${dataId}>
            <label for="files" style="margin-right: 25px;">Upload Files</label>
            <input type="file" name="education_image[]" multiple class="form-control">
            <i class="fa fa-trash remove-btn" aria-hidden="true" data-id="${counter}" style="position: absolute; top: 5px; right: 0; cursor: pointer; color: red; font-size: 20px;"></i>
        </div>
                </div>
            `);
                counter++;
            }

            $('#add-more-btn').on('click', function () {
                addMoreFields();
            });

            $(document).on('click', '.remove-btn', function () {
                const dataId = $(this).data('id');
                $(`.col-md-2[data-id="${dataId}"]`).remove();
            });
        });
    </script>


    <script>
        $(document).ready(function () {

            $('#companySelect').on('change', function () {
                var selectedCompanyId = $('#companySelect').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var branchesDropdown = $('#branchSelect').empty();
                        var otherBranchesDropdown = $('#otherBranchSelect').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {
                            branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                            otherBranchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change(); // Trigger change event on page load

            $('#branchSelect').on('change', function () {
                var selectedCompanyId = $('#companySelect').val();
                // $('#companySelect').trigger('change');
                var selectedBranchId = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var otherBranchesDropdown = $('#otherBranchSelect').empty();

                        data.forEach(function (branch) {
                            otherBranchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                        $('#otherBranchSelect option[value="' + selectedBranchId + '"]').remove();
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            });
        });

    </script>


    <script>
        $(document).ready(function () {
            let workCounter = 1;

            function addMoreWorkFields() {
                const workExperienceFieldsContainer = $('#work-experience-fields-container');
                const workDataId = `data-id="${workCounter}"`;

                workExperienceFieldsContainer.append(`
                <div class="col-md-2" ${workDataId}>
                    <label for="sno">S.No</label>
                    <input type="text" name="work_sno[]" value="${workCounter + 1}" class="form-control">
                </div>

                <div class="col-md-2" ${workDataId}>
                    <label for="institution">Name of Institution</label>
                    <input type="text" name="work_institution[]" class="form-control">
                </div>

                <div class="col-md-2" ${workDataId}>
                    <label for="designation">Designation</label>
                    <input type="text" name="designation[]" class="form-control">
                </div>

                <div class="col-md-2" ${workDataId}>
                    <label for="duration">Duration</label>
                    <select name="duration[]"  class="form-select select2 basic-single" id="durationDropdown">
                            <option> select Duration </option>
                             <option value="0-6months">0-6 months</option>
                             <option value="6months-1year">6 months - 1 year</option>
                               <option value="1-2years">1-2 years</option>
                                <option value="2-3years">2-3 years</option>
                                  <option value="3+years">3+ years</option>
                       </select>
                </div>

                <div class="col-md-2" ${workDataId}>
                    <label for="from">From</label>
                    <input type="date" name="from[]" class="form-control" onclick="CheckDate(this)">
                </div>

                <div class="col-md-2" ${workDataId}>
                    <label for="till">To</label>
                        <input type="date" name="till[]" class="form-control" onclick="CheckDate(this)">
                        <i class="fa fa-trash remove-btn" aria-hidden="true" data-id="${workCounter}" style="position: absolute; top: 5px; right: 0; cursor: pointer; color: red; font-size: 20px;"></i>
                </div>
            `);
                workCounter++;
            }

            $('#add-more-work-btn').on('click', function () {
                addMoreWorkFields();
            });

            $(document).on('click', '.remove-work-icon', function () {
                const workDataId = $(this).data('id');
                $(`.col-md-2[data-id="${workDataId}"]`).remove();
            });
        });
    </script>

    <script>
        var CheckNumber = function (input) {
            var inputValue = input.value;
            if (!/^-?\d{0,1}([.,]?\d{0,1})?$/.test(inputValue)) {
                input.value = inputValue.slice(0, -1);
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            let familyCounter = 1;

            function addMoreFamilyFields() {
                const familyFieldsContainer = $('#family-fields-container');
                const dataId = `data-id="${familyCounter}"`;
                familyFieldsContainer.append(`
                <div class="row mt-2">
                    <div class="col-md-3" ${dataId}>
                        <label for="sr_no">Sr. No</label>
                        <input type="text" name="sr_no[]" value="${familyCounter + 1}" class="form-control">
                    </div>

                    <div class="col-md-3" ${dataId}>
                        <label for="name">Name</label>
                        <input type="text" name="family_name[]" class="form-control">
                    </div>

                    <div class="col-md-3" ${dataId}>
                        <label for="relation">Relation</label>
                        <input type="text" name="relation[]" class="form-control">
                    </div>

                    <div class="col-md-3" ${dataId}>
                        <label for="dob">DOB</label>
                        <input type="date" name="family_dob[]" class="form-control">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3" ${dataId}>
                        <label for="gender">Gender</label>
                        <select name="gender[]" class="form-select">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="col-md-3" ${dataId}>
                            <label for="cnic_card"> CNIC <b>*</b> <span class="small">(with (-) )</span></label>
                                 <input required name="cnic[]" id="cnic_card" type="text" name="cnic_card"
                                      class="form-control cnic_card" data-inputmask="'mask': '99999-9999999-9'"  placeholder="XXXXX-XXXXXXX-X" onchange="checkCNIC(this)" value="{{old('cnic_card')}}"/>

                    </div>
            <div class="col-md-3" ${dataId} style="position: relative;">
                <label for="workplace">Workplace/Institution</label>
                <input type="text" name="workplace[]" class="form-control">
                 <i class="fa fa-trash remove-family-btn" aria-hidden="true" data-id="${familyCounter}" style="position: absolute; top: 0; right: 10px; cursor: pointer; color: red; font-size: 20px; margin-bottom: 25px;"></i>
            </div>
                </div>
            `);
                familyCounter++;
            }

            $('#add-family-btn').on('click', function () {
                addMoreFamilyFields();
            });

            $(document).on('click', '.remove-family-btn', function () {
                const dataId = $(this).data('id');
                $(`.col-md-3[data-id="${dataId}"]`).closest('.row').remove();
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $('input[name="applied_before"]').change(function () {
                if ($(this).val() === 'Yes') {
                    $('#appliedWhenContainer').show();
                } else {
                    $('#appliedWhenContainer').hide();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('input[name="job_seeking"]').change(function () {

                if ($(this).val() === 'VisitingLecturer') {
                    $('#visitngContainer').show();
                } else {
                    $('#visitngContainer').hide();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('input[name="employed_here"]').change(function () {
                if ($(this).val() === 'Yes') {
                    $('#employedWhenContainer').show();
                } else {
                    $('#employedWhenContainer').hide();
                }
            });
        });
    </script>

    <script>

        var CheckDate = function (check) {
            var currentDate = new Date().toISOString().split('T')[0];
            $(check).attr('max', currentDate);
        }

    </script>

    <script>
        $(document).ready(function () {
            $('input[name="engaged_in_other_employment"]').change(function () {
                if ($(this).val() === 'Yes') {
                    $('#otherEmploymentDetailsContainer').show();
                } else {
                    $('#otherEmploymentDetailsContainer').hide();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#companySelect').on('change', function () {
                // var branch_id = $(this).val();
                var selectedCompanyId = $('#companySelect').val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.departments') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {

                        var departmentDropdown = $('#departmentDropdown').empty();

                        departmentDropdown.append('<option>Select Department</option>');

                        $.each(data, function (index, department) {
                            departmentDropdown.append('<option value="' + department.id + '">' + department.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }


                });
            }).change();
        });
    </script>


    <script>
        $(document).ready(function () {
            $('#departmentDropdown').on('change', function () {
                var department_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.designations') }}',
                    data: {
                        department_id: department_id
                    },
                    success: function (data) {
                        var designationDropdown = $('#designationSelect').empty();
                        designationDropdown.append('<option>Select Designation</option>');

                        data.forEach(function (designation) {
                            designationDropdown.append('<option value="' + designation.id + '">' + designation.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }


                });
            });
        });
    </script>




    <script>
        $(document).ready(function () {

            $('#gross_salary').on('input', function () {

                var grossSalary = parseFloat($(this).val());

                var basicSalary = parseFloat($('#basic_salary').val());

                var medicalAllowance = grossSalary - basicSalary;
                console.log("medicalAllowance", medicalAllowance);
                $('#medical_allowance').val(medicalAllowance);

            });
        });

    </script>

    <script>
        $(document).ready(function () {
            calculateEmployeeWelfare();

            $('#gross_salary').on('input', function () {
                calculateEmployeeWelfare();
            });

            function calculateEmployeeWelfare() {
                var grossSalary = $('#gross_salary').val();
                var netSalary = $('#basic_salary').val();
                let deductedAmount = grossSalary - netSalary;
                $('#deductedAmount').val(deductedAmount);
                let times = {!! $employeeWelfare !!};
                let welfareAmount = Math.floor(deductedAmount * times);
                $('#employeeWelfare').val(welfareAmount);
            }
        });
    </script>


    <script>
        $(document).ready(function () {
            calculateProvidentFund();
            $('#gross_salary').on('input', function () {
                calculateProvidentFund();
            });

            function calculateProvidentFund() {
                var grossSalary = $('#gross_salary').val();
                var providentfund = {!! $provitendFund !!} / 100;
                var providentPrice = Math.floor(grossSalary * providentfund);
                $('#providentFund').val(providentPrice);

            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.datePicker, #startDate').on('change', function () {
                var dob = $('.datePicker').val();
                var joiningDate = $('.joiningDate').val();

                if (dob && joiningDate) {
                    var dobParts = dob.split('-');
                    var joiningDateParts = joiningDate.split('-');

                    var dobObj = new Date(dobParts[2], dobParts[1] - 1, dobParts[0]);
                    var joiningDateObj = new Date(joiningDateParts[0], joiningDateParts[1] - 1, joiningDateParts[2]);

                    if (joiningDateObj <= dobObj) {
                        toastr.warning("Joining Date should be greater than DOB");
                        $('.joiningDate').val('');
                    }
                }
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            $('.education_year').on('input', function () {
                let education_year = parseInt($(this).val());
                let join_date = new Date($('#startDate').val());
                let join_year = join_date.getFullYear();

                if (education_year > join_year) {
                    toastr.error("Must be greater than join Date");
                    $(this).val('');
                }
            })
        })
    </script>


    <script>
        $(".cnic_card").inputmask();

    </script>

    <script>
        $(document).ready(function () {
            $('#designationSelect').on('change', function () {
                let department_name = $(this).find('option:selected').text();
                if (department_name == 'Teacher') {
                    $('#specialization_div').show();
                } else {
                    $('#specialization_div').hide();
                }
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            $('.working_hour').on('input', function () {
                var workingHour = $(this).val();
                var grossSalary = parseFloat($('.gross_salary').val());
                if (isNaN(grossSalary)) {
                    toastr.error('Please enter a valid gross salary.');
                    $('.working_hour').val('');
                    return;
                }
                var perHourSalary = Math.round(grossSalary / workingHour);
                $('#hour_salary').val(perHourSalary);
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            function isUnderAge(dob) {
                var currentDate = new Date();

                var parts = dob.split('-');
                var dobDate = new Date(parts[2], parts[1] - 1, parts[0]);

                if (dobDate >= currentDate) {
                    toastr.warning("Invalid date of birth. Please enter a date in the past.");
                    $('.employee_dob').val('');
                    return;
                }

                var age = currentDate.getFullYear() - dobDate.getFullYear();
                var monthDifference = currentDate.getMonth() - dobDate.getMonth();
                var dayDifference = currentDate.getDate() - dobDate.getDate();

                if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                    age--;
                }


                if (age < 18) {
                    toastr.error("Employee is under age 18 and cannot be added.");
                    $('.employee_dob').val('');
                }
            }

            $('.employee_dob').on('change', function () {
                var employeeDob = $(this).val();
                isUnderAge(employeeDob);
            });
        });



    </script>



    <script>
        $(document).ready(function () {
            $('.branch_select').on('change', function () {
                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchEmpNo') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        if (data.empId) {
                            $('.emp-id').val(data.empId);
                        } else {
                            console.error('Error: new Emp ID not returned.');
                        }
                    },
                    error: function (error) {
                        if (error.status === 404) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Branch not found. Please add a branch code.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.student-id').val('');
                                }
                            });
                        } else {
                            console.error('Error fetching new Emp ID:', error);
                        }
                    }

                });
            });
        });


    </script>


@endsection
