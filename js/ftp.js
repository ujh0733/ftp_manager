function ftp_logout(){
    $.ajax({
        url: "./ftp/ftp_connect_out.php",
            success: function(data){
                var data = JSON.parse(data);
                alert(data["msg"]);

                if(data["code"] == "ok"){
                    location.href = data["url"];
                }
            }
    });
}

function node_open(){
    $tree.jstree("open_all");
    $("#open_btn").hide();
    $("#close_btn").show();
}

function node_close(){
    $tree.jstree("close_all");
    $("#open_btn").show();
    $("#close_btn").hide();
}

