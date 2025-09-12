<?php
namespace App\Helpers;

use App\Models\Admin\Groups;
use App\Models\Admin\Ledgers;

/**
 * Class to store the entire group tree
 */
class LedgersTree
{
    var $id = 0;
    var $name = '';
    var $code = '';
    var $number = '';
    var $level = '';

    var $children_groups = array();
    var $children_ledgers = array();

    var $counter = 0;

    var $current_id = -1;

    var $restriction_bankcash = 1;

    var $default_text = 'Please select...';

    /**
     * Initializer
     */
    function LedgersTree()
    {
        return;
    }

    /**
     * Setup which group id to start from
     */
    function build($id)
    {
        if ($id == 0) {
            $this->id = 0;
            $this->name = "None";
        } else {
            $group = Groups::where(['id' => $id])->first()->toArray();
            $this->id = $group['id'];
            $this->name = $group['name'];
            $this->code = $group['code'];
            // $this->number = $group['number'];
            $this->level = $group['level'];
        }
        // if($this->level < 6) {
        // $this->add_sub_ledgers();
        //}
        $this->add_sub_groups();
    }

    /**
     * Find and add subgroups as objects
     */
    function add_sub_groups()
    {
        /* If primary group sort by id else sort by name */
        if ($this->id == 0) {
            $child_group_q = Groups::where(['parent_id' => $this->id])->OrderBy('code', 'asc')->get()->toArray();
        } else {
            $child_group_q = Groups::where(['parent_id' => $this->id])->OrderBy('code', 'asc')->get()->toArray();
        }

        $counter = 0;
        foreach ($child_group_q as $row) {

            //dd($row);
            /* Create new AccountList object */
            $this->children_groups[$counter] = new LedgersTree();
            /* Initial setup */
            $this->children_groups[$counter]->current_id = $this->current_id;
            $this->children_groups[$counter]->build($row['id']);
            $counter++;
        }

    }

    /**
     * Find and add subledgers as array items
     */
    function add_sub_ledgers()
    {
        $child_ledger_q = Ledgers::where(['group_id' => $this->id])->OrderBy('code', 'asc')->get()->toArray();
        $counter = 0;
        if (count($child_ledger_q)) {
            foreach ($child_ledger_q as $row) {
                $this->children_ledgers[$counter]['id'] = $row['id'];
                $this->children_ledgers[$counter]['name'] = $row['name'];
                $this->children_ledgers[$counter]['opening_balance'] = $row['opening_balance'];
                $this->children_ledgers[$counter]['closing_balance'] = $row['closing_balance'];
                $this->children_ledgers[$counter]['balance_type'] = $row['balance_type'];
                $this->children_ledgers[$counter]['dl_opening_balance'] = $row['dl_opening_balance'];
                $this->children_ledgers[$counter]['dl_balance_type'] = $row['dl_balance_type'];
                $this->children_ledgers[$counter]['gl_opening_balance'] = $row['gl_opening_balance'];
                $this->children_ledgers[$counter]['gl_balance_type'] = $row['gl_balance_type'];
                $this->children_ledgers[$counter]['code'] = $row['code'];
                $this->children_ledgers[$counter]['number'] = $row['number'];
                $this->children_ledgers[$counter]['group_number'] = $row['group_number'];
                $counter++;
            }
        }
    }

    var $ledgerList = array();

    /* Convert ledger tree to a list */
    public function toList($tree, $c = 0)
    {
        /* Add group name to list */
        if ($tree->id != 0) {
            /* Set the group id to negative value since we want to disable it */
            //			$this->ledgerList[-$tree->id] = $this->space($c) . $this->toCodeWithName($tree->code, $tree->name);
            $this->ledgerList[-$tree->id] = array(
                'id' => $tree->id,
                'name' => $this->space($c) . $this->toCodeWithNumber($tree->number, $tree->name),
                'code' => $tree->code,
                'number' => $tree->number,
                'level' => $tree->level,
            );
        } else {
            $this->ledgerList[0] = $this->default_text;
        }

        /* Add child ledgers */
        if (count($tree->children_ledgers) > 0) {
            $c++;
            foreach ($tree->children_ledgers as $id => $data) {
                $ledger_name = $this->toCodeWithNumber($data['number'], $data['name']);

                $this->ledgerList[$data['id']] = array(
                    'id' => $data['id'],
                    'name' => $this->space($c) . $ledger_name,
                    'opening_balance' => $data['opening_balance'],
                    'closing_balance' => $data['closing_balance'],
                    'balance_type' => $data['balance_type'],
                    'dl_opening_balance' => $data['dl_opening_balance'],
                    'dl_balance_type' => $data['dl_balance_type'],
                    'gl_opening_balance' => $data['gl_opening_balance'],
                    'gl_balance_type' => $data['gl_balance_type'],
                    'code' => $data['code'],
                    'number' => $data['number'],
                    'group_number' => $data['group_number'],
                );

                /* Add ledgers as per restrictions */
                //				if ($this->restriction_bankcash == 1 ||
//					$this->restriction_bankcash == 2 ||
//					$this->restriction_bankcash == 3) {
//					/* All ledgers */
//					$this->ledgerList[$data['id']] = $this->space($c) . $ledger_name;
//				} else if ($this->restriction_bankcash == 4) {
//					/* Only bank or cash ledgers */
//					if ($data['type'] == 1) {
//						$this->ledgerList[$data['id']] = $this->space($c) . $ledger_name;
//					}
//
//				} else if ($this->restriction_bankcash == 5) {
//					/* Only NON bank or cash ledgers */
//					if ($data['type'] == 0) {
//						$this->ledgerList[$data['id']] = $this->space($c) . $ledger_name;
//					}
//				}
            }
            $c--;
        }

        /* Process child groups recursively */
        foreach ($tree->children_groups as $id => $data) {
            $c++;
            $this->toList($data, $c);
            $c--;
        }
    }

    function space($count)
    {
        $str = '';
        for ($i = 1; $i <= $count; $i++) {
            $str .= '&nbsp;&nbsp;&nbsp;';
        }
        return $str;
    }

    function toCodeWithName($code, $name)
    {
        if (strlen($code) <= 0) {
            return $name;
        } else {
            return '[' . $code . '] ' . $name;
        }
    }

    function toCodeWithNumber($number, $name)
    {
        if (strlen($number) <= 0) {
            return $name;
        } else {
            //return $number. ' - ' . $name;
            return $name;
        }
    }
}
