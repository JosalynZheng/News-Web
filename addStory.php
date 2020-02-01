<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>addStory</title>
    <link type="text/css" rel="stylesheet" href="StyleSheet.css">
</head>
<body>
<div class = "fa">
<h2 style="text-align:center">Add Your Story Here</h2>
     <form action = "addStory.php" class="centered" method = "POST" enctype="multipart/form-data"> 
     Title <ul><li><input type="text" style="text-align:center" name="title"></li></ul><br>
     Content<textarea rows = "5" cols = "40" name = "contentinput" required></textarea><br>
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
     <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>' /> 
     Image <input type="file" name="file" id = "file"/>
     <ul><li><input type = "submit" name = "submit" value = "Submit"></li></ul><br>
    
     </form>
     <br>
     <br>
     <ul><li><a href = "mainpage.php">Back to Home</a></li></ul>
</div>
<?php
    //Connect to database
    require 'database.php';
    //echo $_SESSION['token'];
    // Test for validity of the CSRF token on the server side
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
      die("Request forgery detected");
    }
    //Get userid
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
   
    //Get story_id

    if(isset($_POST['title'])){

         $title = $_POST['title']; // Story content of comment into $comment
         $content = $_POST['contentinput'];
         $tag = $_POST['selection'];
         $file = $_FILES["file"]["name"];

         if($file != null){
            if($_FILES["file"]["error"]){
              echo $_FILES["file"]["error"];
            }else{
                //control the image's size 
                     
                if(($_FILES["file"]["type"]=="image/jpeg" || $_FILES["file"]["type"]=="image/png") && $_FILES["file"]["size"]<1024000){
                  $filepath = "./upload/" .$user_name;
                  // echo $filepath;
                  $filename = "./upload/" .$user_name. "/" . $_FILES["file"]["name"];
                  //  $filename = iconv("UTF-8","gb2312",$filename);
                  if(file_exists($filename)){
                      echo "it has been uploaded";
                  }else{
                        if(!file_exists($filepath)){
                          echo "makidir";
                          mkdir("./upload/" .$user_name, 0777, true);
                          chown("./upload/" .$user_name, "apache");
                          move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
                        }else{
                          move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
                        }
                        
                        $stmt = $mysqli->prepare("insert into stories (user_id, title, content, tag, image) values (?, ?, ?, ?, ?)");
                        if(!$stmt){
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        
                        $stmt->bind_param('issss', $user_id, $title, $content, $tag, $filename);
                        $stmt->execute();
                        // echo "finished";
                        $stmt->close();
                        header('Location: mainpage.php');
                      }
                  }
                }
            
         }else{
           echo "no file";
           //insert into database
            $stmt = $mysqli->prepare("insert into stories (user_id, title, content, tag) values (?, ?, ?, ?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            
            $stmt->bind_param('isss', $user_id, $title, $content, $tag);
            $stmt->execute();
          // echo "finished";
            $stmt->close();
            header('Location: mainpage.php');
         }
         
     }
     
?>
</body>
</html>