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