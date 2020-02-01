<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>

<body>
<div class = "fa">
	<h2 style="text-align:center"> Create your account </h2><br><br>
	<form action = "register.php" class="centered" method = "POST">
		<label>User Name: <input type="text" style="text-align:center" name="username" placeholder = "username"/><br></label>
		<label>Password: <input type="password" style="text-align:center" name="password" placeholder = "password"/><br></label>
        <label>Re-enter Password: <input type="password" style="text-align:center" name="repassword" placeholder = "repassword"/><br></label>
        <input type="submit" class="centered" value="register"><br><br>
    </form>
    <br>
    <br>
    <br>
    Already have an account? <a href = "login.php"> Login now~ </a>
</div>
</body>
</html>


<?php

session_start();
require 'database.php';


if(isset($_POST['username'])){
//echo 1;
    $username = ($_POST['username']);
    $password = ($_POST['password']);
    $repassword = ($_POST['repassword']);

    if($repassword != $password){
        echo "Two passwords are not equal, please set it again";
        exit;
    }


    //then select username from database
    //if it has exist! then echo exist
    $stmt = $mysqli->prepare("select username from users where username = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('s', $username);

    $stmt->execute();

    $result = $stmt->get_result();
   

    //exist

    if($result->fetch_assoc() != null){
        echo "This username has already existed! Change one please~";
        $stmt->close();
    }// not! then insert to database and get the Session_id
    else{
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        //Insert query store it
        $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
               
        $stmt->bind_param('ss', $username, $password_hashed);
        $stmt->execute();
        $stmt->close();

        //then we can get the id from this user

        $stmt = $mysqli->prepare("select user_id from users where username = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('s', $username);

        $stmt->execute();

        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()){
            $_SESSION['user_id'] = $row["user_id"];
            $_SESSION['user_name'] = $username;
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            $stmt->close();
            header("Location:mainpage.php");
        }
    }

}
?>