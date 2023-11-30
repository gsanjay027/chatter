<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        include_once("config.php");
        $con = new db_con();

        $logout_id = $_SESSION["unique_id"];
        $log = $con->logout($logout_id);
        if($log)
            echo "success";
        else
            echo "Warning: You're trying to do vulnerable access..!";
    }
    $_SESSION[] = array();
    session_destroy();
    if($_SERVER["REQUEST_METHOD"] === 'GET')
        header("Location: ../");
?>