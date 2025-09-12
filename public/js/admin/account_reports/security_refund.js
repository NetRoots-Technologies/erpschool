/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        // To make Pace works on Ajax calls
        $(document).ajaxStart(function () {
            Pace.restart()
        })

        $( "#account-Reports-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                group_id: {
                    required: true,
                },
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "group_id") {
                    error.insertAfter($('#group_id_handler'));
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            submitHandler: function(form) {
                $('#load_report').html('<i class="fa fa-spin fa-refresh"></i>&nbsp;Load Report').attr('disabled',true);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route('admin.account_reports.ledger_statement_report'),
                    type: "POST",
                    data: {
                        date_range: $('#date_range').val(),
                        branch_id: $('#branch_id').val(),
                        employee_id: $('#employee_id').val(),
                        department_id: $('#department_id').val(),
                        entry_type_id: $('#entry_type_id').val(),
                        account_type_id: $('#account_type_id').val(),
                        group_id: $('#group_id').val(),
                        medium_type: 'web',
                    },
                    success: function(response){
                        $('#content').html(response);
                        $('#load_report').html('Load Report').removeAttr('disabled');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#load_report').html('Load Report').removeAttr('disabled');
                        return false;
                    }
                });
                return false;
            }
        });

        $('.select2').select2();

        $('#account_type_id').select2().on("select2:select", function(e) {
            if($(this).val() != '') {
                $('#load_report').html('<i class="fa fa-spin fa-refresh"></i>&nbsp;Load Report').attr('disabled',true);
            } else {
                $('#load_report').html('Load Report').removeAttr('disabled');
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: route('admin.account_reports.load_ledgers'),
                type: "POST",
                data: {
                    account_type_id: $('#account_type_id').val(),
                },
                success: function(response){
                    var dropdown = '<select name="group_id" id="group_id" class="form-control select2" style="width: 100%;"> <option value=""> Select a Ledger </option>' + response.dropdown + '</select>';
                    $('#group_id_content').html(dropdown);
                    $('#group_id').select2();
                    $('#load_report').html('Load Report').removeAttr('disabled');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#load_report').html('Load Report').removeAttr('disabled');
                    return false;
                }
            });
        });

        $('#date_range').daterangepicker({
            "alwaysShowCalendars": true,
            locale: {
                // cancelLabel: 'Clear'
            },
            ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year'  : [moment().startOf('year'), moment().endOf('year')],
                'Last Year'  : [moment().subtract(1, 'year').startOf('month'), moment().subtract(1, 'year').endOf('year')],
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
        });

        $('input[name="date_range"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
            // $(this).val('');
        });
    }

    var loadReport = function () {

    }

    var printReport = function (medium_type) {
        $('#date_range-report').val($('#date_range').val());
        $('#branch_id-report').val($('#branch_id').val());
        $('#employee_id-report').val($('#employee_id').val());
        $('#department_id-report').val($('#department_id').val());
        $('#entry_type_id-report').val($('#entry_type_id').val());
        $('#account_type_id-report').val($('#account_type_id').val());
        $('#group_id-report').val($('#group_id').val());
        $('#medium_type-report').val(medium_type);
        $('#report-form').submit();
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        loadReport: loadReport,
        printReport: printReport,
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});