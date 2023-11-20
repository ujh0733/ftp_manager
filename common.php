<?php
    //error_reporting( E_ALL );
    //ini_set( "display_errors", 0 );

    @session_start();
    define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

    include ROOT_DIR."/database/db.php";
    include ROOT_DIR."/tools.php";

<<<<<<< HEAD
<<<<<<< HEAD
=======
    define(TEST, "TEST");
    
>>>>>>> parent of 87b4bbf (test)
=======
    define(TEST, "TEST");
    
>>>>>>> 0369019f32c6d584fddb4ead9a11c37ee3052401
    $db = new DbPdo();
    $tools = new tools();
?>