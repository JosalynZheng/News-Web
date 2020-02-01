<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>MyPosts</title>
        <link type="text/css" rel="stylesheet" href="StyleSheet.css">
    </head>
	<body>
		<ul><li id="editname"><a href="editusername.php">Edit UserName</a></li></ul>
	</body>	
</html> 

<?php 

	require 'database.php';
	session_start();

	//check if user has logined in
	if(isset($_SESSION['user_id']))
		$user_id = $_SESSION['user_id'];
	else
		$user_id = 0;
	$stmt = $mysqli->prepare("select title, content, date, tag, story_id, image from stories where user_id =?");
		
		if(!$stmt){
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
			if(strlen($image) > 0){
				echo '<p style="text-align: center"><img src="' . $image .'" alt = "" height="100" width="200"></p>';
			}
			echo "
			<form action = 'editstory.php' method = 'POST'>
			<input type='hidden' name='story_id' value='" . $story_id . "' />
			<input type='hidden' name='token' value='" . $_SESSION['token'] . "' />         
    		<li><input type='submit' value='Edit'></li>
    		</form>
			";
		}
		echo "</ul>\n";
		$stmt->close();
?>
<li><a href = "mainpage.php">Back to Home</a></li>
