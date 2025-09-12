<script>
    $(document).ready(function () {
        function calculateDiscountedAmount(row) {
            // alert('discountFunction');
            //
            var totalMonthlyAmount = parseFloat(row.find('.total-monthly-amount').val());
            var discountPercentage = parseFloat(row.find('.discount-percentage').val());
            var discountedAmount =  totalMonthlyAmount - ((totalMonthlyAmount / 100) * discountPercentage);

            var discountRupees = parseFloat(row.find('.discount-rupees').val()) || 0;
            var claim1 = parseFloat(row.find('.claim-1').val()) || 0;
            var claim2 = parseFloat(row.find('.claim-2').val()) || 0;
            var currentDeductedAmount = discountRupees + claim1 + claim2;
            var newTotalAmountAfterDiscount = discountedAmount - currentDeductedAmount;
            row.find('.total-amount-after-discount').val(newTotalAmountAfterDiscount.toFixed(0));
        }

        $('.discount-percentage').each(function() {
            calculateDiscountedAmount($(this).closest('tr'));
            updateTotalAmounts();

        });

        $('.discount-percentage').on('input', function () {
            calculateDiscountedAmount($(this).closest('tr'));
            updateTotalAmounts();
        });


        $('.total-monthly-amount').on('input', function () {
            calculateDiscountedAmount($(this).closest('tr'));
            updateTotalAmounts();
        });

        $('.discount-rupees, .claim-1, .claim-2').on('input', function () {
            var row = $(this).closest('tr');
            calculateDiscountedAmount(row);
        }).trigger('input');

    });
</script>
<script>
    $(document).ready(function () {
        // function calculateTotalAmountAfterDiscount(row) {
        //
        //     var totalAmountAfterDiscount = parseFloat(row.find('.total-amount-after-discount').val()) || 0;
        //     var discountRupees = parseFloat(row.find('.discount-rupees').val()) || 0;
        //     var claim1 = parseFloat(row.find('.claim-1').val()) || 0;
        //     var claim2 = parseFloat(row.find('.claim-2').val()) || 0;
        //     var previousDeductedAmount = parseFloat(row.attr('data-previous-deducted-amount')) || 0;
        //     var currentDeductedAmount = discountRupees + claim1 + claim2;
        //     var newTotalAmountAfterDiscount = totalAmountAfterDiscount + previousDeductedAmount - currentDeductedAmount;
        //     row.attr('data-previous-deducted-amount', currentDeductedAmount);
        //     row.find('.total-amount-after-discount').val(newTotalAmountAfterDiscount.toFixed(0));
        // }



    });

</script>


<script>
    $(document).on('input', '.total-monthly-amount', function () {
        updateTotalAmounts();
    });

    $(document).on('input', '.change-amount', function () {
        updateTotalAmounts();
    });

    function updateTotalAmounts() {
        var totalMonthly = 0;
        var totalMonthlyDiscount = 0;
        var totalDiscountPercentage = 0;
        var totalDiscountRupees = 0;
        var totalClaim1 = 0;
        var totalClaim2 = 0;

        $('.total-monthly-amount').each(function () {
            totalMonthly += parseFloat($(this).val()) || 0;
        });

        $('.total-amount-after-discount').each(function () {
            totalMonthlyDiscount += parseFloat($(this).val()) || 0;
        });

        $('.discount-percentage').each(function () {
            totalDiscountPercentage += parseFloat($(this).val()) || 0;
        });

        $('.discount-rupees').each(function () {
            totalDiscountRupees += parseFloat($(this).val()) || 0;
        });

        $('.claim-1').each(function () {
            totalClaim1 += parseFloat($(this).val()) || 0;
        });

        $('.claim-2').each(function () {
            totalClaim2 += parseFloat($(this).val()) || 0;
        });

        $('#totalMonthlyAmount').text(totalMonthly.toFixed(0));
        $('#claim1total').text(totalClaim1.toFixed(0));
        $('#claim2total').text(totalClaim2.toFixed(0));
        $('#totalDiscount').text(totalDiscountPercentage.toFixed(0));
        $('#totalDiscountRupees').text(totalDiscountRupees.toFixed(0));
        $('#total_amount_after_discount').text(totalMonthlyDiscount.toFixed(0));
        $('#hiddenTotalMonthlyAmount').val(totalMonthly.toFixed(0));
        $('#hiddenTotalDiscount').val(totalDiscountRupees.toFixed(0));
        $('#hiddenTotalDiscountMonthly').val(totalMonthlyDiscount.toFixed(0));
        $('#hiddenTotalClaim2').val(totalClaim1.toFixed(0));
        $('#hiddenTotalClaim2').val(totalClaim2.toFixed(0));
    }


</script>
