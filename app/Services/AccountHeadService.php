<?php
namespace App\Services;

use Yajra\DataTables\DataTables;
use App\Models\Admin\AccountHead;
use Illuminate\Support\Facades\Gate;

class AccountHeadService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return AccountHead::create([
            'name' => $request->get('name'),
        ]);
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = AccountHead::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("admin.account-head.destroy", $row->id) . '"   id="company-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm account_head"  data-account-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('company-delete'))
                $btn = $btn . ' <button data-id="company-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $accountHead = AccountHead::find($id);
        $accountHead->update([
            'name' => $request->get('name'),
        ]);

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $accountHead = AccountHead::find($id);
        if ($accountHead) {
            $accountHead->delete();
        }
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $accountHead = AccountHead::find($request->id);
        if ($accountHead) {
            $accountHead->status = ($request->status == 'active') ? 1 : 0;
            $accountHead->save();
            return $accountHead;
        }
    }

}
