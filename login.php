<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link type="text/css" rel="stylesheet" href="StyleSheet.css">
    </head>

<body>
<div class = "fa">
        <h2 style="text-align:center"> Login Window</h2><br><br>
        <form action="login.php" class="centered" method="POST">
             <label>User Name: <input style="text-align:center" type="text" name="username" placeholder = "username"/><br></label>
             <label>Password: <input style="text-align:center" type="password" name="password" placeholder = "password"/><br><br><br></label>
             <label>Submit:<input type="submit" class="centered" value="log in"><br><br></label>
        </form>
        <br>
        <br>
            Or:<a href = "register.php" class="centered" style ="font-size:20px">Create Your Own Account</a>

</div>
</body>
</html>   
<?php
session_start();

require 'database.php';
    
    if(isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
    //fins invalid username
    if (!preg_match('/^[\w_\-]+$/', $username)) {
        echo "Invalid username";
    } else {
        //search it in database, for password we need to verify, so we can first now if it has in database. So we use "count"
        $stmt = $mysqli->prepare("select count(*), user_id, password from users where username = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($count, $id, $password_fromSQL);
        
        $pwd_guess = $_POST['password'];
        if ($stmt->fetch()) {
            //count == 1 means the username is in database  and  second part is the verify for password
            if ($count == 1 && password_verify($pwd_guess, $password_fromSQL)) {
                //store id and name in session for later use
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $username;
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
                header("Location: mainpage.php");
            } else {
                echo "Password incorrect, please try again";
            }
        } else {
            echo "Username or password incorrect, please try again";
        }
        $stmt->close();
    }
    }

?>


