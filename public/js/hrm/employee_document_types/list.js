
var FormControls = function () {
    var token = $("input[name=_token]").val();
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url :'employee_document_types/datatables',
            method: 'POST',
            data:  {
                user_id : 'abcd',
                _token: token,
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action' , orderable: false, searchable: false},
        ]
    });
}();

jQuery(document).ready(function() {
    FormControls.init();
});