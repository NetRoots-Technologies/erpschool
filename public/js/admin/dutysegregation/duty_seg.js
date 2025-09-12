/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $('#branch_id').select2();
        $('#sup_id').select2();
        setTimeout(function (){
            $('.description-data-ajax').select2(Select2AjaxObj());
        },500);

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                branch_id:{
                    required: true
                     },
                sup_id:{
                    required: true
                   }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },errorPlacement: function (error, element) {
               if (element.attr("name") == "sup_id") {
                        error.insertAfter($('#sup_id_handler'));
                    }else if (element.attr("name") == "branch_id") {
                        error.insertAfter($('#branch_id_handler'));
                    }else {
                        error.insertAfter(element);

                     }

            },success: function (label) {
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
            placeholder: "Select Stock Items",
            minimumInputLength: 2,
            ajax: {
                url: route('admin.duty_seg.duty'),
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

                    return {
                        results: data
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

        $('#InventoryItems-duty_id-'+global_counter).select2(Select2AjaxObj());
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
    // $(".about_featured").hide();
    // $(".box-footer").hide();
    $("#sup_id").on('change',function () {
        // $(".about_featured").show();
        // $(".box-footer").show();

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

    $(document).on('blur','.maxQty',function () {
        var $current = $(this).val();
        // alert($current);
        rowId = $(this).attr('data-row-id');
        product = $('#InventoryItems-duty_id-'+rowId).text();
        product1 = product.split('[');
        product2 = product1[1].split(']');
        // alert(product2[0]);
        if ( parseInt($current) > parseInt(product2[0]))
        {
            alert('Quantity value dont exceed!');
            $('#InventoryItems-amount-'+rowId).val('');
            $current.empty();
        }
    })
});