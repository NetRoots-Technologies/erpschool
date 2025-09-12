<?php

namespace App\Services;


use App\Models\Admin\BankAccount;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class BankAccountService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return BankAccount::create([
            'bank_id' => $request->bank_id,
            'bank_branch_id' => $request->bank_branch_id,
            'account_no' => $request->bank_account_no,
            'type' => $request->type,
        ]);
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = BankAccount::with('bank', 'bankBranch')->orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                $btn .= '<a href="' . route("admin.banks_accounts.edit", $row->id) . '" class="btn btn-primary btn-sm" style="margin-right: 4px;">Edit</a>';

                $btn .= '<button onclick="confirmDelete(' . $row->id . ')" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';

                $btn .= '<form id="delete-form-' . $row->id . '" method="POST" action="' . route("admin.banks_accounts.destroy", $row->id) . '" style="display: none;">';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';

                $btn .= '
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    function confirmDelete(id) {
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won\'t be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById("delete-form-" + id).submit();
                            }
                        });
                    }
                </script>
            ';

                $btn .= '</div>';

                return $btn;

            })

            ->addColumn('bank', function ($row) {
                if ($row->bank)
                    return $row->bank->name;
                else
                    return "N/A";

            })->addColumn('bank_branch', function ($row) {
                if ($row->bankBranch)
                    return $row->bankBranch->branch_name;
                else
                    return "N/A";

            })->addColumn('type', function ($row) {
                return $row->type ?? "N/A";
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $bankAccount = BankAccount::find($id);
        $bankAccount->update([
            'bank_id' => $request->bank_id,
            'bank_branch_id' => $request->bank_branch_id,
            'account_no' => $request->bank_account_no,
        ]);

        return  $bankAccount;
    }

    public function delete($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $bank = BankAccount::find($id);
        if ($bank) {
            $bank->delete();
        }
    }


}
