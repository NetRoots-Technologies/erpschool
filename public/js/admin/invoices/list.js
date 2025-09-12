
var FormControls = function () {
    var token = $("input[name=_token]").val();
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                 url :'salepipelines/datatables',
                    method: 'POST',
                    data:  {
                        name : 'abcd',
                        _token: token,
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'emp_name', name: 'emp_name' },
                    { data: 'databank_id', name: 'databank_id' },
                    { data: 'name', name: 'name' },
                    { data: 'products', name: 'products' },

                    { data: 'stage', name: 'stage' },

                    { data: 'next_visit', name: 'next_visit' },
                    { data: 'action', name: 'action' , orderable: false, searchable: false},


                ]
        });


    var baseFunction = function () {

        // for a ceo get sub regions
        if(job_title == 1 || job_title == 2 || job_title == 21){
            //console.log('job_title : : ', job_title);
            fetchsalesman(0,0,0);
            $('#branch').html('');
            var html = '';
            html += '<option value="">Select branch </option>';
            $('#branch').append(html);
        }
         // for region manager get sub branch
        else if(job_title == 22){
             $('#region_div').hide();
             fetchsalesman(region_id,0,0);
             $('#branch').html('');
             var html = '';
             html += '<option value="">Select branch </option>';
             for(var i=0; i < allBranches.length; i++){
                 var selected = '';
                 html += '<option value='+ allBranches[i].id + ' '+ selected + '>'+    allBranches[i].name +'</option>';
             }
             $('#branch').append(html);
         }// for branch manager get sub territor
         else if(job_title == 23){
             $('#region_div').hide();
             $('#branch_div').hide();
             $('#territory').html('');
             var emp_branch = $('#emp_branch').val();
            // console.log('emp_branch :',  emp_branch);
             fetchsalesman(0,emp_branch,0);
             var html = '';
             html += '<option value="">Select Territory </option>';

             for(var i=0; i < allTerritory.length; i++){
                 var selected = '';
                 if(allTerritory[i].branch_id == emp_branch){
                     html += '<option value='+ allTerritory[i].id + ' '+ selected + '>'+    allTerritory[i].name +'</option>';
                 }
             }
             $('#territory').append(html);
         }
         else if(job_title == 24){
             $('#region_div').hide();
             $('#branch_div').hide();
             $('#terr_div').hide();
             var emp_terr = $('#emp_terr').val();

             fetchsalesman(0,0,emp_terr);
         }// marketing executive
         else{
             $('#region_div').hide();
             $('#branch_div').hide();
             $('#terr_div').hide();
             $('#user_div').hide();
             $('#search_button').hide();


         }

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
            if(region_id){
                fetchsalesman(region_id,0,0);
            }else{
                var region = $('#region').val();
                fetchsalesman(region,0,0);
            }
        }
        $('#territory').append(html);
        fetchTeamMember (0);
    }

    var fetchsalesman = function (region_id , branch_id, territory_id ) {
        //console.log('fetchsalesman : ',region_id, branch_id, territory_id);
        $('#user_id').html('');

            if( region_id == 0 && branch_id != 0 &&  territory_id == 0){

                var html = '';
                html += '<option value="">Select Territory Manager </option>';
                for (var i=0; i < allSalesman.length ; i++){
                    if( allSalesman[i].branch_id == branch_id  &&  allSalesman[i].job_title == 24 ){
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

        if( region_id == 0 && branch_id == 0 &&  territory_id == 0){
            var html = '';
            html += '<option value="">Select Region Manager </option>';
            for (var i=0; i < allSalesman.length ; i++ ){
                if(  allSalesman[i].job_title == 22){
                    html += '<option value='+ allSalesman[i].user_id + '>'+ allSalesman[i].first_name +'</option>';
                }
                // if ceo or secretray ceo
                if(job_title == 1 || job_title == 2){

                    if(  allSalesman[i].job_title == 21){

                        html += '<option value='+ allSalesman[i].user_id + '>'+ allSalesman[i].first_name +'</option>';
                    }
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
        fetchTeamMember (0);
    }

    var fetchBranches = function(val) {
        fetchsalesman(val, 0, 0);

        $('#territory').html('<option value="">Select Territory</option>');
        $('#branch').html('');
        var html = '';
        html += '<option value="">Select branch </option>';

        for (var i = 0; i < allBranches.length; i++) {
            var selected = '';
            if (allBranches[i].region_id == val) {
                html += '<option value=' + allBranches[i].id + ' ' + selected + '>' + allBranches[i].name + '</option>';
            }
        }

        $('#branch').append(html);
        fetchTeamMember (0);
    }
    var loadProducts = function (pipeline_id){
        console.log('pipeline_id : ',pipeline_id);

            $.ajax({
                url: 'salepipelines/pipelineProduts/'+ pipeline_id,
                success: function (data) {
                    processProdeucts(data);
                }

            });


    }
    function processProdeucts (data){
        console.log('data :', data);
        var html = '<tr><td colspan="4">No Products in the Pipeline</td></tr>';

        if(data.length > 0){
            html = '';
            var serial = 0;
            for (var  i=0; i<data.length; i++){
                serial++
                html += '<tr>';
                html += '<td>'+ serial +'</td>';
                html += '<td>'+ data[i].products_short_name +'</td>';

                html += '<td>'+ data[i].product_qty +'</td>';
                html += '<td>'+ data[i].total_amount +'</td>';
                html += '</tr>';
            }
        }
        $("#products-table tbody").html(html);
        $('#myModal').modal('show');
    }

    var fetchTeamMember = function (user_id) {
        $('#team_member').html('');

        var html = '';
        if(user_id !=0){
            html += '<option value="">Select Team Member </option>';
            for (var i=0; i < allSalesman.length ; i++){
                if( allSalesman[i].report_to == user_id ){
                    html += '<option value='+ allSalesman[i].user_id +'>'+ allSalesman[i].first_name +'</option>';
                }
            }
        }

        $('#team_member').append(html);
    }

    var fetchFilterRecord = function () {
        var region_id = $('#region').val();
        var branch_id = $('#branch').val();
        var territory_id = $('#territory').val();
        var user_id = $('#user_id').val();
        var team_member = $('#team_member').val();
        if(team_member){
            user_id = team_member;
        }
        $('#users-table').DataTable().destroy();
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url :'salepipelines/datatables',
                method: 'POST',
                data:  {
                    name : 'temp',
                    region_id : region_id,
                    branch_id : branch_id,
                    territory_id : territory_id,
                    user_id : user_id,
                    _token: token
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'emp_name', name: 'emp_name' },
                { data: 'databank_id', name: 'databank_id' },
                { data: 'name', name: 'name' },
                { data: 'products', name: 'products' },
                { data: 'stage', name: 'stage' },

                { data: 'next_visit', name: 'next_visit' },
                { data: 'action', name: 'action' , orderable: false, searchable: false},


            ]
        });

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        fetchTerritory : fetchTerritory,
        territoryChange : territoryChange,
        fetchFilterRecord : fetchFilterRecord,
        fetchBranches: fetchBranches,
        fetchsalesman : fetchsalesman,
        loadProducts : loadProducts,
        fetchTeamMember: fetchTeamMember
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});