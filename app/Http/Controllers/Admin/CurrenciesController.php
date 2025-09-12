<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Currencies;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;


class CurrenciesController extends Controller
{

    public function index()
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Currencies::all();
        return view('admin.currency.index', ["data" => $data]);
    }


    function add(Request $req)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $currency = new Currencies;
        $currency->name = $req->input('name');
        $currency->code = $req->input('code');
        $currency->decimal = $req->input('decimal');
        $currency->symbols = $req->input('symbols');
        $currency->rate = $req->input('rate');
        $currency->status = $req->input('status');
        $currency->save();
        $req->session()->flash('status', 'Currency Added Successfully');
        return redirect('/admin/currency');

    }
    //    public function store(Request $req)
//    {
//        $currency = new Currencies;
//        $currency->name = $req->input('name');
//        $currency->code = $req->input('code');
//        $currency->decimal = $req->input('decimal');
//        $currency->symbols = $req->input('symbols');
//        $currency->rate = $req->input('rate');
//        $currency->status = $req->input('status');
//        $currency->save();
//        $req->session()->flash('status', 'Currency Added Successfully');
//        return redirect('admin/currency/index');
//    }


    public function delete($id)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $var = Currencies::find($id)->delete();
        ;
        Session()->flash('status', 'Currency Deleted Successfully');
        return redirect('admin/currency');
    }


    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Currencies::find($id);
        return view('admin.currency.edit', ['data' => $data]);
        //        return redirect('admin/currency/index');
    }

    function update(Request $req)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $currency = Currencies::find($req->id);
        $currency->name = $req->input('name');
        $currency->code = $req->input('code');
        $currency->decimal = $req->input('decimal');
        $currency->symbols = $req->input('symbols');
        $currency->rate = $req->input('rate');
        $currency->status = $req->input('status');
        $currency->save();

        $req->session()->flash('status', 'Currency Updated Successfully');
        return redirect('admin/currency');
    }
    public function getData()
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Currencies::all();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="get" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("admin.currencies.delete", $row->id) . '"> ';

                $btn = $btn . ' <a href="' . route("admin.currencies.edit", $row->id) . '" class="ml-2 btn btn-xs  btn-primary">  <i class="fa fa-edit"></i></a>';
                $btn = $btn . '<button  type="submit" class="ml-2 btn btn-xs btn-danger" ><i class="fa fa-trash-o"></i></button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
