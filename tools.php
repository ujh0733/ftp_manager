<?php
    function requestValue($val){			//값의 유무 유무여부 판단
		return isset($_REQUEST[$val])?$_REQUEST[$val]:"";
	}
?>