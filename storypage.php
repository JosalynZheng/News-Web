<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Your Story</title>
    <link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>
<body>
<?php
    //Connect to database
    require 'database.php';
    session_start();
    //Get story_id
    $user = $_SESSION['user_id'];
    $story_id = $_GET['story_id'];
    //Show information of story
    $stmt = $mysqli->prepare("select title, content, date, tag, image from stories where story_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->bind_result($title, $content, $date, $tag, $image);
    echo "<ul>\n";
    while($stmt->fetch()){
        echo "<li><a><h1>" . $title . "</h1></a></li>";
        echo "<li><p>" . $content . "</p></li>";
        echo "<li><ol>" . $date . "</ol></li>";
        echo "<li>" . $tag . "</li>";
        if(strlen($image) > 0){
            echo '<p style="text-align: center"><img src="' . $image .'" alt = "" height="100" width="200"></p>';
        }
        // if()
        // echo "<li><img src='{$image}'/></li>";
    }
    echo "</ul>\n";
    $stmt->close(); 
    //Show all comments of the story
    $stmt = $mysqli->prepare("select comment_id, comment, user_id, date from comments where story_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->bind_result($comment_id, $comment, $user_id, $date);
    echo "<ul>\n";
    while($stmt->fetch()){
        echo "<li>" . $comment . "</li>";
        echo "<li><ol>" . $date . "</ol></li>";
        echo "<br><br>";
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id){//if this is the login user, not other users and this comment is the user who posted it.
            // Add edit and delete
            echo "
            <li><form action = 'editcomments.php' method = 'POST'>
            <input type='hidden' name='token' value='" . $_SESSION['token'] . "' />
            <input type='hidden' name='story_id' value='" . $story_id . "' />
            <input type='hidden' name='comment_id' value='" . $comment_id . "' />         
            <input type='submit' name = 'editcomment' value='Edit Comment'>
            </form></li>
            ";
            echo "
            <li><form action = 'deletecomment.php' method = 'POST'>
            <input type='hidden' name='token' value='" . $_SESSION['token'] . "' />
            <input type='hidden' name='story_id' value='" . $story_id . "' />
            <input type='hidden' name='comment_id' value='" . $comment_id . "' />
            <input type='submit' name = 'deletecomment' value='Delete'>
            </form></li>
            ";
        } 
    }
    $stmt->close(); 
    //Add Comments
    if(isset($_SESSION['user_id'])){//If this is the login user
        echo "
        <li><form action = 'addcomments.php?story_id ='" . $story_id . "'' method = 'POST'>
        <input type='hidden' name='token' value='" . $_SESSION['token'] . "' />
        <input type='hidden' name='story_id' value='" . $story_id . "' />
        <input type='submit' value='Add Comment'></li>
        </form>
        "; 
    }
    //Display the number of likes
    $stmt = $mysqli->prepare("select count(like_times) from likes where story_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $story_id);
    $stmt->execute();
    $stmt->bind_result($count);
    while($stmt->fetch()){
        echo "<li>Likes: " . $count . "</li><br>";
    }
    $stmt->close(); 

    //add likes & delete likes
    if(isset($_SESSION['user_id'])){
        $stmt = $mysqli->prepare("select like_times from likes where story_id = ? and user_id = ?");

        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('ii', $story_id, $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
            if($row != null){
                echo "
                <li><form action = 'dislike.php' method = 'POST'>
                <input type='hidden' name='token' value='" . $_SESSION['token'] . "' />
                <input type='hidden' name='story_id' value='" . $story_id . "' />
                <input type='submit' value='Dislike'></li>
                </form>
                ";
            }else{
                echo "
                <li><form action = 'like.php' method = 'POST'>
                <input type='hidden' name='token' value='" . $_SESSION['token'] . "' />
                <input type='hidden' name='story_id' value='" . $story_id . "' />
                <input type='submit' value='Like'>
                </form></li>
                ";
            }
    

            $stmt->close();
    } 
    echo '<li><a href = "mainpage.php">Back to HomePage</a></li>'
?>
</body>
</html>