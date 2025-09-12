/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        $('.select2').select2();

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd' //format: 'DD-MM-YYYY H:m:s A',
        });

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                proforma_no: {
                    required: true
                },
                brands: {
                    required: true
                },
                amount: {
                    required: true
                },
                status: {
                    required: true
                },



            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            // submitHandler: function (form) {
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
                url: route('admin.orders.productlist'),
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    var sup_id = $('#sup_id').val();
                    return {
                        item: params.term,
                        sup_id : sup_id,
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    return {
                        results: data
                    };
                }
            }
        };
    }
    var qcreateLineItem = function () {
        var global_counter = parseInt($('#line_new-global_counter').val()) + 1;

        var line_item = $('#line_new-container').html().replace(/########/g, '').replace(/######/g, global_counter);

        $('#users-table tr:last').after(line_item);
        // Apply Select2 on newly created item


        $('#line_new-unit_name_id-'+global_counter).select2();
        $('#line_new-product_id-'+global_counter).select2(Select2AjaxObj());
        $('#line_new-global_counter').val(global_counter);
    }
    var destroyLineItem = function (itemId) {
        var r = confirm("Are you sure to delete Line Item?");
        if (r == true) {
            $('#line_new-'+itemId).remove();
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
});