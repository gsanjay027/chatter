<?php
    if($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        if(isset($_POST['incommingid']))
        {
            include_once("config.php");
            include_once('server.php');
            $con2 = new db2();
            $con = new db_con();
            session_start();
            $outgoing_id = $_SESSION['unique_id'];
            $incomming_id = $_POST['incommingid'];

            $con->updateView($incomming_id);
            
            $chats = $con->getChat($outgoing_id, $incomming_id);
            $chat2 = $con2->getChat($outgoing_id, $incomming_id);
            $status = $con->getStatus($incomming_id);
            if($chats)
            {
                $chatr = "";
                $count = 0;
                while($msg = mysqli_fetch_assoc($chats))
                {
                    //$chat = $con->str_openssl_dec($msg['msg'], hex2bin($msg['iv']));
                    $chat = $msg['msg'];
                    $iv = $msg['iv'];
                    if($outgoing_id == $msg['outgoing_msg_id'])
                    {
                        $img = $con->getProfileImage($outgoing_id);
                        $flg = 1;
                        // $chatr .= '
                        //     <div class="chat outgoing">
                        //         <p class="message">'.$chat.'</p>
                        //         <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        //     </div>
                        // ';
                        for($i = $count; $i < $count+1; $i++){
                            $ch1[$i] = $chat;
                            $fg[$i] = $flg;
                            $init[$i] = $iv;
                        }
                        $count++;
                    }
                    else if($outgoing_id == $msg['incomming_msg_id'])
                    {
                        $img = $con->getProfileImage($incomming_id);
                        $flg = 0;
                        // $chatr .= '
                        //     <div class="chat incomming">
                        //         <p class="message">'.$chat.'</p>
                        //         <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        //     </div>
                        // ';
                        for($i = $count; $i < $count+1; $i++){
                            $ch1[$i] = $chat;
                            $fg[$i] = $flg;
                            $init[$i] = $iv;
                        }
                        $count++;
                    }
                }
                $count2 = 0;
                while($msg = mysqli_fetch_assoc($chat2))
                {
                    //$chat = $con->str_openssl_dec($msg['msg'], hex2bin($msg['iv']));
                    $chat = $msg['msg'];


                    if($outgoing_id == $msg['outgoing_msg_id'])
                    {
                        $img = $con->getProfileImage($outgoing_id);
                        $flg = 1;
                        // $chatr .= '
                        //     <div class="chat outgoing">
                        //         <p class="message">'.$chat.'</p>
                        //         <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        //     </div>
                        // ';
                        for($i = $count2; $i < $count2+1; $i++){
                            $ch2[$i] = $chat;
                            $fg2[$i] = $flg;
                        }
                        $count2++;
                    }
                    else if($outgoing_id == $msg['incomming_msg_id'])
                    {
                        $img = $con->getProfileImage($incomming_id);
                        $flg = 0;
                        // $chatr .= '
                        //     <div class="chat incomming">
                        //         <p class="message">'.$chat.'</p>
                        //         <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        //     </div>
                        // ';
                        for($i = $count2; $i < $count2+1; $i++){
                            $ch2[$i] = $chat;
                            $fg2[$i] = $flg;
                        }
                        $count2++;
                    }
                }
            }
            else
                $chatr .= 'NoChatsFound';

            for($i=0; $i<$count; $i++)
            {
                if($fg[$i] == 1)
                {
                    $img = $con->getProfileImage($outgoing_id);
                    $chatr .= '
                        <div class="chat outgoing">
                            <p class="message">'.$con->str_openssl_dec($ch1[$i].$ch2[$i], hex2bin($init[$i])).'</p>
                            <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        </div>
                        <!--<div class="chat outgoing image-out">
                            <img height="250%" width="30%" src="/chatter/img/back.png" alt="Received Image" class="message">
                            <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        </div>-->
                    ';  
                }
                else if($fg[$i] == 0)
                {
                    $img = $con->getProfileImage($incomming_id);
                    $chatr .= '
                        <div class="chat incomming">
                            <p class="message">'.$con->str_openssl_dec($ch1[$i].$ch2[$i], hex2bin($init[$i])).'</p>
                            <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        </div>
                        <!--<div class="chat outgoing image-in">
                            <img height="250%" width="30%" src="/chatter/img/back.png" alt="Received Image" class="message">
                            <img src="php/images/'.$img.'" alt="Profile Image" class="image">
                        </div>-->
                    ';   
                }
            }
            echo json_encode(array("data" => $chatr, "status" => $status));
        }
    }
?>