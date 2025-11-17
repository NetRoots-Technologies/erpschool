<?php


return [

    'account_cash_inHand' => 1,
    'head_branch_id' => 1,
    'currency_symbols_setting_id' => 2,
    'currency_symbols' => array('##,###.##' => '##,###.##', '##,##.##' => '##,##.##', '###,###.##' => '###,###.##'),
    'date_format_setting_id' => 3,
    'date_format' => array('d-M-Y|dd-M-yy' => 'Day-Month-Year', 'M-d-Y|M-dd-yy' => 'Month-Day-Year', 'Y-M-d|yy-M-dd' => 'Year-Month-Day'),
    'payment_type' => array(1 => 'Cash', 2 => 'Check', 3 => 'American Express', 4 => 'Discover', 5 => 'MasterCard', 6 => 'Visa', 7 => 'Other Credit Card', 8 => 'Other', 9 => 'Debit Card', 10 => 'Gift Card', 11 => 'E-Check'),
    'accounts_main_heads' => array(1, 2, 3, 4),
    'accounts_company_id' => 1,
    'cash_and_bank_balance' => array(12, 13, 14),
    // Accounts Type Fields
    'accounts_assets' => 1,
    'accounts_equity_liabilities' => 2,
    'accounts_expenses' => 4,
    'operating_expense' => 95,
    //operating expenses
    'expenses' => array(
        'operating_expenses' => array('staff_salaries' => 98, 'staff_bonus' => 99, 'staff_medical' => 100, 'staff_eobi' => 101)
    ),

    'slug' => array(
        'mode_of_payments' => array('cash' => 'cash', 'pure_gold' => 'pure_gold', 'impure_gold' => 'impure_gold', 'credit_debirt' => 'credit_debirt', 'credit' => 'credit', 'cheque' => 'cheque', 'bank' => 'bank_transfer', 'advanced' => 'unbooked_advance')
    ),
    'accounts_incomes' => 3,
    'company_payable' => 156,
    'Costumer_payable' => 157,
    'Impure_Purchase_Profit_loss' => 134,
    'cash_in_hand' => 54,
    'advance_vl' => 146,
    'walk_customer_ledger' => 1,
    /////////*********///////
    ////////******-currencies***///////
    'acounts_banks_currencies' => 14,
    'acounts_karigar_local' => 24,
    'acounts_supplier_local' => 25,
    'acounts_Agents_local' => 68,
    'acounts_misc_charges' => 2,
    ////stock///
    'acounts_Stock_heads' => array(17 => 'InProcess', 18 => 'Sale Stock', 19 => 'Amanat Stock'),
    'acounts_LDS_heads' => array(82 => 'Amanat Stock', 83 => 'InProcess', 84 => 'Sale Stock'),
    'acounts_process_pack' => 83,
    'acounts_amt_pack' => 82,
    'acounts_sale_pack' => 84,
    'acounts_process_tag' => 80,
    'acounts_amt_tag' => 81,
    'acounts_sale_tag' => 79,
    'stock_for_sale_mix' => 143,
    'stock_sale_jewelery' => '48',
    'jewelery_cat_id' => 1,
    'jewelery_sub_cat_id' => 4,
    //sale stock pure gold group id
    'sale_stock_pure_gold' => 51,
    //tag lds stock in process
    'tag_lds_stock_in_process' => [147, 148, 149],
    //tag lds stock for sale
    'tag_lds_stock_sale' => [128, 129, 130],
    //tag lds amanat stock
    'tag_lds_amanat_stock' => 79,
    // stock for sale Tag lds heads
    'LDS' => array(
        'tag_lds' => [79, 80, 81]
    ),
    'in_process_stock' => array(
        'LDS' => array(
            'tag_lds' => array(
                'diamond' => 29
            ),
            'stone' => 29
        ),
        'beads' => 29
    ),
    //end of lds tag...
    //income heads
    'incomes' => array(
        'other_income' => array('Currency_fluctuation' => 58)
    ),
    'List_type_id' => array(
        'sidenave' => 1,
        'paymentmethod' => 2

    ),
    //taxes for watches and accessories under the current assets
    'asset_watch_acc' => array('sale_tax' => 72, 'add_tax' => 73, 'income_tax' => 74),
    'liability_watch_acc' => array('sale_tax' => 75, 'add_tax' => 76, 'income_tax' => 77),
    //expenses list
    'tax_groups' => array(142 => 'Govt Taxes & Duties'),
    'inventory_status' => [
        'in' => 0,
        'out' => 1,
        'end' => 2,
        'toCustomer' => 3,
        'toKariger' => 4
    ],
    'purchase_types' => [
        'impure_local' => 'local',
    ],
    'grn_category' => [
        'impure' => 3,
    ],
    'routing_types' => [
        'impure' => 'impure',
        'mix_waste_gold' => 'mix_waste_gold',
        'mix_lose_stock' => 'mix_lose_stock',
        'mix_lose_stock_melting' => 'mix_lose_stock_melting',
        'mix_routing_account' => 'mix_routing_account',
        'laker' => 'laker'
    ],
    'entery_status' => [
        'tagging' => 'tagging',
        'mix' => 'shift_to_mix',
        'trash' => 'trash'
    ],
    'impure_routing_types' => [
        'tagging' => 'tagging',
        'kariger' => 'shift_to_kariger',
        'laker' => 'shift_to_laker',
        'ad_in_tag' => 'add_in_tag',
        'mix' => 'shift_to_mix',
        'melting' => 'shift_to_melting',
        'refine' => 'shift_to_refine',
        'lds' => 'shift_to_lds',
    ],
    'inventory_type' => [
        'grn' => '1',
        'goodrecipt' => '2',
    ],
    'ledger_id' => [
        'routing_account' => 69,
        'waste_gold' => 122,
        'taar_cap' => 43,
        'safty_chains' => 61,
        'locks' => 123,
    ],
    'inventory_process' => [
        'goodissunace' => 'good issuance',
        'process' => 'process',
        'sale' => 'sale',
        'sale_out' => 'sale out',
    ],
    'grn_categories' => [
        'impure' => 3,
        'gem' => 4,
        'lds_lose_stock_routing' => 5,
    ],
    'impure_status' => [
        'in' => 'in',
        'out' => 'out',
        'tagging' => 'tagging',
        'remaining' => 'remaining',
        'laker' => 'laker'
    ],
    'impure_temp_status' => [
        'in' => 'in',
        'tagging' => 'tagging',
        'remaining' => 'remaining'
    ],
    'inventory_status' => [
        'on' => 0,
        'off' => 1,
    ],
    'Costumerr_type' => [
        'Walking Customer' => 1,
        'Company Customer' => 2,

    ],
    'job_type' => [
        'Laker' => 1,
        'Stone Change' => 2,
        'Stone Change' => 2,
        'Repaire' => 3,
        'Peroi' => 4,

    ],
    'mix_routing_heads' => [
        'mix_routing_acount' => 'mix_routing_acount',
    ],
    'items_categories' => [
        'jewelery' => 1,
        'lds' => 5,
    ],
    'jewelery_subcategories' => [
        'mix' => 4,
    ],
    'lakerHistory' => [
        'shiftingFrom' => 'impure_temp_taggings',
        'shiftToLaker' => 'shiftToLaker',
        'recieve' => 'recieved',
        'history_type' => 'laker'
    ],
    'meltingHistoryStatus' => [
        'shiftingFrom' => 'impure_temp_taggings',
        'shiftToMelting' => 'shiftToMelting',
        'recieve' => 'recieved',
        'history_type' => 'melting',
        'shift_to_refine' => 'shiftToRefine',
        'shift_to_pure_gold' => 'shift_to_pure_gold',
        'refine_received' => 'refine_received',
        'history_type_refine' => 'refine',
        'history_type_pure_g' => 'pure_g',
    ],
    'apiResponceStatus' => [
        'success' => 200, // success data
        'NotFound' => 404, // Not Found (page or other resource doesn’t exist)
        'NotAuthorized' => 401, // Not authorized (not logged in)
        'forbiddenRequest' => 403, // Logged in but access to requested area is forbidden
        'BadRequest' => 400, // Bad request (something wrong with URL or parameters)
        'UnprocessableEntity' => 422, // Unprocessable Entity (validation failed)
        'ServerError' => 500 // General server error
    ],

    'leave_all_status' => array(
        0 => 'Pending Approval',
        1 => 'Approved',
        2 => 'Rejected',
    ),

    'leave_all_status_name' => array(
        'pending' => 'Pending Approval',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ),

    "FixedGroups" =>
        [
            "assets" => 1,
            "liabilities" => 2,
            "income" => 3,
            "expenses" => 4,
            "current_assets" => 5,
            "non_current_assets" => 6,
            "physical_assets" => 7,
            "accumulated_depreciation" => 8,
            "right_of_use_of_asset" => 9,
            "long_term_assets" => 10,
            "fee_receivable" => 11,
            "advances,_deposits_and_prepayments" => 12,
            "due_from_associated_undertakings" => 13,
            "loan_to_directors" => 14,
            // "cash" => 15,
            "banks" => 16,
            "share_capital_&_equity" => 17,
            "share_capital" => 18,
            "loan_from_directors" => 19,
            "retained_earning_/_accumulated_profit" => 20,
            "non_current_liabilities" => 21,
            "long_term_loan" => 22,
            "deferred_tax" => 23,
            "lease_liability" => 24,
            "long_term_security_deposits" => 25,
            "current_liabilities" => 26,
            "short_term_loan" => 27,
            "provision_for_taxation" => 28,
            "trade_and_other_payables" => 29,
            "advance_fee_received" => 30,
            "due_to_associated_undertakings" => 31,
            "fee_income" => 33,
            "fee_heads" => 34,
            "other_income" => 37,
            "administrative_expenses" => 38,
            "director_remuneration" => 42,
            "salaries,_wages_and_benefits" => 43,
            "utilities_expense" => 44,
            "rent,_rates_and_taxes" => 45,
            "repair_and_maintenance" => 46,
            "printing_and_stationary" => 47,
            "depreciation" => 48,
            "postage,_courier_and_customs" => 49,
            "events_and_functions" => 50,
            "teachers_training" => 51,
            "entertainment" => 52,
            "advertisement" => 53,
            "traveling_and_lodging" => 54,
            "bank_charges" => 55,
            "communication" => 56,
            "auditor's_remuneration" => 57,
            "vehicle_and_generator" => 58,
            "insurance" => 59,
            "legal_and_professional" => 60,
            "exam/registration" => 61,
            "laboratory_materials" => 62,
            "erp_maintenance" => 63,
            "office_supplies" => 64,
            "safety_and_security" => 65,
            "miscellaneous_expense" => 66,
            "fee_and_subscription" => 67,
            "suspense_account" => 68,
            "freight_and_transportation" => 69,
            "finance_cost" => 70,
            "taxation" => 71,
            "asset_heads" => 72,
            "accumulated_depreciation_asset_heads" => 76,
            "both_asset_heads" => [72, 76],
            "both_fee_heads" => [72, 76],
            "cash" => 75,
            "cash_in_hands" => 76,
            "fee_heads_income" => 34,
            "fee_heads_recivable" => 75,
            "advance" => 95,
            "advance_salary" => 96,
            "Salary" => 97,
            "Payroll" => 98,
            "Employee_Benefits_Liabilities" => 99,
            "EOBI_Payable" => 100,
            "Provident_Fund_Payable" => 101,
            "Employee_Benefits_Expenses" => 102,
            "EOBI_Contribution" => 103,
            "Provident_Fund_Contribution" => 104,
            "Provision_for_Taxation" => 105,
            "Income_Tax_Liabilities" => 106,
            "Sales_Tax_Payable" => 107,
            "Taxes" => 108,
            "Income_Tax_Expenses" => 110,
            "Sales_Tax" => 111,
            "Income_Tax" => [106, 110],
            "Sales_Tax_groups" => [107, 111],
            // "EOBI" => [112, 113],
            // "PF" => [114, 115],
            // "SS" => [117, 119],

            // local db
            // "EOBI" => [26, 27],
            // "PF" => [29, 30],
            // "SS" => [32, 33],

            // only live
            "EOBI" => [652, 653],
            "PF" => [655, 656],
            "SS" => [658, 679],
            
            "EOBI_Contribution_Monthly_Payments" => 112,
            "EOBI_Payable_Monthly_Payments" => 113,
            "Provident_Fund_Contribution_Monthly_Payments" => 114,
            "Provident_Fund_Payable_Monthly_payments" => 115,
            "Social_Security_Payable" => 116,
            "Social_Security_Monthly Payable" => 117,
            "Social_Security_Contribution" => 118,
            "Social_Security_Monthly_Contribution" => 119,
            "Stationary" => 126,
            "Food" => 127,
            "Inventory" => 134,
            "Cafe_Inventory" => 135,
            "Stationery_Inventory" => 136,
            "Cafe_Inventory_Items" => 137,
            "Stationery_Inventory_Items" => 138,
            "Student_Canteen_Receivables" => 141,
            "Uniform" => 139,                  // <— NEW: supplier/customer group for Uniform
            "Uniform_Inventory" => 140,        // <— NEW: parent inventory group for Uniform (optional but nice)
            "Uniform_Inventory_Items" => 142,  // <— NEW: items ledger group used in ItemController@store
 
        ],
    "UNITS" => [
        "KG" => 1,
        // "CM" => 5,
        // "MM" => 6,
        "L" => 7,
        "ML" => 9,
        "G" => 10,
        // "CARTON" => 11,
        "Unit" => 12,
        "piece" => 13,
        "OZ" => 13,
        // "GR" => 14,
        // "SET" => 15,
        // "PCS" => 16,
        // "BAG" => 17,
        // "PK" => 18,
        // "CASE" => 19,
        // "BARREL" => 20,
      "PCS" => 16,  // <— OPTIONAL: if you want PCS visible in dropdowns
    ],
    "UNIT_CONVERT" => [
        "KG" => "1000G",
        "L" => "1000ML",
        "G" => "0.001KG",
        "ML" => "0.001L",
        "DOZEN" => "12",
    ],
    'status' => [
        1 => "PENDING",
        2 => "APPROVED",
        3 => "REJECTED",
        4 => "FULFILLED",
    ],
    'priority' => [
        1 => "HIGH",
        2 => "MEDIUM",
        3 => "LOW",
    ],
    "type" => [
        1 => "food",
        2 => "stationary",
        3 => "uniform",   // <— NEW
    ],
    'delivery_status' => [
        1 => "PENDING",
        2 => "SHIPPED",
        3 => "CANCELLED",
        4 => "COMPLETED",
        5 => "PARTIALLY",
    ],
    "payment_status" => [
        1 => "PAID",
        2 => "PENDING",
        3 => "OVERDUE",
    ],
    "payment_method" => [
        1 => "CASH",
        2 => "BANK",
        // 3 => "CHEQUE",
    ],
    "batch_type" => [
        1 => "Morning Snack",
        2 => "Evening Snack",
        3 => "Lunch",
        4 => "Dinner",
        5 => "Breakfast",
        6 => "Snack",
        7 => "Others",
    ],
];
