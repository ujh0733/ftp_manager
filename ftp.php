<?php
    include "./include/head.html";
    include "./ftp/ftp_controller.php";
    
    if($_SESSION["ftp_host"] && $_SESSION["ftp_user"] && $_SESSION["ftp_pass"]){
        $ftp_host = $_SESSION["ftp_host"];
        $ftp_user = $_SESSION["ftp_user"];
        $ftp_pass = $_SESSION["ftp_pass"];

        $ftp_controller = new Ftp_Controller();

        print_r($ftp_controller);
    }else{
        $tools->alert_location("로그인을 진행헤 주세요", "./index.htm");
    }
?>

<body class="container mt-5">
    <div style="border: 1px solid red; width:450px; height:550px;">

    </div>
</body>

<?php include "./include/footer.html"; ?>