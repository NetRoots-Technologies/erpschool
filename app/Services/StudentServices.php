<?php

namespace App\Services;

use Config;
use App\Models\HR\Agent;
use App\Models\Admin\City;
use App\Models\Admin\State;
use App\Helpers\ImageHelper;
use App\Models\Admin\Course;
use App\Models\Admin\Country;
use App\Models\Admin\Session;
use App\Models\HRM\Employees;
use App\Models\FamilyTreeTable;
use App\Models\Admin\CourseType;
use App\Models\Student\Students;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin\StudentDataBank;
use App\Models\Student\StudentCourse;
use App\Models\Student\StudentDetail;
use App\Models\Student\StudentSibling;

use App\Models\Student\StudentPictures;
use App\Models\Student\StudentTransport;
use App\Models\Student\StudentPerviousSchool;
use App\Models\Student\StudentEmergencyContact;

class StudentServices
{

    public function apiindex()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Students::all();

    }

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $data['student'] = Students::first();
        $data['country'] = Country::all();
        // $data['city'] = City::all();
        //$data['state'] = State::all();

        $data['course'] = Course::all();
        $data['CourseType'] = CourseType::all();
        $data['agent'] = Agent::all();
        $data['session'] = Session::where('status', 1)->get();
        return $data;
    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        DB::beginTransaction();
        try {

            $isGuardian = $request->has('is_guardian');

            $guardianName = $request->guardian_name ?? $request->father_name;
            $guardianCnic = $request->guardian_cnic ?? $request->father_cnic;


            $student = Students::create([
                'admission_class' => $request->admission_class,
                'campus' => $request->branch_id,
                'admission_date' => $request->admission_date,
                'special_child' => $request->special_child,
                'special_needs' => $request->special_needs,
                'is_guardian' => $request->$isGuardian??1,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'father_name' => $request->father_name,
                'father_cnic' => $request->father_cnic,
                'guardian_name' => $guardianName,
                'guardian_cnic' => $guardianCnic,
                'gender' => $request->student_gender,
                'student_dob' => $request->student_dob,
                'student_current_address' => $request->student_current_address,
                'student_permanent_address' => $request->student_permanent_address,
                'city' => $request->student_city,
                'country' => $request->student_country,
                'cell_no' => $request->student_cell_no,
                'landline' => $request->student_landline,
                'student_email' => $request->student_email,
                'native_language' => $request->native,
                'first_language' => $request->first,
                'second_language' => $request->second,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'branch_id' => $request->branch_id,
                'session_id' => $request->session_id,
                'company_id' => $request->company_id,
                'student_id' => $request->student_id,
                'meal_option' => $request->meal_option,
                'easy_urdu' => $request->easy_urdu == 'Yes' ? 1 : 0,
            ]);

            if ($student) {
                $studentBank = StudentDataBank::find($request->student_databank_id);
                if ($studentBank)
                    $studentBank->delete();
            }

            $existingRecord = FamilyTreeTable::where('cnic_number', $guardianCnic)->first();

            if ($existingRecord) {
                $existingRecord->increment('no_of_children');
            } else {
                FamilyTreeTable::create([
                    'cnic_number' => $guardianCnic,
                    'no_of_children' => 1,
                ]);
            }

            if ($request->has('sibling_name')) {
                foreach ($request->get('sibling_name') as $key => $sibling_name) {
                    if (!empty($sibling_name)) {
                        $studied = $request->get('studied')[$key] ?? 'no';

                        $sibling = StudentSibling::create([
                            'sibling_name' => $sibling_name,
                            'sibling_dob' => $request->get('sibling_dob')[$key],
                            'sibling_gender' => $request->get('sibling_gender')[$key],
                            'student_id' => $student->id,
                            'class_id' => $studied == 'yes' ? $request->get('sibling_class')[$key] : null,
                            'studied' => $studied,
                        ]);
                    }
                }
            }


            if ($request->has('school_name')) {
                $studentSchool = StudentPerviousSchool::create([
                    'school_name' => $request->get('student_name'),
                    'school_address' => $request->get('school_address'),
                    'leaving_reason' => $request->get('leaving_reason'),
                    'local_school_name' => $request->get('local_school_name'),
                    'local_school_address' => $request->get('local_school_address'),
                    'student_id' => $student->id,
                ]);
            }

            if ($request->has('parent_responsibility')) {
                foreach ($request->get('parent_responsibility') as $key => $parent_responsibility) {
                    $studentEmergency = StudentEmergencyContact::create([
                        'name' => $request->get('name')[$key],
                        'relation' => $request->get('relation')[$key],
                        'parent_responsibility' => $parent_responsibility,
                        'home_address' => $request->get('home_address')[$key],
                        'city' => $request->get('student_emergency_city')[$key],
                        'landline' => $request->get('student_emergency_landline')[$key],
                        'cell_no' => $request->get('cell_no')[$key],
                        'email_address' => $request->get('email_address')[$key],
                        'work_address' => $request->get('work_address')[$key],
                        'work_landline' => $request->get('work_landline')[$key],
                        'work_cell_no' => $request->get('work_cell_no')[$key],
                        'work_email' => $request->get('work_email')[$key],
                        'student_id' => $student->id,
                    ]);

                }
            }

            if ($request->has('pickup_dropoff')) {
                $studentTransport = StudentTransport::create([
                    'pickup_dropoff' => $request->get('pickup_dropoff'),
                    'transport_facility' => $request->get('transport_facility'),
                    'pick_address' => $request->get('pick_address'),
                    'picture_permission' => $request->get('picture_permission'),
                    'student_id' => $student->id,

                ]);
            }

            $this->addNewStudentPictures($request, $student);
            DB::commit();
            return $student;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }


    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Students::with('branch', 'student_siblings', 'student_schools', 'student_emergency_contacts', 'student_transports')->orderBy('created_at', 'desc')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                //                if (Gate::allows('Employee-edit'))
                $btn .= '<a href="' . route("academic.students.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                //                if (Gate::allows('Employee-destroy')) {
                $btn .= '<form method="POST" action="' . route("academic.students.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm deleteBtn" data-id="' . $row->id . '" data-url="' . route("academic.students.destroy", $row->id) . '" style="margin-right: 4px;">Delete</button>';
                $btn .= '</form>';
                //                }
    
                $btn .= '</div>';

                return $btn;

            })->addColumn('name', function ($row) {

                if ($row->first_name && $row->last_name) {
                    return $row->first_name . '&nbsp' . $row->last_name;
                } else {
                    return '-';
                }
            })->addColumn('student_id', function ($row) {
                return $row->student_id;
            })
            ->addColumn('campus', function ($row) {

                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return '-';
                }
            })
            ->rawColumns(['action', 'name', 'campus'])
            ->make(true);


    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //        dd($request->student_gender);
        $guardianName = $request->guardian_name;
        $guardianCnic = $request->guardian_cnic;

        if (empty($guardianName)) {
            $guardianName = $request->father_name;
        }

        if (empty($guardianCnic)) {
            $guardianCnic = $request->father_cnic;
        }

        $isGuardian = $request->has('is_guardian') ? 1 : 0;

        $student = Students::find($id);
        $student->update([
            'admission_class' => $request->admission_class,
            'campus' => $request->branch_id,
            'admission_date' => $request->admission_date,
            'special_child' => $request->special_child,
            'special_needs' => $request->special_needs,
            'is_guardian' => $request->$isGuardian,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'father_cnic' => $request->father_cnic,
            'guardian_name' => $guardianName,
            'guardian_cnic' => $guardianCnic,
            'gender' => $request->student_gender,
            'student_dob' => $request->student_dob,
            'student_current_address' => $request->student_current_address,
            'student_permanent_address' => $request->student_permanent_address,
            'city' => $request->student_city,
            'country' => $request->student_country,
            'cell_no' => $request->student_cell_no,
            'landline' => $request->student_landline,
            'student_email' => $request->student_email,
            'native_language' => $request->native,
            'first_language' => $request->first,
            'second_language' => $request->second,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'branch_id' => $request->branch_id,
            'session_id' => $request->session_id,
            'company_id' => $request->company_id,
            'student_id' => $request->student_id,
            'meal_option' => $request->meal_option,
            'easy_urdu' => $request->easy_urdu == 'Yes' ? 1 : 0,
        ]);

        $student->student_siblings()->delete();

        if ($request->has('sibling_name')) {
            foreach ($request->get('sibling_name') as $key => $sibling_name) {
                if (!empty($sibling_name)) {
                    $siblingData = [
                        'sibling_name' => $sibling_name,
                        'sibling_dob' => $request->get('sibling_dob')[$key],
                        'sibling_gender' => $request->get('sibling_gender')[$key],
                        'student_id' => $student->id,
                        'studied' => isset($request->get('studied')[$key]) ? $request->get('studied')[$key] : 'no',
                        // 'class_id' => null,
                    ];

                    if ($siblingData['studied'] == 'yes' && isset($request->get('sibling_class')[$key])) {
                        $siblingData['class_id'] = $request->get('sibling_class')[$key];
                    }

                    $sibling = StudentSibling::create($siblingData);
                }
            }
        }


        $student->student_schools()->delete();

        $existingRecord = FamilyTreeTable::where('cnic_number', $guardianCnic)->first();

        if ($existingRecord) {
            $existingRecord->increment('no_of_children');
        } else {
            FamilyTreeTable::create([
                'cnic_number' => $guardianCnic,
                'no_of_children' => 1,
            ]);
        }

        if ($request->has('school_name')) {
            $studentSchool = StudentPerviousSchool::create([
                'school_name' => $request->get('student_name'),
                'school_address' => $request->get('school_address'),
                'leaving_reason' => $request->get('leaving_reason'),
                'local_school_name' => $request->get('local_school_name'),
                'local_school_address' => $request->get('local_school_address'),
                'student_id' => $student->id,
            ]);
        }

        $student->student_emergency_contacts()->delete();

        if ($request->has('parent_responsibility')) {
            foreach ($request->get('parent_responsibility') as $key => $parent_responsibility) {
                $studentEmergency = StudentEmergencyContact::create([
                    'name' => $request->get('name')[$key],
                    'relation' => $request->get('relation')[$key],
                    'parent_responsibility' => $parent_responsibility,
                    'city' => $request->get('student_emergency_city')[$key],
                    'landline' => $request->get('student_emergency_landline')[$key],
                    'cell_no' => $request->get('cell_no')[$key],
                    'home_address' => $request->get('home_address')[$key],
                    'email_address' => $request->get('email_address')[$key],
                    'work_address' => $request->get('work_address')[$key],
                    'work_landline' => $request->get('work_landline')[$key],
                    'work_cell_no' => $request->get('work_cell_no')[$key],
                    'work_email' => $request->get('work_email')[$key],
                    'student_id' => $student->id,
                ]);

            }
        }

        $student->student_transports()->delete();


        if ($request->has('pickup_dropoff')) {
            $studentTransport = StudentTransport::create([
                'pickup_dropoff' => $request->get('pickup_dropoff'),
                'transport_facility' => $request->get('transport_facility'),
                'pick_address' => $request->get('pick_address'),
                'picture_permission' => $request->get('picture_permission'),
                'student_id' => $student->id,

            ]);
        }

        $student->studentPictures()->delete();

        $this->addNewStudentPictures($request, $student);

        $student->save();


    }

    private function addNewStudentPictures($request, $student)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $studentPictures = $student->studentPictures ?: new StudentPictures();

        if ($request->hasfile('school_leaving_certificate')) {
            $file = $request->file('school_leaving_certificate');
            $filename = $this->uploadFile($file, 'student_id_cards');
            $studentPictures->school_leaving_certificate = $filename;
        }

        if ($request->hasfile('passport_photos')) {
            $file = $request->file('passport_photos');
            $filename = $this->uploadFile($file, 'student_passports');
            $studentPictures->passport_photos = $filename;
        }

        if ($request->hasfile('guardian_document')) {
            $file = $request->file('guardian_document');
            $filename = $this->uploadFile($file, 'student_documents');
            $studentPictures->guardian_document = $filename;
        }

        if ($request->hasfile('birth_certificate')) {
            $file = $request->file('birth_certificate');
            $filename = $this->uploadFile($file, 'student_documents');
            $studentPictures->birth_certificate = $filename;
        }

        $student->studentPictures()->save($studentPictures);
    }


    private function uploadFile($file, $destinationPath)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $filename = $file->getClientOriginalName();
        $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
        $filename = preg_replace("/\s+/", '-', $filename);
        $extension = $file->getClientOriginalExtension();
        $filenameToStore = $filename . '_' . time() . '.' . $extension;
        $file->move(public_path($destinationPath), $filenameToStore);
        return $destinationPath . '/' . $filenameToStore;
    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $student = Students::find($id);
        $student->student_transports()->delete();
        $student->student_siblings()->delete();
        $student->student_schools()->delete();
        $student->student_emergency_contacts()->delete();


        $student->delete();
    }


    public function get_state($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $html = "<option value=''> Select State </option>";
        $state = State::where('country_id', $id)->get();
        foreach ($state as $item) {
            $html = $html . "<option value='" . $item->id . "'>" . $item->name . "</option>";
        }

        return $html;
    }

    public function get_city($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $html = "<option value=''> Select State </option>";
        $city = City::where('state_id', $id)->get();
        foreach ($city as $item) {
            $html = $html . "<option value='" . $item->id . "'>" . $item->name . "</option>";
        }

        return $html;
    }

    public function StudentSiblingData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Students::orderBy('father_cnic')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student_id', function ($row) {
                if ($row->student_id != null || $row->student_id != '') {
                    return $row->student_id;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('student_name', function ($row) {
                if ($row->first_name != null && $row->last_name != null) {
                    return $row->first_name . '' . $row->last_name;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('father_name', function ($row) {
                if ($row->father_name != null) {
                    return $row->father_name;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('father_cnic', function ($row) {
                if ($row->father_cnic != null) {
                    return $row->father_cnic;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('branch', function ($row) {
                if ($row->branch != null) {
                    return $row->branch->name;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('class', function ($row) {
                if ($row->AcademicClass != null) {
                    return $row->AcademicClass->name;
                } else {
                    return 'N/A';
                }
            })
            ->make(true);

    }

}

