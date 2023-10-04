<?php
    include "./common.php";

    $ftp_host = "192.168.29.128";
    $ftp_user = "ftp_manag2er";
    $ftp_pass = "ftp_manager";
        
    $connection = ssh2_connect($ftp_host, 22);
    if($connection){
        if(ssh2_auth_password($connection, $ftp_user, $ftp_pass)){
            echo "로그인 성공";
            echo "다음에 신청";
        }else{
            echo "${ftp_user} 계정 정보를 확인해 주세요.";
        }
    }else{
        echo "${ftp_host} 서버에 접근할 수 없습니다.\n ip 또는 port를 확인해 주세요.";
    }    
?>