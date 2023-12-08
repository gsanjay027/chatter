<?php
    class db_con
    {
        private $host;
        private $db;
        private $usr;
        private $pwd;
        private $con;
        private $key;
        private $chiper;
        private $options;
        
        function __construct()
        {	
            $this->host = "localhost:3306";
            $this->db = "chatter";
            $this->usr = "root";
            $this->pwd = '';
            $this->con = mysqli_connect($this->host,$this->usr,$this->pwd,$this->db);
            if(mysqli_connect_errno())
            {
                echo "Database connection error".mysqli_connect_error();
            }
            $this->key = "randomvalue!@#$%^&*()_+";
            $this->chiper = "AES-128-CTR";
            $this->options = 0;
        }

        function esc_str($str)
        {
            return $this->con->real_escape_string($str);
        }

        function str_openssl_enc($str, $iv) {
            $str = openssl_encrypt($str, $this->chiper, $this->key, $this->options, $iv);
            return $str;
        }
    
        function str_openssl_dec($str, $iv) {
            $str = openssl_decrypt($str, $this->chiper, $this->key, $this->options, $iv);
            return $str;
        }

        function isUser($email)
        {
            $qry = "SELECT `unique_id` FROM `users` WHERE email = ?";
            $res = $this->con->prepare($qry);
            $res->bind_param("s", $email);
            $res->execute();
            $res->store_result();
            
            $numrows = $res->num_rows;

            if ($numrows == 1) {
                $res->bind_result($unique_id);
                $res->fetch();
                return $unique_id;
            } 
            else
                return false;
        }

        function signup($fname, $lname, $email, $pass, $img)
        {
            $unique_id = $this->esc_str(rand(time(), 1000000000));
            $hash_pass = $this->esc_str(password_hash($pass, PASSWORD_BCRYPT));
            $ukey = bin2hex(openssl_random_pseudo_bytes(16));

            $qry = "INSERT INTO `users` (unique_id, fname, lname, email, pass, img, unique_key) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("sssssss", $unique_id, $fname, $lname, $email, $hash_pass, $img, $ukey);
            $stmt->execute();

            $numrows = $stmt->affected_rows;

            $stmt->close();

            if ($numrows == 1)
                return true;
            else 
                return false;
        }


        function login($unique_id, $pass)
        {
            $qry = "SELECT `pass` FROM `users` WHERE unique_id = ?";
            $res = $this->con->prepare($qry);
            $res->bind_param("s", $unique_id);
            $res->execute();
            $res->store_result();
            
            $numrows = $res->num_rows;
            if ($numrows == 1)
            {

                $res->bind_result($hash_pass);
                $res->fetch();
                
                if (password_verify($pass, $hash_pass)) {
                    $res->close();

                    $qry = "UPDATE `users` SET status = 'Online' WHERE unique_id = ?";
                    $res = $this->con->prepare($qry);
                    $res->bind_param("s", $unique_id);
                    $res->execute();
                    $res->close();

                    return true;
                }
                else
                    return false;
            }
            else
                return false;
        }

        function getUserInfo($unique_id)
        {
            $qry = "SELECT `fname`, `lname`, `img`, `status`, `theme` FROM `users` WHERE unique_id = ?";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("s", $unique_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows == 1) {
                $user_info = $res->fetch_assoc();
                return $user_info;
            } else {
                return false;
            }
        }


        function getAllUsers($unique_id)
        {
            $qry = "SELECT `unique_id`, `fname`, `lname`, `img`, `status` FROM `users` WHERE NOT unique_id = ?";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("s", $unique_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows >= 1)
                return $res;
            else
                return false;
        }

        function getUsers($unique_id)
        {
            $qry = "
                SELECT u.`unique_id`, u.`fname`, u.`lname`, u.`img`, u.`status`
                FROM `users` u
                LEFT JOIN `messages` m ON u.`unique_id` = m.`outgoing_msg_id` OR u.`unique_id` = m.`incomming_msg_id`
                WHERE u.`unique_id` <> ?
                GROUP BY u.`unique_id`
                ORDER BY MAX(m.`timestamp`) DESC;
            ";

            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("s", $unique_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows >= 1) {
                return $res;
            } else {
                return false;
            }
        }

        function findMessage($unique_id)
        {
            $qry = "SELECT `msg_id` FROM messages WHERE incomming_msg_id = ? OR outgoing_msg_id = ?";
            $stmt = $this->con->prepare($qry);

            $stmt->bind_param("ss", $unique_id, $unique_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows >= 1)
                return true;
            else 
                return false;
        }


        function getUsersLike($unique_id, $nameLike)
        {
            $nameLike = $this->esc_str($nameLike);
            $qry = "SELECT `unique_id`, `fname`, `lname`, `img`, `status` FROM `users` WHERE NOT unique_id = ? AND (fname LIKE ? OR lname LIKE ?)";
            $nameLike = "%" . $nameLike . "%";

            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("sss", $unique_id, $nameLike, $nameLike);
            $stmt->execute();
            $res = $stmt->get_result();

            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows >= 1) {
                return $res;
            } else {
                return false;
            }
        }


        function getTopMessage($outgoing_id, $incomming_id)
        {
            $qry = "SELECT `msg`, `iv`, `incomming_msg_id`, `outgoing_msg_id`, `status` FROM messages WHERE (incomming_msg_id = ? OR outgoing_msg_id = ?) AND (incomming_msg_id = ? OR outgoing_msg_id = ?) ORDER BY `msg_id` DESC LIMIT 1";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("ssss", $incomming_id, $incomming_id, $outgoing_id, $outgoing_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();
            
            $numrows = $res->num_rows;

            if ($numrows == 1) {
                $flg = 0;
                $row = $res->fetch_assoc();
                $str = $row['msg'];
                $iv = hex2bin($row['iv']);
                $view = $row['status'];
                if($row['outgoing_msg_id'] == $outgoing_id)
                {
                    $you = "Message Sent";
                    $flg = 1;
                }
                else
                    $you ="Received";
                return $view == "False" && $flg == 0 ? "<b>New messages</b>" : $you;
            } else {
                return false;
            }
        }

        function getProfileImage($unique_id)
        {
            $qry = "SELECT `img` FROM `users` WHERE unique_id = ?";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("s", $unique_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows == 1) {
                $img = $res->fetch_assoc()['img'];
                return $img;
            } else {
                return false;
            }
        }


        function getStatus($unique_id)
        {
            $qry = "SELECT `status` FROM `users` WHERE unique_id = $unique_id";
            $res = mysqli_query($this->con,$qry) or die("Error in Query\n".mysqli_error($this->con));
            $numrows = mysqli_num_rows($res);
            if($numrows == 1)
            {
                return mysqli_fetch_assoc($res)['status'];
            }
            else
                return false;
        }

        function insertChat($outgoing_id, $incomming_id, $message, $type)
        {
            $iv = openssl_random_pseudo_bytes(16);
            $enc_message = $this->str_openssl_enc($message, $iv);
            $iv_hex = bin2hex($iv);

            include_once('server.php');
            $con2 = new db2($this);
            

            $length = strlen($enc_message);

            $h1 = substr($enc_message, 0, $length/2);
            $h2 = substr($enc_message, $length/2);

            $con2->insertChat($outgoing_id, $incomming_id, $h2);

            $qry = "INSERT INTO `messages` (outgoing_msg_id, incomming_msg_id, msg, iv, type) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("sssss", $outgoing_id, $incomming_id, $h1, $iv_hex, $type);
            $stmt->execute();

            $numrows = $stmt->affected_rows;
            $stmt->close();
            if ($numrows == 1) {
                return true;
            } else {
                return false;
            }
        }

        function getChat($outgoing_id, $incomming_id)
        {
            $qry = "SELECT `msg`, `iv`, `incomming_msg_id`, `outgoing_msg_id`, `type` FROM `messages` WHERE (incomming_msg_id = ? OR outgoing_msg_id = ?) AND (incomming_msg_id = ? OR outgoing_msg_id = ?) ORDER BY `msg_id`";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("ssss", $incomming_id, $incomming_id, $outgoing_id, $outgoing_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows >= 1){
                return $res;
            }
            else
                return false;
        }

        function logout($logout_id)
        {
            $qry = "SELECT `user_id` FROM `users` WHERE unique_id = ?";
            $res = $this->con->prepare($qry);

            $res->bind_param("s", $logout_id);
            $res->execute();
            $res->store_result();
            
            $numrows = $res->num_rows;
            
            if($numrows == 1)
            {
                $res->close();

                $qry = "UPDATE `users` SET status = 'Offline' WHERE unique_id = ?";
                $res = $this->con->prepare($qry);
                $res->bind_param("s", $logout_id);
                $res->execute();
                $res->close();

                return true;
            }
            else
                return false;
        }

        function updateView($incomming_id)
        {
            $qry = "UPDATE `messages` SET status = 'True' WHERE outgoing_msg_id = ?";
            $res = $this->con->prepare($qry);
            $res->bind_param("s", $incomming_id);
            $res->execute();
            $res->close();
        }
    }
?>