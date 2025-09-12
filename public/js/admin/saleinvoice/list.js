/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    $('#btn_save').on('click', function(event){
        if($('#due_date').val() == '' || $('#rr_no').val() == '' || $('#delivery_type').val() == ''){
            alert('Please fill all the fields');
            event.preventDefault();
        }
    });
    document.getElementById('users-table').style.display = "none";

    var baseFunction = function(){

    }

    var fetchFilterrecord = function () {
        var order_no = $('#order_no').val();
        var token = $("input[name=_token]").val();
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd' //format: 'DD-MM-YYYY H:m:s A',
        });

        if(order_no){
            $.ajax({
                type: "POST",
                url: 'getOrderDetail',
                data: {
                    'order_no' : order_no,
                    '_token': token,
                    },
                success: function(result){
                    $("#users-table tbody > tr").remove();
                    addTable(result.data, result.gross_amount, result.tax_amount, result.tax_percent);
                    $('#invoice_date').val(result.invoice_date);
                    $('#order_id').val(result.order_id);
                    $('#customer_id').val(result.customer_id);
                    $('#amount').val(result.gross_amount.toFixed(2));
                    $('#tax_amount').val(result.tax_amount.toFixed(2));
                    $('#total_amount').val(+result.gross_amount.toFixed(2) + +result.tax_amount.toFixed(2));
                    document.getElementById('due_date').removeAttribute('readonly');
                    document.getElementById('rr_no').removeAttribute('readonly');
                    document.getElementById('btn_save').removeAttribute('disabled');
                    document.getElementById('delivery_type').removeAttribute('disabled');
                }
            });
        }
        else{
            alert('Please Select an Order First');
        }

    }

    var addTable = function(table_data, gross_amount, tax_amount, tax_percent){
    
        
        if(table_data.length > 0){
            var table = document.getElementById("users-table-body");
            document.getElementById('users-table').style.display = "table";

            for (var i = 0; i < table_data.length; i++) {
                var row = table.insertRow(i);
        
                for (var j = 0; j < table_data[i].length; j++) {
                    var cell = row.insertCell(j);
                    if(j == 3 || j == 4)
                        cell.innerHTML = table_data[i][j].toFixed(2);
                    else
                        cell.innerHTML = table_data[i][j];
                }
                
            }

            //For Amount
            var row = table.insertRow(table_data.length);
            for(var i=0; i < 5; i++){
                var cell = row.insertCell(i);
                if(i==3){
                    cell.setAttribute("style", "font-weight: bold;");
                    cell.innerHTML = "Amount";
                }
                else if(i==4){
                    cell.innerHTML = "Rs. "+gross_amount.toFixed(2);
                }
                else
                    cell.innerHTML = " ";
            }

            //For Tax
            var row = table.insertRow(table_data.length+1);
            for(var i=0; i < 5; i++){
                var cell = row.insertCell(i);
                if(i==3){
                    cell.setAttribute("style", "font-weight: bold;");
                    cell.innerHTML = tax_percent+"% GST";
                }
                else if(i==4){
                    cell.innerHTML = "Rs. "+tax_amount.toFixed(2);
                }
                else
                    cell.innerHTML = " ";
            }        

            //For Gross Amount
            var row = table.insertRow(table_data.length+2);
            for(var i=0; i < 5; i++){
                var cell = row.insertCell(i);
                if(i==3){
                    cell.setAttribute("style", "font-weight: bold;");
                    cell.innerHTML = "Gross Amount";
                }
                else if(i==4){
                    cell.innerHTML = "Rs. "+ (+tax_amount.toFixed(2) + +gross_amount.toFixed(2));
                }
                else
                    cell.innerHTML = " ";
            } 
        }
        else{
            document.getElementById('users-table').style.display = "none";
            document.getElementById('due_date').setAttribute('readonly', 'true');
            document.getElementById('rr_no').setAttribute('readonly', 'true');
            document.getElementById('btn_save').setAttribute('disabled', 'true');
            document.getElementById('delivery_type').setAttribute('disabled', 'true');
        }
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        fetchFilterrecord : fetchFilterrecord,
        addTable : addTable,
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});