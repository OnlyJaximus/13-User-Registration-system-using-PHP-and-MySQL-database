<?php
require_once("server.php")
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User registration system using PHP and MySQL</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="header">
        <h2>Login</h2>
    </div>

    <form action="login.php" method="POST">


        <!-- display validation errors here -->
        <?php require_once("errors.php") ?>

        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $username ?>">
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>


        <div class="input-group">
            <button type="submit" name="login_btn" class="btn">Login</button>
        </div>

        <p>
            Not yet a member? <a href="register.php">Sign up</a>
        </p>
    </form>
</body>

</html>