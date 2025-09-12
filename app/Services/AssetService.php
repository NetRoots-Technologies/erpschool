<?php

namespace App\Services;


use Carbon\Carbon;
use App\Helper\Helpers;
use App\Models\HR\Asset;
use App\Helpers\ImageHelper;
use App\Models\Admin\Branch;
use App\Models\HR\AssetType;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class AssetService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //dd($request->all());

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageFile = $request->file('image');
            $image = ImageHelper::uploadImage($imageFile, 'asset_image');
        } else {
            $image = null;
        }

        $branch = Branch::find($request['branch_id']);
        $assetType = AssetType::find($request['asset_type_id']);

        $code = $branch->emp_branch_code . '-' .$assetType->abbreviation. '-' . Helpers::makeAbbreviation($request->get('name')). '-' . Carbon::parse($request['purchase_date'])->format('y') . '-' . Asset::max('id') + 1;
        return Asset::create([
            'credit_type' => $request['credit_type'],
            'credit_ledger' => $request['credit_ledger'],
            'asset_type_id' => $request['asset_type_id'],
            'name' => $request['name'],
            'code' => $code,
            'working' => isset($request['working']),
            'depreciation_type' => $request['depreciation_type'],
            'company_id' => $request['company_id'],
            'branch_id' => $request['branch_id'],
            'purchase_date' => $request['purchase_date'],
            'invoice_number' => $request['invoice_number'],
            'manufacturer' => $request['manufacturer'],
            'serial_number' => $request['serial_number'],
            'end_date' => $request['end_date'],
            'amount' => $request['amount'],
            'depreciation' => $request['depreciation'],
            'sale_tax' => $request['sale_tax'],
            'income_tax' => $request['income_tax'],
            'narration' => $request['narration'],
            'note' => $request['note'],
            'image' => $image
        ]);
    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Asset::with('company', 'branch')->orderBy('created_at', 'desc')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                $btn .= '<a href="' . route("hr.asset.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
                $btn .= '<form method="POST" onsubmit="return confirmDelete(event, this);" action="' . route("hr.asset.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                //                }


                $btn .= '</div>';

                return $btn;

            })->addColumn('company', function ($row) {
                if ($row->company) {
                    return $row->company->name;

                } else {
                    return "N/A";
                }
            })->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }
            })->addColumn('amount', function ($row) {
                if ($row->amount) {
                    return number_format($row->amount);

                } else {
                    return "N/A";
                }
            })->addColumn('code', function ($row) {
                if ($row->code) {
                    return $row->code;

                } else {
                    return "N/A";
                }
            })->addColumn('purchase_date', function ($row) {
                if ($row->purchase_date) {
                    return Carbon::parse($row->purchase_date)->format('d M, Y');

                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'branch', 'employee', 'allow'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $asset = Asset::find($id);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageFile = $request->file('image');
            $image = ImageHelper::uploadImage($imageFile, 'asset_image');
        } else {
            $image = $asset->image;
        }
        $branch = Branch::find($request['branch_id']);
        $assetType = AssetType::find($request['asset_type_id']);

        $code = $request['code'] ?? $branch->emp_branch_code . '-' . $assetType->abbreviation . '-' . Carbon::parse($request['purchase_date'])->format('y') . '-' . Asset::max('id') + 1;
        $asset_data = [
            'credit_type' => $request['credit_type'],
            'credit_ledger' => $request['credit_ledger'],
            'asset_type_id' => $request['asset_type_id'],
            'name' => $request['name'],
            'code' => $code,
            'working' => isset($request['working']) ? 1 : 0,
            'company_id' => $request['company_id'],
            'branch_id' => $request['branch_id'],
            'purchase_date' => $request['purchase_date'],
            'invoice_number' => $request['invoice_number'],
            'manufacturer' => $request['manufacturer'],
            'serial_number' => $request['serial_number'],
            'end_date' => $request['end_date'],
            'amount' => $request['amount'],
            'depreciation' => $request['depreciation'],
            'sale_tax' => $request['sale_tax'],
            'income_tax' => $request['income_tax'],
            'narration' => $request['narration'],
            'note' => $request['note'],
            'image' => $image
        ];

        $asset->update($asset_data);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $asset = Asset::find($id);
        if ($asset) {
            $asset->delete();
        }
    }

}
