<?php
    if($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        include_once("config.php");
        $con = new db_con();
        session_start();
        $outgoing_id = $_SESSION['unique_id'];

        if($con->findMessage($outgoing_id))
            $contacts = $con->getUsers($outgoing_id);
        else
            $contacts = $con->getAllUsers($outgoing_id);
        // if(!isset($_POST['nameLike']))
        //     $contacts = $con->getAllUsers($outgoing_id);
        if(isset($_POST['nameLike']))
        {
            $nameLike = $_POST['nameLike'];
            $contacts = $con->getUsersLike($outgoing_id, $nameLike);
        }
        

        if($contacts)
        {
            while($user = mysqli_fetch_assoc($contacts))
            {
                $incomming_id = $user['unique_id'];
                $message = $con->getTopMessage($outgoing_id, $incomming_id);
                if($message)
                {
                    $msg = $message;
                }
                else
                    $msg = "No Messages Yet";
                $code = '
                    <a class="info" href="chat.php?user='.$incomming_id.'">
                    <img src="php/images/'.$user["img"].'" alt="Profile Image" class="image">
                    <div class="profile" id="'.$incomming_id.'" >
                        <span class="name">'.$user["fname"].' '.$user["lname"].'</span>
                        <p class="message">'.$msg.'</p>
                    </div>
                    <i class="fas fa-circle '.$user["status"].'"></i>
                    </a>
                ';
                echo $code;
            }
        }
        else
            echo "No Contacts Found..!";
    }
?>