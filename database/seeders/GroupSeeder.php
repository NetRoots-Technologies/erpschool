<?php

namespace Database\Seeders;

use App\Models\Account\Group;
use App\Models\Accounts\AccountLedger;
use Illuminate\Database\Seeder;
use App\Models\Account\Ledger;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        $parent1 = Group::create([
            'name' => ' Assets',
            'code' => '000',
            'level' => ' 1',
            'parent_id' => '0 ',
            'account_type_id' => '1 ',
            'status' => ' 1',
        ]);
        //2
        $parent2 = Group::create([
            'name' => ' Liabilities & Owners Equity',
            'code' => '000',
            'level' => ' 1',
            'parent_id' => '0 ',
            'account_type_id' => '5 ',
            'status' => ' 1',
        ]);
        //3
        $parent3 = Group::create([
            'name' => ' Incomes',
            'code' => '000',
            'level' => ' 1',
            'parent_id' => '0 ',
            'account_type_id' => '4 ',
            'status' => ' 1',
        ]);
        //4
        $parent4 = Group::create([
            'name' => ' Expenses',
            'code' => '000',
            'level' => ' 1',
            'parent_id' => '0 ',
            'account_type_id' => '3 ',
            'status' => ' 1',
        ]);
        // Assets\
        //5
        //Fixed Assets
        $child1 = Group::create([
            'name' => 'Fixed Assets',
            'code' => '0001-0',
            'level' => ' 2',
            'parent_id' => $parent1->id,
            'account_type_id' => '1 ',
            'status' => ' 1',
        ]);
        //6
        $Building_Group = Group::create([
            'name' => 'Building',
            'code' => '0001-01-00',
            'level' => ' 2',
            'parent_id' => $child1->id,
            'account_type_id' => '1 ',
            'status' => ' 1',
        ]);
        //Building Ledger
        $Building_Ledgers = AccountLedger::insert([

            [
                'name' => 'Building',
                'code' => '0001-01-001-00',
                'group_number' => $Building_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'c',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Building Ledger
//7

        $Devices_Group = Group::create([
            'name' => 'Devices',
            'code' => '0001-01-00',
            'level' => ' 2',
            'parent_id' => $child1->id,
            'account_type_id' => '1 ',
            'status' => ' 1',
        ]);
        //Devices Ledger
        $Devices_Ledgers = Ledgers::insert([

            [
                'name' => 'Devices',
                'code' => '0001-01-002-00',
                'group_number' => $Devices_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'c',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Device Ledger

        //Current Assets
//8
        $child2 = Group::create([
            'name' => 'Current Assets',
            'code' => '0001-0',
            'level' => ' 2',
            'parent_id' => $parent1->id,
            'account_type_id' => '1 ',
            'status' => ' 1',
        ]);
        // Assets end


        //Current Assets
        //9
        $Receiveables = Group::create([
            'name' => ' Receiveables',
            'code' => '0001-02-00',
            'level' => ' 3',
            'parent_id' => $child2->id,
            'account_type_id' => '1 ',
            'status' => ' 1',

        ]);
        //10
        $Student_Group = Group::create([
            'name' => ' Students',
            'code' => '0001-02-001-00',
            'level' => ' 3',
            'parent_id' => $Receiveables->id,
            'account_type_id' => '1 ',
            'status' => ' 1',

        ]);
        //11
        $Fee_Group = Group::create([
            'name' => 'Current Asset Fee',
            'code' => '0001-02-001-001-00',
            'level' => ' 3',
            'parent_id' => $Student_Group->id,
            'account_type_id' => '1 ',
            'status' => ' 1',

        ]);

        //Fee Ledger
        $Fee_Ledgers = Ledgers::insert([

            [
                'name' => 'Current Assets Fee',
                'code' => '0001-02-001-001-001-00',
                'group_number' => $Fee_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Fee Ledger
//12
        $Tools_Fee_Group = Group::create([
            'name' => 'Current Assets Tools Fee',
            'code' => '0001-02-001-001-00',
            'level' => ' 3',
            'parent_id' => $Student_Group->id,
            'account_type_id' => '1 ',
            'status' => ' 1',

        ]);

        //Tool Fee Ledger
        $Tool_Fee_Ledgers = Ledgers::insert([

            [
                'name' => 'Current Assets Tool Fee',
                'code' => '0001-02-001-001-001-00',
                'group_number' => $Tools_Fee_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Tool Fee Ledger
//13
        $Cash_Group = Group::create([
            'name' => 'Current Assets Cash',
            'code' => '0001-02-001-00',
            'level' => ' 3',
            'parent_id' => $Receiveables->id,
            'account_type_id' => '1 ',
            'status' => ' 1',

        ]);
        //Cash in hand ledger
        $Cash_in_hand_Ledgers = Ledgers::insert([

            [
                'name' => 'Current Assets Cash in Hand',
                'code' => '0001-02-001-002-00',
                'group_number' => $Cash_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Cash in hand ledger
        //14
        $Bank_Group = Group::create([
            'name' => 'Current Assets Bank',
            'code' => '0001-02-001-00',
            'level' => ' 3',
            'parent_id' => $Receiveables->id,
            'account_type_id' => '1 ',
            'status' => ' 1',

        ]);
        //15
        $Bank_Branches_Group = Group::create([
            'name' => 'Current Assets Bank Branches',
            'code' => '0001-02-001-003-00',
            'level' => ' 3',
            'parent_id' => $Bank_Group->id,
            'account_type_id' => '1 ',
            'status' => ' 1',

        ]);

        //Bank Ledger
        $Bank_Name_Ledgers = Ledgers::insert([

            [
                'name' => 'HBL',
                'code' => '0001-02-001-003-001-00',
                'group_number' => $Bank_Branches_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Bank Ledger




        //16
        $Personal_Group = Group::create([
            'name' => 'Current Assets Personals',
            'code' => '0001-02-001-00',
            'level' => ' 3',
            'parent_id' => $Receiveables->id,
            'account_type_id' => '1 ',
            'status' => ' 1',
        ]);
        //Personal Ledger
        $Personal_Ledgers = Ledgers::insert([

            [
                'name' => 'Personal',
                'code' => '0001-02-001-004-00',
                'group_number' => $Personal_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Personal Ledger

        // Current Assets end

        //Liabilities
//17
        $Current_Liability = Group::create([
            'name' => 'Current Liability Group',
            'code' => '0002-0',
            'level' => ' 1',
            'parent_id' => $parent2->id,
            'account_type_id' => '5 ',
            'status' => ' 1',
        ]);
        //Current Liability Ledger
        $Current_Liability_Ledgers = Ledgers::insert([

            [
                'name' => 'Current Liability',
                'code' => '0002-01-00',
                'group_number' => $Current_Liability->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Current Liability Ledger
        //18
        $Current_Liability_Salaries_Group = Group::create([
            'name' => 'Current Liability Salaries',
            'code' => '0002-0',
            'level' => ' 1',
            'parent_id' => $parent2->id,
            'account_type_id' => '5 ',
            'status' => ' 1',
        ]);
        //Current Salaries Ledger
        $Current_Liability_Ledgers = Ledgers::insert([

            [
                'name' => 'Current Liability Salaries',
                'code' => '0002-02-00',
                'group_number' => $Current_Liability_Salaries_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        // Current Salaries Ledger
        //19
        $Liability_Advances_Group = Group::create([
            'name' => 'Current Liability Advances',
            'code' => '0002-0',
            'level' => ' 1',
            'parent_id' => $parent2->id,
            'account_type_id' => '5 ',
            'status' => ' 1',
        ]);
        //Current Advances Ledger
        $Current_Liability_Ledgers = Ledgers::insert([

            [
                'name' => 'Current Liability Advances',
                'code' => '0002-03-00',
                'group_number' => $Liability_Advances_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Current Advcances Ledger
        //Liabilities end

        //Incomes Start
//20
        $Student_Income_Group = Group::create([
            'name' => 'Income Group Students',
            'code' => '0003-0',
            'level' => ' 1',
            'parent_id' => $parent3->id,
            'account_type_id' => '4 ',
            'status' => ' 1',
        ]);
        //21
        $Student_Income_Fee_Group = Group::create([
            'name' => 'Income Group Fee',
            'code' => '0003-01-00',
            'level' => ' 1',
            'parent_id' => $Student_Income_Group->id,
            'account_type_id' => '4 ',
            'status' => ' 1',
        ]);
        //Income Fee Ledger
        $Income_Fee_Ledgers = Ledgers::insert([

            [
                'name' => 'Income Group Fee',
                'code' => '0003-01-001-00',
                'group_number' => $Student_Income_Fee_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Income Fee Ledger
//22
        $Student_Income_Tools_Fee_Group = Group::create([
            'name' => 'Income Group Tools Fee',
            'code' => '0003-01-00',
            'level' => ' 1',
            'parent_id' => $Student_Income_Group->id,
            'account_type_id' => '4 ',
            'status' => ' 1',
        ]);

        //Income Tool Fee Ledger
        $Income_Tool_Fee_Ledgers = Ledgers::insert([

            [
                'name' => 'Income Group Tool Fee',
                'code' => '0003-01-002-00',
                'group_number' => $Student_Income_Tools_Fee_Group->code,
                'balance_type' => 'd',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'd',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'd',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Income Tool Fee Ledger



        //Incomes end

        //Expense
//23
        $Rent_Group = Group::create([
            'name' => 'Expense Group Rents',
            'code' => '0004-0',
            'level' => ' 1',
            'parent_id' => $parent4->id,
            'account_type_id' => '3 ',
            'status' => ' 1',
        ]);
        //Expense Ledger
        $Rent_Ledgers = Ledgers::insert([

            [
                'name' => 'Expense Group Rent',
                'code' => '0004-01-00',
                'group_number' => $Rent_Group->code,
                'balance_type' => 'c',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'c',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'c',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Expense Ledger
        //24
        $Expense_Salaries_Group = Group::create([
            'name' => 'Expense Group Salaries',
            'code' => '0004-0',
            'level' => ' 1',
            'parent_id' => $parent4->id,
            'account_type_id' => '3 ',
            'status' => ' 1',
        ]);
        //Expense Salaries Ledger
        $Expense_Salaries_Ledgers = Ledgers::insert([

            [
                'name' => 'Expense Group Salaries',
                'code' => '0004-02-0',
                'group_number' => $Expense_Salaries_Group->code,
                'balance_type' => 'c',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'c',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'c',
                'account_type_id' => 1,
                'parent_type' => '',

                'branch_id' => null,
            ],
        ]);
        //Expense Salaries Ledger
        //25
        $Expense_Others_Group = Group::create([
            'name' => 'Expense Group Others',
            'code' => '0004-0',
            'level' => ' 1',
            'parent_id' => $parent4->id,
            'account_type_id' => '3 ',
            'status' => ' 1',
        ]);
        //Expense Others Ledger
        $Expense_Others_Ledgers = Ledgers::insert([

            [
                'name' => 'Expense Group Others',
                'code' => '0004-03-00',
                'group_number' => $Expense_Others_Group->code,
                'balance_type' => 'c',
                'opening_balance' => '0',
                'closing_balance' => '0',
                'dl_opening_balance' => '0',
                'dl_closing_balance' => '0',
                'dl_balance_type' => 'c',
                'gl_opening_balance' => '0',
                'gl_closing_balance' => '0',
                'gl_balance_type' => 'c',
                'account_type_id' => 1,
                'parent_type' => '',
                'branch_id' => null,
            ],
        ]);
        //Expense Others Ledger

        //Expense end

    }


    // public function run()
    // {
    //     //1
    //     $parent1 = Group::create([
    //         'name' => 'Assets',
    //         'code' => '1',
    //         'level' => '1',
    //         'parent_id' => '0',
    //         'account_type_id' => 0,
    //         'status' => '1',
    //     ]);
    //     $parent2 = Group::create([
    //         'name' => 'CAPITAL AND RESERVES',
    //         'code' => '2',
    //         'level' => '1',
    //         'parent_id' => '0',
    //         'account_type_id' => 0,
    //         'status' => '1',
    //     ]);
    //     $parent3 = Group::create([
    //         'name' => 'LIABLITIES',
    //         'code' => '3',
    //         'level' => '1',
    //         'parent_id' => '0',
    //         'account_type_id' => 0,
    //         'status' => '1',
    //     ]);
    //     $parent4 = Group::create([
    //         'name' => 'REVENUE',
    //         'code' => '4',
    //         'level' => '1',
    //         'parent_id' => '0',
    //         'account_type_id' => 0,
    //         'status' => '1',
    //     ]);
    //     $parent5 = Group::create([
    //         'name' => 'COST OF GOODS',
    //         'code' => '5',
    //         'level' => '1',
    //         'parent_id' => '0',
    //         'account_type_id' =>0,
    //         'status' => '1',
    //     ]);
    //     $parent6 = Group::create([
    //         'name' => 'ADMINISTRATIVE EXPENSE',
    //         'code' => '6',
    //         'level' => '1',
    //         'parent_id' => '0',
    //         'account_type_id' => 0,
    //         'status' => '1',
    //     ]);
    //     $parent7 = Group::create([
    //         'name' => 'FINANCIAL CHARGES',
    //         'code' => '7',
    //         'level' => '1',
    //         'parent_id' => '0',
    //         'account_type_id' => 0,
    //         'status' => '1',
    //     ]);

    //     //Assets
    //     $child1 = Group::create([
    //         'name' => 'NON CURRENT ASSETS',
    //         'code' => '1-01',
    //         'level' => '2',
    //         'parent_id' => $parent1->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);
    //     $child2 = Group::create([
    //         'name' => 'CURRENT ASSETS',
    //         'code' => '1-02',
    //         'level' => '2',
    //         'parent_id' => $parent1->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     //non current assets
    //     $grandChild1 = Group::create([
    //         'name' => 'PROPERTY PLANT AND EQUIPMENT',
    //         'code' => '1-01-001',
    //         'level' => '3',
    //         'parent_id' => $child1->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     //current assets
    //     $grandChild2 = Group::create([
    //         'name' => 'CASH AND EQUIVALENTS',
    //         'code' => '1-02-001',
    //         'level' => '3',
    //         'parent_id' => $child2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild3 = Group::create([
    //         'name' => 'BANK BALANCE',
    //         'code' => '1-02-002',
    //         'level' => '3',
    //         'parent_id' => $child2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild4 = Group::create([
    //         'name' => 'STOCK IN HAND',
    //         'code' => '1-02-003',
    //         'level' => '3',
    //         'parent_id' => $child2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild5 = Group::create([
    //         'name' => 'ADVANCE TAXES',
    //         'code' => '1-02-004',
    //         'level' => '3',
    //         'parent_id' => $child2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild6 = Group::create([
    //         'name' => 'TRADES RECEIVABLES',
    //         'code' => '1-02-005',
    //         'level' => '3',
    //         'parent_id' => $child2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild7 = Group::create([
    //         'name' => 'ADVANCES',
    //         'code' => '1-02-006',
    //         'level' => '3',
    //         'parent_id' => $child2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // CAPITAL AND RESERVES
    //     $child3 = Group::create([
    //         'name' => "PARTNER'S CAPITAL ACCOUNT",
    //         'code' => '2-01',
    //         'level' => '2',
    //         'parent_id' => $parent2->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild8 = Group::create([
    //         'name' => 'ACCUMULATED CAPITAL AND DRAWING ACCOUNTS',
    //         'code' => '2-01-001',
    //         'level' => '3',
    //         'parent_id' => $child3->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     // LIABILITIES
    //     $child4 = Group::create([
    //         'name' => 'NON CURRENT LIABILITIES',
    //         'code' => '3-01',
    //         'level' => '2',
    //         'parent_id' => $parent3->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $child5 = Group::create([
    //         'name' => 'CURRENT LIABILITIES',
    //         'code' => '3-02',
    //         'level' => '2',
    //         'parent_id' => $parent3->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // NON CURRENT LIABILITIES
    //     $grandChild9 = Group::create([
    //         'name' => 'PAYABLES',
    //         'code' => '3-01-001',
    //         'level' => '3',
    //         'parent_id' => $child4->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // CURRENT LIABILITIES
    //     $grandChild10 = Group::create([
    //         'name' => 'TRADE AND OTHER PAYABLES',
    //         'code' => '3-02-001',
    //         'level' => '3',
    //         'parent_id' => $child5->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild11 = Group::create([
    //         'name' => 'ADVANCE TAX DEDUCTED AND PAYABLE',
    //         'code' => '3-02-002',
    //         'level' => '3',
    //         'parent_id' => $child5->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild12 = Group::create([
    //         'name' => 'WITHHOLDING TAX DEDUCTED AND OTHER PAYABLES',
    //         'code' => '3-02-003',
    //         'level' => '3',
    //         'parent_id' => $child5->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild13 = Group::create([
    //         'name' => 'INTRA COMPANY',
    //         'code' => '3-02-004',
    //         'level' => '3',
    //         'parent_id' => $child5->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild14 = Group::create([
    //         'name' => 'SALES TAX PAYABLE',
    //         'code' => '3-02-005',
    //         'level' => '3',
    //         'parent_id' => $child5->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // REVENUE
    //     $child6 = Group::create([
    //         'name' => 'OPERATIONAL REVENUE',
    //         'code' => '4-01',
    //         'level' => '2',
    //         'parent_id' => $parent4->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     $child7 = Group::create([
    //         'name' => 'OTHER INCOME',
    //         'code' => '4-02',
    //         'level' => '2',
    //         'parent_id' => $parent4->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     // Under OPERATIONAL REVENUE
    //     $grandChild15 = Group::create([
    //         'name' => 'PROFESSIONAL RECEIPTS',
    //         'code' => '4-01-001',
    //         'level' => '3',
    //         'parent_id' => $child6->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild16 = Group::create([
    //         'name' => 'DISCOUNT',
    //         'code' => '4-01-002',
    //         'level' => '3',
    //         'parent_id' => $child6->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     // OTHER INCOME
    //     $grandChild17 = Group::create([
    //         'name' => 'PROFIT ON BANK ACCOUNT',
    //         'code' => '4-02-001',
    //         'level' => '3',
    //         'parent_id' => $child7->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild18 = Group::create([
    //         'name' => 'OTHERS',
    //         'code' => '4-02-002',
    //         'level' => '3',
    //         'parent_id' => $child7->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     //COST OF GOODS
    //     $child8 = Group::create([
    //         'name' => 'DIRECT COST',
    //         'code' => '5-01',
    //         'level' => '2',
    //         'parent_id' => $parent5->id,
    //         'account_type_id' => $parent5->id,
    //         'status' => '1',
    //     ]);

    //     // DIRECT COST
    //     $grandChild19 = Group::create([
    //         'name' => 'COGS',
    //         'code' => '5-01-001',
    //         'level' => '3',
    //         'parent_id' => $child8->id,
    //         'account_type_id' => $parent5->id,
    //         'status' => '1',
    //     ]);

    //     // ADMINISTRATIVE EXPENSE
    //     $child9 = Group::create([
    //         'name' => 'OPERATIONAL EXPENSE',
    //         'code' => '6-01',
    //         'level' => '2',
    //         'parent_id' => $parent6->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under OPERATIONAL EXPENSE
    //     $grandChild20 = Group::create([
    //         'name' => 'SALARIES AND WAGES',
    //         'code' => '6-01-001',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild21 = Group::create([
    //         'name' => 'NEWSPAPERS AND PERIODICALS',
    //         'code' => '6-01-002',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild22 = Group::create([
    //         'name' => 'TRAVELLING AND CONVENIENCE',
    //         'code' => '6-01-003',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild23 = Group::create([
    //         'name' => 'PRINTING AND STATIONERY',
    //         'code' => '6-01-004',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild24 = Group::create([
    //         'name' => 'RENT, RATES AND TAXES',
    //         'code' => '6-01-005',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild25 = Group::create([
    //         'name' => 'POSTAGE AND TELEGRAM',
    //         'code' => '6-01-006',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild26 = Group::create([
    //         'name' => 'CHARITY AND DONATIONS',
    //         'code' => '6-01-007',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild27 = Group::create([
    //         'name' => 'REPAIR AND MAINTENANCE',
    //         'code' => '6-01-008',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild28 = Group::create([
    //         'name' => 'MISC EXPENSES',
    //         'code' => '6-01-009',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild29 = Group::create([
    //         'name' => 'COMMUNICATION',
    //         'code' => '6-01-010',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild30 = Group::create([
    //         'name' => 'ENTERTAINMENT EXPENSE',
    //         'code' => '6-01-011',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild31 = Group::create([
    //         'name' => 'FEES & SUBSCRIPTIONS',
    //         'code' => '6-01-012',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild32 = Group::create([
    //         'name' => 'PROFESSIONAL FEES',
    //         'code' => '6-01-013',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $grandChild33 = Group::create([
    //         'name' => 'CLEANING',
    //         'code' => '6-01-014',
    //         'level' => '3',
    //         'parent_id' => $child9->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // FINANCIAL CHARGES
    //     $child10 = Group::create([
    //         'name' => 'BANK CHARGES',
    //         'code' => '7-01',
    //         'level' => '2',
    //         'parent_id' => $parent7->id,
    //         'account_type_id' => $parent7->id,
    //         'status' => '1',
    //     ]);

    //     $child11 = Group::create([
    //         'name' => 'INTEREST RATE',
    //         'code' => '7-02',
    //         'level' => '2',
    //         'parent_id' => $parent7->id,
    //         'account_type_id' => $parent7->id,
    //         'status' => '1',
    //     ]);

    //     // BANK CHARGES
    //     $grandChild34 = Group::create([
    //         'name' => 'BANK SERVICE CHARGES',
    //         'code' => '7-01-001',
    //         'level' => '3',
    //         'parent_id' => $child10->id,
    //         'account_type_id' => $parent7->id,
    //         'status' => '1',
    //     ]);

    //     //(great-grandchildren) 4TH LEVEL
    //     // PROPERTY PLANT AND EQUIPMENT 
    //     $subGroup1 = Group::create([
    //         'name' => 'FURNITURE AND FIXTURES',
    //         'code' => '1-01-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild1->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup2 = Group::create([
    //         'name' => 'ELECTRONIC EQUIPMENT',
    //         'code' => '1-01-001-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild1->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup3 = Group::create([
    //         'name' => 'VEHICLES AND AUTOMOTIVES',
    //         'code' => '1-01-001-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild1->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup4 = Group::create([
    //         'name' => 'OTHER NON CURRENT ASSETS',
    //         'code' => '1-01-001-0004',
    //         'level' => '4',
    //         'parent_id' => $grandChild1->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // Under CASH AND EQUIVALENTS
    //     $subGroup5 = Group::create([
    //         'name' => 'CASH IN HAND',
    //         'code' => '1-02-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup6 = Group::create([
    //         'name' => 'PETTY CASH',
    //         'code' => '1-02-001-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild2->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // Under BANK BALANCE
    //     $subGroup7 = Group::create([
    //         'name' => 'CURRENT BANK BALANCE',
    //         'code' => '1-02-002-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild3->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // Under STOCK IN HAND
    //     $subGroup8 = Group::create([
    //         'name' => 'INVENTORY ASSETS',
    //         'code' => '1-02-003-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild4->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // Under ADVANCE TAXES
    //     $subGroup9 = Group::create([
    //         'name' => 'WHT ON CASH WITHDRAWAL',
    //         'code' => '1-02-004-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild5->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup10 = Group::create([
    //         'name' => 'WHT ON BANK PROFIT',
    //         'code' => '1-02-004-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild5->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup11 = Group::create([
    //         'name' => 'WHT ADVANCE TAX ON PURCHASES',
    //         'code' => '1-02-004-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild5->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // Under TRADES RECEIVABLES
    //     $subGroup12 = Group::create([
    //         'name' => 'ACCOUNTS RECEIVABLE',
    //         'code' => '1-02-005-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild6->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup13 = Group::create([
    //         'name' => 'IN-TRANSIT RECEIVABLE',
    //         'code' => '1-02-005-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild6->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // Under ADVANCES
    //     $subGroup14 = Group::create([
    //         'name' => 'ADVANCE PAYMENTS',
    //         'code' => '1-02-006-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild7->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup15 = Group::create([
    //         'name' => 'ADVANCE TO EMPLOYEES',
    //         'code' => '1-02-006-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild7->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup16 = Group::create([
    //         'name' => 'LOAN TO EMPLOYEES',
    //         'code' => '1-02-006-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild7->id,
    //         'account_type_id' => $parent1->id,
    //         'status' => '1',
    //     ]);

    //     // Under ACCUMULATED CAPITAL AND DRAWING ACCOUNTS (grandChild8)
    //     $subGroup17 = Group::create([
    //         'name' => 'OPENING EQUITY',
    //         'code' => '2-01-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild8->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup18 = Group::create([
    //         'name' => 'PARTNER 1 CAPITAL ACCOUNT',
    //         'code' => '2-01-001-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild8->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup19 = Group::create([
    //         'name' => 'PARTNER 2 CAPITAL ACCOUNT',
    //         'code' => '2-01-001-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild8->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup20 = Group::create([
    //         'name' => 'PARTNER 3 CAPITAL ACCOUNT',
    //         'code' => '2-01-001-0004',
    //         'level' => '4',
    //         'parent_id' => $grandChild8->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup21 = Group::create([
    //         'name' => 'PARTNER 1 DRAWING ACCOUNT',
    //         'code' => '2-01-001-0005',
    //         'level' => '4',
    //         'parent_id' => $grandChild8->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup22 = Group::create([
    //         'name' => 'PARTNER 2 DRAWING ACCOUNT',
    //         'code' => '2-01-001-0006',
    //         'level' => '4',
    //         'parent_id' => $grandChild8->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup23 = Group::create([
    //         'name' => 'PARTNER 3 DRAWING ACCOUNT',
    //         'code' => '2-01-001-0007',
    //         'level' => '4',
    //         'parent_id' => $grandChild8->id,
    //         'account_type_id' => $parent2->id,
    //         'status' => '1',
    //     ]);

    //     // Under TRADE AND OTHER PAYABLES (grandChild10)
    //     $subGroup24 = Group::create([
    //         'name' => 'SALARIES AND WAGES PAYABLE',
    //         'code' => '3-02-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild10->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup25 = Group::create([
    //         'name' => 'ACCOUNTS PAYABLE',
    //         'code' => '3-02-001-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild10->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup26 = Group::create([
    //         'name' => 'PURCHASE IN-TRANSIT',
    //         'code' => '3-02-001-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild10->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // Under ADVANCE TAX DEDUCTED AND PAYABLE (grandChild11)
    //     $subGroup27 = Group::create([
    //         'name' => 'TAX DEDUCTED ON SERVICE U/S',
    //         'code' => '3-02-002-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild11->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup28 = Group::create([
    //         'name' => 'GST INPUT TAX',
    //         'code' => '3-02-002-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild11->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup29 = Group::create([
    //         'name' => 'GST OUTPUT TAX',
    //         'code' => '3-02-002-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild11->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // Under WITHHOLDING TAX DEDUCTED AND OTHER PAYABLES (grandChild12)
    //     $subGroup30 = Group::create([
    //         'name' => 'WHT DEDUCTED US/153 ON GOODS',
    //         'code' => '3-02-003-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild12->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup31 = Group::create([
    //         'name' => 'WHT DEDUCTED US/153 ON SERVICES',
    //         'code' => '3-02-003-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild12->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup32 = Group::create([
    //         'name' => 'WHT DEDUCTED US/153 ON SALARIES',
    //         'code' => '3-02-003-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild12->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // Under INTRA COMPANY (grandChild13)
    //     $subGroup33 = Group::create([
    //         'name' => 'INTRA COMPANY ACCOUNT',
    //         'code' => '3-02-004-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild13->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup34 = Group::create([
    //         'name' => 'INTRA COMPANY ACCOUNT BALANCE TRANSFER',
    //         'code' => '3-02-004-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild13->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup35 = Group::create([
    //         'name' => 'PURCHASE CONTROL ACCOUNT',
    //         'code' => '3-02-004-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild13->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // Under SALES TAX PAYABLE (grandChild14)
    //     $subGroup36 = Group::create([
    //         'name' => 'INPUT SALES TAX',
    //         'code' => '3-02-005-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild14->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup37 = Group::create([
    //         'name' => 'OUTPUT SALES TAX',
    //         'code' => '3-02-005-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild14->id,
    //         'account_type_id' => $parent3->id,
    //         'status' => '1',
    //     ]);

    //     // Under PROFIT ON BANK ACCOUNT (grandChild17)
    //     $subGroup38 = Group::create([
    //         'name' => 'PROFIT ON SAVING ACCOUNT',
    //         'code' => '4-02-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild17->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     // Under OTHERS (grandChild18)
    //     $subGroup39 = Group::create([
    //         'name' => 'SCRAP SALE',
    //         'code' => '4-02-002-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild18->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup40 = Group::create([
    //         'name' => 'EXCHANGE GAIN (LOSS)',
    //         'code' => '4-02-002-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild18->id,
    //         'account_type_id' => $parent4->id,
    //         'status' => '1',
    //     ]);

    //     // Under COGS (grandChild19)
    //     $subGroup41 = Group::create([
    //         'name' => 'INVENTORY CONSUMED',
    //         'code' => '5-01-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild19->id,
    //         'account_type_id' => $parent5->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup42 = Group::create([
    //         'name' => 'INVENTORY ADJUSTMENTS',
    //         'code' => '5-01-001-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild19->id,
    //         'account_type_id' => $parent5->id,
    //         'status' => '1',
    //     ]);

    //     // Under SALARIES AND WAGES (grandChild20)
    //     $subGroup43 = Group::create([
    //         'name' => 'STAFF SALARIES',
    //         'code' => '6-01-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild20->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup44 = Group::create([
    //         'name' => 'OTHER ALLOWANCES',
    //         'code' => '6-01-001-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild20->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup45 = Group::create([
    //         'name' => 'STAFF WELFARE',
    //         'code' => '6-01-001-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild20->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under TRAVELLING AND CONVENIENCE (grandChild22)
    //     $subGroup46 = Group::create([
    //         'name' => 'TRAVELING',
    //         'code' => '6-01-003-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild22->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup47 = Group::create([
    //         'name' => 'FUEL CHARGES',
    //         'code' => '6-01-003-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild22->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under PRINTING AND STATIONERY (grandChild23)
    //     $subGroup48 = Group::create([
    //         'name' => 'PRINTING COST',
    //         'code' => '6-01-004-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild23->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup49 = Group::create([
    //         'name' => 'STATIONERY COST',
    //         'code' => '6-01-004-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild23->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under RENT, RATES AND TAXES (grandChild24)
    //     $subGroup50 = Group::create([
    //         'name' => 'MARKET RENT',
    //         'code' => '6-01-005-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild24->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup51 = Group::create([
    //         'name' => 'GODOWN RENT',
    //         'code' => '6-01-005-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild24->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under POSTAGE AND TELEGRAM (grandChild25)
    //     $subGroup52 = Group::create([
    //         'name' => 'TELEGRAM EXPENSE',
    //         'code' => '6-01-006-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild25->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup53 = Group::create([
    //         'name' => 'POSTAGE EXPENSE',
    //         'code' => '6-01-006-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild25->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup54 = Group::create([
    //         'name' => 'FREIGHT CHARGES',
    //         'code' => '6-01-006-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild25->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under CHARITY AND DONATIONS (grandChild26)
    //     $subGroup55 = Group::create([
    //         'name' => 'ZAKAT DEDUCTION',
    //         'code' => '6-01-007-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild26->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under REPAIR AND MAINTENANCE (grandChild27)
    //     $subGroup56 = Group::create([
    //         'name' => 'AIR CONDITIONER REPAIRS',
    //         'code' => '6-01-008-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild27->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup57 = Group::create([
    //         'name' => 'BUILDING REPAIRS',
    //         'code' => '6-01-008-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild27->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup58 = Group::create([
    //         'name' => 'COMPUTER EXPENSE',
    //         'code' => '6-01-008-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild27->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup59 = Group::create([
    //         'name' => 'OFFICE EQUIPMENT',
    //         'code' => '6-01-008-0004',
    //         'level' => '4',
    //         'parent_id' => $grandChild27->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup60 = Group::create([
    //         'name' => 'ELECTRICAL REPAIR',
    //         'code' => '6-01-008-0005',
    //         'level' => '4',
    //         'parent_id' => $grandChild27->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup61 = Group::create([
    //         'name' => 'OTHER REPAIR AND MAINTENANCE',
    //         'code' => '6-01-008-0006',
    //         'level' => '4',
    //         'parent_id' => $grandChild27->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under MISC EXPENSES (grandChild28)
    //     $subGroup62 = Group::create([
    //         'name' => 'OTHER EXPENSES',
    //         'code' => '6-01-009-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild28->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup63 = Group::create([
    //         'name' => 'ASK MY ACCOUNTANT',
    //         'code' => '6-01-009-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild28->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under COMMUNICATION (grandChild29)
    //     $subGroup64 = Group::create([
    //         'name' => 'MOBILE EXPENSE',
    //         'code' => '6-01-010-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild29->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup65 = Group::create([
    //         'name' => 'INTERNET CHARGES',
    //         'code' => '6-01-010-0002',
    //         'level' => '4',
    //         'parent_id' => $grandChild29->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     $subGroup66 = Group::create([
    //         'name' => 'TELEPHONE BILL',
    //         'code' => '6-01-010-0003',
    //         'level' => '4',
    //         'parent_id' => $grandChild29->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under ENTERTAINMENT EXPENSE (grandChild30)
    //     $subGroup67 = Group::create([
    //         'name' => 'FOOD EXPENSE',
    //         'code' => '6-01-011-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild30->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under FEES & SUBSCRIPTIONS (grandChild31)
    //     $subGroup68 = Group::create([
    //         'name' => 'REGISTRATION FEES',
    //         'code' => '6-01-012-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild31->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under PROFESSIONAL FEES (grandChild32)
    //     $subGroup69 = Group::create([
    //         'name' => 'ACCOUNTANCY FEES',
    //         'code' => '6-01-013-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild32->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under CLEANING (grandChild33)
    //     $subGroup70 = Group::create([
    //         'name' => 'CLEANING EXPENSE',
    //         'code' => '6-01-014-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild33->id,
    //         'account_type_id' => $parent6->id,
    //         'status' => '1',
    //     ]);

    //     // Under BANK SERVICE CHARGES (grandChild34)
    //     $subGroup71 = Group::create([
    //         'name' => 'BANK FEES',
    //         'code' => '7-01-001-0001',
    //         'level' => '4',
    //         'parent_id' => $grandChild34->id,
    //         'account_type_id' => $parent7->id,
    //         'status' => '1',
    //     ]);


    //     //creating ledgers for all subgroups
    //     Ledger::create([
    //         'name' => $subGroup1->name,
    //         'code' => $subGroup1->code,
    //         'group_id' => $subGroup1->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup2->name,
    //         'code' => $subGroup2->code,
    //         'group_id' => $subGroup2->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup3->name,
    //         'code' => $subGroup3->code,
    //         'group_id' => $subGroup3->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup4->name,
    //         'code' => $subGroup4->code,
    //         'group_id' => $subGroup4->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup5->name,
    //         'code' => $subGroup5->code,
    //         'group_id' => $subGroup5->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup6->name,
    //         'code' => $subGroup6->code,
    //         'group_id' => $subGroup6->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup7->name,
    //         'code' => $subGroup7->code,
    //         'group_id' => $subGroup7->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup8->name,
    //         'code' => $subGroup8->code,
    //         'group_id' => $subGroup8->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup9->name,
    //         'code' => $subGroup9->code,
    //         'group_id' => $subGroup9->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup10->name,
    //         'code' => $subGroup10->code,
    //         'group_id' => $subGroup10->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup11->name,
    //         'code' => $subGroup11->code,
    //         'group_id' => $subGroup11->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup12->name,
    //         'code' => $subGroup12->code,
    //         'group_id' => $subGroup12->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup13->name,
    //         'code' => $subGroup13->code,
    //         'group_id' => $subGroup13->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup14->name,
    //         'code' => $subGroup14->code,
    //         'group_id' => $subGroup14->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup15->name,
    //         'code' => $subGroup15->code,
    //         'group_id' => $subGroup15->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup16->name,
    //         'code' => $subGroup16->code,
    //         'group_id' => $subGroup16->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup17->name,
    //         'code' => $subGroup17->code,
    //         'group_id' => $subGroup17->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup18->name,
    //         'code' => $subGroup18->code,
    //         'group_id' => $subGroup18->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup19->name,
    //         'code' => $subGroup19->code,
    //         'group_id' => $subGroup19->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup20->name,
    //         'code' => $subGroup20->code,
    //         'group_id' => $subGroup20->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup21->name,
    //         'code' => $subGroup21->code,
    //         'group_id' => $subGroup21->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup22->name,
    //         'code' => $subGroup22->code,
    //         'group_id' => $subGroup22->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup23->name,
    //         'code' => $subGroup23->code,
    //         'group_id' => $subGroup23->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup24->name,
    //         'code' => $subGroup24->code,
    //         'group_id' => $subGroup24->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup25->name,
    //         'code' => $subGroup25->code,
    //         'group_id' => $subGroup25->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup26->name,
    //         'code' => $subGroup26->code,
    //         'group_id' => $subGroup26->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup27->name,
    //         'code' => $subGroup27->code,
    //         'group_id' => $subGroup27->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup28->name,
    //         'code' => $subGroup28->code,
    //         'group_id' => $subGroup28->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup29->name,
    //         'code' => $subGroup29->code,
    //         'group_id' => $subGroup29->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup30->name,
    //         'code' => $subGroup30->code,
    //         'group_id' => $subGroup30->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup31->name,
    //         'code' => $subGroup31->code,
    //         'group_id' => $subGroup31->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup32->name,
    //         'code' => $subGroup32->code,
    //         'group_id' => $subGroup32->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup33->name,
    //         'code' => $subGroup33->code,
    //         'group_id' => $subGroup33->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup34->name,
    //         'code' => $subGroup34->code,
    //         'group_id' => $subGroup34->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup35->name,
    //         'code' => $subGroup35->code,
    //         'group_id' => $subGroup35->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup36->name,
    //         'code' => $subGroup36->code,
    //         'group_id' => $subGroup36->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup37->name,
    //         'code' => $subGroup37->code,
    //         'group_id' => $subGroup37->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup38->name,
    //         'code' => $subGroup38->code,
    //         'group_id' => $subGroup38->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup39->name,
    //         'code' => $subGroup39->code,
    //         'group_id' => $subGroup39->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup40->name,
    //         'code' => $subGroup40->code,
    //         'group_id' => $subGroup40->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup41->name,
    //         'code' => $subGroup41->code,
    //         'group_id' => $subGroup41->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup42->name,
    //         'code' => $subGroup42->code,
    //         'group_id' => $subGroup42->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup43->name,
    //         'code' => $subGroup43->code,
    //         'group_id' => $subGroup43->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup44->name,
    //         'code' => $subGroup44->code,
    //         'group_id' => $subGroup44->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup45->name,
    //         'code' => $subGroup45->code,
    //         'group_id' => $subGroup45->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup46->name,
    //         'code' => $subGroup46->code,
    //         'group_id' => $subGroup46->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup47->name,
    //         'code' => $subGroup47->code,
    //         'group_id' => $subGroup47->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup48->name,
    //         'code' => $subGroup48->code,
    //         'group_id' => $subGroup48->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup49->name,
    //         'code' => $subGroup49->code,
    //         'group_id' => $subGroup49->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup50->name,
    //         'code' => $subGroup50->code,
    //         'group_id' => $subGroup50->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup51->name,
    //         'code' => $subGroup51->code,
    //         'group_id' => $subGroup51->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup52->name,
    //         'code' => $subGroup52->code,
    //         'group_id' => $subGroup52->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup53->name,
    //         'code' => $subGroup53->code,
    //         'group_id' => $subGroup53->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup54->name,
    //         'code' => $subGroup54->code,
    //         'group_id' => $subGroup54->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup55->name,
    //         'code' => $subGroup55->code,
    //         'group_id' => $subGroup55->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup56->name,
    //         'code' => $subGroup56->code,
    //         'group_id' => $subGroup56->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup57->name,
    //         'code' => $subGroup57->code,
    //         'group_id' => $subGroup57->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup58->name,
    //         'code' => $subGroup58->code,
    //         'group_id' => $subGroup58->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup59->name,
    //         'code' => $subGroup59->code,
    //         'group_id' => $subGroup59->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup60->name,
    //         'code' => $subGroup60->code,
    //         'group_id' => $subGroup60->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup61->name,
    //         'code' => $subGroup61->code,
    //         'group_id' => $subGroup61->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup62->name,
    //         'code' => $subGroup62->code,
    //         'group_id' => $subGroup62->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup63->name,
    //         'code' => $subGroup63->code,
    //         'group_id' => $subGroup63->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup64->name,
    //         'code' => $subGroup64->code,
    //         'group_id' => $subGroup64->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup65->name,
    //         'code' => $subGroup65->code,
    //         'group_id' => $subGroup65->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup66->name,
    //         'code' => $subGroup66->code,
    //         'group_id' => $subGroup66->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup67->name,
    //         'code' => $subGroup67->code,
    //         'group_id' => $subGroup67->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup68->name,
    //         'code' => $subGroup68->code,
    //         'group_id' => $subGroup68->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup69->name,
    //         'code' => $subGroup69->code,
    //         'group_id' => $subGroup69->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup70->name,
    //         'code' => $subGroup70->code,
    //         'group_id' => $subGroup70->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    //     Ledger::create([
    //         'name' => $subGroup71->name,
    //         'code' => $subGroup71->code,
    //         'group_id' => $subGroup71->id,
    //         'sourceable_id' => null,
    //         'sourceable_type' => null,
    //         'balance' => 0,
    //         'balance_type' => 'c',
    //         'ledger_type' => 'general',
    //     ]);

    // }
}
