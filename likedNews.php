<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>MyPosts</title>
        <link type="text/css" rel="stylesheet" href="StyleSheet.css">
    </head>
</html> 


<?php 

	require 'database.php';
	session_start();

	//check if user has logined in
	if(isset($_SESSION['user_id']))
		$user_id = $_SESSION['user_id'];
	else
		$user_id = 0;
	//content and likes
	

	$stmt = $mysqli->prepare("select title, content, date, tag, stories.story_id, image from likes join stories on stories.story_id = likes.story_id where likes.user_id = ?");
		
	if(!$stmt){
		//echo "$user_id. 2Born in $sql <br>";
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $user_id);
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
	}
	if(strlen($image) > 0){
		echo '<p style="text-align: center"><img src="' . $image .'" alt = "" height="100" width="200"></p>';
	}
	$stmt->close();
?>