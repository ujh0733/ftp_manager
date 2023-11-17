<?php
	class tools{
		function requestValue($val){			//값의 유무 유무여부 판단
			return isset($_REQUEST[$val])?$_REQUEST[$val]:"";
		}

		function alert($msg){
			echo "<script>alert('${msg}')</script>";
		}

		function alert_location($msg, $url){
			echo "<script>alert('${msg}')</script>";
			echo "<script>location.href = '${url}'</script>";
		}
	}
?>