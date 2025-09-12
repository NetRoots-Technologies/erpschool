/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition

jQuery(document).ready(function() {

    var calculated_amount = 0;
    $('.cal_sum').each(function () {
        totalvalue = $(this).val();
        if(totalvalue != '' && totalvalue != '0')
        {
            calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
            $('#total_of_product').val(calculated_amount);
            $('#grand_total').val(calculated_amount);
        }
        else {

        }

    });


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

