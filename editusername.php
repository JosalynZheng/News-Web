<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Comments</title>
    <link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>
<body>
<h2 style="text-align:center">Edit Your UserName Here</h2>
<form action = "editusername.php" method = "POST">
    <ul><li><label>User Name: <input type="text" style="text-align:center" name="newusername" placeholder = "Edit Username Here"/><br></label></li></ul>
    <ul><li><input type = "submit" name = "submit" value = "Update"></li></ul>
</form>
<?php
    //Connect to database
    require 'database.php';
    session_start();
    // Test for validity of the CSRF token on the server side
    // if(!hash_equals($_SESSION['token'], $_POST['token'])){
    //     die("Request forgery detected");
    // }
    //Get userid

    //store the edit story id into comment_id
    $user = $_SESSION['user_id'];

    if(isset($_POST['newusername'])){
        $newname = $_POST['newusername']; // get the input of new username
        //Update old username to a new one
        $stmt = $mysqli->prepare("select username from users where username = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('s', $newname);

        $stmt->execute();

        $result = $stmt->get_result();
    

        //exist

        if($result->fetch_assoc() != null){
            echo "This username has already existed! Change one please~";
            $stmt->close();
        }else{
            $stmt->close();
            $stmt = $mysqli->prepare("update users set username = ? where user_id = ?");
            if (!$stmt) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }

            $stmt->bind_param('si', $newname, $user);
            $stmt->execute();
            $stmt->close();
            echo "UserName Changed!";
        }


    }
    echo '<ul><li><a href = "mainpage.php">Back to Story</a></li></ul>'
?>
</body>
</html>