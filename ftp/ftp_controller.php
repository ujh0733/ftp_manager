<?php
    class Ftp_Controller{
        private $ftp;
        
        public function __construct(){
            if($ftp){
                $ftp_host = $_SESSION["ftp_host"];
                $ftp_user = $_SESSION["ftp_user"];
                $ftp_pass = $_SESSION["ftp_pass"];

                $connection = ssh2_connect($ftp_host, 22);
                $this->ftp = $connection;
            }
        }

        public function ftp_connect($ftp_host, $ftp_user, $ftp_pass){
            $ori_socket_time = ini_get('default_socket_timeout');
            //응답 시간을 줄이기위한 임시 timeout
            ini_set('default_socket_timeout', 5);

            $connection = ssh2_connect($ftp_host, 22);

            $result_msg = array("code" => "", "msg" => "", "url" => "");
            if($connection){
                if(ssh2_auth_password($connection, $ftp_user, $ftp_pass)){
                    $result_msg["code"] = "ok";
                    $result_msg["msg"] = "로그인 성공";
                    $result_msg["url"] = "ftp.php";

                    $_SESSION["ftp_host"] = $ftp_host;
                    $_SESSION["ftp_user"] = $ftp_user;
                    $_SESSION["ftp_pass"] = $ftp_pass;
                }else{
                    $result_msg["code"] = "fail";
                    $result_msg["msg"] =  "${ftp_user} 계정 정보를 확인해 주세요.";
                }
            }else{
                $result_msg["code"] = "fail";
                $result_msg["msg"] =  "${ftp_host} 서버에 접근할 수 없습니다.\n ip 또는 port를 확인해 주세요.";
            }  

            ini_set('default_socket_timeout', $ori_socket_time);
            echo json_encode($result_msg);
		}
    }
?>