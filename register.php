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
        <h2>Register</h2>
    </div>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">


        <!-- display validation errors here -->
        <?php require_once("errors.php") ?>

        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $username ?>">
        </div>

        <div class="input-group">
            <label for="email">Email</label>
            <input type="" id="email" name="email" value="<?php echo $email ?>">
        </div>

        <div class=" input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="input-group">
            <label for="conffirm_password">Confirm Password</label>
            <input type="password" id="conffirm_password" name="conffirm_password">
        </div>

        <div class="input-group">
            <button type="submit" name="register_btn" class="btn">Register</button>
        </div>

        <p>
            Already a member? <a href="login.php">Sign in</a>
        </p>
    </form>
</body>

</html>