/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {



        // $('.description-data-ajax').select2(Select2AjaxObj());

        $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });

        // $('.select2').select2();

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                name: {
                    required: true,
                    maxlength: 50
                },
                stage: {
                    required: true,
                },
                prospect_status: {
                    required: true
                },
                description: {
                    maxlength : 200
                },
                qoute_title: {
                    maxlength : 100
                },
                valid_till: {
                    maxlength : 20
                },
                approval_issues: {
                    maxlength : 200
                },
                qoute_remarks: {
                    maxlength : 200
                },
                tender_win_lose: {
                    maxlength : 100
                }
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
            placeholder: "Product",
            minimumInputLength: 2,
            ajax: {
                url: route('marketing.salepipelines.getProductsByName'),
                dataType: 'json',
                delay: 500,
                data: function (params) {

                    return {
                        name: params.term,
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
    
    var CalculateTotal = function (lineId) {

        var product = $('#line_item-product_id-'+lineId).val();
        if ( product )
        {
            var product_id = $('#line_item-product_id-'+lineId).val();
            var product_qty = $('#line_item-product_qty-'+lineId).val();
            var list_price = $('#line_item-list_price-'+lineId).val();
            var discount = $('#line_item-discount-'+lineId).val();

            var misc_amount = $('#line_item-misc_amount-'+lineId).val();
            var tax_percent = $('#line_item-tax_percent-'+lineId).val();
            var sale_price=$('#line_item-tax_percent-'+lineId).val();
            var discount_amount=0;

            if(! misc_amount){
                misc_amount = 0;
            }else{
                misc_amount =  misc_amount.replace(/,/g , "");
            }

            if(! sale_price){
                sale_price = 0;
            }else{
                sale_price =  sale_price.replace(/,/g , "");
            }
            if(! list_price){
                list_price = 0;

            }else{
                list_price =  list_price.replace(/,/g , "");
            }
            if(! product_qty){
                product_qty=1;
            }
            if(! discount){
                discount=0;
            }else{
                discount =  discount.replace(/,/g , "");
            }

            if(! tax_percent){
                tax_percent=0;
            }

            // var tax_amount = ( Number(list_price) *  Number( tax_percent )) / ( 100 + Number( tax_percent ) );
            tax_amount = Number(list_price) * Number(tax_percent)/100;
            tax_amount = parseFloat(tax_amount).toFixed(2);
            sale_price = Number(list_price) + Number(tax_amount);
            sale_price = parseFloat(sale_price).toFixed(2);

            $("#line_item-sale_price-"+lineId).val(sale_price );

            var total_amount = (Number(list_price) * Number(product_qty)) ;
            var total_line_item_discount = discount_amount * Number(product_qty);
            var total_sale_price = sale_price * Number(product_qty);
            $("#line_item-total_discount-"+lineId).val(total_line_item_discount);
            $("#line_item-tax_amount-"+lineId).val(tax_amount);
            $("#line_item-total_amount-"+lineId).val(total_amount);
            var net_income = Number(total_sale_price) - Number(misc_amount) - Number (discount);
            net_income = parseFloat(net_income).toFixed(2);
            $("#line_item-net_income-"+lineId).val(net_income);


            CalculateGrandTotal();
          //  formateNumberInput(lineId);
        }


    }

    var createLineItem = function () {
        var global_counter = parseInt($('#line_item-global_counter').val()) + 1;
        var line_item = $('#line_item-container').html().replace(/########/g, '').replace(/######/g, global_counter);

        $('#users-table tr:last').after(line_item);
        // Apply Select2 on newly created item
        $('#line_item-product_id-'+global_counter).select2(Select2AjaxObj());
        $('#line_item-product_id-'+global_counter);
        $('#line_item-global_counter').val(global_counter)
    }


    var destroyLineItem = function (itemId) {
        var r = confirm("Are you sure to delete Line Item?");
        if (r == true) {
            $('#entry_item-ledger_id-'+itemId).select2(Select2AjaxObj());
            
            $('#line_item-'+itemId).remove();
            $('#desc_item-'+itemId).remove();

            CalculateGrandTotal();
        }
    }

    var CalculateGrandTotal = function () {

        var total_dr_amount = 0;
        var total_after_discount = 0;

        $(".line_net_income").each(function(){
            if($(this).val()){
                total_dr_amount = total_dr_amount + parseFloat($(this).val());

            }
        });

        var is_discount = $('#is_discount').is(':checked');
        var discount_amount =0;
        if(is_discount){
            var discount_value = $('#discount_value').val();
            var discount_type = $('#discount_type').val();
             discount_amount = discount_value;
            if(discount_type == 2){
                discount_amount = Number (total_dr_amount) * Number (discount_value)/100;
            }
        }else{
            $("#discount_type").val('1');
            $('#discount_value').val('0');

        }

        $('#discount_amount').val(discount_amount);
        total_after_discount = total_dr_amount - discount_amount;
        $('#total').val(total_dr_amount);
        $('#total_after_discount').val(total_after_discount);

        var is_taxable = $('#is_taxable').is(':checked');
        var tax_amount =0;
        if(is_taxable){
            var tax_percentage = $('#tax_percentage').val();
            tax_amount = Number(total_after_discount) * Number(tax_percentage)/100;
        }else{
            $("#tax_percentage").val('0');
        }
        $('#tax_amount').val(tax_amount);
        var total_after_tax = total_after_discount + tax_amount;
        $('#total_after_tax').val(total_after_tax);

        var is_service = $('#is_service').is(':checked');
        var service_amount =0;
        var service_tax = 0;
        if(is_service){
            var service_value = $('#service_value').val();
            var service_type = $('#service_type').val();
            service_tax = $('#service_tax').val();
            service_amount = service_value;
            if(service_type == 2){
                service_amount = Number (total_after_tax) * Number (service_value)/100;
            }
        }
        else{

            $('#service_value').val(0);
            $("#service_type").val('1');
        }
        

        $('#service_amount').val(service_amount);

        var total_payable = Number(total_after_tax) + Number(service_amount);
        service_tax_amount = Number(service_amount) * Number(service_tax)/100;
        $('#service_tax_amount').val(service_tax_amount);
        var grand_payable = Number(service_tax_amount) + Number(total_payable)
        $('#total_payable').val(grand_payable);

    }



    var formateNumberInput = function (lineId){
        var product_qty = $('#line_item-product_qty-'+lineId).val();
        var unit_price = $('#line_item-unit_price-'+lineId).val();
        var discount = $('#line_item-discount-'+lineId).val();
        var sale_price = $('#line_item-sale_price-'+lineId).val();

        var misc_amount = $('#line_item-misc_amount-'+lineId).val();
        var tax_percent = $('#line_item-tax_percent-'+lineId).val();
        if(unit_price){

            unit_price = unit_price.replace(/[\D\s\._\-]+/g, "");
            unit_price = unit_price ? parseFloat( unit_price, 2 ) : 0;

            $("#line_item-unit_price-"+lineId).val( function() {
                return ( unit_price === 0 ) ? "" : unit_price.toLocaleString("en-US");
            } );
        }

        if(sale_price){

            sale_price =  sale_price.toString().split( /(?=(?:\d{3})+(?:\.|$))/g ).join( "," );
            $("#line_item-sale_price-"+lineId).val( function() {
                return ( sale_price === 0 ) ? "" : sale_price.toLocaleString("en-US");
            } );
        }

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        createLineItem: createLineItem,
        destroyLineItem: destroyLineItem,
        CalculateTotal: CalculateTotal,
        CalculateGrandTotal: CalculateGrandTotal,
        formateNumberInput : formateNumberInput,
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});