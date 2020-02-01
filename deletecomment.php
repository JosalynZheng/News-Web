<?php
    //Connect to database
    require 'database.php';
    session_start();
    // Test for validity of the CSRF token on the server side
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    ///Get userid
    $user = $_SESSION['user_id'];
    if(isset($_POST['comment_id'])){
        //Get commentid
        $deletecomment = $_POST['comment_id'];
        //Delete comment
        $stmt= $mysqli->prepare("delete from comments where comment_id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $deletecomment);
        $stmt->execute();
        $stmt->close();
        // echo '<a href = "storypage.php?story_id='. $_POST['story_id'] . '">Back to Story</a>';
    }
    header('Location: storypage.php?story_id='.$_POST['story_id']);
    
?>