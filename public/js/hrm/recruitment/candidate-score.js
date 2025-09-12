$(function() {
    $(".candidate-score").on('click', function () {
        // let districts = $('.district-select');
        // $(districts).prop('disabled',true);
        // let id = parseInt( $(this).val() );
        var id = {{$Employee->id}}
        console.log(id)
        
        // if(id>0){
        //     let url =  "/"+$("html").attr("lang")+"/city/"+id+"/get-areas-new"
        //     axios.get(url).then(function (response) {
        //         response.data
        //         $(districts).html(response.data)
        //         $(districts).prop('disabled',false);


        //         $("#area_parent").html(response.data);
                             

        //     })

        // }
    })
});