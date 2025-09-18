<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\User;
use App\Models\Admin\Course;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
// use App\Models\Fee\StudentFee; // Removed - model no longer exists
// use App\Models\Fee\PaidStudentFee; // Removed - model no longer exists
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\StudentDataBank;

class PaidFeeService
{

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }



    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data['course'] = Course::all();
        $data['databank'] = StudentDataBank::all();
        $data['fee_collections'] = FeeCollection::all(); // Updated to use new fee structure

        return $data;

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

    }






    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->select('users.id as id', 'users.email as email', 'users.name as name', 'users.email_verified_at as email_verified_at', 'users.active as active', 'model_has_roles.role_id as role_id')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                return ($row->active == 1) ? 'Active' : 'Deactive';
            })->addColumn('email_verified_at', function ($row) {
                return ($row->email_verified_at != null) ? ' verified ' : 'Not verified';
            })
            ->addColumn('status', function ($row) {

                return ($row->active == 1) ? 'Active' : 'Deactive';

            })
            //            ->addColumn('email_verified_at', function ($row) {
//                return ($row->email_verified_at != null) ? ' verified ' : 'Not verified';
//            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("users.destroy", $row->id) . '"   id="User-' . $row->id . '"  method="POST"> ';


                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary  btn-sm user_edit"  data-user-edit=\'' . $row . '\'>Edit</a>';

                $btn = $btn . ' <button data-id="User-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';

                $btn = $btn . method_field('DELETE') . '' . csrf_field();

                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['name', 'email', 'role', 'status', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }
}
