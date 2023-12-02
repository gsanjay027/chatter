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

            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK)
            {
                $pdf = $_FILES['pdf']['tmp_name'];
                $base64PDF = "data:application/pdf;base64,".base64_encode(file_get_contents($pdf));
                $type = "pdf";
                $log = $con->insertChat($outgoing_id, $incomming_id, $base64PDF, $type);
            }

            if(isset($_POST['image'])){
                $image = $_POST['image'];
                $type = "image";
                $log = $con->insertChat($outgoing_id, $incomming_id, $image, $type);
            }
            if(isset($_POST['message'])){
                $message = $_POST['message'];
                $type = "text";
                $log = $con->insertChat($outgoing_id, $incomming_id, $message, $type);
            }
            if($log)
                echo "success";
            else
                echo "error";
        }
    }
?>