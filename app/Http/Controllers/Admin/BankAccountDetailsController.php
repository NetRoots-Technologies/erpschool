<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BankAccountDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use App\Models\Admin\BankBranch;
use App\Models\Admin\BanksModel;

class BankAccountDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = BankAccountDetail::with("bank", "bankBranch")->get();
        return view('admin.BankAccountDetail.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.BankAccountDetail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bankaccountdetail = new BankAccountDetail();
        $bankaccountdetail->bank_id = $req->input('bank_id');
        $bankaccountdetail->branch_code_id = $req->input('branch_code_id');
        $bankaccountdetail->account_title = $req->input('account_title');
        $bankaccountdetail->account_no = $req->input('account_no');
        $bankaccountdetail->account_type = $req->input('account_type');
        $bankaccountdetail->phone_no = $req->input('phone_no');
        $bankaccountdetail->address = $req->input('address');
        $bankaccountdetail->save();
        $req->session()->flash('status', 'Account Detail Added Successfully');
        return redirect('admin/BankAccountDetail');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = BankAccountDetail::find($id);
        return view('admin.BankAccountDetail.edit', ['data' => $data]);
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bankaccountdetail = BankAccountDetail::find($req->id);
        $bankaccountdetail->bank_id = $req->input('bank_id');
        $bankaccountdetail->branch_code_id = $req->input('branch_code_id');
        $bankaccountdetail->account_title = $req->input('account_title');
        $bankaccountdetail->account_no = $req->input('account_no');
        $bankaccountdetail->account_type = $req->input('account_type');
        $bankaccountdetail->phone_no = $req->input('phone_no');
        $bankaccountdetail->address = $req->input('address');
        $bankaccountdetail->save();
        $req->session()->flash('status', 'Account Updated Successfully');
        return redirect('admin/BankAccountDetail');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $var = BankAccountDetail::find($id)->delete();
        ;
        Session()->flash('status', 'Account Deleted Successfully');
        return redirect('admin/BankAccountDetail');
    }
    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = BankAccountDetail::with('bank', 'bankBranch');
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('bank_name', function ($data) {
                return @$data->bank->bank_name;
            })
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("admin.BankAccountDetail.destroy", $row->id) . '"> ';

                $btn = $btn . ' <a href="' . route("admin.BankAccountDetail.edit", $row->id) . '" class="ml-2 btn btn-xs  btn-primary">  <i class="fa fa-edit"></i></a>';
                $btn = $btn . '<button  type="submit" class="ml-2 btn btn-xs btn-danger" ><i class="fa fa-trash-o"></i></button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

