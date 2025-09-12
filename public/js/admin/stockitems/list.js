
var FormControls = function () {
    var token = $("input[name=_token]").val();
    $('.select2').select2();
    var baseFunction = function () {

            $('#stock-table').DataTable({
                order: [[ 1, "desc" ]],
                processing: true,
                serverSide: true,
                ajax: {
                    url :'stockitems/datatables',
                    method: 'POST',
                    data:  {
                        name : 'temp',
                        _token: token
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'st_name', name: 'st_name' },
                    { data: 'short_name', name: 'short_name' },
                    { data: 'branch_id', name: 'branch_id' },
                    { data: 'categrory', name: 'categrory' },
                    { data: 'uom', name: 'uom' },
                    { data: 'st_unit_price', name: 'st_unit_price' },
                    { data: 'closing_stock', name: 'closing_stock' },
                    { data: 'stock_value', name: 'stock_value' },
                    { data: 'created_at', name: 'created_at' },
                    // { data: 'action', name: 'action' , orderable: false, searchable: false},

                ]
        });

    }
    var fetchFilterRecord = function () {
        var cat_id = $('#cat_id').val();
        var sup_id = $('#sup_id').val();
        $('#stock-table').DataTable().destroy();
        $('#stock-table').DataTable({

            processing: true,
            serverSide: true,
            ajax: {
                url :'stockitems/datatables',
                method: 'POST',
                data:  {
                    name : 'temp',
                    cat_id : cat_id,
                    sup_id : sup_id,
                    _token: token
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'st_name', name: 'st_name' },
                { data: 'branch_id', name: 'branch_id' },
                { data: 'categrory', name: 'categrory' },
                { data: 'uom', name: 'uom' },
                { data: 'st_unit_price', name: 'st_unit_price' },
                { data: 'closing_stock', name: 'closing_stock' },
                { data: 'stock_value', name: 'stock_value' },

            ]
        });

    }
    
    

    var FilterRecord = function () {

        var cat_id = $('#cat_id').val();
        var sup_id = $('#sup_id').val();
        console.log('sup',sup_id);
        console.log('cat',cat_id);
         $.ajax({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: route('admin.stockitems.filterreport'),
             type: "POST",
            
             data: {
                 'sup_id': sup_id,
                 'cat_id': cat_id,
             },
             success: function(response){
                 
                console.log(data);
                 
             },
             error: function (xhr, ajaxOptions, thrownError) {
 
                 return false;
             }
         });
     };

    return {
        // public functions
        init: function() {
            baseFunction();
        },

        fetchFilterRecord : fetchFilterRecord,
        FilterRecord : FilterRecord

    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});