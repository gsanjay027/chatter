<?php
    if($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        include_once("config.php");
        $con = new db_con();

        $fname = $con->esc_str($_POST["fname"]);
        $lname = $con->esc_str($_POST["lname"]);
        $email = $con->esc_str($_POST["email"]);
        $pass = $con->esc_str($_POST["pass"]);

        $exist = $con->isUser($email);

        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if(!$exist)
            {
                if(isset($_FILES['image']) && !empty($_FILES["image"]["name"]))
                {
                    $img_name = $_FILES['image']['name'];
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    
                    $img_explode = explode('.',$img_name);
                    $img_ext = end($img_explode);
            
                    $extensions = ["jpeg", "png", "jpg", "gif"];
                    if(in_array($img_ext, $extensions) === true)
                    {
                        $types = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
                        if(in_array($img_type, $types) === true)
                        {
                            $time = time();
                            $imageName = $time.$img_name;
                            if(move_uploaded_file($tmp_name,"images/".$imageName))
                            {
                                $imageName = $con->esc_str($imageName);
                                $log = $con->signup($fname, $lname, $email, $pass, $imageName);
                                if($log)
                                    echo "success";
                                else
                                    echo "Somthing wrong..!";
                            }
                            else
                                echo "Image Not Uploaded..!";
                        }
                        else
                            echo "Please choose image on jpg/png/jpeg..!";
                    }
                    else
                        echo "Please choose image on jpg/png/jpeg..!";
                }
                else
                {
                    $defImage = $con->esc_str("logo.png");
                    $log = $con->signup($fname, $lname, $email, $pass, $defImage);
                    if($log)
                        echo "success";
                    else
                        echo "Somthing wrong..!";
                }
            }
            else
                echo "$email - This email already exist!";
        }
        else
            echo "Enter a valid email..!";
    }
    else
        echo "Come form Signup page";
?>