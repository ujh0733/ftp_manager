<?php
    //error_reporting( E_ALL );
    //ini_set( "display_errors", 0 );

    @session_start();
    define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

    include ROOT_DIR."/database/db.php";
    include ROOT_DIR."/tools.php";

    $db = new DbPdo();
    $tools = new tools();
?>