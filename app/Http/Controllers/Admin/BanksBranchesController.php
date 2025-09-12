<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BankBranch;
use App\Models\Admin\BanksModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;


class BanksBranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = BankBranch::with("bank")->paginate(5);


        return view('admin.banksBranches.index', ["data" => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $bank = BanksModel::all()->pluck('id', 'bank_name');

        return view('admin.banksBranches.create', compact('bank'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $bankbranch = new BankBranch();
        $bankbranch->bank_id = $req->bank_id;
        $bankbranch->branch_code_id = $req->input('branch_code_id');
        $bankbranch->address = $req->input('address');
        $bankbranch->number = $req->input('number');
        $bankbranch->save();
        $req->session()->flash('status', 'Branch Added Successfully');
        return redirect('admin/banksBranches');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $bank = BanksModel::all()->pluck('id', 'bank_name');

        $data = BankBranch::find($id);
        return view('admin.banksBranches.edit', compact('bank', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $bankbranch = BankBranch::find($req->id);
        $bankbranch->bank_id = $req->bank_id;
        $bankbranch->branch_code_id = $req->input('branch_code_id');
        $bankbranch->address = $req->input('address');
        $bankbranch->number = $req->input('number');
        $bankbranch->save();
        $req->session()->flash('status', 'Branch Updated Successfully');
        return redirect('admin/banksBranches');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $var = BankBranch::find($id)->delete();
        ;
        Session()->flash('status', 'Branch Deleted Successfully');
        return redirect('admin/banksBranches');
    }
    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = BankBranch::with('bank');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('bank', function ($data) {
                return $data->bank->bank_name;
            })
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("admin.banksBranches.destroy", $row->id) . '"> ';

                $btn = $btn . ' <a href="' . route("admin.banksBranches.edit", $row->id) . '" class="ml-2 btn btn-xs  btn-primary">  <i class="fa fa-edit"></i></a>';
                $btn = $btn . '<button  type="submit" class="ml-2 btn btn-xs btn-danger" ><i class="fa fa-trash-o"></i></button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
