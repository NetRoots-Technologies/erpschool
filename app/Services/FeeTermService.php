<?php

namespace App\Services;


use App\Models\Admin\FeeStructure;
use App\Models\Admin\FeeTerm;
use App\Models\Admin\FeeTermVoucher;
use DataTables;
use Illuminate\Support\Facades\Gate;


class FeeTermService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $existingTerm = FeeTerm::where('term', $request->input('term'))
            ->where('branch_id', $request->input('branch_id'))
            ->where('session_id', $request->input('session_id'))
            ->where('company_id', $request->input('company_id'))
            ->where('class_id', $request->input('class_id'))
            ->first();

        if (!$existingTerm) {
            foreach ($request->input('voucher_date') as $key => $voucherDate) {
                FeeTerm::create([
                    'branch_id' => $request->input('branch_id'),
                    'session_id' => $request->input('session_id'),
                    'company_id' => $request->input('company_id'),
                    'class_id' => $request->input('class_id'),
                    'term' => $request->input('term'),
                    'voucher_date' => $voucherDate,
                    'starting_date' => $request->input('starting_date')[$key],
                    'ending_date' => $request->input('ending_date')[$key],
                    'installment' => $key + 1,
                ]);

            }
            return 'success';
        } else {
            return 'exists';
        }
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = FeeTerm::with('company', 'branch', 'AcademicClass')->orderBy('created_at', 'desc')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                //                if (Gate::allows('Employee-edit'))
                $btn .= '<a href="' . route("admin.fee-terms.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                //                if (Gate::allows('Employee-destroy')) {
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("admin.fee-terms.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                //                }
    

                $btn .= '</div>';

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
            ->addColumn('voucherDate', function ($row) {
                if ($row->voucher_date) {
                    $voucherDates = $row->voucher_date;
                    return $voucherDates;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('startDate', function ($row) {
                if ($row->starting_date) {
                    $startingDates = $row->starting_date;
                    return $startingDates;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('endDate', function ($row) {
                if ($row->ending_date) {
                    $endingDates = $row->ending_date;

                    return $endingDates;
                } else {
                    return "N/A";
                }
            })->addColumn('term', function ($row) {
                if ($row->term) {
                    return $row->term . '-Term';
                } else {
                    return "N/A";
                }
            })->addColumn('installment', function ($row) {
                if ($row->installment) {
                    return $row->installment . '-Installment';
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'company', 'branch', 'AcademicClass', 'voucherDate', 'startDate', 'endDate', 'term', 'installment'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeTerm = FeeTerm::find($id);
        $feeTerm->update([
            'voucher_date' => $request->input('starting_date'),
            'starting_date' => $request->input('starting_date'),
            'ending_date' => $request->input('ending_date'),
            'installment' => $feeTerm->installment,
        ]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeTerm = FeeTerm::find($id);
        if ($feeTerm) {
            $feeTerm->delete();
        }
    }

}
