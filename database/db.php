<?php
    class DbPdo{
        private $host = "localhost";
        private $dbname = "ftp_manager";
        private $user = "manager";
        private $password = "R2v@m&xP";
        private $port = "3306";

        private $db;

        public function __construct(){
			try{
				$this->db = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
				exit($e->getMessage());
			}
		}

        public function getBoardList(){
            try{
				$sql = "select * from bbs order by reg_date desc";

				$pstmt = $this->db->prepare($sql);
				$pstmt->execute();
				$result = $pstmt->fetchAll(PDO::FETCH_ASSOC);

			}catch(PDOException $e){
				exit($e->getMessage());
			}
			return $result;
        }

		public function test(){
			return "test 222";
		}
		public function test22(){
			return "test 222";
		}
    }
?>