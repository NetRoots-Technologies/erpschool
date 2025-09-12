/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });

        $('.select2').select2();
        $('#branch_id_div').hide();
        $('#dealer_id_div').hide();
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
        var check_button = $('input[name="invoice_to"]:checked').val();
        invoice_to(check_button);
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


    var invoice_to = function (to) {
        if( to == 'customer'){
            $('#customer_id_div').show()
            $('#branch_id_div').hide()
            $('#dealer_id_div').hide()
        }else if( to == 'branch' ){
            console.log('Here branch');
            $('#customer_id_div').hide()
            $('#branch_id_div').show()
            $('#dealer_id_div').hide()
        }else if(to == 'dealer'){
            $('#customer_id_div').hide()
            $('#branch_id_div').hide()
            $('#dealer_id_div').show()
        }
    }

    var CalculateTotal = function (lineId) {

        var product = $('#line_item-product_id-'+lineId).val();
        if ( product )
        {
            var product_id = $('#line_item-product_id-'+lineId).val();
            var product_qty = $('#line_item-product_qty-'+lineId).val();
            
            var unit_price = $('#line_item-unit_price-'+ lineId ).val();
            var total_unit_price = Number(product_qty) * Number(unit_price);

            if(! product_qty ){
                product_qty = 1;
            }
            console.log('total_unit_price : ', total_unit_price);

            var list_price = $('#line_item-list_price-'+lineId).val();
            var discount = $('#line_item-discount_val-'+lineId).val();
            var discount_type = $('#line_item-disc_type-'+lineId).val();

            if(! discount){
                discount=0;
            }else{
                discount =  discount.replace(/,/g , "");
            }
            var per_piece_discount = 0;

            var total_after_discount = total_unit_price;
            var per_piece_after_dicount = Number(unit_price);
            var line_dicount_amount = 0;
            if( discount_type == 1 ){
                // per_piece_after_dicount = unit_price - discount;
                total_after_discount = total_unit_price - discount ;
                per_piece_discount = discount;
            }
            if( discount_type == 2 ){

                var discount_amount = unit_price * discount / 100;
                per_piece_discount = unit_price * discount / 100;
                // per_piece_after_dicount = unit_price - discount_amount ;
                // per_piece_after_dicount = unit_price + discount_amount ;
            }

            line_dicount_amount = per_piece_discount * product_qty;
            var tax_percent = $('#line_item-tax_percent-'+lineId).val();
            if(! tax_percent){
                tax_percent = 0;
            }

            var total_after_tax = total_after_discount;
            var per_piece_after_tax = per_piece_after_dicount;
            var tax_amount = 0;
            var line_tax_amount = 0;
            if( tax_percent != 0){
                 tax_amount = per_piece_after_dicount * tax_percent / 100;
                line_tax_amount =  tax_amount * product_qty;
            }
            console.log('tax_amount : ', tax_amount);
            per_piece_after_tax = per_piece_after_dicount + tax_amount ;

            // per piece sale price after tax
            var per_piece_sale_price = per_piece_after_tax  - per_piece_discount;
            var misc = $('#line_item-misc_amount-'+lineId).val();
            if(! misc){
                misc = 0;
            }else{
                misc =  misc.replace(/,/g , "");
            }
            var line_total =  per_piece_after_tax * product_qty;

            total_after_tax = per_piece_after_tax * product_qty;
            var net_income = total_after_tax - misc;

            $('#line_item-disc_amount-' + lineId).val(parseFloat(line_dicount_amount).toFixed());

            $('#line_item-tax_amount-' + lineId).val(parseFloat(line_tax_amount).toFixed());
            // set sale price
            $('#line_item-sale_price-' + lineId).val(parseFloat(per_piece_sale_price ).toFixed());
            // set total
            $('#line_item-total_price-' + lineId).val( parseFloat( per_piece_sale_price * product_qty).toFixed() );

            $('#line_item-line_total-' + lineId).val( parseFloat(unit_price * product_qty).toFixed());
            //set net income
            $('#line_item-net_income-' + lineId).val(parseFloat(((unit_price - per_piece_discount) * product_qty ) - misc ).toFixed() );

             CalculateGrandTotal();
          //  formateNumberInput(lineId);
        }

    }

    var CalculateServiceTotal = function (lineId) {

        var service = $('#service_items-service-'+lineId).val();
        if ( service )
        {

            var list_price = $('#service_item-list_price-'+lineId).val();
            var discount = $('#service_item-discount-'+lineId).val();

            var misc_amount = $('#service_item-misc_amount-'+lineId).val();
            var tax_percent = $('#service_item-tax_percent-'+lineId).val();
            var sale_price=0;
            var discount_amount=0;
            if(! list_price){
                list_price = 0;
            }

            if(! discount){
                discount=0;
            }
            if(! misc_amount){
                misc_amount=0;
            }

            if(! tax_percent){
                tax_percent=0;
            }

           var tax_amount = ( Number(list_price) *  Number( tax_percent )) / (100 + Number( tax_percent ) );
            tax_amount = parseFloat(tax_amount).toFixed(2);
            sale_price = Number(list_price) - tax_amount;
            sale_price = parseFloat(sale_price).toFixed(2);
            $("#service_item-sale_price-"+lineId).val(sale_price );
            var total_amount = sale_price ;

            $("#service_item-tax_amount-"+lineId).val(tax_amount);
            $("#service_item-total_amount-"+lineId).val(list_price);

            var net_income = Number(sale_price) - Number(misc_amount) - Number (discount);
            net_income = parseFloat(net_income).toFixed(2);
            $("#service_item-net_income-"+lineId).val(net_income);
            CalculateGrandTotal();
        }
    }
    var createLineItem = function () {

        var global_counter = parseInt($('#line_item-global_counter').val()) + 1;
        var line_item = $('#line_item-container').html().replace(/########/g, '').replace(/######/g, global_counter);

        $('#users-table tr:last').after(line_item);
        // Apply Select2 on newly created item
        $('#line_item-product_id-'+global_counter).select2(Select2AjaxObj());
        $('#line_item-global_counter').val(global_counter)

    }


    var createOtherLineItem = function () {
        var global_counter = parseInt($('#line_item-global_counter').val()) + 1;
        var line_item = $('#others-container').html().replace(/########/g, '').replace(/######/g, global_counter);

        $('#users-table tr:last').after(line_item);
        // Apply Select2 on newly created item
        $('#line_item-product_id-'+global_counter).select2(Select2AjaxObj());
        $('#line_item-global_counter').val(global_counter);
    }

    var createServiceItem = function () {

        var global_counter = parseInt($('#service_item-global_counter').val()) + 1;
        var service_item = $('#service_item-container').html().replace(/########/g, '').replace(/######/g, global_counter);
        $('#service-table tr:last').after(service_item);

        $('#service_item-global_counter').val(global_counter)
    }

    var destroyServiceItem = function (itemId) {
        var r = confirm("Are you sure to delete Line Item?");
        if (r == true) {
            $('#service_item-'+itemId).remove();
            $('#service_total_item-'+itemId).remove();
            CalculateGrandTotal();
        }
    }
    var destroyLineItem = function (itemId) {
        var r = confirm("Are you sure to delete Line Item?");
        if (r == true) {
            // $('#entry_item-ledger_id-'+itemId).select2(Select2AjaxObj());

            $('#item_header-'+itemId).remove();
            $('#line_item-'+itemId).remove();
            $('#price_disc-'+itemId).remove();
            $('#price_tax-'+itemId).remove();
            $('#desc_item-'+itemId).remove();
            $('#tax_disc-'+itemId).remove();


            CalculateGrandTotal();
        }
    }
    var CalculateGrandTotal = function () {
        var line_disc_amount = 0;
        var line_tax_amount = 0;
        var line_gross_amount = 0;
        var line_misc_amount = 0;
        var total_dr_amount = 0;
        var line_net_income = 0;
        var total_dr_discount = 0;
        var total_line_misc_amount = 0;
        var line_item_total_price = 0 ;
        var is_service = $('#is_service').is(':checked');

        var overall_discount = $('#overall_discount').val();

        if( ! overall_discount ){
            overall_discount = 0;
        }

        $(".line_disc_amount").each(function(){
            if($(this).val()){
                line_disc_amount = line_disc_amount + parseFloat($(this).val());
            }
        });

        $(".line_tax_amount").each(function(){
            if($(this).val()){
                line_tax_amount = line_tax_amount + parseFloat($(this).val());
            }
        });

        $(".line_gross_amount").each(function(){
            if($(this).val()){
                line_gross_amount = line_gross_amount + parseFloat($(this).val());
            }
        });


        $(".line_item_total_price").each(function(){
            if($(this).val()){
                line_item_total_price = line_item_total_price + parseFloat($(this).val());
                
            }
        });

        $(".dr_misc_amount").each(function() {
            if ($(this).val()) {
                line_misc_amount = line_misc_amount + parseFloat($(this).val());
            }
        });

        $(".line_net_income").each(function(){
            if($(this).val()){
                line_net_income = line_net_income + parseFloat($(this).val());
            }
        });

        total_dr_amount = parseFloat(total_dr_amount).toFixed(2);
        line_net_income = parseFloat(line_net_income).toFixed(2);
        total_dr_discount = parseFloat(total_dr_discount).toFixed(2);

        var service_amount = $('#service_amount').val();
        var service_tax = $('#service_tax').val();
        var service_tax_amount = 0;

        if( ! service_amount ){
            service_amount = 0;
        }
        if( ! service_tax ){
            service_tax = 0;
        }

        service_tax_amount = Number(service_amount) * Number(service_tax) / 100 ;

        var total_after_service_tax = line_gross_amount + line_tax_amount - line_disc_amount + Number(service_amount) + Number(service_tax_amount);
        var total_payable = total_after_service_tax - overall_discount ;

        $('#total_discount').val(line_disc_amount);
        $('#total_tax').val( line_tax_amount + Number(service_tax_amount));
        $('#gross_total').val(line_item_total_price);
        $('#total_payable').val(total_payable);
        $('#misc_amount').val(line_misc_amount);

        $('#service_tax_amount').val(service_tax_amount);
        $('#total_after_service_tax').val(total_after_service_tax);


        $('#total').val(total_dr_amount);
        $('#grand_total').val(total_dr_amount);
        $('#net_income').val(Number(line_net_income) + Number(service_amount));
        $('#discount').val(total_dr_discount);

       // CalculateNetIncome();


    }

    var CalculateNetIncome = function () {
        var misc_amount =  $('#misc_amount').val();
        var total = $('#total').val();
        if(total && misc_amount ){
            var net_income =  parseFloat(total) - parseFloat(misc_amount);
            net_income = parseFloat(net_income).toFixed(3);
            $('#net_income').val(net_income);
        }


    }
    var setWarrenty = function () {
        var installation_date = $('#installation_date').val();
        var months = $('#warrenty').val();
//                    var expires_at =

//                    var moment_date  = moment(installation_date).format('YYYY-MM-DD');
        var endDateMoment = moment(installation_date); // moment(...) can also be used to parse dates in string format
        endDateMoment.add(parseInt(months), 'months');
        $('#expires_at').val(moment(endDateMoment).format('YYYY-MM-DD'))
//                    console.log('endDateMoment : ', moment(endDateMoment).format('YYYY-MM-DD'));

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },

        invoice_to: invoice_to,
        createLineItem: createLineItem,
        destroyLineItem: destroyLineItem,
        createServiceItem: createServiceItem,
        destroyServiceItem: destroyServiceItem,
        CalculateTotal: CalculateTotal,
        CalculateGrandTotal: CalculateGrandTotal,
        CalculateServiceTotal : CalculateServiceTotal,
        CalculateNetIncome:  CalculateNetIncome,
        createOtherLineItem: createOtherLineItem,
        setWarrenty: setWarrenty
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});