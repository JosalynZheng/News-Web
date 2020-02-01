<?php
    //Connect to database
    require 'database.php';
    session_start();
    // Test for validity of the CSRF token on the server side
    // if(!hash_equals($_SESSION['token'], $_POST['token'])){
    //     die("Request forgery detected");
    // }
    //Get userid
    $user = $_SESSION['user_id'];
    if(isset($_POST['story_id'])){
        //Get storyid
        $story = $_POST['story_id'];
        //Delete like in database
        $stmt = $mysqli->prepare("delete from likes where user_id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $user);
        $stmt->execute();
        $stmt->close();
        header('Location: storypage.php?story_id='.$_POST['story_id']);
    }
?>