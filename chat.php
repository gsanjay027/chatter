<?php
    session_start();
    if(!isset($_SESSION["unique_id"]))
        header("Location: index.php");
    if(!isset($_GET["user"]))
        header("Location: user.php");
    $outgoing_id = $_SESSION["unique_id"];
    $incomming_id = intval($_GET["user"]);
    
    include_once("php/config.php");
    $con = new db_con();

    $con->updateView($incomming_id);

    echo "<script>var incomming_id = $incomming_id;</script>";
?>

<?php include_once("php/header.php"); include_once("php/config.php"); ?>

<body>
    <section class="message-box center-box">
        <header class="info">
            <?php
                $con = new db_con();

                $info = $con->getUserInfo($incomming_id);
                if(!$info)
                    header("Location: user.php");
            ?>
            <a href="user.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <img src="php/images/<?php echo $info['img'] ?>" alt="Profile Image" class="image">
            <div class="profile"  id="<?php echo $incomming_id ?>">
                <span class="name"><?php echo $info['fname']," ",$info['lname'] ?></span>
                <p class="status <?php echo $info['status'] ?>"><?php echo $info['status'] ?></p>
            </div>
        </header>
        <main class="chat-list" id="chat-lister">
             
        </main>
        <form action="#" method="POST" id="sendMsg" enctype="multipart/form-data">
            <input type="hidden" name="unique_id" value="<?php echo $outgoing_id ?>">
            <input placeholder="Type your message here..." name="message" class="message" autocomplete="off" required>
            <button type="submit" class="submit" onclick="autoScroll()"><i class="fab fa-telegram-plane"></i></button>
            <div class="file-upload">
                <input type="file" id="fileInput" class="images" accept="image/*">
                <label for="fileInput" class="file-label">
                    <i class="fa fa-camera image-icon"></i>
                </label>
            </div>
            <div class="file-upload">
                <input type="file" id="pdfInput" class="images" accept=".pdf">
                <label for="pdfInput" class="file-label">
                    <i class="fa fa-file"></i>
                </label>
            </div>

            <!-- <input type="file" class="images" id="fileInput" accept="image/*"> -->
            <!-- <i class="fa fa-camera image-icon"></i> -->
            <!-- <button type="button" ><i class="fa fa-camera"></i></button> -->
        </form>
    </section>

    <script src="js/chat.js"></script>
</body>