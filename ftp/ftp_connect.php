<?php
    include "../common.php";
    include "./ftp_controller.php";

    print_R($_POST);

    $ftp_host = requestValue("ftp_host");
    $ftp_user = requestValue("ftp_user");
    $ftp_pass = requestValue("ftp_pass");

    new Ftp_Controller($ftp_host, $ftp_user, $ftp_pass);
?>