<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ProgressReportRemark; // model from migration below
use Illuminate\Support\Facades\Auth;

class StudentProgressController extends Controller
{
    public function storeRemarks(Request $request, $student_id)
    {
        $data = $request->validate([
            'remarks' => ['required', 'string', 'max:5000'],
        ]);
        $remark = ProgressReportRemark::updateOrCreate(
            ['student_id' => $student_id],
            [
                'remarks'    => $data['remarks'],
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->back()->with('success', 'Remarks saved successfully.');
    }
}
