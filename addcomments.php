<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>addcomments</title>
    <link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>
<body>
<h2 style="text-align:center">Add Your Comments Here</h2>
     <form action = "addcomments.php" method = "POST">
     <input type = 'hidden' name = 'story_id' value = '<?php echo $_POST['story_id'];?>' />
     <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>' /> 
     <li><textarea rows = "5" cols = "40" name = "commentinput" required></textarea></li>
     <li><input type = "submit" name = "submit" value = "Submit"></li>
     <br>
     <br>
     </form>
     <br>
     <br>
     
<?php
    //Connect to database
    require 'database.php';

    // Test for validity of the CSRF token on the server side
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    //Get userid
    $user = $_SESSION['user_id'];
    //Get story_id
 
    if(isset($_POST['commentinput'])){
         $comment = $_POST['commentinput']; // Store content of comment into $comment
         $story_id = $_POST['story_id'];
         echo "$comment";
         //insert into database
         $stmt = $mysqli->prepare("insert into comments (story_id, user_id, comment) values (?, ?, ?)");
         if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        
        $stmt->bind_param('iis', $story_id, $user, $comment);
        $stmt->execute();
        $stmt->close();
        echo "                 Succeed!";
       
    }
    echo '<li><a href = "storypage.php?story_id='. $_POST['story_id'] . '">Back to Story</a></li>'
?>

</body>
</html>