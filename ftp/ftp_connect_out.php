<?php
    include "../common.php";
    include "./ftp_controller.php";

    $ftp_controller = new Ftp_Controller();
    $result = $ftp_controller->ftp_logout();

    return $result;
?>