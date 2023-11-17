<?php
    include "../common.php";
    include "./ftp_controller.php";
    
    $ftp_host = $tools->requestValue("ftp_host");
    $ftp_user = $tools->requestValue("ftp_user");
    $ftp_pass = $tools->requestValue("ftp_pass");

    $ftp_controller = new Ftp_Controller();
    $result = $ftp_controller->ftp_connect($ftp_host, $ftp_user, $ftp_pass);

    return $result;
?>