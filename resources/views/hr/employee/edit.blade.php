@extends('admin.layouts.main')

@section('title')
    Employee Edit
@stop

@section('content')
    @php
        //    for welfare

        $employeeWelfare = 0;
    $employeeWelfareValue = \App\Helpers\GeneralSettingsHelper::getSetting('employeeWelfare');
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
        $index = 0;
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Employee</h3>
                        <div class="row mt-4 ">
                            <div class="col-md-12">
                                <div class="col-md-6 text-right">
                                    <a href="{!! route('hr.employee.index') !!}" class="btn btn-primary btn-md ">
                                        Back </a>
                                </div>
                                <div class="col-md-6" style="margin-left: 850px!important;">
                                    @if ($employee->employee_profile)
                                        <img src="{{ asset('employee_profile_picture/' . $employee->employee_profile) }}"
                                             alt="Employee Profile Image"
                                             class="img-fluid"
                                             style="max-width: 250px; max-height: 250px;">

                                    @else
                                        <img src="{{ asset('path_to_placeholder_image.jpg') }}" alt="Placeholder Image"
                                             class="img-fluid">
                                    @endif
                                </div>
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
                        <form action="{!! route('hr.employee.update',$employee->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('PUT')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Employee Data</h5>
                                    <div class="row mt-2">
                                        <div class="col-lg-4">
                                            <label for="name"> <b>Applicant Name</b> </label>
                                            <input name="name" id="name" type="text"
                                                   onkeydown="return /[a-z]/i.test(event.key)" class="form-control"
                                                   value="{{$employee->name}}"/>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="name"> <b>Father Name</b> </label>
                                            <input name="father_name" id="name"
                                                   onkeydown="return /[a-z]/i.test(event.key)" type="text"
                                                   class="form-control"
                                                   value="{{$employee->father_name}}"/>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="cnic"> <b>CNIC</b> <span
                                                    class="small">(with (-) )</span></label>
                                            <input name="cnic_card" id="cnic_card" type="text"
                                                   class="form-control cnic_card"
                                                   data-inputmask="'mask': '99999-9999999-9'"
                                                   placeholder="XXXXX-XXXXXXX-X" onchange="checkCNIC(this)"
                                                   value="{{$employee->cnic_card}}"/>
                                        </div>
                                    </div>
                                    <div class="row mt-2">

                                        <div class="col-lg-4 mt-2">
                                            <label for="image"><b>Employee Picture</b></label>
                                            <input class="form-control" type="file" name="image" id="image">

                                        </div>

                                        <div class="col-lg-4 mt-2">
                                            <label for="image"><b>Mobile No</b></label>
                                            <input class="form-control" type="number" value="{{$employee->mobile_no}}"
                                                   name="mobile_no" id="mobile_no">
                                        </div>
                                        <div class="col-lg-4 mt-2">
                                            <label for="tell_no"><b>Emeregency Contact Number</b></label>
                                            <input class="form-control" type="number" value="{{$employee->tell_no}}"
                                                   name="tell_no" id="tell_no">
                                        </div>
                                    </div>
                                    <div class="row mt-2">

                                        <div class="col-lg-4 mt-2">
                                            <label for="email"><b>Personal Email Address
                                                </b></label>
                                            <input class="form-control"  type="email"
                                                   name="personal_email_address" value="{!! $employee->personal_email_address !!}"
                                            >
                                        </div>


                                        <div class="col-lg-4 mt-2">
                                            <label for="email"><b>Professional Email Address </b></label>
                                            <input class="form-control" readonly type="email"
                                                   value="{{$employee->email_address}}"
                                                   name="email_address"
                                                   id="email_address">
                                        </div>
                                        {{--                                        @dd($employee)--}}
                                        <div class="col-md-4 mt-2">
                                            <label for="gender">Gender</label>
                                            <div class="input-group">
                                                <select  name="selectGender" class="form-control select2" id="gender">
                                                    <option value="">Select Gender</option>
                                                    <option
                                                        value="M" {{$employee->gender == 'M' ? 'selected' : ''}}>
                                                        Male
                                                    </option>
                                                    <option
                                                        value="F" {{$employee->gender == 'F' ? 'selected' : ''}}>
                                                        Female
                                                    </option>
                                                    <option
                                                        value="other" {{$employee->gender == 'O' ? 'selected' : ''}}>
                                                        Other
                                                    </option>
                                                </select>
                                            </div>
                                        </div>


                                        {{--                                        <div class="col-lg-6 mt-2">--}}
                                        {{--                                            <label for="email">Roles<b>*</b></label>--}}
                                        {{--                                            <select id="roleselect" name="roleSelect" class="form-select  mb-3"--}}
                                        {{--                                                    aria-label=".form-select-lg example">--}}
                                        {{--                                                @foreach($roles as $role)--}}
                                        {{--                                                    <option value="{{$role->id}}">{{$role->name}}</option>--}}
                                        {{--                                                @endforeach--}}
                                        {{--                                            </select>--}}
                                        {{--                                        </div>--}}

                                        {{--                                        <div class="col-lg-6 mt-2">--}}
                                        {{--                                            <label for="email">Password<b>*</b></label>--}}
                                        {{--                                            <input class="form-control" type="text" name="password"--}}
                                        {{--                                                   id="password">--}}
                                        {{--                                        </div>--}}


                                        <div class="col-lg-12 mt-2">
                                            <label for="email"><b> Present Address </b></label>
                                            <input class="form-control" type="text"
                                                   value="{{$employee->permanent_address}}"
                                                   name="present_address"
                                                   id="present_address">
                                        </div>

                                        <div class="col-lg-12 mt-2">
                                            <label for="email"> <b>Permanent Address</b></label>
                                            <input class="form-control" type="text"
                                                   value="{{$employee->permanent_address}}"
                                                   name="permanent_address"
                                                   id="permanent_address">
                                        </div>

                                        <div class="col-lg-6 mt-2">
                                            <label for="dob"><b>Date of Birth</b></label>
                                            <div class="input-group">
                                                <div class="input-group-text ">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input name="dob" class="form-control datePicker"
                                                       id="datepicker-date" placeholder="MM/DD/YYYY"
                                                       type="text" value="{{$employee->dob}}">
                                            </div>

                                        </div>

                                        <div class="col-lg-6 mt-2">
                                            <label for="start-work"><b>Date of joining?</b></label>
                                            <input type="date" id="startDate" class="form-control joiningDate"
                                                   name="start_date"
                                                   value="{!! $employee->start_date !!}">
                                        </div>

                                      

                                        <div class="row" style="display: none">
                                            <div class="col-md-6">
                                                <label for="remainingSalary"><b>Remaining</b></label>
                                                <input type="number"
                                                       value="{!! $employee->employeeWelfare->deducted_amount ?? ''!!}"
                                                       name="deductedAmount" class="form-control"
                                                       id="deductedAmount">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="start-work"><b>Employee Welfare:</b></label>
                                                <input type="number"
                                                       value="{!! $employee->employeeWelfare->welfare_amount ?? ''!!}"
                                                       class="form-control"
                                                       name="employeeWelfare" id="employeeWelfare">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="start-work"><b>Provident Fund:</b></label>
                                                <input type="number"
                                                       value="{!! $employee->providentFund->providentFund ?? ''!!}"
                                                       class="form-control"
                                                       name="providentFund" id="providentFund">
                                            </div>
                                        </div>




                                        {{--                                        <div class="row mt-3">--}}
                                        {{--                                            <label for="allowance_id"><b>Allowance</b></label>--}}
                                        {{--                                            @foreach($employeeAllowances as $allowance)--}}
                                        {{--                                                <div class="col-md-6">--}}

                                        {{--                                                    <input type="text" name="allowance[]" class="form-control" value="{!! $allowance->allowance !!}" readonly style="margin-bottom: 20px">--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-md-6">--}}
                                        {{--                                                    <input type="number" name="allowance_price[]" class="form-control allowance_price" value="{!! $allowance->allowance_price !!}">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            @endforeach--}}
                                        {{--                                        </div>--}}


                                        <div class="col-lg-12" style="margin-top: 15px">
                                            <label for="Education"><b>Education</b></label>
                                            @if (($employee->educations)->isNotEmpty())
                                                <div class="row" id="education-fields-container">
                                                    @foreach($employee->educations as $education)
                                                        @php($count=$education->id)
                                                        <div class="col-md-2" data-id="{!! $education->id !!}">
                                                            <label for="institution">Institution</label>
                                                            <input type="text" name="education_institution[]"
                                                                   class="form-control"
                                                                   value="{{ $education['institution'] }}">
                                                        </div>

                                                        <div class="col-md-2" data-id="{!! $education->id !!}">
                                                            <label for="year">Year</label>
                                                            <input type="text" name="year[]" class="form-control"
                                                                   value="{{ $education['year'] }}">
                                                        </div>

                                                        <div class="col-md-2" data-id="{!! $education->id !!}">
                                                            <label for="certification">Certification/Degree</label>
                                                            <input type="text" name="certification[]"
                                                                   class="form-control"
                                                                   value="{{ $education['certification'] }}">
                                                        </div>

                                                        <div class="col-md-2" data-id="{!! $education->id !!}">
                                                            <label for="cgpa">CGPA</label>
                                                            <input type="text" name="cgpa[]" class="form-control"
                                                                   value="{{ $education['cgpa'] }}"
                                                                   onkeypress="CheckNumber(this)">
                                                        </div>

                                                        <div class="col-md-2" data-id="{!! $education->id !!}">
                                                            <label for="specialization">Specialization</label>
                                                            <input type="text" name="specialization[]"
                                                                   class="form-control"
                                                                   value="{{ $education['Specialization'] }}">
                                                        </div>

                                                        <div class="col-md-2" data-id="{!! $education->id !!}">
                                                            <label for="files">Upload Files</label>
                                                            <input type="file" name="education_image[]"
                                                                   class="form-control"
                                                                   multiple>
                                                            <i class="fa fa-trash remove-btn" aria-hidden="true"
                                                               data-id="{!! $education->id !!}"
                                                               style="position: absolute; top: 5px; right: 0; cursor: pointer; color: red; font-size: 20px;"></i>
                                                        </div>
                                                    @endforeach
                                                    @php($count++)
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10 mt-2">
                                                        <button id="add-more-btn" type="button"
                                                                class="btn btn-primary">
                                                            Add
                                                            more
                                                        </button>
                                                    </div>
                                                </div>

                                            @else
                                                <div class="row" id="education-fields-container">
                                                    <div class="col-md-2">
                                                        <label for="institution">Institution</label>
                                                        <input type="text" name="education_institution[]"
                                                               class="form-control">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="year">Year</label>
                                                        <input type="text" name="year[]" class="form-control">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="certification">Certification/Degree</label>
                                                        <input type="text" name="certification[]"
                                                               class="form-control">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="cgpa">CGPA</label>
                                                        <input type="text" name="cgpa[]"
                                                               class="form-control cgpa_feild"
                                                               onkeypress="CheckNumber(this)">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="specialization">Specialization</label>
                                                        <input type="text" name="specialization[]"
                                                               class="form-control">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="files">Upload Files</label>
                                                        <input type="file" name="education_image[]"
                                                               class="form-control"
                                                               multiple>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10 mt-2">
                                                        <button id="add-more-btn" type="button"
                                                                class="btn btn-primary">
                                                            Add
                                                            more
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>


                                        <div class="col-md-4">
                                            <label for="Company-name"><b>Company Name</b></label>
                                            <select id="companySelect" name="companySelect"
                                                    class="form-select select2 basic-single"
                                                    aria-label=".form-select-lg example">
                                                @foreach($companies as $company)
                                                    <option
                                                        value="{{ $company->id }}" {{ $employee->company_id == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-4">
                                            <label for="branches"><b>Branch Name</b></label>
                                            <select id="branchSelect" name="branchSelect"
                                                    class="form-select select2 basic-single branch_select"
                                                    aria-label=".form-select-lg example">


                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="student-id">Emp ID:</label>
                                            <input type="text" class="form-control emp-id"  value="{!! $employee->emp_id ?? '' !!}"  name="emp_id">
                                        </div>

                                        @php($selectedbranch = $employee->Otherbranch()->pluck('branch_id')->toArray())

                                        <div class="col-md-12 mt-3">
                                            <label for="otherBranches"><b>Other Branches</b></label>
                                            <select class="form-select select2 basic-single"
                                                    id="otherBranchSelect" aria-label="Default select example"
                                                    name="otherBranchSelect[]"
                                                    multiple="multiple">
                                                {{--                                                @foreach($branches as $branch)--}}
                                                {{--                                                    <option--}}
                                                {{--                                                        value="{{ $branch->id }}" {!! in_array($branch->id, $selectedbranch) ? 'selected' : '' !!}>--}}
                                                {{--                                                        {{ $branch->name }}--}}
                                                {{--                                                    </option>--}}
                                                {{--                                                @endforeach--}}
                                            </select>
                                        </div>


                                        <div class="row mt-2">

                                            <div class="col-lg-6 mt-2">
                                                <label for="email">Department</label>
                                                <select id="departmentDropdown" name="departmentSelect"
                                                        class="form-select select2 basic-single">

                                                </select>
                                            </div>

                                            <div class="col-lg-6 mt-2">
                                                <label for="email">Designation </label>
                                                <select id="designationSelect" name="designationSelect"
                                                        class="form-select select2 basic-single">


                                                </select>
                                            </div>
                                        </div>
                                        @if($designationName == "Teacher")
                                            <div class="row mt-4" id="specialization_div">
                                                <div class="col-md-12">
                                                    <label for="Work Shift">Specialization subject <b>*</b></label>
                                                    <input type="text" class="form-control"
                                                           value="{!! $employee->specialization_subject !!}"
                                                           name="specialization_subject">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <label for="Work Shift">Work Shift <b>*</b></label>
                                                <select id="shiftSelect" name="workShift"
                                                        class="form-select select2 basic-single">
                                                    <option>Select WorkShift</option>
                                                    @foreach($workShifts as $workShift)
                                                        <option
                                                            value="{{$workShift->id}}" {{$employee->work_shift_id == $workShift->id ? 'selected' : ''}}>{{$workShift->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="reportTo">Report To <b>*</b></label>
                                                <select id="shiftSelect" name="employee_id"
                                                        class="form-select select2 basic-single">
                                                    <option>Report To</option>
                                                    @foreach($employees as $item)
                                                        <option
                                                            value="{{$item->id}}" {{$employee->employee_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-12 mt-3">
                                            <label>Are you seeking for a job?</label>
                                            <div class="row">
                                                {{--                                                @foreach($employeeTypes as $type)--}}
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="job_seeking"
                                                               type="radio"
                                                               value="probation" id="partTime"
                                                            {{ $employee->job_seeking === 'probation' ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="partTime">
                                                            Probation
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="job_seeking"
                                                               type="radio"
                                                               value="Permanent" id="permanent"
                                                            {{ $employee->job_seeking === 'Permanent' ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="permanent">
                                                            Permanent
                                                        </label>
                                                    </div>
                                                </div>


                                                {{--                                                @endforeach--}}
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="job_seeking"
                                                               type="radio"
                                                               value="VisitingLecturer" id="visitingLecturer"
                                                            {{ $employee->job_seeking === 'VisitingLecturer' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="flexCheckIndeterminate3">
                                                            Visiting Faculty
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="job_seeking"
                                                               type="radio"
                                                               value="Offsite" id="offside"
                                                            {{ $employee->job_seeking === 'Offsite' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="offside">
                                                            Offsite
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($employee->job_seeking === 'VisitingLecturer')
                                            <div class="col-md-12 mt-2" id="visitngContainer">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="visiting"><b>Working Hours</b></label>
                                                        <input type="text" class="form-control working_hour"
                                                               name="working_hour"
                                                               id="working_hour"
                                                               value="{{$employee->working_hour}}">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="hour_salary"><b>Per Hour salary</b></label>
                                                        <input type="number" class="form-control"
                                                               placeholder="RS"
                                                               name="hour_salary"
                                                               id="hour_salary"
                                                               value="{{$employee->hour_salary}}">
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-12 mt-2" id="visitngContainer" style="display: none;">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="visiting"><b>Working Hours</b></label>
                                                        <input type="text" class="form-control working_hour" name="working_hour"
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
                                        @endif

                                        <div class="col-md-12" style="margin-top: 15px">
                                            <label><b>Provident Fund Applicable?</b></label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="provident_fund" value="1" id="providentFundYes" {{ $employee->provident_fund == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="providentFundYes">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="provident_fund" value="0" id="providentFundNo" {{ $employee->provident_fund == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="providentFundNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 15px">
                                            <label>Applied before at cornerstone?</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="applied_before"
                                                               value="Yes" id="appliedBeforeYes"
                                                            {{ $employee->applied === 'Yes' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="appliedBeforeYes">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="applied_before"
                                                               value="No" id="appliedBeforeNo"
                                                            {{ $employee->applied === 'No' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="appliedBeforeNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        @if($employee->applied === 'Yes')
                                            <div class="col-md-12 mt-2" id="appliedWhenContainer">
                                                <label for="appliedWhen">If yes, When?</label>
                                                <input type="month" value="{{$employee->applied_yes}}"
                                                       class="form-control"
                                                       name="applied_when"
                                                       id="appliedWhen">
                                            </div>
                                        @endif

                                        <div class="col-md-12" style="margin-top: 15px">
                                            <label>Were you ever employed here?</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="employed_here"
                                                               id="employedHereYes"
                                                               value="Yes" {{ $employee->employed === 'Yes' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="employedHereYes">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="employed_here"
                                                               id="employedHereNo"
                                                               value="No" {{ $employee->employed === 'No' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="employedHereNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        @if($employee->employed === 'Yes')
                                            <div class="col-md-12 mt-2" id="employedWhenContainer">
                                                <label for="employedWhen">If yes, When?</label>
                                                <input type="text" value="{{$employee->when_employed_yes}}"
                                                       class="form-control" name="employed_when"
                                                       id="employedWhen">
                                            </div>
                                        @endif


                                        <div class="col-md-12" style="margin-top: 15px">
                                            <label>Engagements in any other business or employment?</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="engaged_in_other_employment" value="Yes"
                                                               id="engagedYes" {{ $employee->engaged_business === 'Yes' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="engagedYes">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="engaged_in_other_employment" value="No"
                                                               id="engagedNo" {{ $employee->engaged_business === 'No' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="engagedNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($employee->engaged_business === 'Yes')
                                            <div class="col-md-12 mt-2" id="otherEmploymentDetailsContainer">
                                                <label for="otherEmploymentDetails">If yes, please
                                                    explain:</label>
                                                <textarea class="form-control" name="other_employment_details"
                                                          id="otherEmploymentDetails">{!! $employee->when_business_yes !!}</textarea>
                                            </div>
                                        @endif

                                        <div class="col-md-12 mt-2">
                                            <label for="skills">Skills or training related to the
                                                position?</label>
                                            <textarea class="form-control" name="skills" id="skills"
                                                      rows="4">{!! $employee->skills !!}</textarea>
                                        </div>
                                        {{--@dd($employee)--}}
                                        {{--@if($employee->workExperince)--}}
                                        <div class="col-lg-12" style="margin-top: 15px">
                                            <label for="work-experince">Work Experince</label>
                                            @if (($employee->workExperince)->isNotEmpty())
                                                <div class="row" id="work-experience-fields-container">
                                                    @foreach($employee->workExperince as $experince)
                                                        @php($count = $experince->id)

                                                        <div class="col-md-2 experince_div"
                                                             data-id="{!! $experince->id !!}">
                                                            <label for="sno">S.No</label>
                                                            <input type="text" name="work_sno[]"
                                                                   value="{{$experince->s_no}}"
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-2 experince_div"
                                                             data-id="{!! $experince->id !!}">
                                                            <label for="institution">Name of Institution</label>
                                                            <input type="text" name="work_institution[]"
                                                                   value="{{$experince->name_of_institution}}"
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-2 experince_div"
                                                             data-id="{!! $experince->id !!}">
                                                            <label for="designation">Designation</label>
                                                            <input type="text" name="designation[]"
                                                                   value="{{$experince->designation}}"
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-2 experince_div"
                                                             data-id="{!! $experince->id !!}">
                                                            <label for="duration">Duration</label>
                                                            <select name="duration[]"
                                                                    class="form-select select2 basic-single mt-3"
                                                                    id="ageDropdown">
                                                                <option>select Duration</option>
                                                                <option
                                                                    value="0-6months" {{$experince->duration == '0-6months' ? 'selected' : ''}}>
                                                                    0-6 months
                                                                </option>
                                                                <option
                                                                    value="6months-1year" {{$experince->duration == '6months-1year' ? 'selected' : ''}}>
                                                                    6 months - 1 year
                                                                </option>
                                                                <option
                                                                    value="1-2years" {{$experince->duration == '1-2years' ? 'selected' : ''}}>
                                                                    1-2 years
                                                                </option>
                                                                <option
                                                                    value="2-3years" {{$experince->duration == '2-3years' ? 'selected' : ''}}>
                                                                    2-3 years
                                                                </option>
                                                                <option
                                                                    value="3+years" {{$experince->duration == '3+years' ? 'selected' : ''}}>
                                                                    3+ years
                                                                </option>
                                                            </select>

                                                        </div>

                                                        <div class="col-md-2 experince_div"
                                                             data-id="{!! $experince->id !!}">
                                                            <label for="from">From</label>
                                                            <input type="date" name="from[]"
                                                                   value="{{$experince->from}}"
                                                                   class="form-control">
                                                        </div>

                                                        <div class="col-md-2 experince_div"
                                                             data-id="{!! $experince->id !!}">
                                                            <label for="till">Till</label>
                                                            <input type="date" name="till[]"
                                                                   value="{{$experince->till}}"
                                                                   class="form-control">
                                                            <i class="fa fa-trash remove-work-icon" aria-hidden="true"
                                                               data-id="{{$experince->id}}"
                                                               style="position: absolute; top: 0; right: 10px; cursor: pointer; color: red; font-size: 20px; margin-bottom: 25px;"></i>

                                                        </div>
                                                    @endforeach
                                                    @php($count++)
                                                </div>

                                            @else
                                                <div class="row" id="work-experience-fields-container">
                                                    <div class="col-md-2 experince_div">
                                                        <label for="sno">S.No</label>
                                                        <input type="text" value="{{$index +1}}"
                                                               name="work_sno[]"
                                                               class="form-control">
                                                    </div>

                                                    <div class="col-md-2 experince_div">
                                                        <label for="institution">Name of
                                                            Institution</label>
                                                        <input type="text" name="work_institution[]"
                                                               class="form-control">
                                                    </div>

                                                    <div class="col-md-2 experince_div">
                                                        <label for="designation">Designation</label>
                                                        <input type="text" name="designation[]"
                                                               class="form-control">
                                                    </div>

                                                    <div class="col-md-2 experince_div">
                                                        <label for="duration">Duration</label>
                                                        <select name="duration[]"
                                                                class="form-select select2 basic-single "
                                                                id="ageDropdown">
                                                            <option> select Duration</option>
                                                            <option value="0-6months">0-6 months
                                                            </option>
                                                            <option value="6months-1year">6 months - 1
                                                                year
                                                            </option>
                                                            <option value="1-2years">1-2 years</option>
                                                            <option value="2-3years">2-3 years</option>
                                                            <option value="3+years">3+ years</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2 experince_div">
                                                        <label for="from">From</label>
                                                        <input type="date" name="from[]"
                                                               class="form-control"
                                                               onclick="CheckDate(this)">
                                                    </div>

                                                    <div class="col-md-2 experince_div">
                                                        <label for="till">To</label>
                                                        <input type="date" name="till[]"
                                                               class="form-control"
                                                               onclick="CheckDate(this)">
                                                    </div>
                                                </div>
                                            @endif

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
                                                            <option
                                                                value="{{ $nationality }}" {{$employee->nationality == $nationality ? 'selected' : ''}}>{{ $nationality }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="col-md-3">
                                                    <label for="religion">Religion</label>
                                                    <select name="religion" id="religion" class="form-control select2">
                                                        <option value="">--- Select One ---</option>
                                                        @foreach($religions as $religion)
                                                            <option
                                                                value="{{ $religion }}" {{$employee->religion == $religion ? 'selected' : ''}}>{{ $religion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="blood_group">Blood Group</label>
                                                    <select name="blood_group" id="blood_group" class="form-control select2">
                                                        <option value="">--- Select One ---</option>
                                                        @foreach($bloodGroups as $item)
                                                            <option value="{{ $item }}" {!! $employee->blood_group == $item ? 'selected' : '' !!}>{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="marital_status">Marital Status</label>
                                                    <select name="marital_status" class="form-control select2">
                                                        <option
                                                            value="Married" {{$employee->martial_status == "Married" ? 'selected' : ''}}>
                                                            Married
                                                        </option>
                                                        <option
                                                            value="Unmarried" {{$employee->martial_status == "Unmarried" ? 'selected' : ''}}>
                                                            Unmarried
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top:20px">
                                            <label for="family-info">Family Information (Specific
                                                Dependents
                                                Only)</label>
                                            @if (($employee->employeeFamily)->isNotEmpty())

                                                <div class="row mt-3" id="family-fields-container">

                                                    @foreach($employee->employeeFamily as $employeeFamily)
                                                        @php($count=$employeeFamily->id)
                                                        <div class="row mt-2" id="employee_count_div">
                                                            <div class="col-md-3"
                                                                 data-id="{{$employeeFamily->id}}">
                                                                <label for="sr_no">Sr. No</label>
                                                                <input type="text" name="sr_no[]"
                                                                       value="{{$employeeFamily->sr_no}}"
                                                                       class="form-control">
                                                            </div>

                                                            <div class="col-md-3"
                                                                 data-id="{{$employeeFamily->id}}">
                                                                <label for="name">Name</label>
                                                                <input type="text" name="family_name[]"
                                                                       value="{{$employeeFamily->name}}"
                                                                       class="form-control">
                                                            </div>

                                                            <div class="col-md-3"
                                                                 data-id="{{$employeeFamily->id}}">
                                                                <label for="relation">Relation</label>
                                                                <input type="text" name="relation[]"
                                                                       value="{{$employeeFamily->relation}}"
                                                                       class="form-control">
                                                            </div>

                                                            <div class="col-md-3"
                                                                 data-id="{{$employeeFamily->id}}">
                                                                <label for="dob">DOB</label>
                                                                <input type="date" name="family_dob[]"
                                                                       value="{{$employeeFamily->dob}}"
                                                                       class="form-control">
                                                            </div>

                                                            <div class="col-md-3"
                                                                 data-id="{{$employeeFamily->id}}">
                                                                <label for="gender">Gender</label>
                                                                <select name="gender[]" class="form-select">
                                                                    <option
                                                                        value="male" {{ $employeeFamily->gender === 'male' ? 'selected' : '' }}>
                                                                        Male
                                                                    </option>
                                                                    <option
                                                                        value="female" {{ $employeeFamily->gender === 'female' ? 'selected' : '' }}>
                                                                        Female
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3"
                                                                 data-id="{{$employeeFamily->id}}">
                                                                <label for="cnic">CNIC <b>*</b> <span
                                                                        class="small">(with (-) )</span></label>
                                                                <input name="cnic[]" id="cnic_card"
                                                                       type="text"
                                                                       data-inputmask="'mask': '99999-9999999-9'"
                                                                       placeholder="XXXXX-XXXXXXX-X"
                                                                       class="form-control cnic_card"
                                                                       onchange="checkCNIC(this)"
                                                                       value="{{$employeeFamily->cnic}}">
                                                            </div>
                                                            <div class="col-md-3"
                                                                 style="position: relative;"
                                                                 data-id="{{$employeeFamily->id}}">
                                                                <label
                                                                    for="workplace">Workplace/Institution</label>
                                                                <input type="text" name="workplace[]"
                                                                       value="{{$employeeFamily->workstation}}"
                                                                       class="form-control">
                                                                <i class="fa fa-trash remove-family-btn"
                                                                   aria-hidden="true"
                                                                   data-id="{{$employeeFamily->id}}"
                                                                   style="position: absolute; top: 0; right: 10px; cursor: pointer; color: red; font-size: 20px; margin-bottom: 25px;"></i>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @php($count++)
                                                </div>
                                            @else
                                                <div class="row mt-3" id="family-fields-container">
                                                    <div class="col-md-3">
                                                        <label for="sr_no">Sr. No</label>
                                                        <input type="text" name="sr_no[]" value="{{$index +1}}"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="name">Name</label>
                                                        <input type="text" name="family_name[]"
                                                               class="form-control">
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
                                                        <label for="cnic"> CNIC <span
                                                                class="small">(with (-) )</span></label>
                                                        <input name="cnic[]" id="cnic_card"
                                                               data-inputmask="'mask': '99999-9999999-9'"
                                                               placeholder="XXXXX-XXXXXXX-X" type="text"
                                                               class="form-control cnic_card" onchange="checkCNIC(this)"
                                                        />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="workplace">Workplace/Institution</label>
                                                        <input type="text" name="workplace[]" class="form-control">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row mt-2">
                                                <div class="col-md-3 offset-md-9">
                                                    <button id="add-family-btn" type="button"
                                                            class="btn btn-primary">
                                                        Add more
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top:20px">
                                        </div>
                                    </div>
                                        <div class="col-lg-12">
                                            <button class="btn btn-primary">Submit</button>
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

@section('js')
    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script>
        $('#datepicker-date').bootstrapdatepicker({
            format: "dd-mm-yyyy",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });
    </script>
    <script>
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
    </script>
    <script>
        $(document).ready(function () {
            let counter = {!! $count ?? 1 !!};

            function addMoreFields() {
                const educationFieldsContainer = $('#education-fields-container');
                const dataId = `data-id="${counter}"`;

                educationFieldsContainer.append(`

                    <div class="col-md-2 position-relative" ${dataId}>
                        <label for="institution">Institution</label>
                        <input type="text" name="education_institution[]" class="form-control">
                    </div>

                    <div class="col-md-2" ${dataId}>
                        <label for="year">Year</label>
                        <input type="text" name="year[]" class="form-control">
                    </div>

                    <div class="col-md-2" ${dataId}>
                        <label for="certification">Certification/Degree</label>
                        <input type="text" name="certification[]" class="form-control">
                    </div>

                    <div class="col-md-2" ${dataId}>
                        <label for="cgpa">CGPA</label>
                        <input type="text" name="cgpa[]" class="form-control" onkeypress="CheckNumber(this)">
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
            `);
                counter++;
            }
            $('#add-more-btn').on('click', function () {
                addMoreFields();
            });
            $(document).on('click', '.remove-btn', function () {
                const dataId = $(this).data('id');
                console.log(dataId);
                $(`.col-md-2[data-id="${dataId}"]`).remove();
            });
        });
    </script>
    {{--    for branches--}}
    <script>
        $(document).ready(function () {
            $('#companySelect').on('change', function () {
                loader('show');
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
                            var selectedBranch = branch.id == '{{ $employee->branch_id }}' ? 'selected' : '';
                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedBranch + '>' + branch.name + '</option>');
                            otherBranchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
                loader('hide');
            }).change();
            $('#branchSelect').on('change', function () {
                loader('show');
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
                loader('hide');
            }).change();
        });

    </script>
    {{--    for work experince--}}
    <script>
        $(document).ready(function () {
            let workCounter =  {!! $count ??  1!!};

            function addMoreWorkFields() {
                const workExperienceFieldsContainer = $('#work-experience-fields-container');
                const workDataId = `data-id="${workCounter}"`;

                workExperienceFieldsContainer.append(`
                <div class="col-md-2 experince_div" ${workDataId}>
                    <label for="sno">S.No</label>
                    <input type="text" name="work_sno[]"  class="form-control">
                </div>

                <div class="col-md-2 experince_div" ${workDataId}>
                    <label for="institution">Name of Institution</label>
                    <input type="text" name="work_institution[]" class="form-control">
                </div>

                <div class="col-md-2 experince_div" ${workDataId}>
                    <label for="designation">Designation</label>
                    <input type="text" name="designation[]" class="form-control">
                </div>

                <div class="col-md-2 experince_div" ${workDataId}>
                    <label for="duration">Duration</label>
                   <select name="duration[]"
                           class="form-select select2 basic-single"
                           id="ageDropdown">
                       <option> select Duration</option>
                       <option value="0-6months">0-6 months
                       </option>
                       <option value="6months-1year">6 months - 1
                           year
                       </option>
                       <option value="1-2years">1-2 years</option>
                       <option value="2-3years">2-3 years</option>
                       <option value="3+years">3+ years</option>
                   </select>
                </div>

                <div class="col-md-2 experince_div" ${workDataId}>
                    <label for="from">From</label>
                    <input type="date" name="from[]" class="form-control">
                </div>

                <div class="col-md-2 experince_div" ${workDataId}>
                    <label for="till">Till</label>
                        <input type="date" name="till[]" class="form-control">
                        <i class="fa fa-trash remove-work-icon" aria-hidden="true" data-id="${workCounter}" style="position: absolute; top: 5px; right: 0; cursor: pointer; color: red; font-size: 20px;"></i>
                </div>
            `);
                workCounter++;
            }

            $('#add-more-work-btn').on('click', function () {
                addMoreWorkFields();
            });

            $(document).on('click', '.remove-work-icon', function () {
                const workDataId = $(this).data('id');
                $(`.experince_div[data-id="${workDataId}"]`).remove();
            });

            



       
           

        });
    </script>


    {{--for family--}}

    <script>
        $(document).ready(function () {
            let familyCounter = {!! $count ?? 1 !!};

            function addMoreFamilyFields() {
                const familyFieldsContainer = $('#family-fields-container');
                const dataId = `data-id="${familyCounter}"`;
                familyFieldsContainer.append(`
                <div class="row mt-2">
                    <div class="col-md-3" ${dataId}>
                        <label for="sr_no">Sr. No</label>
                        <input type="text" name="sr_no[]" class="form-control">
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
                            <label for="cnic"> CNIC <b>*</b> <span
                                         class="small">(with (-) )</span></label>
                                 <input  name="cnic[]" id="cnic_card" type="text"
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
        var company_id;
        $(document).ready(function () {
            $('#companySelect').on('change', function () {
                loader('show');
                company_id = $(this).val();
                {{--if (company_id == null) {--}}
                {{--     company_id = "{{$employee->branch_id}}";--}}
                {{--}--}}
                // alert()
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.departments') }}',
                    data: {
                        companyid: company_id
                    },
                    success: function (data) {
                        var departmentDropdown = $('#departmentDropdown').empty();
                        departmentDropdown.append('<option>Select Department</option>');
                        data.forEach(function (department) {

                            var selectedDepartment = department.id == '{{ $employee->department_id }}' ? 'selected' : '';

                            departmentDropdown.append('<option value="' + department.id + '" ' + selectedDepartment + '>' + department.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }


                });
                loader('hide');
            }).change();
        });
    </script>

    <script>
        var department_id;
        $(document).ready(function () {
            $('#departmentDropdown').on('change', function () {
                loader('show');
                department_id = $(this).val();
                if (department_id == null) {
                    department_id = "{{$employee->department_id}}";
                }
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

                            var selectedDesignation = designation.id == '{{ $employee->designation_id }}' ? 'selected' : '';

                            designationDropdown.append('<option value="' + designation.id + '"' + selectedDesignation + '>' + designation.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
                loader('hide');
            }).change();
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

    {{--    <script>--}}
    {{--        $(document).ready(function() {--}}
    {{--            function calculateGrossSalary() {--}}
    {{--                var basicSalary = parseFloat($('#basic_salary').val());--}}
    {{--                var totalAllowance = 0;--}}
    {{--                $('.allowance_price').each(function() {--}}
    {{--                    totalAllowance += parseFloat($(this).val() || 0);--}}
    {{--                });--}}
    {{--                var grossSalary = basicSalary + totalAllowance;--}}
    {{--                $('#gross_salary').val(grossSalary.toFixed(2));--}}
    {{--            }--}}

    {{--            $('#basic_salary, .allowance_price').on('input', calculateGrossSalary);--}}

    {{--            calculateGrossSalary();--}}
    {{--        });--}}
    {{--    </script>--}}

    <script>
        $(document).ready(function () {
            $('#gross_salary').on('input', function () {
                var grossSalary = parseFloat($(this).val());

                var salaryValue = {!! $salaryValue !!};
                console.log(salaryValue);
                var basicSalary = Math.floor(grossSalary / salaryValue);

                $('#basic_salary').val(basicSalary);
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
                console.log(grossSalary);
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
                loader('show');
                var dob = $('.datePicker').val();
                var joiningDate = $('.joiningDate').val();

                if (dob && joiningDate) {
                    var dobParts = dob.split('-');
                    var joiningDateParts = joiningDate.split('-');

                    var dobObj = new Date(dobParts[2], dobParts[1] - 1, dobParts[0]);
                    var joiningDateObj = new Date(joiningDateParts[0], joiningDateParts[1] - 1, joiningDateParts[2]);

                    if (joiningDateObj <= dobObj) {
                        alert("Joining Date should be greater than DOB");
                        $('.joiningDate').val('');
                    }
                }
                loader('hide');
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
        $(".cnic_card").inputmask();
    </script>

    <script>
        $(document).ready(function () {
            $('#designationSelect').on('change', function () {
                loader('show');
                let department_name = $(this).find('option:selected').text();
                if (department_name == 'Teacher') {
                    $('#specialization_div').show();
                } else {
                    $('#specialization_div').hide();
                }
                loader('hide');
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#account_number').inputmask('9999 9999 9999 9999');
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.working_hour').on('input', function () {
                var workingHour = $(this).val();
                var grossSalary = parseFloat($('.gross_salary').val());
                if (isNaN(grossSalary)) {
                    alert('Please enter a valid gross salary.');
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
                    alert("Invalid date of birth. Please enter a date in the past.");
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
                    alert("Employee is under age 18 and cannot be added.");
                    $('.employee_dob').val('');

                } else {
                    alert("Employee is above age 18 and can be added.");
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
                loader('show');
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
                loader('hide');
            });
        });
    </script>

    <script>
function waitAndSelect($select, value, triggerChange = true, maxTries = 10) {
    if (maxTries <= 0) return;
    if ($select.find(`option[value="${value}"]`).length) {
        $select.val(value);
        if (triggerChange) $select.trigger('change');
    } else {
        setTimeout(() => waitAndSelect($select, value, triggerChange, maxTries - 1), 100);
    }
}

$(document).ready(function () {
    // Load branches when company changes
    $(document).on('change', '.company-select', function () {
        loader('show');
        const companyId = $(this).val();
        const index = $(this).data('index');
        const branchSelect = $('#branch-select-' + index);
        const selectedBranch = branchSelect.data('selected');

        if (!companyId) return;

        $.ajax({
            url: '{{ route('hr.fetch.branches') }}',
            method: 'GET',
            data: { companyid: companyId },
            success: function (branches) {
                branchSelect.empty().append('<option value="">Select Branch</option>');
                $.each(branches, function (_, branch) {
                    branchSelect.append(`<option value="${branch.id}">${branch.name}</option>`);
                });

                if (selectedBranch) {
                    waitAndSelect(branchSelect, selectedBranch);
                }
            }
        });
        loader('hide');
    });

    // Load classes when branch changes
    $(document).on('change', '.branch-select', function () {
        loader('show');
        const branchId = $(this).val();
        const index = $(this).data('index');
        const classSelect = $('#class-select-' + index);
        const selectedClass = classSelect.data('selected');

        classSelect.empty().append('<option value="">Select Class</option>');

        if (!branchId) return;

        $.ajax({
            url: '{{ route("academic.fetchClass") }}',
            method: 'GET',
            data: { branch_id: branchId },
            success: function (classes) {
                $.each(classes, function (_, cls) {
                    classSelect.append(`<option value="${cls.id}">${cls.name}</option>`);
                });

                if (selectedClass) {
                    waitAndSelect(classSelect, selectedClass);
                }
            }
        });
        loader('hide');
    });

    // Trigger company change on page load
    $('.company-select').each(function () {
        $(this).trigger('change');
    });
});
</script>


@endsection

