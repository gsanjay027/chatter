<?php
    if($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        if(isset($_POST['incommingid']))
        {
            include_once("config.php");
            include_once("server.php");
            $con = new db_con();
            $con2 = new db2();
            session_start();
            $outgoing_id = $_SESSION['unique_id'];
            $incomming_id = $_POST['incommingid'];

            $message = $_POST['message'];

            $log = $con->insertChat($outgoing_id, $incomming_id, $message);
            if($log)
                echo "success";
            else
                echo "error";
        }
    }
?>