/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    document.getElementById('total_pf').readonly = true;

    // $('#paid_amount').change(function(){
    //     alert('OK');
    // });

    //== Private functions
    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    $('.select2').select2();
    var baseFunction = function () {
        lockFields();
    }

    var fetchEmployeeDetail = function () {
        var user_id = $('#user_id').val();
        var year = $('#year').val();
        var month = $('#month').val();
        var token = $("input[name=_token]").val();
        if(user_id && year && month){

            var today = new Date();
            var date = new Date(month+'-01-'+year);

            if(date < today){
                $.ajax({
                    type: "POST",
                    url: 'getEmployeeDetail',
                    data: {
                        'user_id' : user_id,
                        '_token':token,
                        'year' :  year,
                        'month' : month
                    },
                    success: function(result){
                        setFieldsValue(result);
                        openFields();
                        // calculateSalary(result.employee, result.holidays, result.attendance, result.leaves, result.halfs, result.overtimes, result.tax_slab, result.working_days);
                    }
                });
            }
            else{
                alert('Please Enter a Valid Date');
            }

        }else{
            alert('Please select required fields');

        }

    }

    //Calculating Salary of An Given Employee

    var calculateSalary = function (result, holidays, attendance, leaves, halfs, overtimes, tax, days) {

        var year = $('#year').val();
        var month = $('#month').val();

        var off_days = calculateSundays(month, year);
        if(days.sat == 8){
            var saturday_array = calculateSaturdays(month, year);
            off_days = off_days.concat(saturday_array).sort();
        }
        var all_holidays = calculateAllHolidays(off_days, holidays);

        var emp_leaves_data = calculateEmpLeaves(month, year, all_holidays, attendance, leaves, halfs, days);
        
        $('#overtime').val(calculateOvertimes(overtimes, result));
        // console.log(result);
        if(result.bank_name !== ''){
           // $('#bank_name').val(getBankNameById(result.bank_name));
            console.log('Res : ',result);
            $('#bank_name').val(result.bnk_name);
        }
        else{
            alert('Error while getting bank name');
        }

        $('#account_number').val(result.account_number);
        $('#basic_salary').val(result.basic_salary);
        if(result.sc_amount)
            $('#sc_amount').val(result.sc_amount);
        else
            $('#sc_amount').val(0);
        var house_rent = 0;
        var utilities = 0;
        var leaves = 0;
        var half_leaves = 0;
        var short_leaves = 0;
        var fined_shorts = 0;
        var sandwitch_leaves = 0;
        var basic_salary = result.basic_salary;
        var presents = 0;
        var allowed_leaves = 0;
        var deducted_amount = 0;
        var total_bonus = 0;
        var total_commission = 0;
        var gross_total = 0;
        var tax_percent = 0;
        var tax_amount = 0;
        var total_after_tax = 0;
        var paid_amount = 0;
        var pending_amount = 0;

        if(result.is_house_rent){
            house_rent = Number(basic_salary)* 0.45;
           $('#house_rent').val(house_rent);
        }else {
            $('#house_rent').val(house_rent);
        }
        if(result.is_utility){
            utilities = Number(basic_salary)* 0.05;
            $('#utilities').val(utilities);
        }else {
            $('#utilities').val(utilities);
        }

        $('#total_pf').val(0);

        var sc_amount = result.sc_amount;
        var overtime = $('#overtime').val();

        var basic_total = Number(basic_salary) + Number(house_rent) + Number(utilities) + Number(sc_amount) + Number(overtime);

        allowed_leaves = $('#allowed_leaves').val();
        leaves =  Number($('#leaves').val());
        short_leaves =  Number($('#short_leaves').val());
        half_leaves =  Number($('#half_leaves').val());
        fined_shorts = short_leaves / 3;
        fined_shorts = Math.floor(fined_shorts);
        presents =  Number($('#presents').val());

       // $('#short_leaves').val(short_leaves);
       // $('#presents').val(presents);
       // $('#sandwitch_leaves').val(sandwitch_leaves);


       var deducted_leaves = (Number(leaves)) + (Number(half_leaves) * 0.5 ) - Number(allowed_leaves);
       if(deducted_leaves < 0){
           deducted_leaves = 0;
       }

    
    //    var all_working_days = emp_leaves_data[0];

       per_day_salary = (basic_salary * 12/ 365 ) ;

       $('#wages').val(parseFloat(per_day_salary).toFixed(2));

       deducted_amount =  deducted_leaves * per_day_salary ;
       deducted_amount = parseFloat(deducted_amount).toFixed(2);

       console.log(deducted_leaves, per_day_salary, deducted_amount);

       if(deducted_leaves == emp_leaves_data[0].length){  /// Working
            $('#deducted_amount').val(basic_salary);
       }
       else{
            $('#deducted_amount').val(deducted_amount);
       }

       var after_leave_deduction = (basic_total - $('#deducted_amount').val()).toFixed(2);

       tax_percent = tax[0].tax_percent;
       var fixed = tax[0].fix_amount;
       var tax_amount = (tax_percent * basic_salary/100).toFixed(2);
       tax_amount = ((Number(tax_amount) + fixed)/12).toFixed(2);
       $('#tax_percentage').val(tax_percent);
       $('#tax_amount').val(tax_amount);

       var after_tax_amount = (after_leave_deduction - tax_amount).toFixed();

       $('#total_after_tax').val(after_tax_amount);
       $('#pending_amount').val(after_tax_amount);
       console.log('after_tax_amount : ', after_tax_amount);

       $('#total_bonus').val(0);            // Not Set
       $('#total_commission').val(0);        // Not Set
       $('#gross_total').val(after_leave_deduction);

       $("#paid_date").prop('readonly', false);
       $("#cash_date").prop('readonly', false);
       $("#paid_amount").prop('readonly', false);
       $(".select2").prop('disabled', false);

    }

    //Calculating Sundays of the Given Month      ---  Working

    var calculateSundays = function (month, year){
        var sunday_array = [] ;
        var d= new Date('20'+year, month, 0).getDate();
        var start = '20'+year+'-'+ month ;

        var totalSundays = 0;

        for (var i = new Date('20'+year, month-1, 1); i <= new Date('20'+year, month-1,d); i.setDate(i.getDate() + 1)) {
            if (i.getDay() == 0){
                var tempDate = i.getDate();
                if(tempDate <10){
                    tempDate = '0'+tempDate;
                }
                var sundayDate = '20'+year+'-'+month+'-'+tempDate;

                sunday_array.push(sundayDate);
                totalSundays++;
            }

        }

        return sunday_array;
    }

    //Calculating Dates Weekwise

    var getWeekDates = function(month, year){
        var start = new Date(month+'-01'+'-'+year),
            start_date = start.getDate(),
            end_date = new Date(start.getFullYear(), start.getMonth() + 1, 0).getDate(),
            week_dates = [], i=0, temp_dates = [];
            
            for(var j=start_date; j<=end_date; j++){
                var date = new Date(month+'-'+j+'-'+year);
                temp_date = date.getDate();
                if(temp_date < 10)
                    temp_date = '0'+temp_date;
                var day_date = '20'+year+'-'+month+'-'+temp_date;
                temp_dates.push(day_date);
                if(date.getDay() == 0){
                    week_dates[i] = temp_dates;
                    i++;
                    temp_dates = [];
                }
            }
            if(temp_dates.length != 0)
                week_dates[i] = temp_dates;
        return week_dates;

    }

    //Calculating Saturdays of the Given Month

    var calculateSaturdays = function (month, year){
        var saturday_array = [] ;
        var d= new Date('20'+year, month, 0).getDate();
        var start = '20'+year+'-'+ month ;

        var totalSaturdays = 0;

        for (var i = new Date('20'+year, month-1, 1); i <= new Date('20'+year, month-1,d); i.setDate(i.getDate() + 1)) {
            if (i.getDay() == 6){
                var tempDate = i.getDate();
                if(tempDate <10){
                    tempDate = '0'+tempDate;
                }
                var saturdayDate = '20'+year+'-'+month+'-'+tempDate;

                saturday_array.push(saturdayDate);
                totalSaturdays++;
            }

        }

        return saturday_array;
    }

    //Calculating Sundays + Holiday of the Given Month   --- Working

    var calculateAllHolidays = function (off_days, holidays){

        for(var i=0; i<holidays.length; i++){

            if(off_days.indexOf(holidays[i].holiday_date) == -1){
                off_days.push(holidays[i].holiday_date);
            }
        }

        off_days = off_days.sort();
        
        return off_days;
    }

    //Calculating Given Employee Leaves on Given Month and Year

    var calculateEmpLeaves = function(month, year, public_holidays, attendance_array, leaves, halfs, days){
        var full_pres = [];
        var half_pres = [];
        var short_pres = [];
        var full_off = [];
        var total_present = [];

        //Calculating Total days of Given Month   --- Working
        var days_of_month =[];
        var d= new Date('20'+year, month, 0).getDate(); //Getting Last Of Month
        for (var i = new Date('20'+year, month-1, 1); i <= new Date('20'+year, month-1,d); i.setDate(i.getDate() + 1)) {
                var tempDate = i.getDate();
                if(tempDate <10){
                    tempDate = '0'+tempDate;
                }
            var formatedDate = '20'+year+'-'+month+'-'+tempDate;
            days_of_month.push(formatedDate);

        }
        console.log('days_of_month : ', days_of_month);
        //Calculating Working Days Only   ---  Working
        var working_days = days_of_month.filter(x => !public_holidays.includes(x));
        console.log('working_days : ', working_days);

        
        var user_id = $('#user_id').val();
        var employee_attendance = attendance_array['employee_wise'][user_id];
        if(typeof employee_attendance !== typeof undefined){
            var dates = working_days.values(), date;
            while (!(date = dates.next()).done) {
                if(typeof employee_attendance[date.value] !== 'undefined'){
                    if(employee_attendance[date.value]['attendance_type'] == '1'){ //Short Leave
                        short_pres.push(date.value);
                    }
                    else if(employee_attendance[date.value]['attendance_type'] == '2'){ //Half Leave
                        half_pres.push(date.value);
                    }
                    else if(employee_attendance[date.value]['attendance_type'] == '3'){ //Full Leave
                        full_off.push(date.value);
                    }
                    else{ // Present
                        full_pres.push(date.value);
                    }
                    total_present.push(date.value);
                }
                else{
                    full_off.push(date.value);
                }
            }
        }
        else{
            full_off = working_days;
        }

        $('#short_leaves').val(short_pres.length);  //Short Leaves Working
        $('#half_leaves').val(half_pres.length);    //Half Leaves Working
        $('#presents').val(total_present.length);   //Present Working

        //One Way to Find Leaves
        var total_leaves = full_off.filter(x => !public_holidays.includes(x));

        //Other way to Find Leaves (Using)
        var total_leaves_2 = working_days.filter(x => !full_pres.includes(x));
        var total_leaves_count = total_leaves_2.length;
        console.log('total_leaves_count', total_leaves);
        //Actual Attendance Of Employee
        var actual_attendance = days_of_month.filter(x => !full_pres.includes(x));
        
        //calculating sandwich leaves
        var first = false, sandwitch = 0;
        total_leaves_2.forEach(element => {
            if(days.sat == 8){
                if(new Date(element).getDay() == '5' && !first)
                    first = !first;
                else if(new Date(element).getDay() == '1' && first){
                    first = !first;
                    // total_leaves_count += 2;
                    sandwitch++;
                }
            }
            else{
                if(new Date(element).getDay() == '6' && !first)
                    first = !first;
                else if(new Date(element).getDay() == '1' && first){
                    first = !first;
                    // total_leaves_count ++;
                    sandwitch++;
                }
            }
        });

        week_dates_before = getWeekDates(month, year);
        var ssfilter = public_holidays.filter(x => !calculateSundays(month, year).includes(x)).filter(x => !calculateSaturdays(month, year).includes(x));
        for(var i=0; i<week_dates_before.length; i++){
            week_dates_before[i] = week_dates_before[i].filter(x => !full_off.concat(ssfilter).sort().includes(x));

            if(week_dates_before[i].length == 2 &&  days.sat == 8){
                week_dates_before[i].forEach(element => {
                    var date = new Date(element);
                    if( date.getDay() == '0' || date.getDay() == '6' ){
                        total_leaves_count++;
                    }
                });
            }
            else if(week_dates_before[i].length == 1 &&  days.sat != 8){
                week_dates_before[i].forEach(element => {
                    var date = new Date(element);
                    if( date.getDay() == '0' ){
                        total_leaves_count++;
                    }
                });
            }
        }

        $('#sandwitch_leaves').val(sandwitch);

        $('#leaves').val(total_leaves_count);      //Leaves Working

        var allowed_leaves = leaves.length + (halfs.length/2)
        $('#allowed_leaves').val(allowed_leaves);   //Allowed Leaves Working
        
        var total_off_days = working_days.filter(x => !full_pres.includes(x));

        total_off_days = total_off_days.filter(x => !leaves.includes(x));
        total_off_days = total_off_days.filter(x => !halfs.includes(x));

        return [days_of_month, working_days, total_off_days, actual_attendance];

    }

    var getBankNameById = function(id){
        var token = $("input[name=_token]").val();

        $.ajax({
            url: 'http://erp.local/hrm/payroll/get_bank_name',
            type: "POST",
            data: {
                'id' : id,
                _token : token
            }, 
            success:function(data) {
                console.log(data);
                $('#bank_name').val(data.name);
                $('#bank_id').val(data.id);
            }
        });
    }
    var lockFields =  function () {
        $("#paid_date").prop('readonly', true);
        $("#cash_date").prop('readonly', true);
        $("#paid_amount").prop('readonly', true);
        $("#salary_type").prop('disabled', true);
        $("#payment_method").prop('disabled', true);

    }
    var openFields =  function () {
        $("#paid_date").prop('readonly', false);
        $("#cash_date").prop('readonly', false);
        $("#paid_amount").prop('readonly', false);
        $(".select2").prop('disabled', false);
    }
    var calculatePending = function () {
        var paid_amount = $('#paid_amount').val();
        var total_after_tax = $('#total_after_tax').val();
        var pending_amount = total_after_tax - paid_amount;
        $('#pending_amount').val(pending_amount);
    }
    var setFieldsValue = function(result){
        console.log('result', result);
        var emp_info = result.employee_info;
        $('#bank_name').val(emp_info.bnk_name);
        $('#bank_id').val(emp_info.bank_id);
        $('#account_number').val(emp_info.account_number);
        $('#basic_salary').val(emp_info.basic_salary);
        $('#sc_amount').val(emp_info.sc_amount);
        $('#house_rent').val(emp_info.house_rent);
        $('#utilities').val(emp_info.utility);
        $('#overtime').val(emp_info.overtime);
        $('#total_pf').val(emp_info.pf);
        $('#leaves').val(result.full_days.length);
        $('#short_leaves').val(result.short_days.length);
        $('#half_leaves').val(result.half_days.length);
        $('#sandwitch_leaves').val(result.sandwitch_count);

        $('#presents').val(result.present_days.length);
        $('#allowed_leaves').val(result.allowed_leaves);
        $('#wages').val(0);
        $('#deducted_amount').val(result.deducted_amount);
        $('#total_bonus').val(emp_info.total_bonus);
        $('#total_commission').val(emp_info.total_commission);
        $('#gross_total').val(result.gross_salary);
        $('#tax_percentage').val(0);
        $('#tax_amount').val(result.tax_amount);
        $('#total_after_tax').val(result.total_after_tax);
        $('#paid_amount').val(result.total_after_tax);
        $('#pending_amount').val(0);

        $('#emp_balance').val(result.emp_balance);


    }

    function calculateOvertimes(data, emp_data){
        var overtimes = data.values(), overtime, sum=0;
        var wages = ((emp_data['basic_salary'] * 12) / (365 * 8)).toFixed(2);
        while (!(overtime = overtimes.next()).done) {
            sum += overtime.value['worked_hours'] * wages;
        }

        return sum;
    }

    // $('#total_pf').on('change', function () {
    //     var total_pf = this.value;
    //     var basic = $('#gross_total').val();
    //     var basic_total = Number(basic)-Number(total_pf);
    //     $('#gross_total').val(basic_total);
    //
    // });
    //
    // $('#deducted_amount').on('change', function () {
    //     var deducted_amount = this.value;
    //     var basic = $('#gross_total').val();
    //     var basic_total = Number(basic)-Number(deducted_amount);
    //     $('#gross_total').val(basic_total);
    //
    // });
    // $('#total_bonus').on('change', function () {
    //     var total_bonus = this.value;
    //     var basic = $('#gross_total').val();
    //     var basic_total = Number(basic)+Number(total_bonus);
    //     $('#gross_total').val(basic_total);
    //
    // });
    // $('#tax_percentage').on('change', function () {
    //     var tax_percentage = this.value/100;
    //     var basic = $('#gross_total').val();
    //     var total = Number(basic)*tax_percentage;
    //     var basic_total = Number(basic)- Number(total);
    //     $('#total_after_tax').val(basic_total);
    //
    // });

    return{
        init:function(){
            baseFunction();
        },
        fetchEmployeeDetail : fetchEmployeeDetail,
        calculateSalary : calculateSalary,
        calculateSundays : calculateSundays,
        calculateAllHolidays : calculateAllHolidays,
        calculateEmpLeaves : calculateEmpLeaves,
        getBankNameById : getBankNameById,
        setFieldsValue : setFieldsValue,
        lockFields : lockFields,
        openFields : openFields,
        calculatePending: calculatePending,


    };
}();

jQuery(document).ready(function() {
    FormControls.init();

    $("#paid_date").prop('readonly', true);
    $("#cash_date").prop('readonly', true);
    $("#paid_amount").prop('readonly', true);
    $("#salary_type").prop('disabled', true);
    $("#payment_method").prop('disabled', true);
    $('#paid_amount').keyup(function(key){
        if(!isNaN(String.fromCharCode(key.which)) || key.which == '8'){
            var total = $('#total_after_tax').val();
            var paid_amount = $(this).val();
            $('#pending_amount').val((total - paid_amount).toFixed(2));
        }
        else if(key.which == '45'){
            $('#pending_amount').val(0);
            $('#paid_amount').val($('#total_after_tax').val());
        }
    });
});
