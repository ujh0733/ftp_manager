function get_loader(){
    $.ajax({
        url: "./include/loader.html",
        success: function(html){
            $("body").append(html);
        }
    })
}

function del_loader(){
    $("#loader").remove();
}