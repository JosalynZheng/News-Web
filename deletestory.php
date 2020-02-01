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
    //Delete story
    
    if(isset($_POST['story_id'])){
        $deletestory = $_POST['story_id'];
        $stmt= $mysqli->prepare("delete from likes where story_id= ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $deletestory);
        $stmt->execute();
        $stmt->close();

        $stmt= $mysqli->prepare("delete from comments where story_id= ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $deletestory);
        $stmt->execute();
        $stmt->close();

        $stmt= $mysqli->prepare("delete from stories where story_id= ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $deletestory);
        $stmt->execute();
        $stmt->close();
    //Delete story comments
        // $stmt= $mysqli->prepare("delete from comments where story_id=?");
        // if(!$stmt){
        //     printf("Query Prep Failed: %s\n", $mysqli->error);
        //     exit;
        // }
        // $stmt->bind_param('i', $deletestory);
        // $stmt->execute();
        // $stmt->close();
        header('Location: mainpage.php');
    }
    
    
    
?>