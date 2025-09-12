/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $('.description-data-ajax').select2(Select2AjaxObj());

        $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });

        $('#branch_id').select2();
        $('#department_id').select2();
        $('#employee_id').select2();
        $('#customers_id').select2();
        $('#stockitems_id').select2();

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                number: {
                    required: true
                },
                voucher_date: {
                    required: true,
                },
                branch_id: {
                    required: true
                },
                employee_id: {
                    required: true
                },
                customers_id:{
                    required: true
                },
                stockitems_id:{
                    required: true
                },
                narration: {
                    required: true
                },
                dr_total: {
                    required: true,
                    number: true,
                    min: 1,
                    equalTo: '#cr_total',
                },
                cr_total: {
                    required: true,
                    number: true,
                    min: 1,
                    equalTo: '#dr_total',
                },
                diff_total: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 0,
                },
            },
            messages: {
                dr_total: {
                    required: "Field is require.",
                    number: "Field is require.",
                    min: "All Items Debit should greater than zero.",
                    equalTo: 'Debit must equal to Credit amount.',
                },
                cr_total: {
                    required: "Field is require.",
                    number: "Field is require.",
                    min: "All Items Credit should greater than zero.",
                    equalTo: 'Credit must equal to Debit amount.',
                },
                diff_total: {
                    required: "Field is require.",
                    number: "Field is require.",
                    min: "Difference of Debit and Credit should zero.",
                    max: "Difference of Debit and Credit should zero.",
                },
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "branch_id") {
                    error.insertAfter($('#branch_id_handler'));
                } else if (element.attr("name") == "employee_id") {
                    error.insertAfter($('#employee_id_handler'));
                } else if (element.attr("name") == "department_id") {
                    error.insertAfter($('#department_id_handler'));
                } else if (element.attr("name") == "entry_items[ledger_id][]") {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            }
        });

        CalculateTotal();
    }

    var Select2AjaxObj = function () {
        return {
            allowClear: true,
            placeholder: "Account",
            minimumInputLength: 2,
            ajax: {
                url: route('admin.voucher.gjv_search'),
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        item: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
            }
        }
    }

    var CalculateTotal = function () {
        var total_dr_amount = 0;
        var total_cr_amount = 0;

        $('.entry_items-dr_amount').each(function (index) {
            var target_cr = $(this).attr('id').replace("entry_item-dr_amount-", "");
            if($(this).val() != '' && $(this).val() != '0') {
                total_dr_amount = total_dr_amount + parseFloat($(this).val());
                $('#entry_item-cr_amount-'+target_cr).attr('readonly', true);
                $('#entry_item-cr_amount-'+target_cr).val('0');
            } else {
                $('#entry_item-cr_amount-'+target_cr).removeAttr('readonly');
                if($(this).val() == '' && $('#entry_item-cr_amount-'+target_cr).val() == '0') {
                    $('#entry_item-cr_amount-'+target_cr).val('');
                }
            }
        });

        $('.entry_items-cr_amount').each(function (index) {
            var target_dr = $(this).attr('id').replace("entry_item-cr_amount-", "");
            if($(this).val() != '' && $(this).val() != '0') {
                total_cr_amount = total_cr_amount + parseFloat($(this).val());
                $('#entry_item-dr_amount-'+target_dr).attr('readonly', true);
                $('#entry_item-dr_amount-'+target_dr).val('0');
            } else {
                $('#entry_item-dr_amount-'+target_dr).removeAttr('readonly');
                if($(this).val() == '' && $('#entry_item-dr_amount-'+target_dr).val() == '0') {
                    $('#entry_item-dr_amount-'+target_dr).val('');
                }
            }
        });

        $('#dr_total').val(total_dr_amount);
        $('#cr_total').val(total_cr_amount);
        $('#diff_total').val(total_dr_amount - total_cr_amount);

        updateNarration();
    }

    var createEntryItem = function () {
        var global_counter = parseInt($('#entry_item-global_counter').val()) + 1;
        var entry_item = $('#entry_item-container').html().replace(/########/g, '').replace(/######/g, global_counter);
        $('#entry_items tr:last').before(entry_item);
        // Apply Select2 on newly created item
        $('#entry_item-ledger_id-'+global_counter).select2(Select2AjaxObj());
        $('#entry_item-global_counter').val(global_counter)
    }

    var destroyEntryItem = function (itemId) {
        var r = confirm("Are you sure to delete Entry Item?");
        if (r == true) {
            $('#entry_item-ledger_id-'+itemId).select2(Select2AjaxObj());
            $('#entry_item-'+itemId).remove();
            CalculateTotal();
        }
    }

    var updateNarration = function() {
        $('.entry_items-narration').each(function (index) {
            console.log(index);
            if($('#narration').val() != '') {
                $(this).val($('#narration').val());
            }
        });
    }


    return {
        // public functions
        init: function() {
            baseFunction();
        },
        createEntryItem: createEntryItem,
        destroyEntryItem: destroyEntryItem,
        CalculateTotal: CalculateTotal,
        updateNarration: updateNarration
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});