<?php
    class Ftp_Controller{
        private $sftp;
        private $ftp_host, $ftp_user, $ftp_pass;
        private $user_root_dir;

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

                $this->sftp = ssh2_sftp($connection);
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

        public function ftp_logout(){
            $result_msg = array("code" => "", "msg" => "", "url" => "");

            if(ssh2_disconnect($this->sftp)){
                $result_msg["code"] = "ok";
                $result_msg["msg"] = "정상적으로 로그아웃 되었습니다.";
                $result_msg["url"] = "index.htm";
            }else{
                $result_msg["code"] = "fail";
                $result_msg["msg"] = "비정상적인 접근입니다.";
                $result_msg["url"] = "ftp.htm";
            }

            echo json_encode($result_msg);
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
                    $result_msg["url"] = "ftp.htm";

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
            
            //기본 linux user 경로로 설정
            $this->user_root_dir = "/home/${ftp_user}";            
            $dir_arr = $this->dir_to_array($this->user_root_dir);
            
            //$dir_arr = $this->record_sort($dir_arr, "path", false);
            /*
            echo "<pre>";
            print_r($dir_arr);
            echo "</pre>";
            */
            return $dir_arr;
        }
        
        /*
            파일 정보 가져오기
            리턴정보 -> https://www.php.net/manual/en/function.stat.php
        */
        function get_file_stat($path){
            $file_stat = stat("ssh2.sftp://".$this->sftp.$path);
            
            //file_stat의 mode 리턴값을 8진수로 변환시켜 파일형식 파악
            $file_mode = str_pad(decoct($file_stat["mode"]), 7 ,0, STR_PAD_LEFT);
            $file_type_cut = substr($file_mode, 0, 3);
            
            //mode 리턴값별 파일 타입 배열
            $type_arr = array("010" => "file",
                            "004" => "dir",
                            "014" => "socket",
                            "012" => "link",
                            "006" => "block",
                            "002" => "msg_device",
                            "001" => "fifo",
                            "000" => "symlink");

            $file_info = array(
                "size" => $file_stat["size"],
                "type" => $type_arr[$file_type_cut]
            );

            return $file_info;
        }

        //전체 디렉토리 구조 가져오기
        function dir_to_array($dir, $i=0, $parent="root") {
            $i++;
            $array_items = array();
            
            if($dir == $this->user_root_dir){
                $file_stat = $this->get_file_stat($dir);
                $file_size = $this->file_size_convert($file_stat["size"]);
                
                $add_btn = "<a onclick='file_jobs(\"upload\", \"#\")' class='ms-1' title='업로드'><i class='fa-solid fa-upload'></i></a>";
                $add_btn .= "<a onclick='file_jobs(\"mkdir\", \"#\")' class='ms-1' title='폴더추가'><i class='fa-solid fa-folder-plus'></i></a>";

                $array_items[] = array_merge($array_items, array("id" => "root",
                                                                "parent" => "#",
                                                                "path" => $this->user_root_dir,
                                                                "text" => $this->ftp_user.$add_btn,
                                                                "type" => "root_dir",
                                                                "size" => $file_size,
                                                                "state" => array(
                                                                    "opened" => true,
                                                                )
                                                                ));
            }

            if ($handle = opendir("ssh2.sftp://".$this->sftp.$dir)) {
                while (false !== ($file = readdir($handle))) {
                    if (substr($file, 0, 1) != "."){          
                        $id = bin2hex(random_bytes(4));              

                        $file_stat = $this->get_file_stat($dir."/".$file);
                        $file_size = $this->file_size_convert($file_stat["size"]);
                        
                        $add_btn = "<div class='ms-5 float-end'>";
                        $add_btn .= "<a onclick='file_jobs(\"rename\", \"${id}\")' class='ms-1' title='수정'><i class='fa-solid fa-pen-to-square'></i></a>";
                        $add_btn .= "<a onclick='file_jobs(\"delete\", \"${id}\")' class='ms-1' title='삭제'><i class='fa-solid fa-trash'></i></a>";
                        if ( $file_stat["type"] == "dir" ) {
                            $add_btn .= "<a onclick='file_jobs(\"upload\", \"${id}\")' class='ms-1' title='업로드'><i class='fa-solid fa-upload'></i></a>";
                            $add_btn .= "<a onclick='file_jobs(\"mkdir\", \"${id}\")' class='ms-1' title='폴더추가'><i class='fa-solid fa-folder-plus'></i></a>";
                        }
                        $add_btn .= "<a onclick='file_jobs(\"download\", \"${id}\")' class='ms-1' title='다운로드로드'><i class='fa-solid fa-download'></i></a>";
                        
                        $add_btn .= "</div>";
                        
                        

                        //파일 정보 배열화
                        $array_items[] = array("id" => $id,
                                                "parent" => $parent,
                                                "path" => $dir."/".$file,
                                                "text" => preg_replace("/\/\//si", "/", $file).$add_btn,
                                                "type" => $file_stat["type"],
                                                "size" => $file_size,
                                                );

                        if ( $file_stat["type"] == "dir" ) {
                            //배열 끝에 추가
                            $array_items = array_merge($array_items, $this->dir_to_array($dir. "/" . $file, $i, $id));
                        }
                    }
                }
                //디렉토리 닫기
                closedir($handle);
            }
            return $array_items;
        }

        //용량 변환
        function file_size_convert($size){
            $unit = array("Byte", "Kb", "Mb", "Gb");

            if($size > 0)
                return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2). " ".$unit[$i]);
            else
                return $size." ".$unit[0];
        }

        //다중배열정렬
        function record_sort($records, $field, $reverse=false){
            $hash = array();
            foreach($records as $record){
                $hash[$record[$field]] = $record;
            }
            ksort($hash);
            $records = array();
            foreach($hash as $record){
                $records []= $record;
            }
            return $records;
        }

        //이름 변경
        function ssh2_rename($from, $to){
            return ssh2_sftp_rename($this->sftp, $from, $to);
        }

        //디렉토리 추가
        function ssh2_mkdir($path, $name){
            return ssh2_sftp_mkdir($this->sftp, $path."/".$name);
        }
    }
?>