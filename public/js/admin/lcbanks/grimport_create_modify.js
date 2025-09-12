var FormControls = function(){
    $('.datatable').DataTable();
    $('.select2').select2();

    // document.getElementById('users-table').style.visibility = "hidden";
    $('#lc_id').on('change', function(){
        fetchLcProducts();
    });

    fetchLcProducts = function(){
        var lc_id = $('#lc_id').val();
        if(lc_id){
            $('#users-table').DataTable().destroy();
            $('#users-table').DataTable({
                 processing: true,
                 serverSide: true,
                 ajax: {
                    method: "POST",
                    url: "grimport/datatables",
                    data:{
                        lc_id: lc_id,
                        _token: $("Input[name=_token]").val()
                    }
                 },
                 columns: [
                    {data: 'id' , name: 'id'},
                    {data: 'product_name' , name: 'product_name'},
                    {data: 'total_qty' , name: 'total_qty'},
                    {data: 'release_qty' , name: 'release_qty'},
                    {data: 'balance_qty' , name: 'balance_qty'},
                    {data: 'total_amount' , name: 'total_amount'},
                 ]
            });
        }
        else{
            alert('Please Select a LC');
            $('#users-table').DataTable().destroy();
            $('#users-table').DataTable();
        }
    }

    baseFunction = function(){

    }

    return{
        init: function(){
            baseFunction();
        },
        fetchLcProducts: fetchLcProducts

    }
}();

$(document).ready(function(){
    FormControls.init();
    
});