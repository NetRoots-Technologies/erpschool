/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd' //format: 'DD-MM-YYYY H:m:s A',
        });
        setTimeout(function (){
            $('#line_item-product_id-1').select2(Select2AjaxObj());
        },500);
        $('#sup_id').select2();
        

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                po_number: {
                    required: true
                },
                po_date: {
                    required: true
                },
                sup_id: {
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
            //     $("#submit").prop("disabled", true);
            //     form[0].submit(); // submit the form
            //
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
                    var sup_id = $('#sup_id_for').val();
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
                    var sup_id = $('#sup_id_loc').val();
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

    var createLineItem = function () {
        var global_counter = parseInt($('#line_item-global_counter').val()) + 1;

        var line_item = $('#line_item-container').html().replace(/########/g, '').replace(/######/g, global_counter);

        $('#users-table tr:last').after(line_item);
        // Apply Select2 on newly created item


        $('#line_item-unit_name_id-'+global_counter).select2();
        $('#line_item-product_id-'+global_counter).select2(Select2AjaxObj());
        $('#line_item-global_counter').val(global_counter);
    }

    var destroyLineItem = function (itemId) {
        var r = confirm("Are you sure to delete Line Item?");
        if (r == true) {
            $('#line_items-'+itemId).remove();
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

$(document).ready(function() {
    $('#local_sup').hide();
    FormControls.init();
    $(document).on('click','#orderType',function(){
        var orderval = $(this).val();
        if(orderval === '1'){
            $('.import').hide();
            $('#local_sup').show();
            $('#import_sup').hide();
        }else{
            $('.import').show();
            $('#local_sup').hide();
            $('#import_sup').show();
        }
    });
    $(document).on('change','.test',function () {
        var calculated_amount = 0;
        var rowId = $(this).attr('data-row-id');
        var quantity = $('#line_item-qunity_id-'+rowId).val();
        var amount = $('#line_item-p_amount_id-'+rowId).val();
        var total = amount * quantity;
        var word;
        var newword;
        

        $('#line_item-amount_id-'+rowId).val(total.toFixed(2));
        $('.cal_sum').each(function () {
            var totalvalue = $(this).val();
            if(totalvalue != '' && totalvalue != '0'){
                calculated_amount = calculated_amount + parseFloat(totalvalue);
                $('#total_of_product').val(calculated_amount.toFixed(4));
                //$("#amount-rupees").val(words + "Rupees Only");
                newword = convertNumberToWords(calculated_amount);
            }
        });
       $('#word').html(newword);
    });

    $(".about_featured").hide();
    $(".box-footer").hide();
    $(".amount_section").hide();
    $(document).on('change','#sup_id_for',function () {
        var r = confirm("Are you sure to select this brand?");
        if (r == true) {
            sup_id = $(this).val();
            $("#sup_id").prop('disabled', 'disabled');
            $("#sup_name_for").val(sup_id);
            $(".about_featured").show();
            $(".box-footer").show();
            $(".amount_section").show();
        }

    });
    $(document).on('change','#sup_id_loc',function () {
        var r = confirm("Are you sure to select this brand?");
        if (r == true) {
            sup_id = $(this).val();
            $("#sup_id").prop('disabled', 'disabled');
            $("#sup_name_loc").val(sup_id);
            $(".about_featured").show();
            $(".box-footer").show();
            $(".amount_section").show();
        }

    });

    $(document).on('change','select.dublicate_product',function () {
        var $current = $(this);
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