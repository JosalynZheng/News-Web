<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Select</title>
        <link type="text/css" rel="stylesheet" href="StyleSheet.css">
    </head>
    <body>
        <form action = "search.php" method = "POST"> 
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" /> 
        <ul><li>By Title <input type="text" name="title"></li></ul><br>
        <ul><li><input type = "submit" name = "submit1" value = "Submit"></li></ul><br>
        </form>

        <form action = "search.php" method = "POST"> 
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" /> 
        <ul><li>By User <input type="text" name="username"></li></ul><br>
        <ul><li><input type = "submit" name = "submit2" value = "Submit"></li></ul><br>
        </form>
        
        <br>
        <br>
        <ul><li><a href = "mainpage.php">Back to Home</a></li></ul>
    </body>
</html> 

<?php
    require 'database.php';
    session_start();
    //Get userid
    $user_id = $_SESSION['user_id'];
    //by title
    if(isset($_POST['title'])){
        $title = $_POST['title']; // Story content of comment into $comment        
        $sql = "select title, content, date, tag from stories where title like '%{$title}%'";
        $stmt = $mysqli->prepare($sql);
        if(!$stmt){
           printf("Query Prep Failed: %s\n", $mysqli->error);
           exit;
        }
       
       
        $stmt->execute();  
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
		if($row != null){
            $stmt1 = $mysqli->prepare("select title, content, date, tag, story_id from stories where title like '%{$title}%'");
            if(!$stmt1){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }     
            
            $stmt1->execute();  
            $result1 = $stmt1->get_result();
            echo "<ul>\n";
            while($row1=$result1->fetch_assoc()){
                echo '<li><a href = "storypage.php?story_id=' . $row1["story_id"] .'">' . $row1["title"] . '</a></li>';
                printf("\t<li>%s %s %s</li>\n",				
                    htmlspecialchars($row1["content"]),
                    htmlspecialchars($row1["date"]),
                    htmlspecialchars($row1["tag"])
                );
              
            }
            echo "</ul>\n";
        }else{
            echo "No such story found!";
            $stmt->close();
        }
		
		
        // echo "finished";
        $stmt1->close();
        
    }//by username
    else if(isset($_POST['username'])){
        $username = $_POST['username']; 
        $sql = "select user_id from users where username like '%{$username}%'";
        $stmt = $mysqli->prepare($sql);
        if(!$stmt){
           printf("Query Prep Failed: %s\n", $mysqli->error);
           exit;
        }
       
        //$stmt->bind_param('s', $username);
        $stmt->execute();  
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
		if($row != null){
            $stmt->close();
            $stmt1 = $mysqli->prepare("select title, content, date, tag, story_id from stories join users on (stories.user_id = users.user_id) where username like '%{$username}%'");
            if(!$stmt1){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }     
            //$stmt1->bind_param('s', $username);
            $stmt1->execute();  
            $result1 = $stmt1->get_result();
            
            echo "<ul>\n";
            while($row1=$result1->fetch_assoc()){
                echo '<li><a href = "storypage.php?story_id=' . $row1["story_id"] .'">' . $row1["title"] . '</a></li>';
                printf("\t<li>%s %s %s</li>\n",				
                    htmlspecialchars($row1["content"]),
                    htmlspecialchars($row1["date"]),
                    htmlspecialchars($row1["tag"])
                );
              
            }
            echo "</ul>\n";
            $stmt1->close();
        }else{
            echo "No such user found!";
            $stmt->close();
        }      
    }
?>