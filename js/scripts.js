
function getWoeids(){

    //populate woeid dropdown
    $.ajax({
        type: "GET",
        url: "functions.php?function=getwoeids",
        dataType: "json",

        success: function(data){
            console.log(data);
            $.each(data, function(){
                console.log(data);
            });
        }
    });
}


$(document).ready(function() {

    $('#go-button').click(function(){
        getWoeids();
    });
});