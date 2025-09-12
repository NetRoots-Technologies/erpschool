/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions
    var token = $("input[name=_token]").val();

    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    $('.select2').select2();

    var baseFunction = function () {

    }

    var fetchEmployeeRecord = function () {

        var user_id = $('#user_id').val();

        if(! user_id){
            alert('Please Select Employee');
        }
        else {

            $.ajax({
                type: "POST",
                url: 'getGratuityDetail',
                data: {
                    'user_id' : user_id,
                    '_token':token

                },
                success: function(result){

                    calculateGratuity(result);
                    //calculateSalary(result.employee, result.holidays, result.attendance, result.leaves, result.halfs, result.overtimes, result.tax_slab, result.working_days);
                }
            });
        }

    }

    var calculateGratuity = function (result){

        if(result.status === 0){
            setFeildsVals('','','','');
            alert('Gratuity has already been paid');
            return false;
        }else if(result.status === 2){
            setFeildsVals('','','','');
            alert('Gratuity cannot be calculated');
            return false;
        }
        console.log(result);
        var years = result.years.years;
        var basic = result.second_last.basic_salary;
        $('#perm_start_date').val(result.basic.perm_start_date);
        $('#years').val(years);
        $('#basic').val(result.second_last.basic_salary);
        var gratuity = 0;
        if(years >= 6){
            gratuity = basic * years;
        }
        $('#amount').val(gratuity);

        setFeildsVals(result.basic.perm_start_date,years,basic,gratuity)

    }
    var setFeildsVals =  function (perm_start_date, years,basic_salary,gratuity){
        $('#perm_start_date').val(perm_start_date);
        $('#years').val(years);
        $('#basic').val(basic_salary);
        $('#amount').val(gratuity);
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        fetchEmployeeRecord : fetchEmployeeRecord,
        calculateGratuity : calculateGratuity,
        setFeildsVals: setFeildsVals,
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});