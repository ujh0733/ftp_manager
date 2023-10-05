<?php
    include "./include/head.html";
    include "./ftp/ftp_controller.php";
    
    if($_SESSION["ftp_host"] && $_SESSION["ftp_user"] && $_SESSION["ftp_pass"]){
        $ftp_host = $_SESSION["ftp_host"];
        $ftp_user = $_SESSION["ftp_user"];
        $ftp_pass = $_SESSION["ftp_pass"];

        $ftp_controller = new Ftp_Controller();
    }else{
        $tools->alert_location("로그인을 진행헤 주세요", "./index.htm");
    }
?>

<body class="container mt-5">
    <div style="border: 1px solid red; width:650px; height:650px;">
       
        <?php
            $file_list = $ftp_controller->get_file_list();
            print_r($file_list);
        ?>
        
    </div>
</body>

<?php include "./include/footer.html"; ?>