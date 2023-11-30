<?php
    session_start();
    if(!isset($_SESSION["unique_id"]))
        header("Location: index.php");
    $unique_id = $_SESSION["unique_id"];
?>

<?php include_once("php/header.php"); include_once("php/config.php"); ?>

<body>
    <section class="user center-box">
        <header class="info">
            <?php
                $con = new db_con();

                $info = $con->getUserInfo($unique_id);
                if(!$info)
                    header("Location: php/logout.php");
            ?>
            <img src="php/images/<?php echo $info['img'] ?>" alt="Profile Image" class="image">
            <div class="profile"  id="<?php echo $unique_id ?>">
                <span class="name"><?php echo $info['fname']," ",$info['lname'] ?></span>
                <p class="status <?php echo $info['status'] ?>"><?php echo $info['status'] ?></p>
            </div>
            <button type="button" class="logout"><i class="fas fa-sign-out"></i>Logout</button>
        </header>
        <hr>
        <div class="search">
            <span class="suggest">Select an user to start chat</span>
            <input type="text" placeholder="Enter name to search..." class="input">
            <button type="button" class="search-btn"><i class="fas fa-search"></i></button>
        </div>
        <main class="contact">
            
        </main>
    </section>

    <script src="js/user.js"></script>
</body>