<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Helper\CoreAccounts;
use App\Models\Admin\Branch;
use App\Models\Admin\Groups;
use App\Helper\CoreAccounts2;
use App\Models\Account\Entry;
use App\Models\Account\Group;
use App\Models\Admin\Ledgers;
use App\Models\Account\Ledger;
use App\Models\Admin\EntryItems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LedgerService
{
    public function createAutoLedgers($group_ids, $ledger_name, $branch_id, $model_name, $model_type_id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $groups = $this->getGroups($group_ids);
        $branch = Branch::where('id', $branch_id)->first();
        foreach ($groups as $group) {
            if ($branch) {
                $data['name'] = $ledger_name . " [$branch->name]";
            } else {
                $data['name'] = $ledger_name;
            }

            $data["branch_id"] = $branch->id ?? 1;
            $data["balanceType"] = 'd';
            $data["group_id"] = $group->id;
            $data["group_number"] = $group->number;
            $data["account_type_id"] = $group->account_type_id;
            $data["parent_id"] = $model_type_id;
            $data["parent_type_id"] = $model_type_id;
            $data["parent_type"] = $model_name;

            $this->createLedger($data);
        }
    }

    public function getGroup($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Group::find($id);
    }

    public function getGroups($ids)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Group::whereIn("id", $ids)->get();
    }

    public function getLedger($parent_type, $parent_type_id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Ledgers::where('parent_type', $parent_type)
            ->where('parent_type_id', $parent_type_id)
            ->first();
    }


    public function createLedger($data): Ledgers
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {

            $ledger = new Ledgers;
            $number = $this->generateLevelAndNumber($data["group_id"])['number'];

            $ledger->name = $data["name"];
            $ledger->parent_type = $data["parent_type"];
            $ledger->parent_id = $data["parent_id"];
            $ledger->parent_type_id = $data["parent_type_id"];
            $ledger->group_id = $data["group_id"];
            $ledger->group_number = $data["group_number"];
            $ledger->balance_type = $data["balanceType"];
            $ledger->dl_balance_type = $data["balanceType"];
            $ledger->gl_balance_type = $data["balanceType"];
            $ledger->branch_id = $data["branch_id"];
            $ledger->account_type_id = $data["account_type_id"];
            $ledger->number = $number;

            $ledger->opening_balance = 0;
            $ledger->closing_balance = 0;
            $ledger->code = null;
            $ledger->dl_opening_balance = 0;
            $ledger->dl_closing_balance = 0;
            $ledger->gl_opening_balance = 0;
            $ledger->gl_closing_balance = 0;
            $ledger->status = 1;
            $ledger->created_by = auth()->id();
            $ledger->save();
            return $ledger;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getLedgers($group_ids, $parent_type, $parent_type_id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Ledgers::whereIn('group_id', $group_ids)->where('parent_type', $parent_type)
            ->where('parent_type_id', $parent_type_id)
            ->get();
    }

    // public function createGroup($data): Group
    // {
    //     try {
    //         $group = Groups::find($data['parent_id']);

    //         if (!$group) {
    //             throw new Exception('No Group found');
    //         }

    //         $data['created_by'] = auth()->user()->id;
    //         $data['updated_by'] = auth()->user()->id;
    //         $data['account_type_id'] = $group->account_type_id;
    //         $data['parent_id'] = $group->id;

    //         $levelAndNumber = $this->generateLevelAndNumber($group->id);

    //         $data['number'] = $levelAndNumber['number'];
    //         $data['level'] = $levelAndNumber['level'];

    //         $newGroup = new Group();


    //         return array(
    //             'status' => true,
    //             'error' => 'Group has been created',
    //             'id' => $Groups->id,
    //             'groups' => $Groups,
    //         );
    //     } catch (Exception $ex) {
    //         throw $ex;
    //     }
    // }
    public function credit()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    public function debit()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    public static function generateLevelAndNumber($parent_id, $adittional_number = 0)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ParentGroup = Groups::findOrFail($parent_id);
        return [
            'number' => $ParentGroup->number . '-' . sprintf('%0' . (count(explode('-', $ParentGroup->number)) + 1) . 'd', (Ledgers::where(['group_id' => $ParentGroup->id])->count() + ($adittional_number + 1))),
            'level' => ++$ParentGroup->level,
        ];
    }

    public function createEntry($data)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {

            $entry = new Entry();

            $v_number_series = $this->getVouchertMaxId($data['entry_type_id'], $data['branch_id']);

            $entry->number = str_pad($v_number_series, 6, '0', STR_PAD_LEFT);
            $entry->dr_total = (float) $data['amount'];
            $entry->cr_total = (float) $data['amount'];
            $entry->narration = $data['narration'];
            $entry->branch_id = (int) $data['branch_id'];
            $entry->entry_type_id = $data['entry_type_id'];

            $entry->voucher_date = carbon::now()->format('Y-m-d');

            $entry->save();
            return $entry;
        } catch (Exception $e) {
            throw $e;
        }


    }

    public function createEntryItems($data)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $entry_items = new EntryItems();

        $entry_items->entry_type_id = $data['entry_type_id'];
        $entry_items->entry_id = $data['entry_id'];
        $entry_items->ledger_id = $data['ledger_id'];
        $entry_items->amount = $data['amount'];
        $entry_items->dc = $data['balanceType'];
        $entry_items->narration = $data['narration'];
        $entry_items->status = 1;
        $entry_items->voucher_date = carbon::now();
        $entry_items->save();

        return $entry_items;
    }

    static function getVouchertMaxId($type, $branch_id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $parent_data = Entry::where('entry_type_id', $type)
            ->where('branch_id', $branch_id)->max('number');

        if ($parent_data > 0) {
            $parent_id = $parent_data + 1;
        } else {
            $parent_id = 1;
        }

        return $parent_id;
    }
}

