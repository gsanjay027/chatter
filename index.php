<?php
    session_start();
    if(isset($_SESSION["unique_id"]))
        header("Location: user.php");
?>

<?php include_once("php/header.php") ?>

<body>
    <section class="login-signup center-box">
        <header><h1>Chatter<h1></header>
        <hr>
        <div class="form">
            <div class="err-txt"></div>
            <form action="#" method="post" id="loginForm">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="field input">
                    <label for="pass">Password</label>
                    <input type="password" name="pass" id="pass" placeholder="Enter your password" required>
                </div>
                <div class="field button">
                    <button type="submit" name="submit">Log In</button>
                </div>
            </form>
            <div class="account">
                <i>Don't have an account ? <a href="signup.php" class="link">Sign Up</a></i>
            </div>
        </div>
    </section>

    <script src="js/login.js"></script>
    <script src="js/config.js"></script>
</body>