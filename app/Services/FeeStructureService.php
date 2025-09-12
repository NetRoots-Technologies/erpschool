<?php

namespace App\Services;

use App\Models\Admin\FeeStructure;
use App\Models\Admin\FeeSection;
use App\Models\Admin\FeeHead;
use App\Models\Admin\FeeStructureValue;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;


class FeeStructureService
{

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //        $totalAnnualAmount = $request->get('total_annual_amount');
        $totalMonthlyAmount = $request->get('total_monthly_amount');

        $feeStructure = FeeStructure::updateOrCreate(
            [
                'session_id' => $request->get('session_id'),
                'company_id' => $request->get('company_id'),
                'branch_id' => $request->get('branch_id'),
                'class_id' => $request->get('class_id'),
            ],
            []
        );

        $feeStructure->update([
            //            'total_annual_amount' => $totalAnnualAmount,
            'total_month_amount' => $totalMonthlyAmount,
        ]);

        $monthly_amount = $request->get('monthly_amount') == null ? array() : $request->get('monthly_amount');

        foreach ($monthly_amount as $key => $amount) {
            $attributes = [
                'monthly_amount' => $amount,
                'fee_head_id' => $request->get('fee_head_id')[$key],
                'discount_percent' => $request->get('discount')[$key],
                'discount_rupees' => $request->get('discount_rupees')[$key],
                'claim1' => $request->get('claim_1')[$key],
                'claim2' => $request->get('claim_2')[$key],
                'total_amount_after_discount' => $request->get('total_amount_after_discount')[$key],

            ];

            if ($request->has('fee_structure_id') && isset($request->fee_structure_id[$key])) {
                $feeStructureId = $request->get('fee_structure_id')[$key];

                $feeStructureValue = FeeStructureValue::find($feeStructureId);

                if ($feeStructureValue) {
                    $feeStructureValue->update($attributes);
                }
            } else {
                $attributes['fee_structure_id'] = $feeStructure->id;
                FeeStructureValue::create($attributes);
            }
        }
    }



    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = FeeStructure::with('branch', 'company', 'AcademicClass')->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('action', function ($row) {

                //                $btn = ' <form class="delete_form" data-route="' . route("admin.company.destroy", $row->id) . '"   id="company-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = '<a href="' . route("admin.fee-structure.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';


                //                // if (Gate::allows('company-delete'))
//                $btn = $btn . ' <button data-id="company-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
//                $btn = $btn . method_field('DELETE') . '' . csrf_field();
//                $btn = $btn . ' </form>';
                return $btn;
            })
            ->addColumn('company', function ($row) {
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


            })->addColumn('AcademicClass', function ($row) {
                if ($row->AcademicClass) {
                    return $row->AcademicClass->name;

                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'feeHead', 'company', 'branch', 'AcademicClass'])
            ->make(true);
    }



}
