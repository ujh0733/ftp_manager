<?php
    include "../common.php";
    include "./ftp_controller.php";

    $ftp_controller = new Ftp_Controller();

    $jobs = $tools->requestValue("jobs");
    $node = json_encode($tools->requestValue("node"));

    $result_msg = array("code" => "fail", "msg" => "관리자에게 문의해주세요.");
    
    //echo $jobs;
    //echo "<BR>";
    echo $node;

    if($jobs == "rename"){
        $result_msg["code"] = "ok";
        $result_msg["msg"] = "파일명 수정이 완료되었습니다.";
        /*
        if($ftp_controller->ssh2_rename($params)){
            $result_msg["code"] = "ok";
            $result_msg["msg"] = "파일명 수정이 완료되었습니다.";
        }*/
    }else if($jobs == "delete"){

    }else if($jobs == "upload"){

    }else if($jobs == "download"){

    }else if($jobs == "mkdir"){

    }

    //echo json_encode($result_msg);
?>