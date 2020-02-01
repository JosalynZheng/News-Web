<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Story</title>
    <link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>
<body>
<h2 style="text-align:center">Edit Your Story Here</h2>
<form action = "editstory.php" method = "POST" enctype="multipart/form-data">
<input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>' /> 
<input type = 'hidden' name = 'story_id' value = '<?php echo $_POST['story_id'];?>' />
    <ul><li>Title<input style="text-align:center" type="text" name="title"></li></ul><br>
    <ul><li> Content<textarea rows = "5" cols = "40" name = "contentinput" required></textarea></li></ul><br>
    <div  class="div">
 		  <input type="radio" id="soc" name="selection" value="SOCIAL" checked>
  		<label for="soc">Social</label>
  		<input type="radio" id="sci" name="selection" value="SCIENCE">
  		<label for="sci">Science</label>
  		<input type="radio" id="hea" name="selection" value="HEALTH">
  		<label for="hea">Health</label>
  		<input type="radio" id="pol" name="selection" value="POLITICS">
  		<label for="pol">Politics</label>
        <input type="radio" id="oth" name="selection" value="OTHER">
  		<label for="oth">Other</label>
     </div>
    <ul><li>Image<input type="file" name="file" id = "file"/></li></ul>
    <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>' /> 
    <ul><li><input type = "submit" name = "submit" value = "Update"></li></ul>
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

    //store the edit story id into comment_id
    $user = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    

    if(isset($_POST['title'])){
          $title = $_POST['title']; // Story content of comment into $comment
          $content = $_POST['contentinput'];
          $tag = $_POST['selection'];
          $story_id = $_POST['story_id'];
          $file = $_FILES["file"]["name"];
          
          if($file != null){
            echo "$file";
            if($_FILES["file"]["error"]){
                echo $_FILES["file"]["error"];
            }else{
                if(($_FILES["file"]["type"]=="image/jpeg" || $_FILES["file"]["type"]=="image/png") && $_FILES["file"]["size"]<1024000){
        
                    $filename = "./upload/" .$user_name."/". $_FILES["file"]["name"];
                    move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
                    $stmt = $mysqli->prepare("update stories set title =?, content =?, tag =?, image = ? where story_id =?");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt->bind_param('ssssi', $title, $content, $tag, $filename, $story_id);
                    $stmt->execute();
                    $stmt->close();
                    echo "Story and image Changed!";
                }else{
                    echo "invalid image";
                }
          }
        }else{
             //update stories table in database
            $stmt = $mysqli->prepare("update stories set title =?, content =?, tag =? where story_id =?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('sssi', $title, $content, $tag, $story_id);
            $stmt->execute();
            $stmt->close();
            echo "Story Changed!";
        }
        
    }
    echo '<ul><li><a href = "mainpage.php">Back to HomePage</a></li></ul>';
?>
</body>
</html>