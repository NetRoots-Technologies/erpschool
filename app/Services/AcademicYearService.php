<?php

namespace App\Services;

use App\Models\Admin\AcademicYear;
use App\Models\Admin\Company;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;


class AcademicYearService
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
        return Company::all();

        // return Permission::with('child')->where('main', 1)->get();

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $Company = Company::create(['name' => $request->name]);

    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Company::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('status', function ($row) {
                return ($row->status == 1) ? 'Active' : 'Deactive';
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("admin.company.destroy", $row->id) . '"   id="company-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary  btn-sm company_edit"  data-company-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('company-delete'))
                $btn = $btn . ' <button data-id="company-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Company::find($id);


    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Company::find($id);
        $input = $request->all();
        $data->update($input);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Company = Company::findOrFail($id);
        if ($Company)
            $Company->delete();
    }
}
