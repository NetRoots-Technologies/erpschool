
var FormControls = function(){
    var token = $("Input[name=_token]").val();    
    $("#users-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'workdays/datatables',
            method: 'POST',
            data:{
                title : 'all',
                date : 'all',
                workday_length : 'all',
                _token : token,
            }
        },
        columns: [
            {data: 'id' , name: 'id'},
            {data: 'title' , name: 'title'},
            {data: 'date' , name: 'date'},
            {data: 'workday_length' , name: 'workday_length'},
            {data: 'applicable_to' , name: 'applicable_to'},
            {data: 'created_at' , name: 'created_at'},
            {data: 'action' , name: 'action', orderable: false, searchable: false},
        ]
    });
}();

jQuery(document).ready(function(){
    FormControls.init();
});