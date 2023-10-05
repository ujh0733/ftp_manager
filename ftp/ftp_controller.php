<?php
    class Ftp_Controller{
        private $ftp;
        private $ftp_host, $ftp_user, $ftp_pass;

        public function __construct(){
            $ftp_host = $_SESSION["ftp_host"];
            $ftp_user = $_SESSION["ftp_user"];
            $ftp_pass = $_SESSION["ftp_pass"];

            if($ftp_host && $ftp_user && $ftp_pass){
                $this->setFtpInfo("host", $ftp_host);
                $this->setFtpInfo("user", $ftp_user);
                $this->setFtpInfo("pass", $ftp_pass);

                $connection = ssh2_connect($ftp_host, 22);
                $ftp_auth = ssh2_auth_password($connection, $ftp_user, $ftp_pass);

                $this->ftp = $connection;
            }
        }
        
        public function getFtpInfo($type){
            if($type == "host"){
                return $this->ftp_host;
            }else if($type == "user"){
                return $this->ftp_user;
            }else if($type == "pass"){
                return $this->ftp_pass;
            }
        }

        public function setFtpInfo($type, $value){
            if($type == "host"){
                $this->ftp_host = $value;
            }else if($type == "user"){
                $this->ftp_user = $value;
            }else if($type == "pass"){
                $this->ftp_pass = $value;
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

        function get_file_list(){
            $ftp_user = $this->ftp_user;

            $sftp = ssh2_sftp($this->ftp);

            $user_root_dir = "/home/${ftp_user}";
           // $user_root_dir = "/etc";
            $dh = opendir("ssh2.sftp://${sftp}${user_root_dir}");
            
            
            while (($file = readdir($dh)) !== false) {
                if (substr($file, 0, 1) != "."){
                    print_r($file);
                    echo "<BR>";
                    $file_type = $this->get_file_stat($user_root_dir."/".$file, "type");
                    echo $file_type;
                    echo "<BR>";
                    echo "<BR>";
                }
            }
            
            //$view_dir = directoryToArray($user_root_dir,1);
        }
        
        /*
            파일 정보 가져오기
            리턴정보 -> https://www.php.net/manual/en/function.stat.php
        */
        function get_file_stat($path, $type){
            $sftp = ssh2_sftp($this->ftp);
            $file_stat = stat("ssh2.sftp://${sftp}${path}");

            if($type == "size"){
                return $file_stat["size"];
            }else if($type == "type"){
                //file_stat의 mode 리턴값을 8진수로 변환시켜 파일형식 파악
                $file_stat = str_pad(decoct($file_stat["mode"]), 7 ,0, STR_PAD_LEFT);
                $file_type_cut = substr($file_stat, 0, 3);
                
                //mode 리턴값별 파일 타입 배열
                $type_arr = array("010" => "file", "004" => "dir", "014" => "socket", "012" => "link", "006" => "block", "002" => "msg_device", "001" => "fifo");

                return $type_arr["${file_type_cut}"];
            }
        }

        //디렉토리 구조 가져오기
        function dir_to_array($dir, $i=0) {
            $i++;
            $array_items = array();
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    //경로 기호 제거
                    if ($file != "." && $file != "..") {
                        //파일 이름 디렉토리 뒤에 부착
                        if (is_dir($dir. "/" . $file)) {
                            //트리구조에 파일 뿌리기 위한 item화
                            $file = $file;
                            $array_items[] = array("dd"=>$i, "dir"=>$dir. "/" . $file, "file"=>preg_replace("/\/\//si", "/", $file));
                            //배열 끝에 추가되도록 추가
                            $array_items = array_merge($array_items, dir_to_array($dir. "/" . $file, $i));
                        }
                    }
                }
                //디렉토리 닫기
                closedir($handle);
            }
            return $array_items;
        }
    }
?>