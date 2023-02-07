<?php
$username = "";
$email = "";

$errors = array();

// Connect to the database  1 way

$host = "localhost";
$db = "comp_login";
$usernameDB = "root";
$password = "";

$mysqli = new mysqli(
    hostname: $host,
    database: $db,
    username: $usernameDB,
    password: $password
);

if ($mysqli->connect_errno) {
    die("Connect error: " . $mysqli->connect_error);
}
// return $mysqli;


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    // $data = mysql_real_escape_string($data);
    return $data;
}


# mysqli_real_escape_string() function
# escapes special characters in a string for use in an SQL query, 
# taking into account the current character set of the connection. 
# This function is used to create a legal SQL string that can be used in an SQL statement.

// Connect to the database 2 way
// $db = mysqli_connect("localhost", "root", "", "comp_login");



// if the register button is clicked
if (isset($_POST['register_btn'])) {

    // $username = test_input($_POST['username']);
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);

    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($mysqli, $_POST['conffirm_password']);

    $usernamelength = strlen($username);


    $sql = $mysqli->prepare("SELECT username, email FROM users2 WHERE username=? OR email=?");
    $sql->bind_param("ss",  $username, $email);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    // Test
    // echo "ime je: " . $row['username'];

    // ensure that form fields are filled properly
    if (empty($username)) {
        array_push($errors, "User name is required");  // add error to erros array
    } elseif ($usernamelength < 4) {
        array_push($errors, "Invalid username. Username must be at least 6!");
    } elseif ($usernamelength > 15) {
        array_push($errors, "Invalid username. Username cannot be greater than 15!");
    } elseif (isset($row['username']) && $row['username'] ==  $username) {
        array_push($errors, "Username is not available, try different!");
    } else {
        $username = test_input($_POST['username']);
        if (!preg_match("/^[a-zA-Z]*$/", $username)) {
            array_push($errors, "Only alphabets and white spaces are allowed!");
        }
    }


    if (empty($email)) {
        array_push($errors, "Email is required");
    } elseif (isset($row['email']) && $row['email'] == $email) {
        array_push($errors, "Email address is not available, try different!");
    } else {
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email format!");
        }
    }

    if (empty($password)) {
        array_push($errors, "Password is required");
    } elseif (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters!");
    } elseif (!preg_match("/[a-z]/i", $password)) {
        array_push($errors, "Password must contain at least one letter!");
    } elseif (!preg_match("/[0-9]/", $password)) {
        array_push($errors, "Password must contain at least one number!");
    } else {
        $password = test_input($password);
    }



    if ($password != $confirm_password) {
        array_push($errors, "The two passwords do not match!");
    }


    # ********  encrypt password before storing in database (security)  ******** #

    // $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // $password_hash = md5($password);

    # ***************************************************************************



    // if there are no errors, save user to database
    // $sql = "";
    // $sql = "INSERT INTO users2 (username, email, password) VALUES (?,?,?)";

    if (count($errors) == 0) {
        $password_hash = md5($password);
        $sql = "INSERT INTO users2 (username, email, password) VALUES (?,?,?)";

        # mysqli_stmt_init — Initializes a statement and returns an object for use with mysqli_stmt_prepare
        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }

        $stmt->bind_param(
            "sss",
            $username,
            $email,
            $password_hash
        );

        if ($stmt->execute()) {
            // echo "Daaa";
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header("Location: index.php");
        } else {
            // echo "Neeeee";
            header("Location: register.php");
        }
    }
}

// log user in from login page
if (isset($_POST['login_btn'])) {


    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $password = md5($_POST['password']);


    // ensure that form fields are filled properly
    if (empty($username)) {
        array_push($errors, "User name is required");  // add error to erros array
    } else {
        $username = test_input($_POST['username']);
        if (!preg_match("/^[a-zA-Z]*$/", $username)) {
            array_push($errors, "Only alphabets and white spaces are allowed for name!");
        }
    }


    if (empty($password)) {
        array_push($errors, "Password is required");
    }



    if (count($errors) == 0) {

        // print_r("Nema gresaka. <br>");


        // $password_hash = md5($password);  // encrypt password before comparing with that from database
        $sql = $mysqli->prepare("SELECT * FROM users2 WHERE username =? AND password =?");
        $sql->bind_param("ss", $username, $password);
        $sql->execute();

        // 1 nacin
        // $result = $sql->get_result();
        // $row = $result->fetch_array(MYSQLI_ASSOC);

        // 2 nacin
        $user = $sql->fetch();



        // Samo za moju neku proveru
        // if ($row) {
        //     echo "postji";
        // } else {
        //     echo "ne postoji!";
        // }



        if ($user != null) {
            // log user in
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header("Location: index.php");
        } else {
            array_push($errors, "Wrong username/password combination!");
        }
    }
    //  else {
    //     echo "ima gresaka";
    // }
}



// logout
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    unset($_SESSION['username']);
    header("Location: login.php");
}
