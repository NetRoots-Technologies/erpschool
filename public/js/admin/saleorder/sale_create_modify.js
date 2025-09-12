/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {

    //== Private functions

    var baseFunction = function () {
        $('#Customer_name').select2();

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd' //format: 'DD-MM-YYYY H:m:s A',
        });
console.log($('#line_items_quantity_id-1').val());
        $('#line_item-product_id-1').select2(Select2AjaxObj());
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                order_date: {
                    required: true
                },
                delivery_date: {
                    required: true
                },
                Customer_name: {
                    required: true
                },
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "Customer_name") {
                    error.insertAfter($('#Customer_id_handler'));
                }
                else {
                    error.insertAfter(element);
                }
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
                url: route('admin.saleorder.productlist'),
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

    var createLineItem = function () {
        var global_counter = parseInt($('#line_item-global_counter').val()) + 1;

        var line_item = $('#line_item-container').html().replace(/########/g, '').replace(/######/g, global_counter);

        $('#users-table tr:last').after(line_item);
        // Apply Select2 on newly created item


        //$('#line_item-unit_name_id-'+global_counter).select2();
        $('#line_item-product_id-'+global_counter).select2(Select2AjaxObj());
        $('#line_item-global_counter').val(global_counter)
    }

    var destroyLineItem = function (itemId) {
        var r = confirm("Are you sure to delete Line Item?");
        if (r == true) {
            $('#line_item-'+itemId).remove();
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
    //prevent dublicate Entries
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





    // calculate total with Quanuity and unit price for product-request enter products
    /* this is only for when price will b change */
    $(document).on('keyup change','.manual_unit_price',function () {
        calculated_amount = 0;
        var amount = $(this).val();
        var rowId = $(this).attr('data-row-id');
        var quantity = $('#line_items_quantity_id-'+rowId).val();
        console.log("id :", rowId,' q: ', quantity,' a : ',amount);
        total = amount * quantity;
        $('#line_items_total_price_id-'+rowId).val(total.toFixed(2));
        $('.cal_sum').each(function () {
            totalvalue = $(this).val();
            if(totalvalue != '' && totalvalue != '0')
            {
                calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
                $('#total_of_product').val(calculated_amount);
                $('#grand_total').val(calculated_amount);
            }

        });
    })

    // calculate total with Quanuity and unit price for manually enter products
    /* this is only for when Quanuity will b change */
    $(document).on('keyup change','.manual_quantity',function () {
        calculated_amount = 0;
        var rowId            =           $(this).attr('data-row-id');
        var amount           =           $('#line_items_Unit_price_id-'+rowId).val();
        var quantity_        =           $(this).val();
        var weight           =           $('#line_items_weight_id-'+rowId).val();
        console.log('id:   ',       rowId,
                    'q:    ',       quantity_,
                    'a:    ',       amount,
                    'Weight:    ',  weight);

        total_weight = weight*quantity_;
        total = amount * quantity_;
        $('#line_items_total_price_id-'+rowId).val(total.toFixed(2));
        $('#line_items_total_weight_id-'+rowId).val(total_weight.toFixed(2));

        $('.cal_sum').each(function () {
            totalvalue = $(this).val();
            if(totalvalue != '' && totalvalue != '0')
            {
                calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
                $('#total_of_product').val(calculated_amount);
                $('#grand_total').val(calculated_amount);
            }

        });
    })

    // calculate total Weight with Quanuity for manually enter products
    /* this is only for when Quanuity will b change */
    $(document).on('keyup change','.manual_weight',function () {
        var weight         =   $(this).val();
        var rowId          =   $(this).attr('data-row-id');
        var quantity_      =   $('#line_items_quantity_id-'+rowId).val();
        var total_weight =   weight*quantity_;
        $('#line_items_total_weight_id-'+rowId).val(total_weight.toFixed(2));
        console.log('weight: ' +weight +
                    '     quantity: ' + quantity_);
        console.log('Total weight is :' +total_weight);



    })

    // calculate total with Unit_Price for product-request enter products
    /* this is only for when price will b change */
    $(document).on('keyup change','.request_unit_price',function () {

        var amount = $(this).val();
        var rowId = $(this).attr('data-row-id');
        var quantity_unit_price = $('#line_items_qty_id-'+rowId).val();
        console.log("id :", rowId,' q: ', quantity_unit_price,' a : ',amount);
        total = amount * quantity_unit_price;

        $('#line_items_total_id-'+rowId).val(total.toFixed(2));

        $('.cal_sum').each(function () {
            var totalvalue = $(this).val();
            if(totalvalue != '' && totalvalue != '0')
            {
                var calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
                $('#total_of_product').val(calculated_amount);
                $('#grand_total').val(calculated_amount);
            }

        });
    })

    // calculate total with Quanuity and unit price for product-request enter products
    /* this is only for when quantity will b change */
    $(document).on('keyup change','.request_quantity',function () {
        var quantity_request = $(this).val();
        var rowId = $(this).attr('data-row-id');
        var amount = $('#line_items_Uprice_id-'+rowId).val();
        /*console.log("id :", rowId,' q: ', quantity_request,' a : ',amount);*/
        total = amount * quantity_request;
        $('#line_items_total_id-'+rowId).val(total.toFixed(2));
        var weight =  $('#line_items_Weight_id-'+rowId).val();
        var total_weight = weight*quantity_request;
        $('#line_items_Total_Weight_id-'+rowId).val(total_weight.toFixed(2));
        /* console.log('Quantity is:',quantity_request,
         'weight is:',weight,
         'total weight is',total_weight);*/

        $('.cal_sum').each(function () {
            var totalvalue = $(this).val();
            if(totalvalue != '' && totalvalue != '0')
            {
                var calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
                $('#total_of_product').val(calculated_amount);
                $('#grand_total').val(calculated_amount);
                console.log('total amount is',calculated_amount);
            }

        });
    })

    // calculate total_weight with weight for product-request enter products
    /* this is only for when quantity will b change */
    $(document).on('keyup change','.request_weight',function () {
        var weight         =   $(this).val();
        var rowId          =   $(this).attr('data-row-id');
        var quantity_      =   $('#line_items_qty_id-'+rowId).val();
        var total_weight =   weight*quantity_;
        $('#line_items_Total_Weight_id-'+rowId).val(total_weight.toFixed(2));


        /*console.log('weight: ' +weight +
         '     quantity: ' + quantity_);
         console.log('Total weight is :' +total_weight);*/
    })











    $('#total_tax').keyup(function () {
        tax = $(this).val();
        total_of_product = $('#total_of_product').val();
        freight = $('#total_freight').val();
        discount = $('#total_discount').val();
        grand_total = parseFloat(total_of_product)+parseFloat(tax)+parseFloat(freight)-parseFloat(discount);
        $('#grand_total').val(grand_total.toFixed(2));
        /*console.log('Tax:  ' + tax  + 'Freight:  ' + freight + 'discount:  ' + discount + 'Total of products:  ' + total_of_product+ 'Grand Total:  ' + grand_total);*/
    });

    $('#total_freight').keyup(function () {
        tax = $('#total_tax').val();
        total_of_product = $('#total_of_product').val();
        freight = $(this).val();
        discount = $('#total_discount').val();
        grand_total = parseFloat(total_of_product)+parseFloat(tax)+parseFloat(freight)-parseFloat(discount);
        $('#grand_total').val(grand_total.toFixed(2));
        // console.log('Tax:  ' + tax  + 'Freight:  ' + freight + 'discount:  ' + discount + 'Total of products:  ' + total_of_product+ 'Grand Total:  ' + grand_total);
    });

    $('#total_discount').keyup(function () {
        tax = $('#total_tax').val();
        total_of_product = $('#total_of_product').val();
        freight = $('#total_freight').val();
        discount = $(this).val();
        grand_total = parseFloat(total_of_product)+parseFloat(tax)+parseFloat(freight)-parseFloat(discount);
        $('#grand_total').val(grand_total.toFixed(2));
        // console.log('Tax:  ' + tax  + 'Freight:  ' + freight + 'discount:  ' + discount + 'Total of products:  ' + total_of_product+ 'Grand Total:  ' + grand_total);
    });


});

