
var FormControls = function () {
    var token = $("input[name=_token]").val();

    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    $('.select2').select2();

    var baseFunction = function () {

    }

    var fetchTerritory = function (val) {
        fetchsalesman(0,val,0);
        $('#territory').html('');
        var html = '';
        html += '<option value="">Select Territory </option>';
        if(val){
            for(var i=0; i < allTerritory.length; i++){

                if(allTerritory[i].branch_id == val){
                    html += '<option value='+ allTerritory[i].id + '>'+    allTerritory[i].name +'</option>';
                }

            }

        }
        else{
            fetchsalesman(region_id,0,0);
        }
        $('#territory').append(html);
    }

    var fetchsalesman = function (region_id , branch_id, territory_id ) {
        //console.log('fetchsalesman : ',region_id, branch_id, territory_id);
        $('#user_id').html('');

            if( region_id == 0 && branch_id != 0 &&  territory_id == 0){

                var html = '';
                html += '<option value="">Select Territory Manager </option>';
                for (var i=0; i < allSalesman.length ; i++){
                    if( allSalesman[i].branch_id == branch_id &&  allSalesman[i].job_title == 24){
                        html += '<option value='+ allSalesman[i].user_id +'>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }

            if( region_id == 0 && branch_id == 0 &&  territory_id != 0){

                var html = '';
                html += '<option value="">Select Marketing Executive </option>';
                for (var i=0; i < allSalesman.length ; i++ ){
                    if( allSalesman[i].territory_id == territory_id && allSalesman[i].job_title == 25){
                        html += '<option value='+ allSalesman[i].user_id + '>'+ allSalesman[i].first_name +'</option>';
                    }
                }

            }
            if( region_id != 0 && branch_id == 0 &&  territory_id == 0){
                var html = '';
                html += '<option value="">Select Branch Manager </option>';
                for (var i=0; i < allSalesman.length ; i++ ){
                    if( allSalesman[i].region_id == region_id && allSalesman[i].job_title == 23){
                        html += '<option value='+ allSalesman[i].user_id + '>'+ allSalesman[i].first_name +'</option>';
                    }
                }
            }

            $('#user_id').append(html);
    }

    var territoryChange = function (val) {
        if(val){
            fetchsalesman(0,0,val);
        }else {
            var branch = $('#branch').val();
            if(branch != 0 ){
                fetchsalesman(0,branch,0);

            }else{
                var emp_branch = $('#emp_branch').val();
                fetchsalesman(0,emp_branch,0);
            }
        }

    }

    var fetchFilterRecord = function () {
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var user_id = $('#user_id').val();
        $('#users-table').DataTable().destroy();
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url :'attendance/datatables',
                method: 'POST',
                data:  {
                    name : 'temp',
                    from_date : from_date,
                    to_date : to_date,
                    user_id : user_id,
                    _token: token
                }
            },
            columns: [
                        { data: 'id', name: 'id' },
                        { data: 'user_name', name: 'user_name' },
                        { data: 'date_time', name: 'checkin_time' },
                        { data: 'attendance_status', name: 'attendance_status' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action' , orderable: false, searchable: false},

                    ]          
        });
        

        //For Faulted Attendance Table

        // $('#users-table-f').DataTable().destroy();
        // $('#users-table-f').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url :'attendance/datatables1',
        //         method: 'POST',
        //         data:  {
        //             name : 'temp',
        //             from_date : from_date,
        //             to_date : to_date,
        //             user_id : user_id,
        //             _token: token
        //         },
        //     },
        //     columns: [
        //                 { data: 'id', name: 'id' },
        //                 { data: 'user_name', name: 'user_name' },
        //                 { data: 'job_title', name: 'job_title' },
        //                 { data: 'date', name: 'date' },
        //                 { data: 'time', name: 'time' },
        //                 { data: 'type', name: 'type' },
        //                 { data: 'created_at', name: 'created_at' },
        //             ]
        // });

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        fetchTerritory : fetchTerritory,
        territoryChange : territoryChange,
        fetchFilterRecord : fetchFilterRecord,
        fetchsalesman : fetchsalesman
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});