<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Comments</title>
    <link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>
<body>
<h2 style="text-align:center">Edit Your Comments Here</h2>
    <form action = "editcomments.php" method = "POST">
    <input type = 'hidden' name = 'story_id' value = '<?php echo $_POST['story_id'];?>' />
    <input type = 'hidden' name = 'comment_id' value = '<?php echo $_POST['comment_id'];?>' />
    <ul><li><textarea rows = "5" cols = "40" name = "commentinput" required></textarea></li></ul>
    <br>
    <br>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <ul><li><input type = "submit" name = "submit" value = "Update"></li></ul>
    </form> 
<?php
    //Connect to database
    require 'database.php';
    // Test for validity of the CSRF token on the server side
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    //Get userid

    //store the edit story id into comment_id
    $user = $_SESSION['user_id'];

    if(isset($_POST['commentinput'])){
        $comment_id = $_POST['comment_id'];
        $story_id = $_POST['story_id'];
        $comment_content = $_POST['commentinput']; // get the input content
        //Update old comment to a new one
        $stmt = $mysqli->prepare("update comments set comment = ? where comment_id = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('si', $comment_content, $comment_id);
        $stmt->execute();
        $stmt->close();
        echo "Comments Changed!";
    }
    echo '<ul><li><a href = "storypage.php?story_id=' . $_POST['story_id'] . '">Back to Story</a></li></ul>'
?>
</body>
</html>