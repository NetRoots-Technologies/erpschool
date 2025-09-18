<?php

namespace App\Services;

use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
use App\Models\Fee\FeeStructure;
use App\Models\Admin\Student;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FeeCollectionService
{
    public function store($request)
    {
        if (!Gate::allows('FeeCollection-create')) {
            return abort(403);
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['created_by'] = Auth::id();
            $input['company_id'] = Auth::user()->company_id ?? $request->company_id;
            $input['branch_id'] = Auth::user()->branch_id ?? $request->branch_id;
            $input['collection_date'] = now();
            $input['receipt_number'] = $this->generateReceiptNumber();

            $feeCollection = FeeCollection::create($input);

            // Create fee collection details
            if ($request->has('fee_details') && is_array($request->fee_details)) {
                foreach ($request->fee_details as $detail) {
                    $detail['fee_collection_id'] = $feeCollection->id;
                    FeeCollectionDetail::create($detail);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Fee collection created successfully', 'data' => $feeCollection]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create fee collection: ' . $e->getMessage()], 500);
        }
    }

    public function getdata()
    {
        if (!Gate::allows('FeeCollection-list')) {
            return abort(403);
        }
        
        $query = FeeCollection::with(['student', 'company', 'branch', 'academicSession', 'createdBy'])
            ->orderBy('created_at', 'desc');

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $query->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        return Datatables::of($query)->addIndexColumn()
            ->addColumn('student', function ($row) {
                return $row->student ? $row->student->name : "N/A";
            })
            ->addColumn('receipt_number', function ($row) {
                return $row->receipt_number;
            })
            ->addColumn('collection_date', function ($row) {
                return $row->collection_date ? $row->collection_date->format('Y-m-d') : "N/A";
            })
            ->addColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 2);
            })
            ->addColumn('paid_amount', function ($row) {
                return number_format($row->paid_amount, 2);
            })
            ->addColumn('company', function ($row) {
                return $row->company ? $row->company->name : "N/A";
            })
            ->addColumn('branch', function ($row) {
                return $row->branch ? $row->branch->name : "N/A";
            })
            ->addColumn('payment_status', function ($row) {
                $status = $row->payment_status;
                $class = $status == 'paid' ? 'success' : ($status == 'partial' ? 'warning' : 'danger');
                return '<span class="badge badge-' . $class . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('status', function ($row) {
                return ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("fee.fee-collections.destroy", $row->id) . '" id="fee-collection-' . $row->id . '" method="POST">';

                if (Gate::allows('FeeCollection-list')) {
                    $btn .= '<a href="' . route("fee.fee-collections.receipt", $row->id) . '" class="btn btn-info text-white btn-sm" target="_blank">Receipt</a> ';
                }

                if (Gate::allows('FeeCollection-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm fee-collection-edit" data-fee-collection-edit=\'' . $row . '\'>Edit</a> ';
                }

                if (Gate::allows('FeeCollection-delete')) {
                    $btn .= '<button data-id="fee-collection-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                }

                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['action', 'status', 'payment_status'])
            ->make(true);
    }

    public function edit($id)
    {
        return FeeCollection::with(['student', 'company', 'branch', 'academicSession', 'feeCollectionDetails'])->find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('FeeCollection-edit')) {
            return abort(403);
        }
        
        DB::beginTransaction();
        try {
            $feeCollection = FeeCollection::find($id);
            $input = $request->all();
            $input['updated_by'] = Auth::id();
            $feeCollection->update($input);

            // Update fee collection details if provided
            if ($request->has('fee_details') && is_array($request->fee_details)) {
                // Delete existing details
                FeeCollectionDetail::where('fee_collection_id', $id)->delete();
                
                // Create new details
                foreach ($request->fee_details as $detail) {
                    $detail['fee_collection_id'] = $feeCollection->id;
                    FeeCollectionDetail::create($detail);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Fee collection updated successfully', 'data' => $feeCollection]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update fee collection: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        if (!Gate::allows('FeeCollection-delete')) {
            return abort(403);
        }
        
        DB::beginTransaction();
        try {
            $feeCollection = FeeCollection::findOrFail($id);
            
            // Delete related details first
            FeeCollectionDetail::where('fee_collection_id', $id)->delete();
            
            // Delete the main record
            $feeCollection->delete();

            DB::commit();
            return response()->json(['message' => 'Fee collection deleted successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to delete fee collection: ' . $e->getMessage()], 500);
        }
    }

    public function changeStatus($request)
    {
        $feeCollection = FeeCollection::find($request->id);
        if ($feeCollection) {
            $feeCollection->status = ($request->status == 'active') ? 1 : 0;
            $feeCollection->save();
            return $feeCollection;
        }
    }

    public function getStudentFeeDetails($request)
    {
        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $feeStructures = FeeStructure::where('class_id', $student->class_id)
            ->where('academic_session_id', $request->academic_session_id)
            ->where('status', 1)
            ->with(['feeCategory'])
            ->get();

        return response()->json(['student' => $student, 'fee_structures' => $feeStructures]);
    }

    public function generateReceipt($id)
    {
        $feeCollection = FeeCollection::with(['student', 'company', 'branch', 'academicSession', 'feeCollectionDetails.feeHead'])
            ->findOrFail($id);

        // Generate PDF or return view for receipt
        return view('fee.fee_collection.receipt', compact('feeCollection'));
    }

    private function generateReceiptNumber()
    {
        $lastReceipt = FeeCollection::orderBy('id', 'desc')->first();
        $number = $lastReceipt ? (int)substr($lastReceipt->receipt_number, -6) + 1 : 1;
        return 'RCP' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}