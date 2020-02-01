<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>tag</title>
        <link type="text/css" rel="stylesheet" href="StyleSheet.css">
    </head>

    </body>
</html>  


<?php
	session_start();

	if(isset($_SESSION['user_id']))
		$user_id = $_SESSION['user_id'];
	else
		$user_id = 0;
	//get tag name and according to the tag to select in database
    $tagname = $_GET['tagname'];
		require 'database.php';
		$stmt = $mysqli->prepare("select title, content, date, tag, stories.story_id, image from stories where tag =?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('s', $tagname);
		$stmt->execute();	
		$stmt->bind_result($title, $content, $date, $tag, $story_id, $image);
		echo "<ul>\n";
		while($stmt->fetch()){
			echo '<li><a href = "storypage.php?story_id=' . $story_id .'">' . $title . '</a></li>';
			printf("\t<li>%s %s %s</li>\n",				
				htmlspecialchars($content),
				htmlspecialchars($date),
				htmlspecialchars($tag)
			);
			if(strlen($image) > 0){
				echo '<p style="text-align: center"><img src="' . $image .'" alt = "" height="100" width="200"></p>';
			}
		}
		echo "</ul>\n";
		echo '<ul><li><a href = "mainpage.php">Back to HomePage</a></li></ul>';
?>