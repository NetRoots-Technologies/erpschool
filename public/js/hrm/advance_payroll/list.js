
var FormControls = function(){
    var token = $("Input[name=_token]").val();    
    $("#users-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'advance_payroll/datatables',
            method: 'POST',
            data:{
                user_id : 'all',
                month : 'all',
                year : 'all',
                salary_type : 'all',
                comments : 'all',
                _token : token,
            }
        },
        columns: [
            {data: 'id' , name: 'id'},
            {data: 'user_name' , name: 'user_name'},
            {data: 'month' , name: 'month'},
            {data: 'year' , name: 'year'},
            {data: 'salary_type' , name: 'salary_type'},
            {data: 'comments' , name: 'comments'},
            {data: 'created_at' , name: 'created_at'},
            {data: 'action' , name: 'action', orderable: false, searchable: false},
        ]
    });

    var baseFunction = function(){

    }

    return {
        init: function(){
          baseFunction();  
        },
    }
}();

jQuery(document).ready(function(){
    FormControls.init();
});