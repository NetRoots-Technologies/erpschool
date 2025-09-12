/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        $('#branch_from').select2();
        $('#branch_to').select2();
        $('#parent_id').select2();
        $('#InventoryItems-unit_name_id-1').select2();
        $('.description-data-ajax').select2(Select2AjaxObj());
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                // sup_id:{
                //     required: true
                // }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "parent_id") {
                    error.insertAfter($('#parent_id_handler'));
                } else if (element.attr("name") == "branch_id") {
                    error.insertAfter($('#branch_id_handler'));
                 } else if (element.attr("name") == "sup_id") {
                     error.insertAfter($('#sup_id_handler'));
                } else if (element.attr("name") == "cat_id") {
                    error.insertAfter($('#cat_id_handler'));
                }else if (element.attr("name") == "st_name") {
                    error.insertAfter($('#st_name_handler'));
                }else {
                    error.insertAfter(element);
                 }
                },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            // submitHandler: function (form) {
            //
            //     form[0].submit(); // submit the form
            // }
        });
    }
    var Select2AjaxObj = function () {

        return {
            allowClear: true,
            placeholder: "Select a Product",
            minimumInputLength: 2,
            ajax: {
                url: route('admin.stockitems.inventoryproduct'),
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        item: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
            }
        }
    }

    var createLineItem = function () {
        var global_counter = parseInt($('#InventoryItems-global_counter').val()) + 1;

        var line_item = $('#line_item-container').html().replace(/########/g, '').replace(/######/g, global_counter);

        $('#users-table tr:last').after(line_item);
        // Apply Select2 on newly created item


        $('#InventoryItems-unit_name_id-'+global_counter).select2();
        $('#InventoryItems-product_id-'+global_counter).select2(Select2AjaxObj());
        $('#InventoryItems-global_counter').val(global_counter)
    }

    var destroyLineItem = function (itemId) {
        var r = confirm("Are you sure to delete Line Item?");
        if (r == true) {
            $('#InventoryItems-'+itemId).remove();
        }
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        createLineItem: createLineItem,
        destroyLineItem: destroyLineItem
    };
}();

jQuery(document).ready(function() {
    FormControls.init();

    $(document).on('blur','.test',function () {
        amount = $(this).val();
        rowId = $(this).attr('data-row-id');
        quantity = $('#InventoryItems-qunity_id-'+rowId).val();
        total = amount * quantity;
        $('#InventoryItems-amount_id-'+rowId).val(total.toFixed(2));
    })
    $(document).on('blur','.qty',function () {
        qtyid = $(this).attr('data-row-id');
        $('#line_item-p_amount_id-'+qtyid).val(null);
        $('#line_item-amount_id-'+qtyid).val(null);
    })


    $(document).on('change','select.dublicate_product',function () {
        var $current = $(this);
        /*alert($current);*/
        $('select.dublicate_product').each(function() {
            if ($(this).val() == $current.val() && $(this).attr('id') != $current.attr('id'))
            {
                alert('Duplicate Product Found!');
                $current.addClass('duplicate');
                $current.empty();
                return false;
            }
        });


    })
});