<script>
    $(document).ready(function () {
        $('#end-date, #start-date').on('change', function () {
            var start_date_str = $('#start-date').val();
            var end_date_str = $('#end-date').val();

            var start_date = moment(start_date_str, "YYYY-MM-DD");
            var end_date = moment(end_date_str, "YYYY-MM-DD");

            if (end_date < start_date || start_date > end_date) {
                alert("End Date should be greater than Start Date");
                $('#end-date').val('');
                $('#num-days').val('');
            } else {
                var days = 1;
                $('#select-duration').show();

                if (!start_date.isSame(end_date, 'day')) {
                    var current_date = moment(start_date);

                    while (current_date.isBefore(end_date)) {

                        if (current_date.weekday() !== 6 && current_date.weekday() !== 0) {
                            days++;
                        }

                        current_date.add(1, 'days');
                    }

                    if (days > 3) {
                        alert("You can only select a maximum of 3 days.");
                        $('#end-date').val('');
                        $('#num-days').val('');
                        return;
                    }

                    $('#select-duration').hide();
                }


                $('#num-days').val(days);
            }
        });
    });

</script>

<script>

    $(document).ready(function () {


        $('#days_select').on('change', function () {
            var days = $(this).val();
            if (days === 'half_day') {
                $('#time-duration').show()
            } else {
                $('#time-duration').hide()
            }

        })
    })

</script>

<script>
    $('.datepicker-date').bootstrapdatepicker({
        format: "yyyy-mm-dd",
        viewMode: "date",
        multidate: false,
        multidateSeparator: "-",
    });
</script>


<script>
    $(document).ready(function () {
        var leave_type = $('#leave_type').val();
        var employee_id = $('#employee_id').val();

        leavebalance();

        $('#leave_type').on('change', function () {
            leave_type = $(this).val();
            employee_id = $('#employee_id').val();
            leavebalance();
        });

        function leavebalance() {
            $.ajax({
                type: 'post',
                data: {
                    leave_type: leave_type,
                    employee_id: employee_id
                },
                url: '{{ route('hr.leave_balance') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#loadEmployeeEntitlements').html(data);
                },
            });
        }
    });
</script>

