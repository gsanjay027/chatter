<?php include_once("php/header.php") ?>

<body>
    <section class="login-signup center-box">
        <header><h1>Chatter<h1></header>
        <hr>
        <div class="form">
            <div class="err-txt"></div>
            <form action="#" method="post" id="signupForm">
                <div class="field input name">
                    <label for="fname">First Name</label>
                    <input name="fname" id="fname" placeholder="Enter first name" required>
                </div>
                <div class="field input name">
                    <label for="lname">Last Name</label>
                    <input name="lname" id="lname" placeholder="Enter last name" required>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="field input">
                    <label for="pass">Password</label>
                    <input type="password" name="pass" id="pass" placeholder="Enter your password" required>
                </div>
                <div class="field input">
                    <label for="image">Profile Image</label>
                    <input type="file" name="image" id="image" accept="image/x-png,image/gif,image/jpeg,image/jpg">
                </div>
                <div class="field button">
                    <button type="submit" name="submit">Sign Up</button>
                </div>
            </form>
            <div class="account">
                <i>Already have an account ? <a href="index.php" class="link">Log In</a></i>
            </div>
        </div>
    </section>

    <script src="js/signup.js"></script>
    <script src="js/config.js"></script>
</body>