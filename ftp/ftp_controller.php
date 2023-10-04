<?php
    class Ftp_Controller{
        private $ftp;

        public function __construct($ftp_host, $ftp_user, $ftp_pass){
			try{
				$conn_id = ftp_connect($ftp_host);
                $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);

                return $conn_id;
			}catch(Exception $e){
				exit($e->getMessage());
			}
		}
    }
?>