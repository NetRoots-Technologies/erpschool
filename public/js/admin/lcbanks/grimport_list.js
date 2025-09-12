var FormControls = function(){
    $('.datatable').DataTable();

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