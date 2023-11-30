<?php
    class db2
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
            $this->db = "batter";
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

        function str_openssl_enc($str, $iv) {
            $str = openssl_encrypt($str, $this->chiper, $this->key, $this->options, $iv);
            return $str;
        }
    
        function str_openssl_dec($str, $iv) {
            $str = openssl_decrypt($str, $this->chiper, $this->key, $this->options, $iv);
            return $str;
        }

        function insertChat($outgoing_id, $incomming_id, $message)
        {
            $iv = openssl_random_pseudo_bytes(16);
            $enc_message = $message;
            $iv_hex = bin2hex($iv);

            $qry = "INSERT INTO `messages` (outgoing_msg_id, incomming_msg_id, msg, iv) VALUES (?, ?, ?, ?)";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("ssss", $outgoing_id, $incomming_id, $enc_message, $iv_hex);
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
            $qry = "SELECT `msg`, `iv`, `incomming_msg_id`, `outgoing_msg_id` FROM `messages` WHERE (incomming_msg_id = ? OR outgoing_msg_id = ?) AND (incomming_msg_id = ? OR outgoing_msg_id = ?) ORDER BY `msg_id`";
            $stmt = $this->con->prepare($qry);
            $stmt->bind_param("ssss", $incomming_id, $incomming_id, $outgoing_id, $outgoing_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $stmt->close();

            $numrows = $res->num_rows;

            if ($numrows >= 1)
                return $res;
            else
                return false;
        }
    }
?>