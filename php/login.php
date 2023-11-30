<?php
    if($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        include_once("config.php");
        $con = new db_con();

        $email = $con->esc_str($_POST["email"]);
        $pass = $con->esc_str($_POST["pass"]);
        
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $exist = $con->isUser($email);

            if($exist)
            {
                $log = $con->login($exist,$pass);
                if($log)
                {
                    session_start();
                    $_SESSION["unique_id"] = $exist;
                    echo "success";
                }
                else
                    echo "Email or Password Incorrect..!";
            }
            else
                echo "Email not exists..!";
        }
        else
            echo "Enter a valid email..!";
    }
    else
    {
        echo "Come form Login page.";
    }
?>