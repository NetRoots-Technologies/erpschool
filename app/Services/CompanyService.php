<?php

namespace App\Services;

use App\Models\Admin\Company;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;


class CompanyService
{

    public function index()
    {
        if (!Gate::allows('Company-list')) {
            return abort(503);
        }
    }


    public function create()
    {
        if (!Gate::allows('Company-create')) {
            return abort(503);
        }
    }

    public function store($request, $image = null)
    {

        if (!Gate::allows('Company-create')) {
            return abort(503);
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoFileName = time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('logos'), $logoFileName);
            $logoPath = 'logos/' . $logoFileName;
        }
        Company::create(['name' => $request->name, 'logo' => $logoPath, 'voucher_image' => $image]);
    }


    public function getdata()
    {
        if (!Gate::allows('Company-list')) {
            return abort(503);
        }
        $data = Company::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {
                $btn  = "";

                if (auth()->user()->can('Company-edit')) {
                    $payload = e(json_encode($row)); // safely encode $row
                    $btn .= '<button type="button"
                        data-id="' . $row->id . '"
                        class="btn btn-primary text-white btn-sm company_edit me-1"
                        data-company-edit=\'' . $payload . '\'>
                        <i class="fa fa-edit"></i> Edit
                 </button>';
                }

                if (auth()->user()->can('Company-delete')) {
                    $btn .= '<form class="delete_form"
                         data-route="' . route('admin.company.destroy', $row->id) . '"
                         id="company-' . $row->id . '"
                         method="POST"
                         style="display:inline-block; margin-left:5px;">'
                        . csrf_field()
                        . method_field('DELETE') . '
              <button data-id="company-' . $row->id . '"
                      type="button"
                      class="btn btn-danger btn-sm delete">
                      <i class="fa fa-trash"></i> Delete
              </button>
            </form>';
                }

                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Company-edit')) {
            return abort(503);
        }
        return Company::find($id);
    }


    public function update($request, $id, $image = null)
    {
        if (!Gate::allows('Company-edit')) {
            return abort(503);
        }
        $company = Company::find($id);
        $company->name = $request->name;

        if ($request->hasFile('logo')) {
            if ($company->logo && file_exists(public_path($company->logo))) {
                unlink(public_path($company->logo));
            }

            $logoFileName = time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('logos'), $logoFileName);
            $company->logo = 'logos/' . $logoFileName;
        }

        if ($image !== null) {
            $company->voucher_image = $image;
        }

        $company->save();
    }


    public function destroy($id)
    {
        if (!Gate::allows('Company-delete')) {
            return abort(503);
        }
        $Company = Company::findOrFail($id);
        if ($Company)
            $Company->delete();
    }

    public function changeStatus($request)
    {
        $company = Company::find($request->id);
        if ($company) {
            $company->status = ($request->status == 'active') ? 1 : 0;
            $company->save();
            return $company;
        }
    }
}
