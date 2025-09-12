/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions
    var token = $("input[name=_token]").val();
    var baseFunction = function () {

        $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
        //loaded from
        $('#employee_id').select2();
        $('#available-table').hide();
        $('#av_para').hide();
    //    if( jobTitlee != 0 ){
    //        jobTitle(jobTitlee);
    //    }

        $('#date_of_birth').datetimepicker({
        format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
        sideBySide: true
        });
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                device_id: {
                    required: true,
                    number: true
                },
                first_name: {
                    required: true

                },
                father_name: {
                    required: true

                },
                date_of_birth: {
                    required: true

                },
                mobile: {
                    required: true

                },
                emergency_mobile: {
                    required: true

                },
                city: {
                    required: true

                },
                address: {
                    required: true

                },

                //cnic: {
                 //     required: true,
                 //     number: true

                 // },

                gender: {
                    required: true
                },
                marital_status: {
                    required: true
                },
                department_id: {
                    required: true
                }

            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    
   

    var jobTitle = function (val) {

        var dept = $('#department_id').val();

        if(dept == 4){
            fetchsalesman();
            // marketing Executive or territory manager
            if( val == 25 || val == 24){
                $('#region_id_h').show();
                $('#territory_id_h').show();
                $('#branch_id_h').show();
            }
            // branch manager
            else if ( val == 23)
            {
                $('#region_id_h').show();
                $('#branch_id_h').show();
                $('#territory_id_h').hide();
            }
            // regional manager
            else if ( val == 22)
            {
                $('#region_id_h').show();
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();
            }
            // none
            else
            {
                $('#region_id_h').hide();
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();

            }

        }
        else if(dept == 3){
            fetchEngrs();

            // Service manager manager
            if ( val == 20 )
            {
                $('#region_id_h').show();
                $('#territory_id_h').show();
                $('#branch_id_h').show();
            }
            else if(val == 19){
                $('#region_id_h').show();
                $('#territory_id_h').hide();
                $('#branch_id_h').show();
            }// CSR for  each branch
            else if(val == 18){
                $('#region_id_h').show();
                $('#territory_id_h').hide();
                $('#branch_id_h').show();
            }
            // none
            else
            {
                $('#region_id_h').hide();
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();
            }

        }
        else{
            $('#region_id_h').hide();
            $('#branch_id_h').hide();
            $('#territory_id_h').hide();
        }
        if(dept == 1 || dept == 2  ){
            fetchGenralEmp()
        }
    }

    var fetchEngrs = function () {

        var prev_report = $('#prev_report').val();
        var jobTitle  = $('#job_title').val();
        var region_id = $('#region_id').val();
        var branch_id = $('#branch_id').val();
        var territory_id = $('#territory_id').val();

        $('#report_to').html('');
        // territory manager
        var dept = $('#department_id').val();
        if(dept == 3) {
            if (jobTitle == 19) {
                var html = '';
                if (region_id && branch_id) {
                    html += '<option value="">Select branch manager </option>';
                    for (var i = 0; i < allSalesman.length; i++) {
                        if (allSalesman[i].region_id == region_id &&
                            allSalesman[i].branch_id == branch_id &&
                            allSalesman[i].job_title == 23
                        ) {
                            var selected = '';

                            if (prev_report == allSalesman[i].user_id) {
                                selected = 'selected';
                            }
                            console.log('selected: ', selected);
                            html += '<option value=' + allSalesman[i].user_id + ' ' + selected + '>' + allSalesman[i].first_name + '</option>';
                        }
                    }

                }

            }
            // Service Engineer
            if (jobTitle == 20) {
                var html = '';
                if (region_id && branch_id && territory_id) {
                    html += '<option value="">Select Service Manager </option>';
                    for (var i = 0; i < allSalesman.length; i++) {
                        if (allSalesman[i].region_id == region_id &&
                            allSalesman[i].branch_id == branch_id &&

                            allSalesman[i].job_title == 19
                        ) {
                            var selected = '';
                            if (prev_report == allSalesman[i].user_id) {
                                selected = 'selected';
                            }
                            console.log('selected: ', selected);
                            html += '<option value=' + allSalesman[i].user_id + ' ' + selected + '>' + allSalesman[i].first_name + '</option>';
                        }
                    }

                }
            }
            if (jobTitle == 18) {
                if(region_id && branch_id ){
                    html = '';
                    for (var i=0; i < allSalesman.length ; i++ ){
                        if(allSalesman[i].region_id == region_id &&
                            allSalesman[i].branch_id == branch_id &&
                            allSalesman[i].job_title == 23
                        ){
                            var selected = '';

                            if(prev_report == allSalesman[i].user_id){
                                selected = 'selected';
                            }
                            console.log('selected: ', selected);
                            html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                        }
                    }

                }
            }
            if(  jobTitle == 16  ){
                var html = '';
                for (var i=0; i < allSalesman.length ; i++){
                    if(allSalesman[i].job_title == 2)
                    {
                        var selected = '';

                        if(prev_report == allSalesman[i].user_id){
                            selected = 'selected';
                        }
                        html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }
            $('#report_to').append(html);
        }
    }
    var fetchsalesman = function () {
        var dept = $('#department_id').val();
        if(dept == 3){
            fetchEngrs();
        }
        else if(dept == 1 || dept == 2 ){
            fetchGenralEmp();
        }
        else if(dept == 4){

            var prev_report = $('#prev_report').val();
            var jobTitle  = $('#job_title').val();
            var region_id = $('#region_id').val();
            var branch_id = $('#branch_id').val();
            var territory_id = $('#territory_id').val();

            $('#report_to').html('');
            //Marketing Executive
            if( jobTitle == 25){

                var html = '';
                if(region_id && branch_id && territory_id){
                    html += '<option value="">Select territory manager </option>';
                    for (var i=0; i < allSalesman.length ; i++){
                        if( allSalesman[i].region_id == region_id &&
                            allSalesman[i].branch_id == branch_id &&
                            allSalesman[i].territory_id == territory_id &&
                            allSalesman[i].job_title == 24){
                            var selected = '';
                            if(prev_report == allSalesman[i].user_id){
                                selected = 'selected';
                            }
                            html += '<option value='+ allSalesman[i].user_id +' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                        }
                    }

                }

            }
            // territory manager
            if( jobTitle == 24){

                var html = '';

                if(region_id && branch_id ){
                    html += '<option value="">Select branch manager </option>';
                    for (var i=0; i < allSalesman.length ; i++ ){
                        if(allSalesman[i].region_id == region_id &&
                            allSalesman[i].branch_id == branch_id &&
                            allSalesman[i].job_title == 23
                            ){
                            var selected = '';

                            if(prev_report == allSalesman[i].user_id){
                                selected = 'selected';
                            }
                            console.log('selected: ', selected);
                            html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                        }
                    }

                }

            }
            // Branch manager
            if( jobTitle == 23){
                var html = '';
                 if(region_id  ){
                    html += '<option value="">Select regional manager </option>';
                    for (var i=0; i < allSalesman.length ; i++){
                        if(allSalesman[i].region_id == region_id && allSalesman[i].job_title == 22
                        ){
                            var selected = '';

                            if(prev_report == allSalesman[i].user_id){
                                selected = 'selected';
                            }
                            html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                        }
                    }

                }
            }
            // Region manager
            if( jobTitle == 22){
                var html = '';

                for (var i=0; i < allSalesman.length ; i++){
                    if(allSalesman[i].job_title == 21)
                    {
                        var selected = '';

                        if(prev_report == allSalesman[i].user_id){
                            selected = 'selected';
                        }
                        html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }
            // country manager
            if( jobTitle == 21){
                var html = '';

                    for (var i=0; i < allSalesman.length ; i++){
                        if(allSalesman[i].job_title == 2)
                        {
                            var selected = '';

                            if(prev_report == allSalesman[i].user_id){
                                selected = 'selected';
                            }
                            html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                        }
                    }

            }
        
         $('#report_to').append(html);
        }

    }

    var fetchGenralEmp = function () {
        console.log('fetchGenralEmp');

        var prev_report = $('#prev_report').val();
        var jobTitle  = $('#job_title').val();
        var region_id = $('#region_id').val();
        var branch_id = $('#branch_id').val();
        var territory_id = $('#territory_id').val();

        $('#report_to').html('');
        // territory manager
        var dept = $('#department_id').val();
        if(dept == 1 || dept == 2  ) {
            // secretry Ceo
            if( jobTitle == 2 ){

                var html = '';
                for (var i=0; i < allSalesman.length ; i++){
                    if(allSalesman[i].job_title == 1)
                    {
                        var selected = '';

                        if(prev_report == allSalesman[i].user_id){
                            selected = 'selected';
                        }
                        html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }
// Country Marketing manager, country service manager, country finnace manager Reports to sectry ceo
            if( jobTitle == 8 || jobTitle == 16 ||  jobTitle == 21 ){

                var html = '';
                for (var i=0; i < allSalesman.length ; i++){
                    if(allSalesman[i].job_title == 2)
                    {
                        var selected = '';

                        if(prev_report == allSalesman[i].user_id){
                            selected = 'selected';
                        }
                        html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }
            //store incharge Reports to Deputy manager
            if( jobTitle == 9 || jobTitle == 10 ){

                var html = '';
                for (var i=0; i < allSalesman.length ; i++){
                    if(allSalesman[i].job_title == 8)
                    {
                        var selected = '';

                        if(prev_report == allSalesman[i].user_id){
                            selected = 'selected';
                        }
                        html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }
            //store incharge Reports to Deputy manager
            if( jobTitle == 13 ){

                var html = '';
                for (var i=0; i < allSalesman.length ; i++){
                    if(allSalesman[i].job_title == 9)
                    {
                        var selected = '';

                        if(prev_report == allSalesman[i].user_id){
                            selected = 'selected';
                        }
                        html += '<option value='+ allSalesman[i].user_id + ' '+ selected +'>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }

            $('#report_to').append(html);
        }
    }

    var fetchBranches = function (val) {

        $('#branch_id').html('');
        $("#territory_id").html('');

        var html = '';
        html += '<option value="">Select Branch </option>';
        if(val){

            for(var i=0; i < allBranches.length; i++){
                if(allBranches[i].region_id == val){
                    html += '<option value='+ allBranches[i].id +'>'+ allBranches[i].name +'</option>';
                }

            }

        }
        $('#branch_id').append(html);
        fetchEngrs();
        fetchsalesman();

    }
    var fetchTerritory = function (val) {

        $('#territory_id').html('');
        var html = '';
        html += '<option value="">Select Territory </option>';
        if(val){

            for(var i=0; i < allTerritory.length; i++){

                if(allTerritory[i].branch_id == val){
                    html += '<option value='+ allTerritory[i].id +'>'+ allTerritory[i].name +'</option>';
                }

            }

        }
        $('#territory_id').append(html);
        fetchEngrs();
        fetchsalesman();

    }
    var departmentChange = function (val) {

        $("#job_title").val(['']);
        $("#region_id").val(['']);
        $("#branch_id").html('');
        $("#territory_id").html('');
    }
    var checkAvailable =  function () {
        var user_id = $('#employee_id').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        console.log('Hello world', user_id);
        if(user_id, from_date){
            $.ajax({
                type: "POST",
                url: 'employees/availableStatus',
                data: {
                    'user_id' : user_id,
                    'from_date' : from_date,
                    'to_date' : to_date,
                    '_token':token,

                },
                success: function(result){
                    var tableHtml = '';
                    console.log('result : ', result);

                    var res = JSON.parse(result);

                    if(res.length > 0){
                        $.each(res, function( key, val ) {
                            tableHtml += '<tr>';
                            tableHtml += '<td>'+ (key+1) +'</td>';
                            tableHtml += '<td>'+ val.emp_name +'</td>';
                            tableHtml += '<td>'+ val.leave_type +'</td>';
                            tableHtml += '<td>'+ val.leave_status +'</td>';
                            tableHtml += '<td>'+ val.leave_date +'</td>';
                            tableHtml += '<td>'+ val.created_at +'</td>';
                            tableHtml += '</tr>';
                        });
                        $('#available-table').show();
                        $('#av_para').hide();
                        $('#avail-body').html(tableHtml);
                    }else{
                        $('#available-table').hide();
                        $('#av_para').show();
                    }

                }

            });
        }else {
            alert('Please select an Employee and start date');
        }
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        fetchBranches: fetchBranches,
        fetchTerritory: fetchTerritory,
        fetchsalesman: fetchsalesman,
        fetchEngrs : fetchEngrs,
        fetchGenralEmp: fetchGenralEmp,
        jobTitle : jobTitle,
        departmentChange: departmentChange,
        checkAvailable: checkAvailable,

    };
}();



jQuery(document).ready(function() {
    FormControls.init();
});